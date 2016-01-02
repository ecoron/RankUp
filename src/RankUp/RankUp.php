<?php
namespace RankUp;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use RankUp\Config;
use RankUp\MainRankUp;
use RankUp\events\PlayerRankUpEvent;

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

    public function setRank(MainRankUp $plugin, Player $player, $pureRank, $rank)
    {
        $message = str_replace("##rank##", $rank, $this->config->getMessage("rank-new"));
        $event = new PlayerRankUpEvent($plugin, $player, $rank, $message);
        $this->pluginManager->callEvent($event);

        if(!$event->isCancelled()){
            $this->purePerms->setGroup($player, $pureRank);
            return $event->getMessage();
        }
    }

    public function rankUp(MainRankUp $plugin, Player $player)
    {
        $userGroup = $this->getUserGroup($player);

        if(array_key_exists($userGroup, $this->config->getRanks())){
            $oldRankId = $this->config->getRankId($userGroup);
            $newRankId = $oldRankId + 1;
            $newRank = array_search($newRankId, $this->config->getVoteRanks());
            if($newRank !== false){
                $pureRank = $this->getPureRank($newRank);
                if ($pureRank != null) {
                    return $this->setRank($plugin, $player, $pureRank, $newRank);
                }
            }
        }

        $message = str_replace("##rank##", $userGroup, $this->config->getMessage("rank-failed"));
        $this->logger->alert($message);
    }

    public function autoRankUp(MainRankUp $plugin, Player $player)
    {
        $userGroup = $this->getUserGroup($player);
        $oldRankId = $this->config->getRankId($userGroup);
        $timeplayed = $plugin->data->get(strtolower($player->getName()));
        $newRank = false;
        if($oldRankId !== false) {
            $newRankId = $oldRankId + 1;
            $newRank = array_search($newRankId, $this->config->getRanks());
            $newRankMinutes = $this->config->getAutoRankMinutes($newRank);
        }

        if($newRank !== false && $newRankMinutes !== false && $timeplayed >= $newRankMinutes){
            $pureRank = $this->getPureRank($newRank);
            if ($pureRank != null) {
                $command = "say " . str_replace("##player##",$player->getName(),$this->config->getMessage("timer-newrank"));
                $command = str_replace("##rank##", $newRank, $command);
                $plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), $command);
                return $this->setRank($plugin, $player, $pureRank, $newRank);
            }
        }
    }

    public function jobRankUp(MainRankUp $plugin, Player $player, array $args) {
        $userGroup = $this->getUserGroup($player);
        $oldRankId = $this->config->getRankId($userGroup);
        $jobConfig = $this->config->getJobRanks();
        $jobNames = array_keys($jobConfig);

        $sub = array_shift($args);
        switch(strtolower($sub)){
            case "list":
                return str_replace("##joblist##", implode(', ', $jobNames), $this->config->getMessage("job-list"));
                break;
            case "start":
                    if (!empty($args[0]) && in_array($args[0], $jobNames)) {
                        if ($oldRankId >= $jobConfig[$args[0]]) {
                            //check if playerrank is allowed to choose a jobrank
                            $newRankId = $this->config->getRankId($args[0]);
                            $newRank = array_search($newRankId, $this->config->getRanks());
                            if($newRank !== false){
                                $pureRank = $this->getPureRank($newRank);
                                if ($pureRank != null) {
                                    return $this->setRank($plugin, $player, $pureRank, $newRank);
                                }
                            }
                            return $this->config->getMessage("job-rank-error");
                        }
                        return $this->config->getMessage("job-rank-low");
                    }
                    return str_replace("##joblist##", implode(', ', $jobNames), $this->config->getMessage("job-choose"));

                break;
            case "stop":
                $timeplayed = $plugin->data->get(strtolower($player->getName()));
                ranks = $this->config->getRanks();
                //search the origin rank
                foreach($ranks as $rankName => $rankId) {
                    if($timeplayed >= $this->config->getAutoRankMinutes($rankName)){
                        $newRank = $rankName;
                    }
                }
                if($newRank !== false){
                    $pureRank = $this->getPureRank($newRank);
                    if ($pureRank != null) {
                        return $this->setRank($plugin, $player, $pureRank, $newRank);
                    }
                }

                return $this->config->getMessage("job-leave");
                break;
            default:
                return $this->config->getMessage("job-usage");
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