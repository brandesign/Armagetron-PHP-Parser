# Armagetron-PHP-Parser #

A framework to handle ladderlog input from [Armagetron](http://armagetronad.net/)

## Usage ##

Create a file with the following lines:

    #!/usr/bin/php
    <?php namespace Armagetron;
    require_once('/path/to/bootstrap.php');
    
    class Parser extends Parser\Main
    {
        
    }
    
    Parser::run();

Inside your Parser class you can define a function for any ladderlog line.
For example:

    // PLAYER_ENTERED <name> <ip> <screen_name>
    protected static function player_entered($name, $ip, $screenName)
    {
        // do something
    }

The ladderlog arguments should match with your function arguments.
The function must be static and not private.

## Configuration ##

Configuration is done inside config.json

    {
        "extraParser" : "StyCt",
        "input" : "stdin",
        "output" : "stdout",
        "debug" : true
    }

extraParser: 'StyCt' (sty+ct server), 'Trunk' (trunk server) or false (any other server)
input: currently only supports 'stdin'
output: 'stdout' or '/path/to/file'
debug: true or false. Writes some debug messages into server log.

## Available Classes ##

The script automaticly saves players and teams into memory.

### Get a player ###
    $player = Player::get($id); // where $id is the player name
    $all_players = Player::getAll(); // array with all players
    
### Store a player attribute ###
    Player::get($id)->attribute_name = 'value';

### Default player attributes ###
#### Always available ####
    $player->name
    $player->ip
    $player->screenName
    $player->ping
    $player->joined // timestamp
    $player->is_human
    $player->team // Team object

#### Available in StyCt ####
    $player->red
    $player->green
    $player->blue
    $player->xpos
    $player->ypos
    $player->xdir
    $player->ydir
    $player->authenticated // true or false

#### Available in Trunk ####
    $player->accessLevel

### Available functions for Player ###
    Player::get($id)
        ->kick()
        ->ban()
        ->kill()
        ->suspend() // optional pass the number of rounds here. Default = 5
        ->unsuspend()
        ->message('Some message') // Performs PLAYER_MESSAGE <player> <message>
        ->online_time(); // returns the online time in seconds.
        
### Get a team ###
    $team = Team::get($id); // where $id is the teams name
    $all_teams = Team::getAll(); // array with all teams

### Default team attributes ###
    $team->players // array with player objects

## The Attribute class ##
By default, encoding and game_time are stored. To get a stored value use:
    Attribute::get($key, $default); // $default is optional and will be returned if the value does not exist.
To get the encoding just use:
    Attribute::get('encoding');
To get the game time use:
    Attribute::get('game_time');
To store your own attributes use:
    Attribute::set($key, $value);
    
