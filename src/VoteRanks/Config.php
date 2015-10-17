<?php
namespace VoteRanks;

class Config {

    private $apiKey;

    private $voteUrl;

    private $ranks;

    function __construct($configFile)
    {
        $config = yaml_parse(file_get_contents($configFile));
        $num = 0;
        $this->apiKey = $config["APIKey"];
        $this->voteUrl = $config["VoteURL"];
        $this->ranks = $config["Ranks"];
        $this->messages = $config["Messages"];
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function getVoteUrl()
    {
        return $this->voteUrl;
    }

    public function getRanks()
    {
        return $this->ranks;
    }

    public function getRankId($userGroup)
    {
        return intval($this->ranks[$userGroup]);
    }

    public function getMessage($messageId)
    {
        if(array_key_exists($messageId, $this->messages)) {
            return $this->messages[$messageId];
        }
    }
}