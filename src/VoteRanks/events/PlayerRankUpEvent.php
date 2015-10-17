<?php
namespace VoteRanks\events;

use pocketmine\event\Cancellable;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;
use VoteRanks\VoteRanks;

class PlayerRankUpEvent extends PluginEvent implements Cancellable{

    public static $handlerList = null;

    private $player;
    private $newRank;
    private $message;

    public function __construct(VoteRanks $plugin, Player $player, $newRank, $message){
        parent::__construct($plugin);
        $this->player = $player;
        $this->newRank = $newRank;
        $this->message = $message;
    }

    public function getPlayer(){
        return $this->player;
    }

    public function getNewRank(){
        return $this->newRank;
    }

    public function getMessage(){
        return $this->message;
    }

    public function setMessage($message){
        $this->message = $message;
    }

}