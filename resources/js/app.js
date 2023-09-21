import { createApp } from 'vue';
import axios from 'axios';

// Create a fresh Vue app instance
const app = createApp({});


// Register components
import ExampleComponent from './components/ExampleComponent.vue';
import MainPage from './components/MainPage.vue';

//Compare Page
import Compare from './components/Compare/Compare.vue';
import HeroOrLeagueChoiceBox from './components/Compare/HeroOrLeagueChoiceBox.vue';
import SearchPlayer from './components/Compare/SearchPlayer.vue';


//Random Pieces - rename later to what you want and place in folder
import PageHeading from './components/PageHeading.vue';
import Infobox from './components/Infobox.vue';
import RoundImage from './components/RoundImage.vue';
import HeroGroupBox from './components/HeroGroupBox.vue';
import GroupBox from './components/GroupBox.vue';
import RoleBox from './components/RoleBox.vue';
import CustomButton from './components/CustomButton.vue';
import CustomTable from './components/CustomTable.vue';
import GameSummaryBox from './components/GameSummaryBox.vue';
import SearchComponent from './components/SearchComponent.vue';
import SearchComponentNav from './components/SearchComponentNav.vue';  //delete later if I can figure out why its not working in the nav

//Match Page
import SingleMatch from './components/SingleMatch.vue';


//Wrappers
import TalentImageWrapper from './components/Wrappers/TalentImageWrapper.vue';
import HeroImageWrapper from './components/Wrappers/HeroImageWrapper.vue';
import MapImageWrapper from './components/Wrappers/MapImageWrapper.vue';


//Charts
import BubbleChart from './components/Charts/BubbleChart.vue';
import BarChart from './components/Charts/BarChart.vue';
import LineChart from './components/Charts/LineChart.vue';

//Filtering
import Filters from './components/Filtering/Filters.vue';
import SingleSelectFilter from './components/Filtering/SingleSelectFilter.vue';
import MultiSelectFilter from './components/Filtering/MultiSelectFilter.vue';

import StatBox from './components/StatBox.vue';
import StatBarBox from './components/StatBarBox.vue';
import SearchedBattletagHolding from './components/SearchedBattletagHolding.vue';
import LoadingComponent from './components/LoadingComponent.vue';
import HeroSelection from './components/HeroSelection.vue';

//Global Pages
import GlobalHeroStats from './components/Global/Hero/GlobalHeroStats.vue';
import GlobalHeroMapStats from './components/Global/Hero/Map/GlobalHeroMapStats.vue';
import GlobalTalentsStats from './components/Global/Talents/GlobalTalentsStats.vue';
import GlobalLeaderboard from './components/Global/Leaderboard/GlobalLeaderboard.vue';
import GlobalMatchupsStats from './components/Global/Matchups/GlobalMatchupsStats.vue';
import GlobalMatchupsTalentsStats from './components/Global/Matchups/Talent/GlobalMatchupsTalentsStats.vue';
import CompositionsStats from './components/Global/Compositions/CompositionsStats.vue';
import GlobalTalentDetailsSection from './components/Global/Talents/GlobalTalentDetailsSection.vue';
import GlobalTalentBuildsSection from './components/Global/Talents/GlobalTalentBuildsSection.vue';
import GlolbalDraftStats from './components/Global/Draft/GlobalDraftStats.vue';
import GlobalPartyStats from './components/Global/Party/GlobalPartyStats.vue';
import GlobalExtraStats from './components/Global/Extra/GlobalExtraStats.vue';


//Authentication
import ProfileSettings from './components/Profile/ProfileSettings.vue';
import BattlenetAuthenticate from './components/Battlenet/BattlenetAuthenticate.vue';

//Player Stas
import PlayerStats from './components/Player/PlayerStats.vue';
import FriendFoe from './components/Player/FriendFoe.vue';
import PlayerHeroesAllStats from './components/Player/Heroes/PlayerHeroesAllStats.vue';
import PlayerHeroSingleStats from './components/Player/Heroes/PlayerHeroSingleStats.vue';
import PlayerMatchup from './components/Player/PlayerMatchup.vue';
import PlayerRolesAllStats from './components/Player/Roles/PlayerRolesAllStats.vue';
import PlayerRoleSingleStats from './components/Player/Roles/PlayerRoleSingleStats.vue';
import PlayerMapsAllStats from './components/Player/Maps/PlayerMapsAllStats.vue';
import PlayerMapSingleStats from './components/Player/Maps/PlayerMapSingleStats.vue';


// Automatically register Vue components
const components = import.meta.globEager('./components/**/*.vue');
Object.entries(components).forEach(([path, definition]) => {
    app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
});

// Set up axios on Vue's prototype
app.config.globalProperties.$axios = axios;


// Attach the application instance to an HTML element with id "app"
app.mount('#app');

// Set up CSRF token for Axios
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

