<?php
namespace VoteRanks;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use VoteRanks\VoteReward\VoteReward;
use VoteRanks\VoteReward\QueryTask;

class VoteRanks extends PluginBase{

    var $purePerms;

    public function onEnable(){
    }

    public function onDisable(){
    }

    public function onCommand(CommandSender $p,Command $cmd,$label,array $args) {
        if(!($p instanceof Player)) {
            $p->sendMessage("Command must be used in-game.");
            return true;
        }
        if($p->hasPermission("voteranks") || $p->hasPermission("voteranks.vote")) {
            $query = new QueryTask("http://minecraftpocket-servers.com/api/?object=votes&element=claim&key=" . $this->key . "&username=" . $p->getName(),$p->getName(),true);
            $this->getServer()->getScheduler()->scheduleAsyncTask($query);
        } else {
            $p->sendMessage("You do not have permission to vote.");
        }
        return true;
    }

    private function initPPerms() {
        if(($plugin = $this->getServer()->getPluginManager()->getPlugin("PurePerms")) instanceof Plugin){
            $this->purePerms = $plugin;
            $this->getLogger()->info("Successfully loaded with PurePerms");
        }else{
            $this->getLogger()->alert("Dependency PurePerms not found");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }

}