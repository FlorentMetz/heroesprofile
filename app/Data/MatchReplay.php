<?php
namespace App\Data;

class MatchReplay
{
  private $replayID;

  public function __construct($replayID) {
    $this->replayID = $replayID;
    $match_data = $this->getMatchData();
  }


  private function getMatchData(){
    $replay_data = \App\Models\Replay::select(
      "replay.replayID",
      "replay.game_type",
      "replay.game_date",
      "replay.game_length",
      "replay.game_map",
      "replay.region",
      "player.hero",
      "player.hero_level",
      "player.team",
      "player.winner",
      "player.player_conservative_rating",
      "player.player_change",
      "player.hero_conservative_rating",
      "player.hero_change",
      "player.role_conservative_rating",
      "player.role_change",
      "player.mmr_date_parsed",
      "scores.kills",
      "scores.takedowns",
      "scores.deaths",
      "scores.first_to_ten",
      "talents.level_one",
      "talents.level_four",
      "talents.level_seven",
      "talents.level_ten",
      "talents.level_thirteen",
      "talents.level_sixteen",
      "talents.level_twenty"
      )
      ->join('player', 'player.replayID', '=', 'replay.replayID')
      ->join('scores', function($join)
      {
        $join->on('scores.replayID', '=', 'replay.replayID');
        $join->on('scores.battletag', '=', 'player.battletag');
      }
      )
      ->join('talents', function($join)
      {
        $join->on('talents.replayID', '=', 'replay.replayID');
        $join->on('talents.battletag', '=', 'player.battletag');
      }
      )
      ->where('replay.replayID', '=', $this->replayID)
      ->get();
      for($i = 0; $i < count($replay_data); $i++){
      }
      return $replay_data;
    }
    /*
    private function getReplayBans($replayID){
      $replay_bans = \App\Models\ReplayBan::where('replayID', $replayID)->get();
      return $replay_bans;
    }

    public function getReplayExperienceBreakdown($replayID){
      $replay_bans = \App\Models\ReplayExperienceBreakdown::where('replayID', $replayID)->get();
      return $replay_bans;
    }

    */
  }