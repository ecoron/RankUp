<?php
namespace RankUp;

class Config {

    private $apiKey;

    private $voteUrl;

    private $autoRanks;

    private $jobRanks;

    private $jobStopFallback;

    private $ranks;

    private $voteRanks;

    function __construct($configFile)
    {
        $config = yaml_parse(file_get_contents($configFile));
        $num = 0;
        $this->apiKey = $config["APIKey"];
        $this->voteUrl = $config["VoteURL"];
        $this->ranks = $config["Ranks"];
        $this->autoRanks = $config["AutoRanks"];
        $this->jobRanks = $config["JobRanks"];
        $this->jobStopFallback = $config["JobStopFallback"];
        $this->voteRanks = $config["VoteRanks"];
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

    public function getJobRanks()
    {
        return $this->jobRanks;
    }

    public function getJobStopFallback($key)
    {
        return $this->jobStopFallback[$key];
    }

    public function getVoteRanks()
    {
        return $this->voteRanks;
    }

    public function getAutoRankMinutes($userGroup)
    {
        if($userGroup == false) {
            return false;
        }
        if(array_key_exists($userGroup, $this->autoRanks)) {
            return $this->autoRanks[$userGroup];
        }

        return false;
    }

    public function getRankId($userGroup)
    {
        if($userGroup == false) {
            return false;
        }
        if(array_key_exists($userGroup, $this->ranks)) {
            return intval($this->ranks[$userGroup]);
        }

        return false;
    }

    public function getMessage($messageId)
    {
        if(array_key_exists($messageId, $this->messages)) {
            return $this->messages[$messageId];
        }
    }
}