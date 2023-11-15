@extends('layouts.app')
@section('title', "Match History")
@section('meta_keywords', 'Match History, Player Matches, Game Results, Match Stats')
@section('content')
  <esports-match-history
    :defaultseason="{{ json_encode($defaultseason) }}" 
    :filters="{{ json_encode($filters) }}"
    :division="{{ json_encode($division) }}"
    :esport="{{ json_encode($esport) }}"
  >
    
  </esports-match-history>
@endsection
