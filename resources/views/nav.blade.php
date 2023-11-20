<div class="max-md:bg-gray-dark">
    <div class="flex p-2 px-4 justify-between">
<a class=" flex items-center font-logo text-2xl md:hidden" href="/">
                Heroes
                <img class="w-10 mx-2" src="/images/logo/heroesprofilelogo.png" alt="Heroes Profile Logo" />
                Profile
        </a>
        <button class="md:hidden bg-blue rounded-lg">=</button>
    </div>
</div>

<div class="  main-navigation-wrapper"> <!-- max-md:hidden  - This needs to hide/show on click of the button but only on mobile - either detect mobile with vue, or have a class that is added/taken away that only applies to mobile -->
    <nav class="bg-gray-dark text-white z-50 relative px-4 text-sm ">
        
        <div class="flex items-center justify-between flex-wrap  " >
             
            <a class=" flex items-center font-logo text-2xl" href="/">
                Heroes
                <img class="w-10 mx-2" src="/images/logo/heroesprofilelogo.png" alt="Heroes Profile Logo" />
                Profile
            </a>
            <div class="flex items-center justify-between flex-wrap  text-sm ml-auto ">
                <div class="flex items-center space-x-5 justify-end flex-wrap md:flex-nowrap pr-5">
                    
                    <div class="relative group inline-block nav-item">
                        <a class=" cursor-pointer">Global Hero Stats</a>
                        <div class="nav-dropdown absolute left-0 hidden group-hover:block z-50 pt-3  min-w-[200px]">
                            <div class="bg-blue  rounded-b-lg rounded-tr-lg text-sm drop-shadow">
                                {{-- ... (Global Hero Stats dropdown items) --}}
                                <a href="/Global/Hero" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Hero Stats</a>
                                <a href="/Global/Talents" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Talent Stats</a>
                                <a href="/Global/Hero/Maps" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Map Stats</a>
                                <a href="/Global/Matchups" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Matchup Stats</a>
                                <a href="/Global/Matchups/Talents" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Matchup Talent Stats</a>
                                <a href="/Global/Compositions" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Compositional Stats</a>
                                <a href="/Global/Draft" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Draft Stats</a>
                                <a href="/Global/Party" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Party Stats</a>
                                {{--<a href="/Global/Extra" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Extra Stats</a>--}}
                            </div>
                        </div>
                    </div>

                    <a class="  nav-item" href="/Global/Leaderboard">Leaderboards</a>

                    <div class="relative group inline-block nav-item">
                        <a class=" cursor-pointer">Tools</a>
                        <div class="nav-dropdown absolute left-0 hidden  group-hover:block z-50 pt-3 min-w-[200px]">
                            <div class="bg-blue  rounded-b-lg rounded-tr-lg text-sm drop-shadow ">
                                <a href="/Global/Talents/Build" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Talent Builder</a>
                                <a href="/Compare" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Compare</a>
                                <a href="https://drafter.heroesprofile.com/Drafter" target="_blank" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Drafter</a>
                                <a href="https://dev.heroesprofile.com/" target="_blank" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Game Data</a>
                                <a href="https://api.heroesprofile.com/upload" target="_blank" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Replay Uploader</a>
                                {{--<a href="/" class="block px-4 py-2 border-b border-darken hover:bg-lighten cursor-not-allowed pointer-events-none">Activity Graphs</a>--}}
                                <a href="https://autobattler.setup.heroesprofile.com/" target="_blank" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Auto Battler</a>
                                <a href="/" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Find a Player</a>


                            </div>
                        </div>
                    </div>
                    <a class=" nav-item" href="/Esports">Community Esports</a>



                    <div class="relative group inline-block nav-item">
                        <a class=" cursor-pointer">Help</a>
                        <div class="nav-dropdown absolute left-0 hidden  group-hover:block z-50 pt-3 min-w-[200px]">
                            <div class="bg-blue  rounded-b-lg rounded-tr-lg text-sm drop-shadow ">
                                <a href="https://github.com/Heroes-Profile/heroesprofile/issues" target="_blank" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Submit an Issue</a>
                                <a href="/Contact" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Contact Us</a>
                            </div>

             
                        </div>
                    </div>

                    
                        @if(isset($mainSearchAccount))
                        <div class="relative group inline-block nav-item">
                            <a class="cursor-pointer">
                            {{ $mainSearchAccount['battletag'] }} ({{ $regions[$mainSearchAccount['region']] }})
                            </a>
                            <div class="nav-dropdown absolute left-0 hidden group-hover:block z-50 pt-3  drop-menu-wrapper min-w-[200px]">
                                <div class="bg-blue  rounded-b-lg rounded-tr-lg text-sm drop-shadow drop-menu">
                                    {{-- ... (mainSearchAccount dropdown items) --}}
                                    <a href="/Player/{{ $mainSearchAccount['battletag'] }}/{{ $mainSearchAccount['blizz_id'] }}/{{ $mainSearchAccount['region'] }}" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Profile</a>
                                    <a href="/Player/{{ $mainSearchAccount['battletag'] }}/{{ $mainSearchAccount['blizz_id'] }}/{{ $mainSearchAccount['region'] }}/FriendFoe" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Friends and Foes</a>
                                    <a href="/Player/{{ $mainSearchAccount['battletag'] }}/{{ $mainSearchAccount['blizz_id'] }}/{{ $mainSearchAccount['region'] }}/Hero" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Heroes</a>
                                    <a href="/Player/{{ $mainSearchAccount['battletag'] }}/{{ $mainSearchAccount['blizz_id'] }}/{{ $mainSearchAccount['region'] }}/Role" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Roles</a>
                                    <a href="/Player/{{ $mainSearchAccount['battletag'] }}/{{ $mainSearchAccount['blizz_id'] }}/{{ $mainSearchAccount['region'] }}/Map" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Maps</a>
                                    <a href="/Player/{{ $mainSearchAccount['battletag'] }}/{{ $mainSearchAccount['blizz_id'] }}/{{ $mainSearchAccount['region'] }}/Matchups" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Matchups</a>
                                    <a href="/Player/{{ $mainSearchAccount['battletag'] }}/{{ $mainSearchAccount['blizz_id'] }}/{{ $mainSearchAccount['region'] }}/Talents" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Talents</a>
                                    <a href="/Player/{{ $mainSearchAccount['battletag'] }}/{{ $mainSearchAccount['blizz_id'] }}/{{ $mainSearchAccount['region'] }}/MMR" class="block px-4 py-2 border-b border-darken hover:bg-lighten">MMR Breakdown</a>
                                    <a href="/Player/{{ $mainSearchAccount['battletag'] }}/{{ $mainSearchAccount['blizz_id'] }}/{{ $mainSearchAccount['region'] }}/Match/History" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Match History</a>
                                </div>
                            </div>
                        </div>
                        @endif
                    
                </div>

                {{-- Distinct white or grey line show the seperation here  --}}
                <div class="flex space-x-5 bg-lighten p-3 mx-2 max-md:order-1">
                    <custom-button :href="'https://api.heroesprofile.com/Api'" :targetblank="true" :text="'API'" :alt="'API'"  :size="'small'" :color="'teal'"></custom-button>
                    <custom-button :href="'https://api.heroesprofile.com/upload'" :targetblank="true" :text="'Replay Uploader'" :alt="'Replay Uploader'"  :size="'small'" :color="'blue'"></custom-button>
                    <custom-button :href="'https://www.patreon.com/heroesprofile'" :targetblank="true" :text="'Patreon'" :alt="'Patreon'"  :size="'small'" :color="'red'"></custom-button>
                </div>


                @if($isAuthenticated)
                    <div class="relative group inline-block  ml-5">
                            <div class="flex items-center cursor-pointer mr-5">
                                <img 
                                class="card-img-top relative hover:opacity-75 w-12 h-12 rounded-full" 
                                src="/images/heroes/auto_select.jpg" 
                                alt="Settings">
                            </div>
                            <div class="absolute right-0 hidden group-hover:block z-50  ">
                                <div class="bg-blue  rounded-b-lg rounded-tl-lg text-sm drop-shadow">
                                    <a href="/Profile/Settings" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Settings</a>
                                    <a href="/Battlenet/Logout" class="block px-4 py-2 border-b border-darken hover:bg-lighten">Logout</a>
                                </div>
                            </div>
                        </div>





                @else
                    <custom-button class="ml-4" :href="'/Authenticate/Battlenet'" :text="'Login'" :alt="'Login'" :size="'small'"></custom-button>
                @endif
            </div>
        </div>
    </nav>
    <nav class="flex justify-end mr-8">
        @foreach($altSearchAccounts as $index => $account)
            @if($account)
                <div class="relative group inline-block  p-4 mx-4 text-sm  ">
                    <a class=" cursor-pointer">{{ $account['battletag'] }} ({{ $regions[$account['region']] }})</a>
                    <div class="nav-dropdown absolute  hidden group-hover:block z-50 pt-3 absolute right-0 min-w-[200px]">
                        <div class="bg-blue  rounded-b-lg rounded-tl-lg text-sm drop-shadow">
                        <a href="/Player/{{ $account['battletag'] }}/{{ $account['blizz_id'] }}/{{ $account['region'] }}" class="block px-4 py-2 border-b border-darken hover:bg-lighten ">Profile</a>
                        <a href="/Player/{{ $account['battletag'] }}/{{ $account['blizz_id'] }}/{{ $account['region'] }}/FriendFoe" class="block px-4 py-2 border-b border-darken hover:bg-lighten ">Friends and Foes</a>
                        <a href="/Player/{{ $account['battletag'] }}/{{ $account['blizz_id'] }}/{{ $account['region'] }}/Hero" class="block px-4 py-2 border-b border-darken hover:bg-lighten ">Heroes</a>
                        <a href="/Player/{{ $account['battletag'] }}/{{ $account['blizz_id'] }}/{{ $account['region'] }}/Role" class="block px-4 py-2 border-b border-darken hover:bg-lighten ">Roles</a>
                        <a href="/Player/{{ $account['battletag'] }}/{{ $account['blizz_id'] }}/{{ $account['region'] }}/Map" class="block px-4 py-2 border-b border-darken hover:bg-lighten  ">Maps</a>
                        <a href="/Player/{{ $account['battletag'] }}/{{ $account['blizz_id'] }}/{{ $account['region'] }}/Matchups" class="block px-4 py-2 border-b border-darken hover:bg-lighten ">Matchups</a>
                        <a href="/Player/{{ $account['battletag'] }}/{{ $account['blizz_id'] }}/{{ $account['region'] }}/Talents" class="block px-4 py-2 border-b border-darken hover:bg-lighten ">Talents</a>
                        <a href="/Player/{{ $account['battletag'] }}/{{ $account['blizz_id'] }}/{{ $account['region'] }}/MMR" class="block px-4 py-2 border-b border-darken hover:bg-lighten ">MMR Breakdown</a>
                        <a href="/Player/{{ $account['battletag'] }}/{{ $account['blizz_id'] }}/{{ $account['region'] }}/Match/History" class="block px-4 py-2 border-b border-darken hover:bg-lighten ">Match History</a>
                    </div>
                    </div>
                </div>
            @endif
        @endforeach
    </nav>
</div>
