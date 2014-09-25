# Armagetron-PHP-Parser #

A framework to handle ladderlog input from [Armagetron](http://armagetronad.net/)

## Usage ##

```PHP
    <?php
    require_once('vendor/autoload.php');

    use Armagetron\Parser\ParserInterface;

    class Example implements ParserInterface
    {
        public function playerEntered(Event $event)
        {
            // Do something with $event
        }
    }
    
    $parser = new Parser(new Example());

    $parser->run();
```
Inside your Parser class you can define a function for any ladderlog line.
For example:
```PHP
    // PLAYER_ENTERED <name> <ip> <screen_name>

    // CamelCase
    public function playerEntered(Event $event)
    {
        // Do something with $event
    }

    // or under_score
    protected function player_entered($event)
    {
        // Do something with $event
    }
```
$event is an object of Armagetron\Event (see below)

Also see example.php

## Available Classes ##

The script automaticly saves players and teams into memory.

### Get a player ###
```PHP
    $player = $event->getGameObjects()->getPlayers()->getById($id); // where $id is the player name/gid
    $all_players = $event->getGameObjects()->getPlayers(); // array with all players
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
    $team = $event->getGameObjects()->getTeams()->getById($id); // where $id is the teams name
    $all_players = $event->getGameObjects()->getTeams(); // array with all teams
```

### Default team attributes ###
```PHP
    $team->getPlayers(); // array with player objects
```

### The Command class ###
```PHP
    Command::raw($s); // write $s\n to output
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
    Command::sinclude($filename);
```

## The Event Object ##

### Common Servers ###
```PHP
public function authority_blurb( $event )
```
      $event => Class of Armagetron\Event\Event (
            blurb => string
            player => Object of Armagetron\GameObject\Player
            text => string
      )

```PHP
public function basezone_conquered( $event )
```
      $event => Class of Armagetron\Event\Event (
            team => Object of Armagetron\GameObject\Team
            x => double
            y => double
      )

```PHP
public function basezone_conquerer( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
      )

```PHP
public function chat( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
            text => string
      )

```PHP
public function command( $event )
```
      $event => Class of Armagetron\Event\Event (
            command => string
            player => Object of Armagetron\GameObject\Player
            text => string
      )

```PHP
public function death_frag( $event )
```
      $event => Class of Armagetron\Event\Event (
            prey => Object of Armagetron\GameObject\Player
            hunter => Object of Armagetron\GameObject\Player
      )

```PHP
public function death_suicide( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
      )

```PHP
public function death_teamkill( $event )
```
      $event => Class of Armagetron\Event\Event (
            prey => Object of Armagetron\GameObject\Player
            hunter => Object of Armagetron\GameObject\Player
      )

```PHP
public function encoding( $event )
```
      $event => Class of Armagetron\Event\Event (
            charset => string
      )

```PHP
public function game_end( $event )
```
      $event => Class of Armagetron\Event\Event (
            time_string => string
      )

```PHP
public function game_time( $event )
```
      $event => Class of Armagetron\Event\Event (
            time => integer
      )

```PHP
public function match_winner( $event )
```
      $event => Class of Armagetron\Event\Event (
            team => Object of Armagetron\GameObject\Team
            players => Array(
                  Object of Armagetron\GameObject\Player
                  ...
            )
      )

```PHP
public function new_match( $event )
```
      $event => Class of Armagetron\Event\Event (
            time_string => string
      )

```PHP
public function new_round( $event )
```
      $event => Class of Armagetron\Event\Event (
            time_string => string
      )

```PHP
public function num_humans( $event )
```
      $event => Class of Armagetron\Event\Event (
            number_humans => integer
      )

```PHP
public function online_player( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
            ping => double
            team => Object of Armagetron\GameObject\Team
      )

```PHP
public function player_entered( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
            ip => string
            screen_name => string
      )

```PHP
public function player_left( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => string
            ip => string
      )

```PHP
public function player_renamed( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
            new_name => string
            ip => string
            screen_name => string
      )

```PHP
public function positions( $event )
```
      $event => Class of Armagetron\Event\Event (
            team => Object of Armagetron\GameObject\Team
            players => Array(
                  Object of Armagetron\GameObject\Player
                  ...
            )
      )

```PHP
public function round_score( $event )
```
      $event => Class of Armagetron\Event\Event (
            score => integer
            player => Object of Armagetron\GameObject\Player
            team => Object of Armagetron\GameObject\Team
      )

```PHP
public function round_score_team( $event )
```
      $event => Class of Armagetron\Event\Event (
            score => integer
            team => Object of Armagetron\GameObject\Team
      )

```PHP
public function round_winner( $event )
```
      $event => Class of Armagetron\Event\Event (
            team => Object of Armagetron\GameObject\Team
            players => Array(
                  Object of Armagetron\GameObject\Player
                  ...
            )
      )

```PHP
public function sacrifice( $event )
```
      $event => Class of Armagetron\Event\Event (
            hole_user => Object of Armagetron\GameObject\Player
            hole_maker => Object of Armagetron\GameObject\Player
            wall_owner => Object of Armagetron\GameObject\Player
      )

```PHP
public function team_created( $event )
```
      $event => Class of Armagetron\Event\Event (
            team => Object of Armagetron\GameObject\Team
      )

```PHP
public function team_destroyed( $event )
```
      $event => Class of Armagetron\Event\Event (
            team => string
      )

```PHP
public function team_player_added( $event )
```
      $event => Class of Armagetron\Event\Event (
            team => Object of Armagetron\GameObject\Team
            player => Object of Armagetron\GameObject\Player
      )

```PHP
public function team_player_removed( $event )
```
      $event => Class of Armagetron\Event\Event (
            team => Object of Armagetron\GameObject\Team
            player => Object of Armagetron\GameObject\Player
      )

```PHP
public function team_renamed( $event )
```
      $event => Class of Armagetron\Event\Event (
            team => Object of Armagetron\GameObject\Team
            new_name => string
      )

```PHP
public function wait_for_external_script( $event )
```
      $event => Class of Armagetron\Event\Event (
      )

### For Trunk Servers ###
```PHP
public function death_deathzone( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
      )

```PHP
public function death_explosion( $event )
```
      $event => Class of Armagetron\Event\Event (
            prey => Object of Armagetron\GameObject\Player
            hunter => Object of Armagetron\GameObject\Player
      )

```PHP
public function matches_left( $event )
```
      $event => Class of Armagetron\Event\Event (
            number_matches => integer
      )

```PHP
public function new_warmup( $event )
```
      $event => Class of Armagetron\Event\Event (
            number_matches => integer
            time_string => string
      )

```PHP
public function online_player( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
            ping => double
            team => Object of Armagetron\GameObject\Team
            access_level => integer
      )

```PHP
public function player_respawn( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
            team => Object of Armagetron\GameObject\Team
            respawner_team => Object of Armagetron\GameObject\Team
      )

```PHP
public function winzone_player_enter( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
      )

### For sty+ct Servers ###
```PHP
public function admin_command( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
            ip => string
            access_level => integer
            command => string
      )

```PHP
public function admin_login( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
            ip => string
      )

```PHP
public function admin_logout( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
            ip => string
      )

```PHP
public function ball_vanish( $event )
```
      $event => Class of Armagetron\Event\Event (
            goid => string
            zone_name => string
            x => double
            y => double
      )

```PHP
public function basezone_conquered( $event )
```
      $event => Class of Armagetron\Event\Event (
            team => Object of Armagetron\GameObject\Team
            x => double
            y => double
            enemies_in_zone => Array(
                  Object of Armagetron\GameObject\Player
                  ...
            )
      )

```PHP
public function basezone_conquerer( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
            percent_won => string
      )

```PHP
public function basezone_conquerer_team( $event )
```
      $event => Class of Armagetron\Event\Event (
            team => Object of Armagetron\GameObject\Team
            score => string
      )

```PHP
public function base_enemy_respawn( $event )
```
      $event => Class of Armagetron\Event\Event (
            respawner => Object of Armagetron\GameObject\Player
            player_respawned => string
      )

```PHP
public function base_respawn( $event )
```
      $event => Class of Armagetron\Event\Event (
            respawner => Object of Armagetron\GameObject\Player
            player_respawned => string
      )

```PHP
public function command( $event )
```
      $event => Class of Armagetron\Event\Event (
            command => string
            player => Object of Armagetron\GameObject\Player
            ip => string
            access_level => integer
            text => string
      )

```PHP
public function cycle_created( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
            x => double
            y => double
            x_dir => integer
            y_dir => integer
      )

```PHP
public function death_basezone_conquered( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
            enemies_in_zone => integer
      )

```PHP
public function death_deathshot( $event )
```
      $event => Class of Armagetron\Event\Event (
            prey => Object of Armagetron\GameObject\Player
            hunter => Object of Armagetron\GameObject\Player
      )

```PHP
public function death_deathzone( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
      )

```PHP
public function death_rubberzone( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
      )

```PHP
public function death_self_destruct( $event )
```
      $event => Class of Armagetron\Event\Event (
            prey => Object of Armagetron\GameObject\Player
            hunter => Object of Armagetron\GameObject\Player
      )

```PHP
public function death_shot_frag( $event )
```
      $event => Class of Armagetron\Event\Event (
            prey => Object of Armagetron\GameObject\Player
            hunter => Object of Armagetron\GameObject\Player
      )

```PHP
public function death_shot_suicide( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
      )

```PHP
public function death_shot_teamkill( $event )
```
      $event => Class of Armagetron\Event\Event (
            prey => Object of Armagetron\GameObject\Player
            hunter => Object of Armagetron\GameObject\Player
      )

```PHP
public function death_zombiezone( $event )
```
      $event => Class of Armagetron\Event\Event (
            prey => Object of Armagetron\GameObject\Player
            hunter => Object of Armagetron\GameObject\Player
      )

```PHP
public function end_challenge( $event )
```
      $event => Class of Armagetron\Event\Event (
            time_string => string
      )

```PHP
public function flag_conquest_round_win( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
            flag_team => Object of Armagetron\GameObject\Team
      )

```PHP
public function flag_drop( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
            flag_team => Object of Armagetron\GameObject\Team
      )

```PHP
public function flag_held( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
      )

```PHP
public function flag_return( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
      )

```PHP
public function flag_score( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
            flag_team => Object of Armagetron\GameObject\Team
      )

```PHP
public function flag_take( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
            flag_team => Object of Armagetron\GameObject\Team
      )

```PHP
public function flag_timeout( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
            flag_team => Object of Armagetron\GameObject\Team
      )

```PHP
public function invalid_command( $event )
```
      $event => Class of Armagetron\Event\Event (
            command => string
            player => Object of Armagetron\GameObject\Player
            ip => string
            access_level => integer
            text => string
      )

```PHP
public function match_score( $event )
```
      $event => Class of Armagetron\Event\Event (
            score => integer
            player => Object of Armagetron\GameObject\Player
            team => Object of Armagetron\GameObject\Team
      )

```PHP
public function match_score_team( $event )
```
      $event => Class of Armagetron\Event\Event (
            score => integer
            team => Object of Armagetron\GameObject\Team
            sets_won => integer
      )

```PHP
public function new_set( $event )
```
      $event => Class of Armagetron\Event\Event (
            sets_played => integer
            time_string => string
      )

```PHP
public function next_round( $event )
```
      $event => Class of Armagetron\Event\Event (
            round => integer
            limit_rounds => integer
            map => string
            round_center_message => string
      )

```PHP
public function online_player( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
            r => integer
            g => integer
            b => integer
            ping => double
            team => Object of Armagetron\GameObject\Team
      )

```PHP
public function player_gridpos( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
            x => double
            y => double
            x_dir => integer
            y_dir => integer
            team => Object of Armagetron\GameObject\Team
      )

```PHP
public function player_killed( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
            ip => string
            x => double
            y => double
            x_dir => integer
            y_dir => integer
      )

```PHP
public function player_renamed( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
            new_name => string
            ip => string
            authenticated => boolean
            screen_name => string
      )

```PHP
public function round_commencing( $event )
```
      $event => Class of Armagetron\Event\Event (
            round => integer
            limit_rounds => integer
      )

```PHP
public function set_winner( $event )
```
      $event => Class of Armagetron\Event\Event (
            team => Object of Armagetron\GameObject\Team
      )

```PHP
public function spawn_position_team( $event )
```
      $event => Class of Armagetron\Event\Event (
            team => Object of Armagetron\GameObject\Team
            position => integer
      )

```PHP
public function start_challenge( $event )
```
      $event => Class of Armagetron\Event\Event (
            time_string => string
      )

```PHP
public function svg_created( $event )
```
      $event => Class of Armagetron\Event\Event (
      )

```PHP
public function tactical_position( $event )
```
      $event => Class of Armagetron\Event\Event (
            time => double
            player => Object of Armagetron\GameObject\Player
            tactical_position => string
      )

```PHP
public function tactical_statistics( $event )
```
      $event => Class of Armagetron\Event\Event (
            tactical_position => string
            player => Object of Armagetron\GameObject\Player
            time => double
            state => string
            kills => integer
      )

```PHP
public function targetzone_conquered( $event )
```
      $event => Class of Armagetron\Event\Event (
            goid => integer
            zone_name => string
            x => double
            y => double
            player => Object of Armagetron\GameObject\Player
            team => Object of Armagetron\GameObject\Team
      )

```PHP
public function targetzone_player_enter( $event )
```
      $event => Class of Armagetron\Event\Event (
            goid => integer
            zone_name => string
            zone_x => double
            zone_y => double
            player => Object of Armagetron\GameObject\Player
            player_x => double
            player_y => double
            player_x_dir => integer
            player_y_dir => integer
            time => double
      )

```PHP
public function targetzone_player_left( $event )
```
      $event => Class of Armagetron\Event\Event (
            goid => integer
            zone_name => string
            zone_x => double
            zone_y => double
            player => Object of Armagetron\GameObject\Player
            player_x => double
            player_y => double
            player_x_dir => integer
            player_y_dir => integer
      )

```PHP
public function targetzone_timeout( $event )
```
      $event => Class of Armagetron\Event\Event (
            goid => string
            zone_name => string
            x => double
            y => double
      )

```PHP
public function voter( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
            choice => boolean
            description => string
      )

```PHP
public function vote_created( $event )
```
      $event => Class of Armagetron\Event\Event (
            player => Object of Armagetron\GameObject\Player
            description => string
      )

```PHP
public function winzone_player_enter( $event )
```
      $event => Class of Armagetron\Event\Event (
            goid => integer
            zone_name => string
            zone_x => double
            zone_y => double
            player => Object of Armagetron\GameObject\Player
            player_x => double
            player_y => double
            player_x_dir => integer
            player_y_dir => integer
            time => double
      )

```PHP
public function zone_collapsed( $event )
```
      $event => Class of Armagetron\Event\Event (
            zone_id => integer
            zone_name => string
            zone_x => double
            zone_y => double
      )

```PHP
public function zone_spawned( $event )
```
      $event => Class of Armagetron\Event\Event (
            goid => integer
            zone_name => string
            x => double
            y => double
      )

```PHP
public function wait_for_external_script( $event )
```
      $event => Class of Armagetron\Event\Event (
      )

