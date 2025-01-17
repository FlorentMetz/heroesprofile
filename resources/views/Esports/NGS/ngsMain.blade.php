@extends('layouts.app')

@section('title', 'NGS Esports')
@section('meta_keywords', 'NGS league, Nexus Gaming Series, Heroes of the Storm, NGS esports, competitive gaming, Heroes of the Storm league')
@section('meta_description', 'Stay up to date with the latest news and updates from the NGS (Nexus Gaming Series) league. Review competitive Heroes of the Storm matches and follow your favorite teams.')

@section('content')
  <ngs-main 
    :heroes="{{ json_encode(session('heroes')) }}" 
    :defaultseason="{{ json_encode($defaultseason) }}" 
    :filters="{{ json_encode($filters) }}"
    :talentimages="{{ json_encode($talentimages) }}" 
  >
  </ngs-main>
@endsection
