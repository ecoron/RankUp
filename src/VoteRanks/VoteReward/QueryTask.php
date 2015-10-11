<?php
namespace VoteRanks\VoteReward;
use pocketmine\Player;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
class QueryTask extends AsyncTask
{
    private $url;
    private $player;
    private $r;

    function __construct($url,Player $player,$r)
    {
        $this->url = $url;
        $this->player = $player;
        $this->r = $r;
    }

    public function onRun()
    {
        $this->data = file_get_contents($this->url);
    }

    public function onCompletion(Server $server)
    {
        if($this->r) {
            $player = $server->getPlayer($this->player);
            if($player instanceof Player) {
                $server->getPluginManager()->getPlugin("VoteRanks")->executeRankUp($player,$this->data);
            }
        }
    }
}
?>