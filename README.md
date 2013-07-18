# Armagetron-PHP-Parser #

A framework to handle ladderlog input from [Armagetron](http://armagetronad.net/)

## Usage ##

Create a file with the following lines:
```PHP
    #!/usr/bin/php
    <?php
    require_once('bootstrap.php');

    class Example extends Armagetron\Parser\Main
    {
        
    }
    
    Example::run();
```
Inside your Parser class you can define a function for any ladderlog line.
For example:
```PHP
    // PLAYER_ENTERED <name> <ip> <screen_name>
    protected function player_entered($event)
    {
        // do something
    }
```
$event is an object of Armagetron\Event (see below)

Also see example.php

## Configuration ##
Configuration is done inside config.json
```JSON
    {
        "intern_parser" : "StyCt",
        "input" : "stdin",
        "output" : "stdout",
        "debug" : true
    }
```
intern_parser: 'StyCt' (sty+ct server), 'Trunk' (trunk server) or false (any other server)

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
    $player->team_kills
    $player->kills
    $player->deaths
    $player->suicides
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

## The Event Object ##

### Common Servers ###
```PHP
protected function authority_blurb( $event )
```
	$event => Class of Armagetron\Event (
		blurb => string
		player => Object of Armagetron\Player
		text => string
	)

```PHP
protected function basezone_conquered( $event )
```
	$event => Class of Armagetron\Event (
		team => Object of Armagetron\Team
		x => double
		y => double
	)

```PHP
protected function basezone_conquerer( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
	)

```PHP
protected function chat( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
		text => string
	)

```PHP
protected function command( $event )
```
	$event => Class of Armagetron\Event (
		command => string
		player => Object of Armagetron\Player
		text => string
	)

```PHP
protected function death_frag( $event )
```
	$event => Class of Armagetron\Event (
		prey => Object of Armagetron\Player
		hunter => Object of Armagetron\Player
	)

```PHP
protected function death_suicide( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
	)

```PHP
protected function death_teamkill( $event )
```
	$event => Class of Armagetron\Event (
		prey => Object of Armagetron\Player
		hunter => Object of Armagetron\Player
	)

```PHP
protected function encoding( $event )
```
	$event => Class of Armagetron\Event (
		charset => string
	)

```PHP
protected function game_end( $event )
```
	$event => Class of Armagetron\Event (
		time_string => string
	)

```PHP
protected function game_time( $event )
```
	$event => Class of Armagetron\Event (
		time => integer
	)

```PHP
protected function match_winner( $event )
```
	$event => Class of Armagetron\Event (
		team => Object of Armagetron\Team
		players => Array(
			Object of Armagetron\Player
			...
		)
	)

```PHP
protected function new_match( $event )
```
	$event => Class of Armagetron\Event (
		time_string => string
	)

```PHP
protected function new_round( $event )
```
	$event => Class of Armagetron\Event (
		time_string => string
	)

```PHP
protected function num_humans( $event )
```
	$event => Class of Armagetron\Event (
		number_humans => integer
	)

```PHP
protected function online_player( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
		ping => double
		team => Object of Armagetron\Team
	)

```PHP
protected function player_entered( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
		ip => string
		screen_name => string
	)

```PHP
protected function player_left( $event )
```
	$event => Class of Armagetron\Event (
		player => string
		ip => string
	)

```PHP
protected function player_renamed( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
		new_name => string
		ip => string
		screen_name => string
	)

```PHP
protected function positions( $event )
```
	$event => Class of Armagetron\Event (
		team => Object of Armagetron\Team
		players => Array(
			Object of Armagetron\Player
			...
		)
	)

```PHP
protected function round_score( $event )
```
	$event => Class of Armagetron\Event (
		score => integer
		player => Object of Armagetron\Player
		team => Object of Armagetron\Team
	)

```PHP
protected function round_score_team( $event )
```
	$event => Class of Armagetron\Event (
		score => integer
		team => Object of Armagetron\Team
	)

```PHP
protected function round_winner( $event )
```
	$event => Class of Armagetron\Event (
		team => Object of Armagetron\Team
		players => Array(
			Object of Armagetron\Player
			...
		)
	)

```PHP
protected function sacrifice( $event )
```
	$event => Class of Armagetron\Event (
		hole_user => Object of Armagetron\Player
		hole_maker => Object of Armagetron\Player
		wall_owner => Object of Armagetron\Player
	)

```PHP
protected function team_created( $event )
```
	$event => Class of Armagetron\Event (
		team => Object of Armagetron\Team
	)

```PHP
protected function team_destroyed( $event )
```
	$event => Class of Armagetron\Event (
		team => string
	)

```PHP
protected function team_player_added( $event )
```
	$event => Class of Armagetron\Event (
		team => Object of Armagetron\Team
		player => Object of Armagetron\Player
	)

```PHP
protected function team_player_removed( $event )
```
	$event => Class of Armagetron\Event (
		team => Object of Armagetron\Team
		player => Object of Armagetron\Player
	)

```PHP
protected function team_renamed( $event )
```
	$event => Class of Armagetron\Event (
		team => Object of Armagetron\Team
		new_name => string
	)

```PHP
protected function wait_for_external_script( $event )
```
	$event => Class of Armagetron\Event (
	)

### For Trunk Servers ###
```PHP
protected function death_deathzone( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
	)

```PHP
protected function death_explosion( $event )
```
	$event => Class of Armagetron\Event (
		prey => Object of Armagetron\Player
		hunter => Object of Armagetron\Player
	)

```PHP
protected function matches_left( $event )
```
	$event => Class of Armagetron\Event (
		number_matches => integer
	)

```PHP
protected function new_warmup( $event )
```
	$event => Class of Armagetron\Event (
		number_matches => integer
		time_string => string
	)

```PHP
protected function online_player( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
		ping => double
		team => Object of Armagetron\Team
		access_level => integer
	)

```PHP
protected function player_respawn( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
		team => Object of Armagetron\Team
		respawner_team => Object of Armagetron\Team
	)

```PHP
protected function winzone_player_enter( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
	)

### For sty+ct Servers ###
```PHP
protected function admin_command( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
		ip => string
		access_level => integer
		command => string
	)

```PHP
protected function admin_login( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
		ip => string
	)

```PHP
protected function admin_logout( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
		ip => string
	)

```PHP
protected function ball_vanish( $event )
```
	$event => Class of Armagetron\Event (
		goid => string
		zone_name => string
		x => double
		y => double
	)

```PHP
protected function basezone_conquered( $event )
```
	$event => Class of Armagetron\Event (
		team => Object of Armagetron\Team
		x => double
		y => double
		enemies_in_zone => Array(
			Object of Armagetron\Player
			...
		)
	)

```PHP
protected function basezone_conquerer( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
		percent_won => string
	)

```PHP
protected function basezone_conquerer_team( $event )
```
	$event => Class of Armagetron\Event (
		team => Object of Armagetron\Team
		score => string
	)

```PHP
protected function base_enemy_respawn( $event )
```
	$event => Class of Armagetron\Event (
		respawner => Object of Armagetron\Player
		player_respawned => string
	)

```PHP
protected function base_respawn( $event )
```
	$event => Class of Armagetron\Event (
		respawner => Object of Armagetron\Player
		player_respawned => string
	)

```PHP
protected function command( $event )
```
	$event => Class of Armagetron\Event (
		command => string
		player => Object of Armagetron\Player
		ip => string
		access_level => integer
		text => string
	)

```PHP
protected function cycle_created( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
		x => double
		y => double
		x_dir => integer
		y_dir => integer
	)

```PHP
protected function death_basezone_conquered( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
		enemies_in_zone => integer
	)

```PHP
protected function death_deathshot( $event )
```
	$event => Class of Armagetron\Event (
		prey => Object of Armagetron\Player
		hunter => Object of Armagetron\Player
	)

```PHP
protected function death_deathzone( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
	)

```PHP
protected function death_rubberzone( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
	)

```PHP
protected function death_self_destruct( $event )
```
	$event => Class of Armagetron\Event (
		prey => Object of Armagetron\Player
		hunter => Object of Armagetron\Player
	)

```PHP
protected function death_shot_frag( $event )
```
	$event => Class of Armagetron\Event (
		prey => Object of Armagetron\Player
		hunter => Object of Armagetron\Player
	)

```PHP
protected function death_shot_suicide( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
	)

```PHP
protected function death_shot_teamkill( $event )
```
	$event => Class of Armagetron\Event (
		prey => Object of Armagetron\Player
		hunter => Object of Armagetron\Player
	)

```PHP
protected function death_zombiezone( $event )
```
	$event => Class of Armagetron\Event (
		prey => Object of Armagetron\Player
		hunter => Object of Armagetron\Player
	)

```PHP
protected function end_challenge( $event )
```
	$event => Class of Armagetron\Event (
		time_string => string
	)

```PHP
protected function flag_conquest_round_win( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
		flag_team => Object of Armagetron\Team
	)

```PHP
protected function flag_drop( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
		flag_team => Object of Armagetron\Team
	)

```PHP
protected function flag_held( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
	)

```PHP
protected function flag_return( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
	)

```PHP
protected function flag_score( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
		flag_team => Object of Armagetron\Team
	)

```PHP
protected function flag_take( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
		flag_team => Object of Armagetron\Team
	)

```PHP
protected function flag_timeout( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
		flag_team => Object of Armagetron\Team
	)

```PHP
protected function invalid_command( $event )
```
	$event => Class of Armagetron\Event (
		command => string
		player => Object of Armagetron\Player
		ip => string
		access_level => integer
		text => string
	)

```PHP
protected function match_score( $event )
```
	$event => Class of Armagetron\Event (
		score => integer
		player => Object of Armagetron\Player
		team => Object of Armagetron\Team
	)

```PHP
protected function match_score_team( $event )
```
	$event => Class of Armagetron\Event (
		score => integer
		team => Object of Armagetron\Team
		sets_won => integer
	)

```PHP
protected function new_set( $event )
```
	$event => Class of Armagetron\Event (
		sets_played => integer
		time_string => string
	)

```PHP
protected function next_round( $event )
```
	$event => Class of Armagetron\Event (
		round => integer
		limit_rounds => integer
		map => string
		round_center_message => string
	)

```PHP
protected function online_player( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
		r => integer
		g => integer
		b => integer
		ping => double
		team => Object of Armagetron\Team
	)

```PHP
protected function player_gridpos( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
		x => double
		y => double
		x_dir => integer
		y_dir => integer
		team => Object of Armagetron\Team
	)

```PHP
protected function player_killed( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
		ip => string
		x => double
		y => double
		x_dir => integer
		y_dir => integer
	)

```PHP
protected function player_renamed( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
		new_name => string
		ip => string
		authenticated => boolean
		screen_name => string
	)

```PHP
protected function round_commencing( $event )
```
	$event => Class of Armagetron\Event (
		round => integer
		limit_rounds => integer
	)

```PHP
protected function set_winner( $event )
```
	$event => Class of Armagetron\Event (
		team => Object of Armagetron\Team
	)

```PHP
protected function spawn_position_team( $event )
```
	$event => Class of Armagetron\Event (
		team => Object of Armagetron\Team
		position => integer
	)

```PHP
protected function start_challenge( $event )
```
	$event => Class of Armagetron\Event (
		time_string => string
	)

```PHP
protected function svg_created( $event )
```
	$event => Class of Armagetron\Event (
	)

```PHP
protected function tactical_position( $event )
```
	$event => Class of Armagetron\Event (
		time => double
		player => Object of Armagetron\Player
		tactical_position => string
	)

```PHP
protected function tactical_statistics( $event )
```
	$event => Class of Armagetron\Event (
		tactical_position => string
		player => Object of Armagetron\Player
		time => double
		state => string
		kills => integer
	)

```PHP
protected function targetzone_conquered( $event )
```
	$event => Class of Armagetron\Event (
		goid => integer
		zone_name => string
		x => double
		y => double
		player => Object of Armagetron\Player
		team => Object of Armagetron\Team
	)

```PHP
protected function targetzone_player_enter( $event )
```
	$event => Class of Armagetron\Event (
		goid => integer
		zone_name => string
		zone_x => double
		zone_y => double
		player => Object of Armagetron\Player
		player_x => double
		player_y => double
		player_x_dir => integer
		player_y_dir => integer
		time => double
	)

```PHP
protected function targetzone_player_left( $event )
```
	$event => Class of Armagetron\Event (
		goid => integer
		zone_name => string
		zone_x => double
		zone_y => double
		player => Object of Armagetron\Player
		player_x => double
		player_y => double
		player_x_dir => integer
		player_y_dir => integer
	)

```PHP
protected function targetzone_timeout( $event )
```
	$event => Class of Armagetron\Event (
		goid => string
		zone_name => string
		x => double
		y => double
	)

```PHP
protected function voter( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
		choice => boolean
		description => string
	)

```PHP
protected function vote_created( $event )
```
	$event => Class of Armagetron\Event (
		player => Object of Armagetron\Player
		description => string
	)

```PHP
protected function winzone_player_enter( $event )
```
	$event => Class of Armagetron\Event (
		goid => integer
		zone_name => string
		zone_x => double
		zone_y => double
		player => Object of Armagetron\Player
		player_x => double
		player_y => double
		player_x_dir => integer
		player_y_dir => integer
		time => double
	)

```PHP
protected function zone_collapsed( $event )
```
	$event => Class of Armagetron\Event (
		zone_id => integer
		zone_name => string
		zone_x => double
		zone_y => double
	)

```PHP
protected function zone_spawned( $event )
```
	$event => Class of Armagetron\Event (
		goid => integer
		zone_name => string
		x => double
		y => double
	)

```PHP
protected function wait_for_external_script( $event )
```
	$event => Class of Armagetron\Event (
	)

