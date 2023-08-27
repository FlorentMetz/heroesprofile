<?php

namespace App\Http\Controllers\Global;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GlobalDataService;

use App\Rules\HeroInputValidation;
use App\Rules\TimeframeMinorInputValidation;
use App\Rules\GameTypeInputValidation;
use App\Rules\TierInputValidation;
use App\Rules\GameMapInputValidation;
use App\Rules\HeroLevelInputValidation;
use App\Rules\MirrorInputValidation;
use App\Rules\RegionInputValidation;

use App\Models\Hero;
use App\Models\GlobalHeroTalentDetails;
use App\Models\GlobalHeroTalents;
use App\Models\TalentCombination;

class GlobalTalentStatsController extends Controller
{
    private $buildsToReturn;

    public function __construct(GlobalDataService $globalDataService)
    {
        $this->globalDataService = $globalDataService;
        $this->buildsToReturn = 7;
    }

    public function show(Request $request){
        $userinput = $this->globalDataService->getHeroModel($request["hero"]);
        return view('Global.Talents.globalTalentStats', compact('userinput'));
    }

    public function getGlobalHeroTalentData(Request $request){
        $hero = (new HeroInputValidation())->passes('hero', $request["hero"]);
        $gameVersion = (new TimeframeMinorInputValidation())->passes('timeframe', explode(',', $request["timeframe"]));
        $gameType = (new GameTypeInputValidation())->passes('game_type', explode(',', $request["game_type"]));
        $leagueTier = (new TierInputValidation())->passes('league_tier', explode(',', $request["league_tier"]));
        $heroLeagueTier = (new TierInputValidation())->passes('hero_league_tier', explode(',', $request["hero_league_tier"]));
        $roleLeagueTier = (new TierInputValidation())->passes('role_league_tier', explode(',', $request["role_league_tier"]));
        $gameMap = (new GameMapInputValidation())->passes('map', explode(',', $request["map"]));
        $heroLevel = (new HeroLevelInputValidation())->passes('hero_level', explode(',', $request["hero_level"]));
        $mirror = (new MirrorInputValidation())->passes('mirror', $request["mirror"]);
        $region = (new RegionInputValidation())->passes('region', $request["region"]);

        $cacheKey = "GlobalHeroTalentStats|" . implode('|', [
            'hero' => $hero,
            'gameVersion' => implode(',', $gameVersion),
            'gameType' => implode(',', $gameType),
            'leagueTier' => implode(',', $leagueTier),
            'heroLeagueTier' => implode(',', $heroLeagueTier),
            'roleLeagueTier' => implode(',', $roleLeagueTier),
            'gameMap' => implode(',', $gameMap),
            'heroLevel' => implode(',', $heroLevel),
            'mirror' => $mirror,
            'region' => implode(',', $region),
        ]);
        //return $cacheKey;

        $data = Cache::remember($cacheKey, $this->globalDataService->calculateCacheTimeInMinutes($gameVersion), function () use (
                                                                                                                         $hero,
                                                                                                                         $gameVersion, 
                                                                                                                         $gameType, 
                                                                                                                         $leagueTier, 
                                                                                                                         $heroLeagueTier,
                                                                                                                         $roleLeagueTier,
                                                                                                                         $gameMap,
                                                                                                                         $heroLevel,
                                                                                                                         $mirror,
                                                                                                                         $region
                                                                                                                        ){
  
            $data = GlobalHeroTalentDetails::query()
                ->join('heroes_data_talents', 'heroes_data_talents.talent_id', '=', 'global_hero_talents_details.talent')
                ->join('heroes', 'heroes.id', '=', 'global_hero_talents_details.hero')
                ->select('name', 'hero as id', 'win_loss', 'talent', 'global_hero_talents_details.level')
                ->selectRaw('SUM(global_hero_talents_details.games_played) as games_played')
                ->filterByGameVersion($gameVersion)
                ->filterByGameType($gameType)
                ->filterByHero($hero)
                ->filterByLeagueTier($leagueTier)
                ->filterByHeroLeagueTier($heroLeagueTier)
                ->filterByRoleLeagueTier($roleLeagueTier)
                ->filterByGameMap($gameMap)
                ->filterByHeroLevel($heroLevel)
                ->excludeMirror($mirror)
                ->filterByRegion($region)
                ->groupBy('hero', 'win_loss', 'talent', 'global_hero_talents_details.level')
                ->orderBy('global_hero_talents_details.level')
                ->orderBy('sort')
                ->orderBy('talent')
                ->orderBy('win_loss')
                ->with(['talentInfo'])
                ->get();

            $data = collect($data)->groupBy('level')->map(function ($levelGroup) {
                return $levelGroup->groupBy('talent')->map(function ($talentGroup) {
                    $firstItem = $talentGroup->first();

                    $wins = $talentGroup->where('win_loss', 1)->sum('games_played');
                    $losses = $talentGroup->where('win_loss', 0)->sum('games_played');
                    $gamesPlayed = $wins + $losses;

                    $talentInfo = $firstItem->talentInfo;

                    return [
                        'name' => $firstItem['name'],
                        'hero_id' => $firstItem['id'],
                        'wins' => $wins,
                        'losses' => $losses,
                        'games_played' => $gamesPlayed,
                        'level' => $firstItem['level'],
                        'talentInfo' => $talentInfo,
                    ];
                })->values()->toArray();
            });


            return $data;
        });
        return $data;
    }

    public function getGlobalHeroTalentBuildData(Request $request){
        $hero = (new HeroInputValidation())->passes('hero', $request["hero"]);
        $gameVersion = (new TimeframeMinorInputValidation())->passes('timeframe', explode(',', $request["timeframe"]));
        $gameType = (new GameTypeInputValidation())->passes('game_type', explode(',', $request["game_type"]));
        $leagueTier = (new TierInputValidation())->passes('league_tier', explode(',', $request["league_tier"]));
        $heroLeagueTier = (new TierInputValidation())->passes('hero_league_tier', explode(',', $request["hero_league_tier"]));
        $roleLeagueTier = (new TierInputValidation())->passes('role_league_tier', explode(',', $request["role_league_tier"]));
        $gameMap = (new GameMapInputValidation())->passes('map', explode(',', $request["map"]));
        $heroLevel = (new HeroLevelInputValidation())->passes('hero_level', explode(',', $request["hero_level"]));
        $mirror = (new MirrorInputValidation())->passes('mirror', $request["mirror"]);
        $region = (new RegionInputValidation())->passes('region', $request["region"]);

        $cacheKey = "GlobalHeroTalentBuildStats|" . implode('|', [
            'hero' => $hero,
            'gameVersion' => implode(',', $gameVersion),
            'gameType' => implode(',', $gameType),
            'leagueTier' => implode(',', $leagueTier),
            'heroLeagueTier' => implode(',', $heroLeagueTier),
            'roleLeagueTier' => implode(',', $roleLeagueTier),
            'gameMap' => implode(',', $gameMap),
            'heroLevel' => implode(',', $heroLevel),
            'mirror' => $mirror,
            'region' => implode(',', $region),
        ]);
        //return $cacheKey;

        $data = Cache::remember($cacheKey, $this->globalDataService->calculateCacheTimeInMinutes($gameVersion), function () use (
                                                                                                                 $hero,
                                                                                                                 $gameVersion, 
                                                                                                                 $gameType, 
                                                                                                                 $leagueTier, 
                                                                                                                 $heroLeagueTier,
                                                                                                                 $roleLeagueTier,
                                                                                                                 $gameMap,
                                                                                                                 $heroLevel,
                                                                                                                 $mirror,
                                                                                                                 $region
                                                                                                                ){

            $topBuilds = $this->topBuildsOnPopularity($hero, $gameVersion, $gameType, $leagueTier, $heroLeagueTier, $roleLeagueTier, $gameMap, $heroLevel, $mirror, $region);
            foreach ($topBuilds as $build) {
                $build->buildData = $this->getTopBuildsData($build, 1, $hero, $gameVersion, $gameType, $leagueTier, $heroLeagueTier, $roleLeagueTier, $gameMap, $heroLevel, $mirror, $region);
            }
            return $topBuilds;
        });

        $data->transform(function ($item) {
            $wins = $item['buildData']['wins'];
            $losses = $item['buildData']['losses'];
            
            // Calculate win rate, account for division by zero
            $winRate = ($wins + $losses) > 0 ? $wins / ($wins + $losses) : 0;

            // Add win rate to the item
            $item['winRate'] = $winRate * 100;

            return $item;
        });
        $data = $data->sortByDesc('winRate')->values();
        return $data;
    }

    private function topBuildsOnPopularity($hero, $gameVersion, $gameType, $leagueTier, $heroLeagueTier, $roleLeagueTier, $gameMap, $heroLevel, $mirror, $region){
        $data = GlobalHeroTalents::query()
            ->join('talent_combinations', 'talent_combinations.talent_combination_id', '=', 'global_hero_talents.talent_combination_id')
            ->select('heroesprofile.global_hero_talents.hero', 'level_one', 'level_four', 'level_seven', 'level_ten', 'level_thirteen', 'level_sixteen', 'level_twenty')
            ->selectRaw('SUM(games_played) AS games_played')
            ->filterByGameVersion($gameVersion)
            ->filterByGameType($gameType)
            ->filterByHero($hero)
            ->filterByLeagueTier($leagueTier)
            ->filterByHeroLeagueTier($heroLeagueTier)
            ->filterByRoleLeagueTier($roleLeagueTier)
            ->filterByGameMap($gameMap)
            ->filterByHeroLevel($heroLevel)
            ->filterByRegion($region)
            ->groupBy('heroesprofile.global_hero_talents.hero', 'level_one', 'level_four', 'level_seven', 'level_ten', 'level_thirteen', 'level_sixteen', 'level_twenty')
            ->orderBy('games_played', 'DESC')
            ->limit($this->buildsToReturn)
            //->toSql();
            ->get();
        return $data;
    }

    private function getTopBuildsData($build, $win_loss, $hero, $gameVersion, $gameType, $leagueTier, $heroLeagueTier, $roleLeagueTier, $gameMap, $heroLevel, $mirror, $region){
        $transformedData = [
            'wins' => 0,
            'losses' => 0,
        ];

        $data = GlobalHeroTalents::query()
            ->join('talent_combinations', 'talent_combinations.talent_combination_id', '=', 'global_hero_talents.talent_combination_id')
            ->select('win_loss')
            ->selectRaw('SUM(games_played) AS games_played')
            ->filterByGameVersion($gameVersion)
            ->filterByGameType($gameType)
            ->filterByHero($hero)
            ->filterByLeagueTier($leagueTier)
            ->filterByHeroLeagueTier($heroLeagueTier)
            ->filterByRoleLeagueTier($roleLeagueTier)
            ->filterByGameMap($gameMap)
            ->filterByHeroLevel($heroLevel)
            ->filterByRegion($region)
            ->where("level_one", $build->level_one)
            ->where("level_four", $build->level_four)
            ->where("level_seven", $build->level_seven)
            ->where("level_ten", $build->level_ten)
            ->where("level_thirteen", 0)
            ->where("level_sixteen", 0)
            ->where("level_twenty", 0)
            //->toSql();
            ->groupBy("win_loss")
            ->get();

        $transformedData = [
            'wins' => ($transformedData["wins"] + $data->where('win_loss', 1)->sum('games_played')),
            'losses' => ($transformedData["losses"] + $data->where('win_loss', 0)->sum('games_played')),
        ];


        $data = GlobalHeroTalents::query()
            ->join('talent_combinations', 'talent_combinations.talent_combination_id', '=', 'global_hero_talents.talent_combination_id')
            ->select('win_loss')
            ->selectRaw('SUM(games_played) AS games_played')
            ->filterByGameVersion($gameVersion)
            ->filterByGameType($gameType)
            ->filterByHero($hero)
            ->filterByLeagueTier($leagueTier)
            ->filterByHeroLeagueTier($heroLeagueTier)
            ->filterByRoleLeagueTier($roleLeagueTier)
            ->filterByGameMap($gameMap)
            ->filterByHeroLevel($heroLevel)
            ->filterByRegion($region)
            ->where("level_one", $build->level_one)
            ->where("level_four", $build->level_four)
            ->where("level_seven", $build->level_seven)
            ->where("level_ten", $build->level_ten)
            ->where("level_thirteen", $build->level_thirteen)
            ->where("level_sixteen", 0)
            ->where("level_twenty", 0)
            //->toSql();
            ->groupBy("win_loss")
            ->get();

        $transformedData = [
            'wins' => ($transformedData["wins"] + ($data->where('win_loss', 1)->sum('games_played') * 1.125)),
            'losses' => ($transformedData["losses"] + ($data->where('win_loss', 0)->sum('games_played')* 1.125)),
        ];
        



        $data = GlobalHeroTalents::query()
            ->join('talent_combinations', 'talent_combinations.talent_combination_id', '=', 'global_hero_talents.talent_combination_id')
            ->select('win_loss')
            ->selectRaw('SUM(games_played) AS games_played')
            ->filterByGameVersion($gameVersion)
            ->filterByGameType($gameType)
            ->filterByHero($hero)
            ->filterByLeagueTier($leagueTier)
            ->filterByHeroLeagueTier($heroLeagueTier)
            ->filterByRoleLeagueTier($roleLeagueTier)
            ->filterByGameMap($gameMap)
            ->filterByHeroLevel($heroLevel)
            ->filterByRegion($region)
            ->where("level_one", $build->level_one)
            ->where("level_four", $build->level_four)
            ->where("level_seven", $build->level_seven)
            ->where("level_ten", $build->level_ten)
            ->where("level_thirteen", $build->level_thirteen)
            ->where("level_sixteen", $build->level_sixteen)
            ->where("level_twenty", 0)
            //->toSql();
            ->groupBy("win_loss")
            ->get();

        $transformedData = [
            'wins' => ($transformedData["wins"] + ($data->where('win_loss', 1)->sum('games_played') * 1.33)),
            'losses' => ($transformedData["losses"] + ($data->where('win_loss', 0)->sum('games_played')* 1.33)),
        ];




        $data = GlobalHeroTalents::query()
            ->join('talent_combinations', 'talent_combinations.talent_combination_id', '=', 'global_hero_talents.talent_combination_id')
            ->select('win_loss')
            ->selectRaw('SUM(games_played) AS games_played')
            ->filterByGameVersion($gameVersion)
            ->filterByGameType($gameType)
            ->filterByHero($hero)
            ->filterByLeagueTier($leagueTier)
            ->filterByHeroLeagueTier($heroLeagueTier)
            ->filterByRoleLeagueTier($roleLeagueTier)
            ->filterByGameMap($gameMap)
            ->filterByHeroLevel($heroLevel)
            ->filterByRegion($region)
            ->where("level_one", $build->level_one)
            ->where("level_four", $build->level_four)
            ->where("level_seven", $build->level_seven)
            ->where("level_ten", $build->level_ten)
            ->where("level_thirteen", $build->level_thirteen)
            ->where("level_sixteen", $build->level_sixteen)
            ->where("level_twenty", $build->level_twenty)
            //->toSql();
            ->groupBy("win_loss")
            ->get();

        $transformedData = [
            'wins' => round($transformedData["wins"] + ($data->where('win_loss', 1)->sum('games_played') * 1.5)),
            'losses' => round($transformedData["losses"] + ($data->where('win_loss', 0)->sum('games_played')* 1.5)),
        ];

         return $transformedData;
    }
}
