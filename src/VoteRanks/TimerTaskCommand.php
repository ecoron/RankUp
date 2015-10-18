<?php

namespace VoteRanks;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use VoteRanks\VoteRanks;
use VoteRanks\Config;

class TimerTaskCommand{

    private $plugin;

    private $config;

    public function __construct(VoteRanks $plugin, Config $config){
        $this->plugin = $plugin;
        $this->config = $config;
    }

    public function run(CommandSender $sender, array $args){
        if(!isset($args[0])){
            return str_replace("##player##", "<player>", $this->config->getMessage("timer-usage"));
        }
        $sub = array_shift($args);
        switch(strtolower($sub)){
            case "check":
                if(isset($args[0])){
                    if(!$this->plugin->getServer()->getOfflinePlayer($args[0])->hasPlayedBefore()){
                        return $this->config->getMessage("timer-neverplayed");
                    }

                    if(!$this->plugin->data->exists(strtolower($args[0]))){
                        return str_replace("##player##", $args[0], $this->config->getMessage("timer-newplayer"));
                        //$sender->sendMessage("Rank is: ".$this->plugin->default);
                    }

                    $timeplayed = $this->plugin->data->get(strtolower($args[0]));
                    return str_replace("##timeplayed##", $timeplayed, $this->config->getMessage("timer-timeplayer"));
                    //$sender->sendMessage("Rank is: ".$this->plugin->getRank(strtolower($args[0])));
                }

                if(!$this->plugin->data->exists(strtolower($sender->getName()))){
                    if(!($sender instanceof Player)){
                        return str_replace("##player##", $args[0], $this->config->getMessage("timer-usage"));
                    }
                    return str_replace("##player##", $args[0], $this->config->getMessage("timer-newplayer"));
                    //$sender->sendMessage("Rank is: ".$this->plugin->default);
                }

                $timeplayed = $this->plugin->data->get(strtolower($sender->getName()));
                $timetoplay = $this->plugin->rankUp->getTimeToAutoRankUp($this->plugin->data, $sender);
                $message = str_replace("##timeplayed##", $timeplayed, $this->config->getMessage("timer-timeplayed"));
                $message = str_replace("##timetoplay##", $timetoplay, $this->config->getMessage("timer-timeplayed"));
                return $message;
                //$sender->sendMessage("Rank is: ".$this->plugin->getRank(strtolower($sender->getName())));
            break;
            default:
                return false;
        }
    }

}