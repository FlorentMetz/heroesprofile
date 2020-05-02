@extends('layouts.app')
@section('title', 'Global Stats')

@section('content')
@include('filters.globals')
    <div class="container">
        <table id="stats-table" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th data-field="hero">Hero</th>
                <th data-field="win_rate">Win Rate</th>
                <th data-field="change">Change</th>
                <th data-field="popularity">Popularity</th>
                <th data-field="pick_rate">Pick Rate</th>
                <th data-field="ban_rate">Ban Rate</th>
                <th data-field="influence">Influence</th>
                <th data-field="games_played">Games Played</th>
                <th data-field="wins">Wins</th>
                <th data-field="losses">Losses</th>
                <th data-field="games_banned">Games Banned</th>
            </tr>
        </thead>
    </table>
    <div id="echodata">
    </div>
    </div>
@endsection

@section('scripts')


<!--
Child Row example for show talent builds
https://datatables.net/examples/api/row_details.html
-->


<script>
$(document).ready(function() {


  inputUrl = '/getGlobalStatData';
  inputColumns = [
      { data: "hero"},
      { data: "win_rate" },
      { data: "change" },
      { data: "popularity" },
      { data: "pick_rate" },
      { data: "ban_rate" },
      { data: "influence" },
      { data: "games_played" },
      { data: "wins" },
      { data: "losses" },
      { data: "games_banned" },
  ];
  inputPaging = false;
  inputSearching = false;
  inputColReorder = true;
  inputFixedHeader = true;
  inputBInfo = false;
  inputSortOrder = [[ 1, "desc" ]];
  columnDefinition = [];

  //Filters/Parameters
  var formData = $('#basic_search').serializeArray();

  parameters =
  {
    'page' : 'stat',
    'data' : formData
  }
  $.ajax({
    url: inputUrl,
    data: parameters,
    type: "POST",
    success: function(results){
      createTableJS('#stats-table', results, inputColumns, columnDefinition, inputPaging, inputSearching, inputColReorder, inputFixedHeader, inputBInfo, inputSortOrder);
    }
  });

  $('#basic_search').on('hide.bs.select', function (e, clickedIndex, isSelected, previousValue) {
    var formData = $('#basic_search').serializeArray();
    parameters =
    {
      'page' : 'stat',
      'data' : formData
    }

    $.ajax({
      url: inputUrl,
      data: parameters,
      type: "POST",
      success: function(results){
        var table = $('#stats-table').DataTable();
        table
            .clear()
            .rows.add(results)
            .draw();
      }
    });

  });


  $('.dataTables_length').addClass('bs-select');
});


</script>



@endsection
