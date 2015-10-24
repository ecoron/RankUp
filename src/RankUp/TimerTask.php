<?php

namespace RankUp;

use pocketmine\scheduler\PluginTask;
use RankUp\VoteRanks;

class TimerTask extends PluginTask{

    public function __construct(VoteRanks $plugin){
        parent::__construct($plugin);
        $this->plugin = $plugin;
    }

    public function onRun($tick){
        foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
            if($this->plugin->data->exists($name = strtolower($player->getName()))) {
                $this->plugin->data->set($name, (int) $this->plugin->data->get($name) + 1);
            }else{
                $this->plugin->data->set($name, 1);
            }
            $this->plugin->rankUp->autoRankUp($this->plugin, $player);
        }
        $this->plugin->data->save();
        return true;
    }

}