<?php
namespace VoteRanks\VoteReward;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use VoteRanks\Config;
use VoteRanks\VoteReward\QueryTask;

class VoteReward
{
    private $config;

    public function __construct(Config $config){
        $this->config = $config;
    }

  public function requestApiTaks($scheduler, $playerName) {
      $query = new QueryTask("http://minecraftpocket-servers.com/api/?object=votes&element=claim&key=" . $this->config->getApiKey() . "&username=" . $playerName, $playerName, true);
      $scheduler->scheduleAsyncTask($query);
  }

  public function voteOpen()
  {
      //response = 0
      return "You haven't voted yet!\n" . $this->config->getVoteUrl() . "\nVote to get higher rank!";
  }

  public function voteSuccess($scheduler, $playerName)
  {
      //response = 1
      $query = new QueryTask("http://minecraftpocket-servers.com/api/?action=post&object=votes&element=claim&key=" . $this->config->getApiKey() . "&username=" . $playerName, $playerName, false);
      $scheduler->scheduleAsyncTask($query);
  }

  public function voteClosed()
  {
      //response = 2
      return "You've already voted today! Come back tomorrow to vote again.";
  }

  public function voteFailed()
  {
      //response is anything else
      return "[VoteRanks] Error fetching vote status!";
  }
}
?>