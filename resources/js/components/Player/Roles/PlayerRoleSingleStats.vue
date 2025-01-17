<template>
  <div>
    <page-heading :infoText1="'Role data for ' + battletag + ' on ' + role" :heading="battletag +`(`+ regionsmap[region] + `)`" :isPatreon="isPatreon" :isOwner="isOwner">
      <slot>
        <round-image :image="`/images/roles/${role}.png`" :excludehover="true"></round-image>
      </slot>
    </page-heading>

    <div class="flex justify-center max-w-[1500px] mx-auto">
      <single-select-filter :values="gameTypesWithAll" :text="'Game Type'" @input-changed="handleInputChange" @dropdown-closed="handleDropdownClosed" :trackclosure="true" :defaultValue="'All'"></single-select-filter>
      <single-select-filter :values="seasonsWithAll" :text="'Season'" @input-changed="handleInputChange" @dropdown-closed="handleDropdownClosed" :trackclosure="true" :defaultValue="'All'"></single-select-filter>
    </div>

    <takeover-ad :patreon-user="patreonUser"></takeover-ad>
    
    <div v-if="data">
      <div class="flex md:p-20 gap-10 mx-auto justify-center items-center ">
        <div class="flex-1 flex flex-wrap justify-between max-w-[450px] w-full items-between mt-[1em]">
          <stat-box class="w-[48%]" :title="'Wins'" :value="data.wins.toLocaleString()"></stat-box>
          <stat-box class="w-[48%]" :title="'Losses'" :value="data.losses.toLocaleString()"></stat-box>
          <stat-bar-box class="w-full mb-5" size="full" :title="'Win Rate'" :value="data.win_rate.toFixed(2)" color="teal"></stat-bar-box>       
          <stat-box class="w-[48%]" :title="'KDR'" :value="data.kdr" color="red"></stat-box>          
          <stat-box class="w-[48%]" :title="'KDA'" :value="data.kda" color="red"></stat-box>                  
        </div>
        <div class="my-auto">
          {{ "This is where the role image goes, should we do that? : yes" }}
        </div>

        <div class="flex flex-wrap max-w-[450px] text-left w-full items-between h-full justify-center mt-[1em]">
          <stat-box class="w-[48%]" :title="'Takedowns'" :value="data.sum_takedowns.toLocaleString()"></stat-box>
          <stat-box class="w-[48%]" :title="'Kills'" :value="data.sum_kills.toLocaleString()"></stat-box>
          <stat-box class="w-[48%] mt-4 mb-4" :title="'Assists'" color="teal" :value="data.sum_assists.toLocaleString()"></stat-box>
          
          <stat-box class="w-[48%] mt-4 mb-4" :title="'Deaths'" color="teal" :value="data.sum_deaths.toLocaleString()"></stat-box>
          <stat-box class="w-[100%]" :title="'# Games'" color="red" :value="data.games_played.toLocaleString()"></stat-box>
        </div>

      </div>


      <div class="bg-lighten p-10 ">
        <div class=" max-w-[90em] ml-auto mr-auto">
          <h2 class="text-3xl font-bold py-5 text-center">Heroes played on {{ role }}</h2>
          <div class="flex flex-wrap justify-center">
            <group-box :playerlink="true" :text="'Most Played'" :data="data.hero_data_top_played.slice(0, 3)" color="blue"></group-box>
            <group-box :playerlink="true" :text="'Highest Win Rate'" :data="data.hero_data_top_win_rate.slice(0, 3)" color="teal"></group-box>
            <group-box :playerlink="true" :text="'Latest Played'" :data="data.hero_data_top_latest_played.slice(0, 3)" color="yellow"></group-box>
          </div>
          <div class="flex flex-wrap mx-auto gap-2 pt-5 justify-center">
            <a :href="'/Player/' + item.battletag + '/' + item.blizz_id + '/' + item.region + '/Hero/' + item.hero.name" v-for="(item, index) in data.hero_data_all_heroes">
              <hero-image-wrapper :hero="item.hero" size="big">
                <image-hover-box :title="item.hero.name" :paragraph-one="'Win Rate: ' + item.win_rate" :paragraph-two="'Games Played: ' + item.games_played"></image-hover-box>
              </hero-image-wrapper>
            </a>
          </div>
        </div>
      </div>


      <line-chart v-if="seasonWinRateDataArray" class="max-w-[1500px] mx-auto" :data="seasonWinRateDataArray" :dataAttribute="'win_rate'" :title="`${battletag} Win Rate Data Per Season with ${role}`"></line-chart>
      <dynamic-banner-ad :patreon-user="patreonUser" :index="1" :mobile-override="false"></dynamic-banner-ad>

      <div class="bg-lighten p-10 ">
        <div class=" max-w-[90em] ml-auto mr-auto">
          <h2 class="text-3xl font-bold py-5 text-center">Maps played on {{ role }}</h2>
          <div class="flex flex-wrap justify-center">
            <group-box :playerlink="true" :text="'Most Played'" :data="data.map_data_top_played.slice(0, 3)" color="blue"></group-box>
            <group-box :playerlink="true" :text="'Highest Win Rate'" :data="data.map_data_top_win_rate.slice(0, 3)" color="teal"></group-box>
            <group-box :playerlink="true" :text="'Latest Played'" :data="data.map_data_top_latest_played.slice(0, 3)" color="yellow"></group-box>
          </div>
          <div class="flex flex-wrap mx-auto gap-2 pt-5 justify-center">
            <a :href="'/Player/' + item.battletag + '/' + item.blizz_id + '/' + item.region + '/Map/' + item.game_map.name" v-for="(item, index) in data.map_data" :key="index">
              <map-image-wrapper :map="item.game_map" size="big">
                <image-hover-box :title="item.game_map.name" :paragraph-one="'Win Rate: ' + item.win_rate" :paragraph-two="'Games Played: ' + item.games_played"></image-hover-box>
              </map-image-wrapper>
            </a>
          </div>
        </div>
        <dynamic-banner-ad :patreon-user="patreonUser" :index="2"></dynamic-banner-ad>
      </div>


      <div class="flex justify-center max-w-[1500px] mx-auto items-center">
        <div class="p-10 max-w-[90em] ml-auto mr-auto">
          <h2 class="text-3xl font-bold py-5">Party Size Win Rates</h2>
          <div class="w-[1000px] items-center gap-10 md:px-20 py-5 justify-center" >
            <div class="flex gap-10 text-s"><span>Solo</span><span>Total Games: {{ (data.stack_size_one_wins + data.stack_size_one_losses).toLocaleString() }} </span></div>
            <stat-bar-box size="big" :value="data.stack_size_one_win_rate.toFixed(2) "></stat-bar-box>     
          </div>
          <div class="w-[1000px] items-center gap-10 md:px-20 py-5 justify-center" >
            <div class="flex gap-10 text-s"><span>Two Stack</span><span>Total Games: {{ (data.stack_size_two_wins + data.stack_size_two_losses).toLocaleString() }} </span></div>
            <stat-bar-box size="big" :value="data.stack_size_two_win_rate.toFixed(2) " color="teal"></stat-bar-box>     
          </div>
          <div class="w-[1000px] items-center gap-10 md:px-20 py-5 justify-center" >
            <div class="flex gap-10 text-s"><span>Three Stack</span><span>Total Games: {{ (data.stack_size_three_wins + data.stack_size_three_losses).toLocaleString() }} </span></div>
            <stat-bar-box size="big" :value="data.stack_size_three_win_rate.toFixed(2) " color="red"></stat-bar-box>     
          </div>
          <div class="w-[1000px] items-center gap-10 md:px-20 py-5 justify-center" >
            <div class="flex gap-10 text-s"><span>Four Stack</span><span>Total Games: {{ (data.stack_size_four_wins + data.stack_size_four_losses).toLocaleString() }} </span></div>
            <stat-bar-box size="big" :value="data.stack_size_four_win_rate.toFixed(2) " color="yellow"></stat-bar-box>     
          </div>
          <div class="w-[1000px] items-center gap-10 md:px-20 py-5 justify-center" >
            <div class="flex gap-10 text-s"><span>Five Stack</span><span>Total Games: {{ (data.stack_size_five_wins + data.stack_size_five_losses).toLocaleString() }} </span></div>
            <stat-bar-box size="big" :value="data.stack_size_five_win_rate.toFixed(2) "></stat-bar-box>     
          </div>
          
        </div>
        <dynamic-square-ad :patreon-user="patreonUser" :index="1"></dynamic-square-ad>
      </div>

      <div class="bg-lighten">
        <div class="p-10 max-w-[90em] ml-auto mr-auto" v-if="data && data.latestGames">
          <h2 class="text-3xl font-bold py-5">Most Recent matches</h2>
          <game-summary-box v-for="(item, index) in data.latestGames" :data="item"></game-summary-box>
          <div class="max-w-[1500px] mx-auto text-right my-2">
            <custom-button :href="'/Player/' + this.battletag + '/' + this.blizzid + '/' + this.region + '/Match/History/?role=' + role" class="" text="View Match History"></custom-button>
          </div>
        </div>
      </div>

    </div>
    <div v-else-if="isLoading">
      <loading-component @cancel-request="cancelAxiosRequest" :textoverride="true">Large amount of data.<br/>Please be patient.<br/>Loading Data...</loading-component>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'PlayerRoleSingleStats',
    components: {
    },
    props: {
      filters: Object,
      role: String,
      battletag: String,
      blizzid: {
        type: [String, Number]
      },
      region: Number,
      regionsmap: Object,
      isPatreon: Boolean,
      patreonUser: Boolean,
    },
    data(){
      return {
        isLoading: false,
        cancelTokenSource: null,
        inputrole: null,
        data: null,
        modifiedgametype: null,
        modifiedseason: null,
      }
    },
    created(){
      this.inputrole = this.hero;
    },
    mounted() {
      this.getData();
    },
    computed: {
      seasonWinRateDataArray() {
        return this.data && this.data.season_win_rate_data ? Object.values(this.data.season_win_rate_data) : null;
      },
      gameTypesWithAll() {
        const newValue = { code: 'All', name: 'All' };
        const updatedList = [...this.filters.game_types_full];
        updatedList.unshift(newValue);
        return updatedList;
      },
      seasonsWithAll() {
        const newValue = { code: 'All', name: 'All' };
        const updatedList = [...this.filters.seasons];
        updatedList.unshift(newValue);
        return updatedList;
      },
      isOwner(){
        if(this.battletag == "Zemill" && this.blizzid == 67280 && this.region == 1){
          return true;
        }
        return false;
      },
    },
    watch: {
    },
    methods: {
      async getData(type){

        this.isLoading = true;

        if (this.cancelTokenSource) {
          this.cancelTokenSource.cancel('Request canceled');
        }
        this.cancelTokenSource = this.$axios.CancelToken.source();

        try{
          const response = await this.$axios.post("/api/v1/player/role/single", {
            battletag: this.battletag,
            blizz_id: this.blizzid,
            region: this.region,
            game_type: this.modifiedgametype,
            season: this.modifiedseason,
            type: "single",
            page: "role",
            role: this.role,
          }, 
          {
            cancelToken: this.cancelTokenSource.token,
          });

          this.data = response.data[0];
        }catch(error){
          //Do something here
        }finally {
          this.cancelTokenSource = null;
          this.isLoading = false;
        }
      },
      cancelAxiosRequest() {
        if (this.cancelTokenSource) {
          this.cancelTokenSource.cancel('Request canceled by user');
        }
      },
      handleInputChange(eventPayload) {
        if(eventPayload.field == "Game Type"){
          if(eventPayload.value == "All"){
            this.modifiedgametype = null;
          }else{
            this.modifiedgametype = eventPayload.value;
          }
        }

        if(eventPayload.field == "Season"){
          if(eventPayload.value == "All"){
            this.modifiedseason = null;
          }else{
            this.modifiedseason = eventPayload.value;
          }
        }
      },
      handleDropdownClosed(){
        this.data = null;
        this.getData();
      },
    }
  }
</script>