<?php
namespace VoteRanks;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
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
        $this->rankUp = new RankUp($this->config, $this->getServer()->getPluginManager(), $this->getLogger());
    }

    public function onDisable(){
    }

    public function onCommand(CommandSender $player,Command $cmd,$label,array $args) {
        if(!($player instanceof Player)) {
            $player->sendMessage("Command must be used in-game.");
            return true;
        }
        if($player->hasPermission("voteranks") || $player->hasPermission("voteranks.vote")) {
            $this->voteReward->requestApiTaks($this->getServer()->getScheduler(), $player->getName(), true);
        } else {
            $player->sendMessage("You do not have permission to vote.");
        }
        return true;
    }

    public function executeRankUp(Player $player, $response) {
        $message = null;
        switch($response) {
            case "0":
                    $message = $this->voteReward->voteOpen();
                break;
            case "1":
                    $this->voteReward->requestApiTaks($this->getServer()->getScheduler(), $player->getName(), false);

                    if ($this->rankUp->initPurePerms() == false) {
                        $this->getServer()->getPluginManager()->disablePlugin($this);
                    }

                    $this->rankUp->rankUp($this, $player);
                    $command = $this->voteReward->voteSuccess();
                    $this->getServer()->dispatchCommand(new ConsoleCommandSender(),str_replace("{PLAYER}",$player->getName(),$command));
                break;
            case "2":
                    $message = $this->voteReward->voteClosed();
                break;
            default:
                    $this->getLogger()->warning(TextFormat::RED . "Error fetching vote status! Try again later.");
                    $message = $this->voteReward->voteFailed();
                break;
        }

        if($message) {
            $player->sendMessage($message);
        }
    }
}