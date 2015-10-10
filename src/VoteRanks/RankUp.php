<?php
namespace VoteRanks;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class RankUp {

    var $purePerms;

    function __construct($pluginManager, $logger) {
        $this->pluginManager = $pluginManager;
        $this->logger = $logger;
        $this->initPurePerms();
    }

    private function initPurePerms() {
        if(($plugin = $this->pluginManager->getPlugin("PurePerms")) instanceof Plugin){
            $this->purePerms = $plugin;
            $this->logger->info("Successfully loaded with PurePerms");
        }else{
            $this->getLogger()->alert("Dependency PurePerms not found");
            $this->pluginManager->disablePlugin($this);
        }
    }

    public function getRank(){
    }

    public function setRank(){
    }

    public function rankUp() {

    }

}