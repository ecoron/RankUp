<?php
namespace VoteRanks;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use VoteRanks\Config;
use VoteRanks\VoteRanks;
use VoteRanks\events\PlayerRankUpEvent;

class RankUp {

    private $config;
    private $purePerms;
    private $pluginManager;
    private $logger;

    function __construct(Config $config, $pluginManager, $logger)
    {
        $this->config = $config;
        $this->pluginManager = $pluginManager;
        $this->logger = $logger;
    }

    public function initPurePerms() {
        if(($plugin = $this->pluginManager->getPlugin("PurePerms")) instanceof Plugin){
            $this->purePerms = $plugin;
            $this->logger->info("Successfully loaded with PurePerms");
            return true;
        }

        $this->logger->alert("Dependency PurePerms not found");
        return false;
    }

    public function getPureRank($groupName)
    {
        return $this->purePerms->getGroup($groupName);
    }

    public function getUserGroup(Player $player)
    {
        $ppuser = $this->purePerms->getUser($player);
        $ppusergroup = $ppuser->getGroup();
        return $ppusergroup->getName();
    }

    public function setRank(VoteRanks $plugin, Player $player, $pureRank, $rank)
    {
        $event = new PlayerRankUpEvent($plugin, $player, $rank, "You are now rank ".$rank);
        $this->pluginManager->callEvent($event);

        if(!$event->isCancelled()){
            $this->purePerms->setGroup($player, $pureRank);
            return $event->getMessage();
        }
    }

    public function rankUp(VoteRanks $plugin, Player $player)
    {
        $userGroup = $this->getUserGroup($player);

        if(array_key_exists($userGroup, $this->config->getRanks())){
            $oldRankId = $this->config->getRankId($userGroup);
            $newRankId = $oldRankId + 1;
            $newRank = array_search($newRankId, $this->config->getRanks());
            if($newRank !== false){
                $pureRank = $this->getPureRank($newRank);
                if ($pureRank != null) {
                    return $this->setRank($plugin, $player, $pureRank, $newRank);
                }
            }
        }

        $this->logger->alert("RankUp failed with rank: " . $newRank);
    }

}