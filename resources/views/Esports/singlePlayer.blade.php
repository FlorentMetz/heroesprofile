@extends('layouts.app')

@section('title', 'Player Stats Esports')
@section('meta_keywords', 'Heroes Profile, esports player stats, esports leagues, player performance, Heroes of the Storm')
@section('meta_description', 'Explore player statistics and performance in various esports leagues on Heroes Profile. Analyze player data, track their performance, and compare stats across different esports leagues.')

@section('content')
  <esports-player-stats 
    :esport="{{ json_encode($esport) }}" 
    :division="{{ json_encode($division) }}" 
    :battletag="{{ json_encode($battletag) }}" 
    :blizz_id="{{ json_encode($blizz_id) }}" 
    :season="{{ json_encode($season) }}" 
    :tournament="{{ json_encode($tournament) }}" 
  ></esports-player-stats>
@endsection
