# VoteRanks 1.0

A @PocketMine MCPE server plugin. Player gets a higher rank as reward for voting.
For mcpe 0.11.1 / 0.12.1 with api 1.12.0 / 1.13.0 

##Download and Install


##Usage / Command

```
/vr
```

oder

```
/voternks
```

##Configuration

```
# API key; Can be found on your server settings page.
APIKey: ""

# URL where players can vote for this server;
VoteURL: ""

# list of ranks that can be reached. cutomize this with your server ranks
Ranks:
    Guest: 1
    Bronze: 2
    Silver: 3
    Gold: 4
    Member: 5

#messages
Messages:
    command-in-game: "Command must be used in-game."
    error-fetching-vote: "[VoteRanks] Error fetching vote status! Try again later."
    no-permission: "You do not have permission to vote."
    pureperms-loaded: "Successfully loaded with PurePerms"
    pureperms-notfound: "Dependency PurePerms not found"
    rank-new: "You are now rank ##rank##"
    rank-failed: "RankUp failed with rank: ##rank##"
    vote-nextday: "You've already voted today! Come back tomorrow to vote again."
    vote-success: "##player## voted with /vote and got a higher rank!"
    vote-open: "You haven't voted yet!\n +++ ##voteurl## +++ \nVote to get higher rank!"
```

##Permissions

```
permissions:
    voteranks.command:
        description: "rankup a player as vote reward"
        default: true
```