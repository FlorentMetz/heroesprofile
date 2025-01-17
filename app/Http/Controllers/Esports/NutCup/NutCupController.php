<?php

namespace App\Http\Controllers\Esports\NutCup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NutCupController extends Controller
{
    public function show(Request $request)
    {
        return view('Esports.NutCup.nutCupMain')
            ->with([
                'filters' => $this->globalDataService->getFilterData(),
                'talentimages' => $this->globalDataService->getPreloadTalentImageUrls(),
            ]);
    }
}
