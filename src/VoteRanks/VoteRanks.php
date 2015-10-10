<?php
namespace VoteRanks;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use VoteRanks\RankUp;
use VoteRanks\VoteReward\VoteReward;


class VoteRanks extends PluginBase{

    var $voteRewards;

    public function onEnable(){
        $this->voteRewards = new VoteReward();
        $this->rankUp = new RankUp($this->getServer(), $this->getLogger());
    }

    public function onDisable(){
    }

    public function onCommand(CommandSender $p,Command $cmd,$label,array $args) {
        if(!($p instanceof Player)) {
            $p->sendMessage("Command must be used in-game.");
            return true;
        }
        if($p->hasPermission("voteranks") || $p->hasPermission("voteranks.vote")) {
            $this->voteRewards->requestApiTaks();
        } else {
            $p->sendMessage("You do not have permission to vote.");
        }
        return true;
    }

    public function executeRankUp($p, $s) {
        $this->rankUp->rankUp($p, $s);
        $this->voteRewards->give($p, $s);
    }
}