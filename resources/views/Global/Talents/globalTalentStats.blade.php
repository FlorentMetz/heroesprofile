@extends('layouts.app')
@section('title', 'Heroes Profile')
@section('meta_keywords', '')
@section('meta_description', '')

@section('content')
  <global-talents-stats :heroes="{{ json_encode(session('heroes')) }}" :inputhero="{{ json_encode($userinput)}}" :filters="{{ json_encode($filters) }}" :defaulttimeframe="{{ json_encode($defaulttimeframe) }}" :gametypedefault="{{ json_encode($gametypedefault) }}" :defaultbuildtype="{{ json_encode($defaultbuildtype) }}"></global-talents-stats>
@endsection