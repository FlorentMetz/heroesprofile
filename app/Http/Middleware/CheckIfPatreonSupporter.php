<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PatreonAccount;

class CheckIfPatreonSupporter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Auth::check()) {            
            $user = Auth::user();
            $patreonUser = PatreonAccount::where("battlenet_accounts_id", $user->battlenet_accounts_id)->first();

            if($patreonUser){
                if ($patreonUser->site_flair == 1) {
                    session(['patreonSubscriberSiteFlair' => true]);
                }

                if ($patreonUser->ad_free == 1) {
                    session(['patreonSubscriberAdFree' => true]);
                }
            }
       
        }

        return $next($request);
    }
}
