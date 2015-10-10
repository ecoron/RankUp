<?php
namespace VoteRanks\VoteReward;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\item\Item;
class VoteReward extends PluginBase {
  private $commands, $key, $url;

  public function __construct(){

    if(!file_exists($this->getDataFolder() . "config.yml")) {
      @mkdir($this->getDataFolder());
      file_put_contents($this->getDataFolder() . "config.yml",$this->getResource("config.yml"));
    }
    $c = yaml_parse(file_get_contents($this->getDataFolder() . "config.yml"));
    $num = 0;
    $this->key = $c["API-Key"];
    $this->url = $c["Vote-URL"];

  }

  public function give(Player $p,$s) {
    if($s == "0") {
      $p->sendMessage("You haven't voted yet!\n" . $this->url . "\nVote now for cool rewards!");
    } else if($s == "1") {
      $query = new QueryTask("http://minecraftpocket-servers.com/api/?action=post&object=votes&element=claim&key=" . $this->key . "&username=" . $p->getName(),$p->getName(),false);
      $this->getServer()->getScheduler()->scheduleAsyncTask($query);
    } else if($s == "2") {
      $p->sendMessage("You've already voted today! Come back tomorrow to vote again.");
    } else {
      $this->getLogger()->warning(TextFormat::RED . "Error fetching vote status! Are you hosting your server on a mobile device, or is your Internet out?");
      $p->sendMessage("[VoteReward] Error fetching vote status!");
    }
  }
}
?>