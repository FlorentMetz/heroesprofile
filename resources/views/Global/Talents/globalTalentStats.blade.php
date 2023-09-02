@extends('layouts.app')
@section('title', 'Heroes Profile')
@section('meta_keywords', '')
@section('meta_description', '')

@section('content')
  <global-talents-stats :heroes="{{ json_encode(session('heroes')) }}" :inputhero="{{ json_encode($userinput)}}" :filters="{{ json_encode($filters) }}" :gametypedefault="{{ json_encode($gametypedefault) }}"></global-talents-stats>
@endsection