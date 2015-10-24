# RankUp 1.1

A @PocketMine MCPE server plugin. Player gets a higher rank as reward for voting or
after some time playing on the server.
For mcpe 0.11.1 / 0.12.1 with api 1.12.0 / 1.13.0

RankUp requires PurePerms v1.1.11 or v1.1.12 on your server

##Download and Install

### [Download: RankUp.phar v1.0](https://s3-eu-west-1.amazonaws.com/devron/RankUp.phar)


and copy the file into your plugins folder.

restart your server.

modify the config.yml

restart again

##Usage / Command

###vote / get next rank for voting

```
/vr
```
```
/voteranks
```
```
/vote
```

###check time played / time to play for next rank

own status

```
/tr check
```

status of other players

```
/tr check <playername>
```

##Configuration

```
# API key; Can be found on your server settings page (minecraftpocket-servers.com)
APIKey: ""

# URL where players can vote for this server;
VoteURL: ""

# list of ranks that can be reached. cutomize this with your server ranks
# ranks must have the same name like the group in pureperms
# add all groups that exists, and add the order by giving them a value from 1 to X
# where 1 is the lowest rank and X the highest
Ranks:
    Guest: 1
    rank2: 2
    rank3: 3
    rank4: 4
    rank5: 5

VoteRanks:
    Guest: 1
    rank2: 2
    rank3: 3

# time to reach this rank, ranks must have the same name like in pureperms
AutoRanks:
    Guest: true
    rank1: 20
    rank2: 60
    rank3: 120
    rank4: 240
    rank5: 720

#messages
Messages:
    command-in-game: "Command must be used in-game."
    error-fetching-vote: "[RankUp] Error fetching vote status! Try again later."
    no-permission: "You do not have permission to vote."
    pureperms-loaded: "Successfully loaded with PurePerms"
    pureperms-notfound: "Dependency PurePerms not found"
    rank-new: "You are now rank ##rank##"
    rank-failed: "RankUp failed with rank: ##rank##"
    vote-nextday: "You've already voted today! Come back tomorrow to vote again."
    vote-success: "##player## voted with /vote and got a higher rank!"
    vote-open: "You haven't voted yet!\n +++ ##voteurl## +++ \nVote to get higher rank!"
    timer-usage: "Use /tr check ##player##"
    timer-neverplayed: "Player ##player## never played on this server"
    timer-newplayer: "##player## has played less than 1 minute on this server"
    timer-newrank: "##player## reached new Rank: ##rank##"
    timer-rankis: "Rank is: ##rank##"
    timer-timeplayed: "You have played ##timeplayed## minutes on this server.\n ##timetoplay## minutes until next rankup"
    timer-timeplayer: "Has played ##timeplayed## minutes on this server"
```

##Permissions

```
permissions:
    voteranks.command:
        description: "rankup a player as vote reward"
        default: true
    timeranks.command:
        description: "check time to play for next rank"
        default: true
```