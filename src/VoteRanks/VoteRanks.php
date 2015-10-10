<?php
namespace VoteRanks;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class VoteRanks extends PluginBase{

    public function onEnable(){
    }

    public function onDisable(){
    }

    public function onCommand(CommandSender $sender, Command $command, $label, array $args){

        return true;
    }

}