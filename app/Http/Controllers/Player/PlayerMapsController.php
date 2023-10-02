<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Rules\GameMapInputValidation;

class PlayerMapsController extends Controller
{
    public function showAll(Request $request, $battletag, $blizz_id, $region)
    {
        $validationRules = [
            'battletag' => 'required|string',
            'blizz_id' => 'required|integer',
            'region' => 'required|integer',
        ];

        $validator = Validator::make(compact('battletag', 'blizz_id', 'region'), $validationRules);

        if ($validator->fails()) {
            return [
                "data" => compact('battletag', 'blizz_id', 'region'),
                "status" => "failure to validate inputs"
            ];
        }


        return view('Player.Maps.allMapData')->with([
                'battletag' => $battletag,
                'blizz_id' => $blizz_id,
                'region' => $region,
                'filters' => $this->globalDataService->getFilterData(),
                ]);
    }

    public function showSingle(Request $request, $battletag, $blizz_id, $region, $map){
        $validationRules = [
            'battletag' => 'required|string',
            'blizz_id' => 'required|integer',
            'region' => 'required|integer',
            'map' => ['required', new GameMapInputValidation()],
        ];

        $validator = Validator::make(compact('battletag', 'blizz_id', 'region', 'map'), $validationRules);

        if ($validator->fails()) {
            return [
                "data" => compact('battletag', 'blizz_id', 'region', 'map'),
                "status" => "failure to validate inputs"
            ];
        }

        $map = $request["map"];


        return view('Player.Maps.singleMapData')->with([
                'battletag' => $battletag,
                'blizz_id' => $blizz_id,
                'region' => $region,
                'map' => $map,
                'filters' => $this->globalDataService->getFilterData(),
                'regions' => $this->globalDataService->getRegionIDtoString(),
                ]);
    }
}
