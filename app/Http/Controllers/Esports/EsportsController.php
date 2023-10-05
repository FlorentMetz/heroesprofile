<?php

namespace App\Http\Controllers\Esports;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Rules\NGSSeasonInputValidation;
use App\Rules\NGSDivisionInputValidation;
use App\Rules\HeroInputValidation;
use App\Rules\GameMapInputValidation;

use App\Models\NGS\Team;
use App\Models\Map;

class EsportsController extends Controller
{
    private $esport;
    private $schema;

    private $battletag;
    private $blizz_id;
    private $season;
    private $division;
    private $team;
    private $hero;
    private $game_map;

    public function show(Request $request){
        return view('Esports.esportsMain')  
            ->with([
                //'filters' => $this->globalDataService->getFilterData(),
                //'gametypedefault' => $this->globalDataService->getGameTypeDefault(),
                //'defaulttimeframetype' => $this->globalDataService->getDefaultTimeframeType(),
                //'defaulttimeframe' => [$this->globalDataService->getDefaultTimeframe()],
                //'defaultbuildtype' => $this->globalDataService->getDefaultBuildType()
            ]);
    }

    public function showSingleTeam(Request $request, $esport, $team){
        $validationRules = [
            'esport' => 'required|in:NGS',
            'team' => 'required|string',
        ];

        $otherValidationRules = [
            'division' => 'nullable|string',
            'season' => 'nullable|numeric',
        ];

        $validator = Validator::make(compact('esport', 'team'), $validationRules);

        $otherValidator = Validator::make($request->all(), $otherValidationRules);

        if ($validator->fails() || $otherValidator->fails()) {
            return [
                "data" => [$request->input("division"), $request->input("season"), $team, $esport],
                "status" => "failure to validate inputs"
            ];
        }

        return view('Esports.team')  
            ->with([
                'esport' => $esport,
                'team' => $team,
                'season' => $request["season"],
                'division' => $request["division"],
            ]);
    }

    public function showPlayer(Request $request, $esport, $battletag, $blizz_id){
        $validationRules = [
            'esport' => 'required|in:NGS',
            'battletag' => 'required|string',
            'blizz_id' => 'required|numeric',
        ];

        $otherValidationRules = [
            'division' => 'nullable|string',
            'season' => 'nullable|numeric',
        ];

        $validator = Validator::make(compact('esport', 'battletag', 'blizz_id'), $validationRules);

        $otherValidator = Validator::make($request->all(), $otherValidationRules);

        if ($validator->fails() || $otherValidator->fails()) {
            return [
                "data" => [$request->input("division"), $request->input("season"), $battletag, $esport, $blizz_id],
                "status" => "failure to validate inputs"
            ];
        }

        return view('Esports.singlePlayer')  
            ->with([
                'esport' => $esport,
                'battletag' => $battletag,
                'blizz_id' => $blizz_id,
                'season' => $request["season"],
                'division' => $request["division"],
            ]);
    }

    public function showPlayerHero(Request $request, $esport, $battletag, $blizz_id, $hero){
        $validationRules = [
            'esport' => 'required|in:NGS',
            'battletag' => 'required|string',
            'blizz_id' => 'required|numeric',
            'hero' => ['required', new HeroInputValidation()],
        ];

        $otherValidationRules = [
            'division' => 'nullable|string',
            'season' => 'nullable|numeric',
        ];

        $validator = Validator::make(compact('esport', 'battletag', 'blizz_id', 'hero'), $validationRules);

        $otherValidator = Validator::make($request->all(), $otherValidationRules);

        if ($validator->fails() || $otherValidator->fails()) {
            return [
                "data" => [$request->input("division"), $request->input("season"), $battletag, $esport, $blizz_id, $hero],
                "status" => "failure to validate inputs"
            ];
        }

        return view('Esports.singlePlayerHero')  
            ->with([
                'esport' => $esport,
                'battletag' => $battletag,
                'blizz_id' => $blizz_id,
                'season' => $request["season"],
                'division' => $request["division"],
                'hero' => $hero,
            ]);
    }

    public function showPlayerMap(Request $request, $esport, $battletag, $blizz_id, $game_map){
        $validationRules = [
            'esport' => 'required|in:NGS',
            'battletag' => 'required|string',
            'blizz_id' => 'required|numeric',
            'game_map' => ['required', new GameMapInputValidation()],
        ];

        $otherValidationRules = [
            'division' => 'nullable|string',
            'season' => 'nullable|numeric',
        ];

        $validator = Validator::make(compact('esport', 'battletag', 'blizz_id', 'game_map'), $validationRules);

        $otherValidator = Validator::make($request->all(), $otherValidationRules);

        if ($validator->fails() || $otherValidator->fails()) {
            return [
                "data" => [$request->input("division"), $request->input("season"), $battletag, $esport, $blizz_id, $game_map],
                "status" => "failure to validate inputs"
            ];
        }

        return view('Esports.singlePlayerMap')  
            ->with([
                'esport' => $esport,
                'battletag' => $battletag,
                'blizz_id' => $blizz_id,
                'season' => $request["season"],
                'division' => $request["division"],
                'game_map' => $game_map,
            ]);
    }
    


    public function getData(Request $request){
       //return response()->json($request->all());

       $validationRules = [
            'esport' => 'required|in:NGS',
            'team' => 'nullable|string',
            'battletag' => 'nullable|string',
            'blizz_id' => 'nullable|string',
            'division' => 'nullable|string',
            'season' => 'nullable|numeric',
            'hero' => ['sometimes', 'nullable', new HeroInputValidation()],
            'game_map' => ['sometimes', 'nullable',  new GameMapInputValidation()],
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()){
            return [
                "data" => [$request->all()],
                "status" => "failure to validate inputs"
            ];
        }


        $this->esport = $request["esport"];
        $this->schema = "heroesprofile";

        $this->blizz_id = $request["blizz_id"];
        $this->battletag = $request["battletag"];
        $this->team = $request["team"];
        $this->division = $request["division"];
        $this->season = $request["season"];
        $this->hero = $request["hero"] ? session('heroes')->keyBy('name')[$request["hero"]]->id : null;
        $this->game_map = $request["game_map"] ? Map::where('name', $request["game_map"])->pluck('map_id')->toArray() : null;


        if($this->esport){
            $this->schema .= "_" . strtolower($this->esport);
        }

        $results = DB::table($this->schema . '.replay')
        ->join($this->schema . '.player', $this->schema . '.player.replayID', '=', $this->schema . '.replay.replayID')
        ->join($this->schema . '.battletags', $this->schema . '.battletags.player_id', '=', $this->schema . '.player.battletag')
        ->join($this->schema . '.scores', function($join) {
            $join->on($this->schema . '.scores.replayID', '=', $this->schema . '.replay.replayID')
            ->on($this->schema . '.scores.battletag', '=', $this->schema . '.player.battletag');
        })
        ->join($this->schema . '.talents', function($join) {
            $join->on($this->schema . '.talents.replayID', '=', $this->schema . '.replay.replayID')
            ->on($this->schema . '.talents.battletag', '=', $this->schema . '.player.battletag');
        })
        ->join($this->schema . '.teams', $this->schema . '.teams.team_id', '=', $this->schema . '.player.team_name')
        ->join("heroes", "heroes.id", '=', $this->schema . '.player.hero')
        ->select([
            $this->schema . ".teams.team_name",
            $this->schema . ".teams.image",
            $this->schema . ".battletags.battletag",
            $this->schema . ".battletags.blizz_id",
            $this->schema . ".replay.replayID",
            $this->schema . ".replay.game_date",
            $this->schema . ".replay.game_map",
            $this->schema . ".replay.game",
            $this->schema . ".replay.round",
            $this->schema . ".replay.team_0_name",
            $this->schema . ".replay.team_0_map_ban",
            $this->schema . ".replay.team_0_map_ban_2",
            $this->schema . ".replay.team_1_name",
            $this->schema . ".replay.team_1_map_ban",
            $this->schema . ".replay.team_1_map_ban_2",
            "heroes.new_role",
            $this->schema . ".player.winner",
            $this->schema . ".player.blizz_id",
            $this->schema . ".player.party",
            $this->schema . ".player.hero",
            $this->schema . ".player.team",
            $this->schema . ".player.hero_level",
            $this->schema . ".player.mastery_tier as mastery_taunt",
            $this->schema . ".player.team_name",
            $this->schema . ".scores.kills", 
            $this->schema . ".scores.assists", 
            $this->schema . ".scores.takedowns", 
            $this->schema . ".scores.time_spent_dead", 
            $this->schema . ".scores.deaths", 
            $this->schema . ".talents.level_one AS level_one",
            $this->schema . ".talents.level_four AS level_four",
            $this->schema . ".talents.level_seven AS level_seven",
            $this->schema . ".talents.level_ten AS level_ten",
            $this->schema . ".talents.level_thirteen AS level_thirteen",
            $this->schema . ".talents.level_sixteen AS level_sixteen",
            $this->schema . ".talents.level_twenty AS level_twenty",

        ])
        /*
        ->when($this->esport, function ($query) {
            return $query->addSelect([
        

            ]);
        })
        */
        ->when(!is_null($this->game_map), function ($query) {
            return $query->where($this->schema . ".replay.game_map", $this->game_map);
        })
        ->when(!is_null($this->team), function ($query) {
            return $query->where($this->schema . ".teams.team_name", $this->team);
        })
        ->when(!is_null($this->blizz_id), function ($query) {
            return $query->where($this->schema . ".player.blizz_id", $this->blizz_id);
        })
        ->when(!is_null($this->hero), function ($query) {
            return $query->where($this->schema . ".player.hero", $this->hero);
        })
        ->when(!is_null($this->division), function ($query) {
            return $query->where($this->schema . ".teams.division", $this->division);
        })
        ->when(!is_null($this->season), function ($query) {
            return $query->where($this->schema . ".teams.season", $this->season);
        })
        //->toSql();
        ->get();
        

        $heroData = $this->globalDataService->getHeroes();
        $heroData = $heroData->keyBy('id');

        $maps = Map::all();
        $maps = $maps->keyBy('map_id');



        $replaysLost = $results->filter(function ($result) {
            return $result->winner === 0;
        });

        $replaysWon = $results->filter(function ($result) {
            return $result->winner === 1;
        });

        $topEnemyHeroes = $this->getTopEnemyAlly($replaysLost->pluck('replayID')->toArray(), $this->team, $heroData, 0);
        $topAllyHeroes = $this->getTopEnemyAlly($replaysWon->pluck('replayID')->toArray(), $this->team, $heroData, 1);





        $teamData = DB::table($this->schema . '.replay')
        ->join($this->schema . '.player', $this->schema . '.player.replayID', '=', $this->schema . '.replay.replayID')
        ->join($this->schema . '.teams', $this->schema . '.teams.team_id', '=', $this->schema . '.player.team_name')
        ->select([
            $this->schema . ".teams.team_name",
            $this->schema . ".teams.image",
        ])
 
        ->whereIn($this->schema . ".replay.replayID", $results->pluck('replayID')->toArray())
        ->get();


        $teamImageMap = [];

        foreach ($teamData as $result) {
            $teamImageMap[$result->team_name] = $result->image;
        }

        $topEnemyTeams = $this->getTopEnemyTeams($replaysLost->pluck('replayID')->toArray(), $this->team, $teamImageMap);



        $players = $results->groupBy('battletag')->map(function ($playerResults, $battletag) use ($heroData) {
            $totalGames = $playerResults->count();
            $mostPlayedHero = $playerResults->groupBy('hero')->sortDesc()->keys()->first();

            $totalGamesOnHero = $playerResults->where('hero', $mostPlayedHero)->count();
            $winsOnMostPlayedHero = $playerResults->where('hero', $mostPlayedHero)->where('winner', 1)->count();

            $winRateOnMostPlayedHero = ($totalGamesOnHero > 0) ? round(($winsOnMostPlayedHero / $totalGamesOnHero) * 100, 2) : 0;

            $mostPlayedRole = $playerResults->groupBy('new_role')->sortDesc()->keys()->first();
            $totalGames = $playerResults->count();
            $blizzId = $playerResults->first()->blizz_id;

            $battletag = explode('#', $battletag)[0];

            $playerlink = "/Esports/{$this->esport}/Player/{$battletag}/{$blizzId}";

            if ($this->season) {
                $playerlink .= "?season={$this->season}";

                if ($this->division) {
                    $playerlink .= "&division={$this->division}";
                }
            } elseif ($this->division) {
                $playerlink .= "?division={$this->division}";
            }


            $herolink = "/Esports/{$this->esport}/Player/{$battletag}/{$blizzId}/Hero/{$heroData[$mostPlayedHero]["name"]}";

            if ($this->season) {
                $herolink .= "?season={$this->season}";

                if ($this->division) {
                    $herolink .= "&division={$this->division}";
                }
            } elseif ($this->division) {
                $herolink .= "?division={$this->division}";
            }


            return [
                'battletag' => explode('#', $battletag)[0],
                'blizz_id' => $blizzId,
                'most_played_hero' => $heroData[$mostPlayedHero],
                'win_rate_on_hero' => $winRateOnMostPlayedHero,
                'most_played_role' => $mostPlayedRole,
                'games_played' => $totalGames,
                'playerlink' => $playerlink,
                'herolink' => $herolink,
            ];
        })->sortByDesc('totalGames');

        $matches = $results->groupBy('replayID')->map(function ($group) use ($heroData, $maps) {

            if(!is_null($this->blizz_id)){
                $heroes = [
                    0 => $group[0] && $group[0]->hero ? ["hero" => $heroData[$group[0]->hero]] : null,
                ];
            }else{
                $heroes = [
                    0 => $group[0] && $group[0]->hero ? ["hero" => $heroData[$group[0]->hero]] : null,
                    1 => $group[1] && $group[1]->hero ? ["hero" => $heroData[$group[1]->hero]] : null,
                    2 => $group[2] && $group[2]->hero ? ["hero" => $heroData[$group[2]->hero]] : null,
                    3 => $group[3] && $group[3]->hero ? ["hero" => $heroData[$group[3]->hero]] : null,
                    4 => $group[4] && $group[4]->hero ? ["hero" => $heroData[$group[4]->hero]] : null,
                ];
            }

            return [
                'replayID' => $group[0]->replayID,
                'game_date' => $group[0]->game_date,
                'game_map' => $maps[$group[0]->game_map],
                'game' => $group[0]->game,
                'round' => $group[0]->round,
                'team_0_name' => $group[0]->team_0_name,
                'team_1_name' => $group[0]->team_1_name,
                'winner' => 1,
                'heroes' => $heroes,
            ];
        })->sortByDesc('game_date')->take(10)->values()->all();


        $heroes = $results->groupBy('hero')->map(function ($group) use ($heroData) {
            $wins = $group->sum(function ($item) {
                return $item->winner == 1 ? 1 : 0;
            });

            $losses = $group->sum(function ($item) {
                return $item->winner == 0 ? 1 : 0;
            });

            $gamesPlayed = $wins + $losses;


            $link = "/Esports/{$this->esport}/Player/{$this->battletag}/{$this->blizz_id}/Hero/{$heroData[$group[0]->hero]["name"]}";

            if ($this->season) {
                $link .= "?season={$this->season}";

                if ($this->division) {
                    $link .= "&division={$this->division}";
                }
            } elseif ($this->division) {
                $link .= "?division={$this->division}";
            }

            return [
                'hero_id' => $group[0]->hero,
                'hero' => $heroData[$group[0]->hero],
                'name' => $heroData[$group[0]->hero]["name"],
                'wins' => $wins,
                'losses' => $losses,
                'win_rate' => $gamesPlayed > 0 ? round(($wins / $gamesPlayed) * 100, 2) : 0,
                'games_played' => $gamesPlayed,

                'battletag' => $this->battletag,
                'blizz_id' => $this->blizz_id,
                'season' => $this->season,
                'division' => $this->division,
                'link' => $link,
            ];
        });



        $filteredHeroes = $heroes->filter(function ($hero){
            return is_null($this->blizz_id) ? $hero['games_played'] >= 5 : $hero['games_played'];
        });

        $heroTopthreeHighestWinRate = $filteredHeroes->sortByDesc('win_rate')->take(3);
        $heroTopthreeLowestWinRate = $filteredHeroes->sortBy('win_rate')->take(3);
        $heroTopthreeMostPlayed = $filteredHeroes->sortByDesc('games_played')->take(3);




        $mapData = $results->groupBy('game_map')->map(function ($group) use ($maps) {
            $wins = $group->sum(function ($item) {
                return $item->winner == 1 ? 1 : 0;
            }) / 5;

            $losses = $group->sum(function ($item) {
                return $item->winner == 0 ? 1 : 0;
            }) / 5;


            if(!is_null($this->blizz_id)){
                $wins *= 5;
                $losses *= 5;
            }

            $gamesPlayed = $wins + $losses;

            $link = "/Esports/{$this->esport}/Player/{$this->battletag}/{$this->blizz_id}/Map/{$maps[$group[0]->game_map]['name']}";

            if ($this->season) {
                $link .= "?season={$this->season}";

                if ($this->division) {
                    $link .= "&division={$this->division}";
                }
            } elseif ($this->division) {
                $link .= "?division={$this->division}";
            }


            return [
                'map_id' => $group[0]->game_map,
                'map' => $maps[$group[0]->game_map],
                'game_map' => $maps[$group[0]->game_map],
                'name' => $maps[$group[0]->game_map]["name"],
                'wins' => $wins,
                'losses' => $losses,
                'win_rate' => $gamesPlayed > 0 ? round(($wins / $gamesPlayed) * 100, 2) : 0,
                'games_played' => $gamesPlayed,

                'battletag' => $this->battletag,
                'blizz_id' => $this->blizz_id,
                'season' => $this->season,
                'division' => $this->division,
                'link' => $link,
            ];
        });

        $mapTopthreeHighestWinRate = $mapData->sortByDesc('win_rate')->take(3);
        $mapTopthreeLowestWinRate = $mapData->sortBy('win_rate')->take(3);
        $mapTopthreeMostPlayed = $mapData->sortByDesc('games_played')->take(3);



        $replayIDs = $results->pluck('replayID')->toArray();

        $mapBanData = DB::table($this->schema . '.replay')
            ->selectRaw("distinct(round), team_0_map_ban as ban1, team_0_map_ban_2 as ban2")
            ->whereIn($this->schema . '.replay.replayID', $replayIDs)
            ->where('team_0_name', $this->team)
            ->union(function ($query) use ($replayIDs) {
                $query->selectRaw("distinct(round), team_1_map_ban as ban1, team_1_map_ban_2 as ban2")
                    ->from($this->schema . '.replay')
                    ->whereIn($this->schema . '.replay.replayID', $replayIDs)
                    ->where('team_1_name', $this->team);
            })
            ->get();

        $mapBanDataTransformed = [];

        foreach($mapBanData as $data){
            if(!array_key_exists($data->ban1, $mapBanDataTransformed)){
                $mapBanDataTransformed[$data->ban1] = 0;
            }

            if(!array_key_exists($data->ban2, $mapBanDataTransformed)){
                $mapBanDataTransformed[$data->ban2] = 0;
            }

            $mapBanDataTransformed[$data->ban1]++;
            $mapBanDataTransformed[$data->ban2]++;
        }

        $mapBanReturn = [];
        $counter = 0;
        foreach($mapBanDataTransformed as $mapValue => $value){
            $mapBanReturn[$counter]["game_map"] = $maps[$mapValue];
            $mapBanReturn[$counter]["value"] = $value;
            $mapBanReturn[$counter]["inputhover"] = "Times Banned: " . $value;
            $counter++;
        }
        $mapBanReturn = collect($mapBanReturn)->sortByDesc('value')->values()->all();

        $seasons = DB::table($this->schema . '.teams')
            ->join($this->schema . '.player', $this->schema . '.player.team_name', '=', $this->schema . '.teams.team_id')
            ->join($this->schema . '.battletags', $this->schema . '.battletags.player_id', '=', $this->schema . '.player.battletag')
            ->select("season")
            ->when(!is_null($this->team), function ($query) {
                return $query->where($this->schema . ".teams.team_name", $this->team);
            })
            ->when(!is_null($this->blizz_id), function ($query) {
                return $query->where($this->schema . ".player.blizz_id", $this->blizz_id);
            })
            ->when(!is_null($this->hero), function ($query) {
                return $query->where($this->schema . ".player.hero", $this->hero);
            })
            ->distinct()
            ->orderByDesc('season') // Order by season in descending order
            ->pluck('season')
            ->map(function ($season) {
                return ['code' => strval($season), 'name' => strval($season)];
            })
            ->values()
            ->all();

        array_unshift($seasons, ['code' => null, 'name' => 'All']);


        $divisions = DB::table($this->schema . '.teams')
            ->join($this->schema . '.player', $this->schema . '.player.team_name', '=', $this->schema . '.teams.team_id')
            ->join($this->schema . '.battletags', $this->schema . '.battletags.player_id', '=', $this->schema . '.player.battletag')
            ->select("division")
            ->when(!is_null($this->team), function ($query) {
                return $query->where($this->schema . ".teams.team_name", $this->team);
            })
            ->when(!is_null($this->blizz_id), function ($query) {
                return $query->where($this->schema . ".player.blizz_id", $this->blizz_id);
            })
            ->when(!is_null($this->hero), function ($query) {
                return $query->where($this->schema . ".player.hero", $this->hero);
            })
            ->distinct()
            ->pluck('division')
            ->map(function ($division) {
                return ['code' => $division, 'name' => $division];
            })
            ->values()
            ->all();

        array_unshift($divisions, ['code' => null, 'name' => 'All']);




        $wins = 0;
        $losses = 0;
        if(!is_null($this->battletag)){
            $wins = $results->where("winner", 1)->count();
            $losses = $results->where("winner", 0)->count();
        }else{
            $wins = $results->where("winner", 1)->count() / 5;
            $losses = $results->where("winner", 0)->count() / 5;       
        }

        $gamesPlayed = $wins + $losses;

        $win_rate = $gamesPlayed > 0 ? round(($wins / $gamesPlayed) * 100, 2) : 0;

        $totalDeaths = $results->sum('deaths');

        $kdr = $totalDeaths > 0 ? round($results->sum('kills') / $totalDeaths, 2) : $results->sum('kills');
        $kda = $totalDeaths > 0 ? round($results->sum('takedowns') / $totalDeaths, 2) : $results->sum('takedowns');


        $totalSeconds = $results->sum('time_spent_dead');
        $days = floor($totalSeconds / (3600 * 24));
        $hours = floor(($totalSeconds % (3600 * 24)) / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;
        $time_spent_dead = $days > 0 ? "{$days} days, {$hours} hours, {$minutes} minutes, {$seconds} seconds" : "{$hours} hours, {$minutes} minutes, {$seconds} seconds";

        $banned_data = $this->getBanData($results, 0, 1, $heroData);
        $enemy_banned_data = $this->getBanData($results, 1, 0, $heroData);




        return [
            "wins" => $wins,
            "losses" => $losses,
            "win_rate" => $win_rate,
            "kdr" => $kdr,
            "kda" => $kda,
            "takedowns" => $results->sum('takedowns'),
            "kills" => $results->sum('kills'),
            "assists" => $results->sum('assists'),
            "time_spent_dead" => $time_spent_dead,
            "deaths" => $totalDeaths,
            "total_games" => $gamesPlayed,
            "matches" => $matches,
            "heroes" => $heroes->sortBy('name')->values(),
            "hero_top_three_most_played" => $heroTopthreeMostPlayed->values(),
            "hero_top_three_highest_win_rate" => $heroTopthreeHighestWinRate->values(),
            "hero_top_three_lowest_win_rate" => $heroTopthreeLowestWinRate->values(),
            "maps" => $mapData->sortBy('name')->values(),
            "map_top_three_most_played" => $mapTopthreeMostPlayed->values(),
            "map_top_three_highest_win_rate" => $mapTopthreeHighestWinRate->values(),
            "map_top_three_lowest_win_rate" => $mapTopthreeLowestWinRate->values(),
            "seasons" => $seasons,
            "divisions" => $divisions,
            "players" => $players,
            "heroes_lost_against" => $topEnemyHeroes,
            "heroes_won_against" => $topAllyHeroes,
            "enemy_teams" => $topEnemyTeams,
            "team_ban_date" => $banned_data,
            "enemy_ban_date" => $enemy_banned_data,
            "maps_banned" => $mapBanReturn,
        ];
    }

    private function getTopEnemyAlly($replayIDs, $team, $heroData, $type){
        $results = DB::table($this->schema . '.replay')
            ->join($this->schema . '.player', $this->schema . '.player.replayID', '=', $this->schema . '.replay.replayID')
            ->join($this->schema . '.teams', $this->schema . '.teams.team_id', '=', $this->schema . '.player.team_name')
            ->select([
                $this->schema . ".replay.replayID",
                $this->schema . ".player.hero",
            ])
            ->whereIn($this->schema . ".replay.replayID", $replayIDs)
            ->whereNot($this->schema . ".teams.team_name", $team)
            ->get();
        $groupedResults = $results->groupBy('hero')->map(function ($group) {
            return $group->count();
        })->sortByDesc(function ($count) {
            return $count;
        });
        $returnData = [];
        $counter = 0;
        foreach($groupedResults as $hero => $count){
            $returnData[$counter]["hero"] = $heroData[$hero];
            $returnData[$counter]["total"] = $count;

            if($type == 1){
                $returnData[$counter]["inputhover"] = "Won while on a team with " . $heroData[$hero]["name"] . " " . $count . " times (" . round(($count / (count($replayIDs)) * 5) * 100, 2) . "% of all games won as " . $team . ")";
            }else{
                $returnData[$counter]["inputhover"] = "Lost against a team with " . $heroData[$hero]["name"] . " " . $count . " times (" . round(($count / (count($replayIDs)) * 5) * 100, 2) . "% of all games lost as " . $team . ")";
            }
            $counter++;
        }
        return $returnData;  
    }

    private function getTopEnemyTeams($replayIDs, $team, $teamImageMap){
        $results = DB::table($this->schema . '.replay')
            ->join($this->schema . '.player', $this->schema . '.player.replayID', '=', $this->schema . '.replay.replayID')
            ->join($this->schema . '.teams', $this->schema . '.teams.team_id', '=', $this->schema . '.player.team_name')
            ->select([
                $this->schema . ".replay.replayID",
                $this->schema . ".teams.team_name",
                $this->schema . ".teams.image",
            ])
            ->whereIn($this->schema . ".replay.replayID", $replayIDs)
            ->whereNot($this->schema . ".teams.team_name", $team)
            ->get();
        $groupedResults = $results->groupBy('team_name')->map(function ($group) {
            return $group->count() / 5;
        })->sortByDesc(function ($count) {
            return $count;
        });
        $returnData = [];
        $counter = 0;
        foreach($groupedResults as $enemyteam => $count){
            $returnData[$counter]["team"] = $enemyteam;
            $returnData[$counter]["total"] = $count;


            $image = $teamImageMap[$enemyteam];

            if (strpos($image, 'https://s3.amazonaws.com/ngs-image-storage/') !== false) {
                $image = explode("https://s3.amazonaws.com/ngs-image-storage/", $image);
                $image = "https://s3.amazonaws.com/ngs-image-storage/" . urlencode($image[1]);


                if(strpos($image, 'undefined') !== false){
                    $image = "/images/NGS/no-image-clipped.png";
                }
            }else{
                $image = $image;
            }



            $returnData[$counter]["icon_url"] = $image;


            $returnData[$counter]["inputhover"] = "Lost agains team " . $enemyteam . " " . $count . " times (" . round((($count / (count($replayIDs))) * 100)*5, 2) . "% of all games lost as " . $team . ")";
          



            $enemy_link = "/Esports/{$this->esport}/Team/{$enemyteam}";

            if ($this->season) {
                $enemy_link .= "?season={$this->season}";

                if ($this->division) {
                    $enemy_link .= "&division={$this->division}";
                }
            } elseif ($this->division) {
                $enemy_link .= "?division={$this->division}";
            }


            $returnData[$counter]["enemy_link"] = $enemy_link;



            $counter++;
            if($counter == 5){
                break;
            }
        }
        return $returnData;
    }

    private function getBanData($results, $team1, $team2, $heroData){
        $replaysTeam0 = $results->filter(function ($result) {
            return $result->team === 0;
        });

        $replaysTeam1 = $results->filter(function ($result) {
            return $result->team === 1;
        });

        $banData0 = DB::table($this->schema . '.replay_bans')
                        ->whereIn($this->schema . '.replay_bans.replayID', $replaysTeam0->pluck('replayID')->toArray())
                        ->where($this->schema . '.replay_bans.team', $team1)
                        ->get();

        $banData1 = DB::table($this->schema . '.replay_bans')
                        ->whereIn($this->schema . '.replay_bans.replayID', $replaysTeam1->pluck('replayID')->toArray())
                        ->where($this->schema . '.replay_bans.team', $team2)
                        ->get();

        $banned_heroes = [];
        foreach($banData0 as $banData){

            if(!array_key_exists($banData->hero, $banned_heroes)){
                $banned_heroes[$banData->hero] = 0;
            }
            $banned_heroes[$banData->hero]++;
        }
        foreach($banData1 as $banData){

            if(!array_key_exists($banData->hero, $banned_heroes)){
                $banned_heroes[$banData->hero] = 0;
            }
            $banned_heroes[$banData->hero]++;
        }

        $return_banned_data = [];
        $counter = 0;
        foreach($banned_heroes as $heroBanData => $value){
            $return_banned_data[$counter]["hero"] = $heroData[$heroBanData];
            $return_banned_data[$counter]["bans"] = $value;
            $return_banned_data[$counter]["inputhover"] = "Times Banned: " . $value;
            $counter++;
        }

        usort($return_banned_data, function ($a, $b) {
            return $b["bans"] - $a["bans"];
        });

        return $return_banned_data;
    }
}
