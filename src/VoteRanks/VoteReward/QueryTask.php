<?php
namespace VoteRanks\VoteReward;
use pocketmine\Player;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
class QueryTask extends AsyncTask
{
    private $data;
    private $url;
    private $player;
    private $result;

    function __construct($url,$player,$result)
    {
        $this->url = $url;
        $this->player = $player;
        $this->result = $result;
    }

    public function onRun()
    {
        $this->data = file_get_contents($this->url);
    }

    public function onCompletion(Server $server)
    {
        if($this->result) {
            $player = $server->getPlayer($this->player);
            if($player instanceof Player) {
                $server->getPluginManager()->getPlugin("VoteRanks")->executeRankUp($player,$this->data);
            }
        }
    }
}
?>