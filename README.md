# Armagetron-PHP-Parser #

A framework to handle ladderlog input from [Armagetron](http://armagetronad.net/)

## Usage ##

Create a file with the following lines:
```PHP
    #!/usr/bin/php
    <?php namespace Armagetron;
    require_once('/path/to/bootstrap.php');
    
    class Parser extends Parser\Main
    {
        
    }
    
    Parser::run();
```
Inside your Parser class you can define a function for any ladderlog line.
For example:
```PHP
    // PLAYER_ENTERED <name> <ip> <screen_name>
    protected static function player_entered($name, $ip, $screenName)
    {
        // do something
    }
```
The ladderlog arguments should match with your function arguments.
The function must be static and not private.

Also see example.php

## Configuration ##
Configuration is done inside config.json
```JSON
    {
        "extraParser" : "StyCt",
        "input" : "stdin",
        "output" : "stdout",
        "debug" : true
    }
```
extraParser: 'StyCt' (sty+ct server), 'Trunk' (trunk server) or false (any other server)

input: currently only supports 'stdin'

output: 'stdout' or '/path/to/file'

debug: true or false. Writes some debug messages into server log.

## Available Classes ##

The script automaticly saves players and teams into memory.

### Get a player ###
```PHP
    $player = Player::get($id); // where $id is the player name
    $all_players = Player::getAll(); // array with all players
```
    
### Store a player attribute ###
```PHP
    Player::get($id)->attribute_name = 'value';
```

### Default player attributes ###
#### Always available ####
```PHP
    $player->name
    $player->ip
    $player->screenName
    $player->ping
    $player->joined // timestamp
    $player->is_human
    $player->team // Team object
```
#### Available in StyCt ####
```PHP
    $player->red
    $player->green
    $player->blue
    $player->xpos
    $player->ypos
    $player->xdir
    $player->ydir
    $player->authenticated // true or false
```

#### Available in Trunk ####
```PHP
    $player->accessLevel
```

### Available functions for Player ###
```PHP
    Player::get($id)
        ->kick()
        ->ban()
        ->kill()
        ->suspend() // optional pass the number of rounds here. Default = 5
        ->unsuspend()
        ->message('Some message') // Performs PLAYER_MESSAGE <player> <message>
        ->online_time(); // returns the online time in seconds.
```
        
### Get a team ###
```PHP
    $team = Team::get($id); // where $id is the teams name
    $all_teams = Team::getAll(); // array with all teams
```

### Default team attributes ###
```PHP
    $team->players // array with player objects
```

### The Command class ###
```PHP
    Command::write($s); // write $s\n to Config::get('output')
    Command::comment($s); // write a comment to the log file
    Command::say($s); // SAY $s
    Command::console_message($s); // CONSOLE_MESSAGE $s
    Command::center_message($s); // CENTER_MESSAGE $s
    Command::player_message(Player $player, $message); // send $message to $player
    Command::kill(Player $player);
    Command::kick(Player $player);
    Command::ban_player(Player $player, $minutes = 5);
    Command::ban_ip($ip, $minutes = 5);
    Command::suspend(Player $player, $rounds = null);
    Command::unsuspend(Player $player);
    Command::include($filename);
    Command::sinclude($filename);
```

### The Attribute class ###
By default, encoding and game_time are stored. To get a stored value use:
```PHP
    Attribute::get($key, $default); // $default is optional and will be returned if the value does not exist.
```

To get the encoding just use:
```PHP
    Attribute::get('encoding');
```

To get the game time use:
```PHP
    Attribute::get('game_time');
```

To store your own attributes use:
```PHP
    Attribute::set($key, $value);
```