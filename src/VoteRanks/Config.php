<?php
namespace VoteRanks;

class Config {

    private $apiKey;

    private $voteUrl;

    function __construct($configFile)
    {
        $config = yaml_parse(file_get_contents($configFile));
        $num = 0;
        $this->apiKey = $config["API-Key"];
        $this->voteUrl = $config["Vote-URL"];
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function getVoteUrl()
    {
        return $this->voteUrl;
    }
}