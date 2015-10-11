<?php
namespace VoteRanks;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use VoteRanks\Config;
use VoteRanks\RankUp;
use VoteRanks\VoteReward\VoteReward;


class VoteRanks extends PluginBase{

    var $config;
    var $voteReward;
    var $rankUp;

    public function onEnable(){

        if(!file_exists($this->getDataFolder() . "config.yml")) {
            @mkdir($this->getDataFolder());
            file_put_contents($this->getDataFolder() . "config.yml",$this->getResource("config.yml"));
        }
        $this->config = new Config($this->getDataFolder() . "config.yml");
        $this->voteReward = new VoteReward($this->config);
        $this->rankUp = new RankUp($this->getServer(), $this->getLogger());
    }

    public function onDisable(){
    }

    public function onCommand(CommandSender $player,Command $cmd,$label,array $args) {
        if(!($player instanceof Player)) {
            $player->sendMessage("Command must be used in-game.");
            return true;
        }
        if($player->hasPermission("voteranks") || $player->hasPermission("voteranks.vote")) {
            $this->voteReward->requestApiTaks($this->getServer()->getScheduler(), $player->getName());
        } else {
            $player->sendMessage("You do not have permission to vote.");
        }
        return true;
    }

    public function executeRankUp(Player $player, $response) {
        switch($response) {
            case "0":
                    $message = $this->voteReward->voteOpen();
                break;
            case "1":
                    $this->voteReward->voteSuccess($this->getServer()->getScheduler(), $player->getName());
                    $this->rankUp->rankUp($player);
                break;
            case "2":
                    $message = $this->voteReward->voteClosed();
                break;
            default:
                    $this->getLogger()->warning(TextFormat::RED . "Error fetching vote status! Try again later.");
                    $message = $this->voteReward->voteFailed();
                break;
        }

        $player->sendMessage($message);
    }
}