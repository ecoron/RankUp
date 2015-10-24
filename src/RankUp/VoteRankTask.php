<?php
namespace RankUp;

use pocketmine\Player;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class VoteRankTask extends AsyncTask
{
    var $data;

    var $url;

    var $player;

    var $gotreward;

    function __construct($url, $player, $gotreward = false)
    {
        $this->url = $url;
        $this->player = $player;
        $this->gotreward = $gotreward;
    }

    public function onRun()
    {
        $this->data = file_get_contents($this->url);
    }

    public function onCompletion(Server $server)
    {

        $player = $server->getPlayer($this->player);
        if($player instanceof Player && in_array($this->data, array(0,1,2))) {
            $server->getPluginManager()->getPlugin("RankUp")->executeRankUp($player, $this->data, $this->gotreward);
        }

    }
}
?>