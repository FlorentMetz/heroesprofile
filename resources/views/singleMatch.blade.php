@extends('layouts.app')
@section('title', 'Heroes Profile')
@section('meta_keywords', '')
@section('meta_description', '')

@section('content')
  <single-match :esport="{{ json_encode($esport) }}" :replayid="{{ $replayID }}"></single-match >
@endsection
