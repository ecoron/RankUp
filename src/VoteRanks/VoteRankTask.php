<?php
namespace VoteRanks;

use pocketmine\Player;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class VoteRankTask extends AsyncTask
{
    var $data;

    var $url;

    var $player;

    var $result;

    function __construct($url, $player)
    {
        $this->url = $url;
        $this->player = $player;
    }

    public function onRun()
    {
        $this->data = file_get_contents($this->url);
    }

    public function onCompletion(Server $server)
    {

        $player = $server->getPlayer($this->player);
        if($player instanceof Player && in_array($this->data, array("1","2"))) {
            $server->getPluginManager()->getPlugin("VoteRanks")->executeRankUp($player,$this->data);
        }

    }
}
?>