@extends('layouts.app')
@section('title', 'Drafter')

@section('content')
  @include('filters.globals')



  <div class="draft-hero-picker">

  <div class="draft-section draft-left">
    <span>Bans</span>
    <div class="draft-bans draft-bans-left">
      <div class="draft-single-ban compare-box disabled" id="team1-ban1" data-team="team1" data-pick="ban1" >
      </div>
      <div class="draft-single-ban compare-box disabled" id="team1-ban2" data-team="team1" data-pick="ban2">
      </div>
      <div class="draft-single-ban compare-box disabled" id="team1-ban3" data-team="team1" data-pick="ban3">
      </div>
    </div>
    <div class="compare-box disabled" id="team1-pick1" data-team="team1" data-pick="pick1" >
    </div>
    <div class="compare-box disabled" id="team1-pick2" data-team="team1" data-pick="pick2">
    </div>
    <div class="compare-box disabled" id="team1-pick3" data-team="team1" data-pick="pick3">
    </div>
    <div class="compare-box disabled" id="team1-pick4" data-team="team1" data-pick="pick4">
    </div>
    <div class="compare-box disabled" id="team1-pick5" data-team="team1" data-pick="pick5">
    </div>
  </div>







  <div class="hero-category-wrapper all-heroes">
    <div class="container hero-wrapper" id="draft-hero-wrapper">
      @include('Drafter.draftPicks')
    </div>
  </div>



  <div class="draft-section draft-right">
    <span>Bans</span>
    <div class="draft-bans draft-bans-right">
      <div class="draft-single-ban compare-box disabled" id="team2-ban1" data-team="team2" data-pick="ban1">
      </div>
      <div class="draft-single-ban compare-box disabled" id="team2-ban2" data-team="team2" data-pick="ban2">
      </div>
      <div class="draft-single-ban compare-box disabled" id="team2-ban3" data-team="team2" data-pick="ban3">
      </div>
    </div>
    <div class="hero-ban-wrapper">
      <div class="compare-box disabled" id="team2-pick1" data-team="team2" data-pick="pick1">
      </div>
      <div class="compare-box disabled" id="team2-pick2" data-team="team2" data-pick="pick2">
      </div>
      <div class="compare-box disabled" id="team2-pick3" data-team="team2" data-pick="pick3">
      </div>
      <div class="compare-box disabled" id="team2-pick4" data-team="team2" data-pick="pick4">
      </div>
      <div class="compare-box disabled" id="team2-pick5" data-team="team2" data-pick="pick5">
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
  <script>
  $(document).ready(function() {

    var pickOrderTeam1 = [
      "team1-ban1",
      "team2-ban1",
      "team1-ban2",
      "team2-ban2",
      "team1-pick1",
      "team2-pick1",
      "team2-pick2",
      "team1-pick2",
      "team1-pick3",
      "team2-ban3",
      "team1-ban3",
      "team2-pick3",
      "team2-pick4",
      "team1-pick4",
      "team1-pick5",
      "team2-pick5"
    ];

    var pickOrderTeam2 = [
      "team2-ban1",
      "team1-ban1",
      "team2-ban2",
      "team1-ban2",
      "team2-pick1",
      "team1-pick1",
      "team1-pick2",
      "team2-pick2",
      "team2-pick3",
      "team1-ban3",
      "team2-ban3",
      "team1-pick3",
      "team1-pick4",
      "team2-pick4",
      "team2-pick5",
      "team1-pick5"
    ];

    var pickOrder = pickOrderTeam1;
    var currentPickNumber=0;
    var heroesPicked = [];
    var teamOneHeroes = [];
    var teamTwoheroes = [];

    //$('.draft-hero-picker').fadeIn();



    updatePick(pickOrder[currentPickNumber], heroesPicked, currentPickNumber);


    //On click of an image
    $('.all-heroes').on('click', '.hero-wrapper .popup-trigger', function(){
      $('#draft-hero-wrapper').html('');
      var heroname = $(this).data('heroname');
      var id = $(this).data('heroid');


      if(currentPickNumber == 4 || currentPickNumber == 7 || currentPickNumber == 8 || currentPickNumber == 13 || currentPickNumber == 14){
        console.log("Team 1 Picks");
        teamOneHeroes.push(id);
      }else if(currentPickNumber == 5 || currentPickNumber == 6 || currentPickNumber == 11 || currentPickNumber == 12 || currentPickNumber == 15){
        console.log("Team 2 Picks");
        teamTwoheroes.push(id);
      }

      heroesPicked.push(id);
      var heropicture = $(this).data('heroimg');
      var teampick = $('.highlight-player').data('team')+'-'+$('.highlight-player').data('pick');
      $('.compare-box.highlight-player').css('background-image', 'url(' + heropicture + ')');

      if(currentPickNumber < pickOrderTeam1.length){

        currentPickNumber++;
      }


      updatePick(pickOrder[currentPickNumber], heroesPicked, currentPickNumber);

    });

    function updatePick(pickOrder, heroesPicked, currentPickNumber){
      $('.highlight-player').removeClass('highlight-player');
      $('#'+pickOrder).removeClass('disabled');
      $('#'+pickOrder).addClass('highlight-player');
      updateDraftHeroes(heroesPicked, currentPickNumber);
    }

    function updateDraftHeroes(heroesPicked, currentPickNumber){
      var formData = $('#basic_search').serializeArray();


      var teamPick;
      if(currentPickNumber == 4 || currentPickNumber == 7 || currentPickNumber == 8 || currentPickNumber == 13 || currentPickNumber == 14){
        teamPick = teamOneHeroes;
      }else if(currentPickNumber == 5 || currentPickNumber == 6 || currentPickNumber == 11 || currentPickNumber == 12 || currentPickNumber == 15){
        teamPick = teamTwoheroes;
      }


      parameters =
      {
        'data' : formData,
        'heroesPicked' : heroesPicked,
        'teamPicks' :teamPick,
        'currentPickNumber' : currentPickNumber
      }


      var url;
      if(currentPickNumber <= 3 || currentPickNumber == 9 || currentPickNumber == 10){
        console.log("bans");

        url = '/getDraftBanData';
      }else if(currentPickNumber == 4 || currentPickNumber == 5){
        console.log("Initial Draft Data");

        url = '/getInitialDraftData';
      }else{
        console.log("Composition Data");

        url = '/getCompositionData';
      }

      console.log(parameters);
      $.ajax({
        url: url,
        data: parameters,
        //type: "POST",
        success: function(results){
          $('#draft-hero-wrapper').html(results);

          //console.log(results);
        }
      });
    }


    $('.pick-label input').click(function(){
      $('.pick-label').addClass('disabled');
      if($(this).is(':checked')){
        $(this).parent('.pick-label').removeClass('disabled');
      }
    });



    //DOC Ready Function Ending Bracket
  });




  /*
  var picks = []

  $('#start').click(function(e){
  parameters =
  {
  'data' : formData
}

$.ajax({
url: '/getInitialDraftData',
data: parameters,
//type: "POST",
success: function(results){
$('#ban-1').text(results.picks[0].hero);
picks.push(results.picks[0].id);

$('#ban-2').text(results.picks[1].hero);
picks.push(results.picks[1].id);

$('#ban-3').text(results.picks[2].hero);
picks.push(results.picks[2].id);

$('#ban-4').text(results.picks[3].hero);
picks.push(results.picks[3].id);

$('#ban-5').text(results.picks[4].hero);
picks.push(results.picks[4].id);

$('#ban-6').text(results.picks[5].hero);
picks.push(results.picks[5].id);

$('#pick-1').text(results.picks[6].hero + " Team 1 #1");
picks.push(results.picks[6].id);

$('#pick-2').text(results.picks[7].hero + " Team 2 #1");
picks.push(results.picks[7].id);
}
});
});




$('#hero-pick').click(function(e){
parameters =
{
'data' : formData,
'picks' :picks,
'heroPick' : 47
}

$.ajax({
url: '/getPickData',
data: parameters,
//type: "POST",
success: function(results){
$('#pick-3').text(results.name + " Team2 #2");
console.log(results);
}
});
});



$('#hero-pick').click(function(e){
parameters =
{
'data' : formData,
'picks' : [85]
}

$.ajax({
url: '/getPickData',
data: parameters,
//type: "POST",
success: function(results){
$('#pick-4').text(results.name + " Team1 #2");


}
});
});

});

*/
</script>

@endsection
