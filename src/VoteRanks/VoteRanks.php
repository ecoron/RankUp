<?php
namespace VoteRanks;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config as PMConfig;
use pocketmine\utils\TextFormat;
use VoteRanks\Config;
use VoteRanks\RankUp;
use VoteRanks\VoteRankTask;
use VoteRanks\TimerTask;
use VoteRanks\TimerTaskCommand;


class VoteRanks extends PluginBase{

    var $config;
    var $data;
    var $voteRankTask;
    var $rankUp;

    public function onEnable(){

        if(!file_exists($this->getDataFolder() . "config.yml")) {
            @mkdir($this->getDataFolder());
            file_put_contents($this->getDataFolder() . "config.yml",$this->getResource("config.yml"));
        }
        $this->config = new Config($this->getDataFolder() . "config.yml");
        $this->rankUp = new RankUp($this->config, $this->getServer()->getPluginManager(), $this->getLogger());
        $this->data = new PMConfig($this->getDataFolder()."data.properties", PMConfig::PROPERTIES);
        //TimerTask
        $this->getServer()->getScheduler()->scheduleDelayedRepeatingTask(new TimerTask($this), 1200, 1200);
        # Command
        $this->timerTaskCommand = new TimerTaskCommand($this, $this->config);

    }

    public function onDisable(){
    }

    public function onCommand(CommandSender $player,Command $cmd,$label,array $args) {
        if(!($player instanceof Player)) {
            $player->sendMessage($this->config->getMessage("command-in-game"));
            return true;
        }
        if($player->hasPermission("voteranks") || $player->hasPermission("voteranks.vote")) {
            $this->requestApiTaks($player->getName(), true);
        } elseif (strtolower($cmd->getName()) === "timeranks"){
                $this->timerTaskCommand->run($player, $args);
        } else {
            $player->sendMessage($this->config->getMessage("no-permission"));
        }
        return true;
    }

    public function executeRankUp(Player $player, $response) {
        $message = null;
        switch($response) {
            case "0":
                    $message = str_replace("##voteurl##", $this->config->getVoteUrl(), $this->config->getMessage("vote-open"));
                break;
            case "1":
                    $this->requestApiTaks($player->getName(), false);

                    if ($this->rankUp->initPurePerms() == false) {
                        $this->getServer()->getPluginManager()->disablePlugin($this);
                    }

                    $this->rankUp->rankUp($this, $player);
                    $command = "say " . $this->config->getMessage("vote-success");
                    $this->getServer()->dispatchCommand(new ConsoleCommandSender(),str_replace("##player##",$player->getName(),$command));
                break;
            case "2":
                    $message = $this->config->getMessage("vote-nextday");
                break;
            default:
                    $message = $this->config->getMessage("error-fetching-vote");
                    $this->getLogger()->warning(TextFormat::RED . $message);
                break;
        }

        if($message) {
            $player->sendMessage($message);
        }
    }

    private function requestApiTaks($playerName, $result) {
        $query = new VoteRankTask("http://minecraftpocket-servers.com/api/?object=votes&element=claim&key=" . $this->config->getApiKey() . "&username=" . $playerName, $playerName, $result);
        $this->getServer()->getScheduler()->scheduleAsyncTask($query);
    }
}