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
            $this->logger->info($this->config->getMessage("pureperms-loaded"));
            return true;
        }

        $this->logger->alert($this->config->getMessage("pureperms-notfound"));
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
        $message = str_replace("##rank##", $rank, $this->config->getMessage("rank-new"));
        $event = new PlayerRankUpEvent($plugin, $player, $rank, $message);
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

        $message = str_replace("##rank##", $rank, $this->config->getMessage("rank-failed"));
        $this->logger->alert($message);
    }

    public function autoRankUp($data, Player $player)
    {
        $userGroup = $this->getUserGroup($player);
        $oldRankId = $this->config->getRankId($userGroup);
        $oldRankMinutes = $this->config->getAutoRankMinutes($userGroup);
        $timeplayed = $data->get(strtolower($player->getName()));
        $newRankId = $oldRankId + 1;
        $newRank = array_search($newRankId, $this->config->getRanks());

        if($newRank !== false && $timeplayed >= $this->config->getAutoRankMinutes($newRank)){
            $pureRank = $this->getPureRank($newRank);
            if ($pureRank != null) {
                return $this->setRank($plugin, $player, $pureRank, $newRank);
            }
        }
    }

    public function getTimeToAutoRankUp($data, Player $player)
    {
        $userGroup = $this->getUserGroup($player);
        $oldRankId = $this->config->getRankId($userGroup);
        $timeplayed = $data->get(strtolower($player->getName()));
        $newRankId = $oldRankId + 1;
        $newRank = array_search($newRankId, $this->config->getRanks());
        if($newRank !== false && $timeplayed < $this->config->getAutoRankMinutes($newRank)){
            return $this->config->getAutoRankMinutes($newRank) - $timeplayed;
        }

        return false;
    }

}