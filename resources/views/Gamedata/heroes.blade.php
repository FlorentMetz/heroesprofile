@extends('layouts.app')
@section('title', $title)

@section('content')
	@include('Gamedata.form')

	<table class="table table-striped">
		<thead>
			<tr>
				<th scope="col">ID</th>
				<th scope="col">Name</th>
				<th scope="col">Title</th>
				<th scope="col">Role</th>
				<th scope="col">Difficulty</th>
				<th scope="col">Description</th>
			</tr>
		</thead>
		<tbody>

			@foreach ($heroes as $hero)
			<tr>
				<td><a href="/Gamedata/Heroes/{{ $hero->id() }}">{{ $hero->id() }}</a></td>
				<td>{!! $hero->string('name')->asHtml() !!}</td>
				<td>{!! $hero->string('title')->asHtml() !!}</td>
				<td>{!! $hero->string('expandedrole')->asHtml() !!}</td>
				<td>{!! $hero->string('difficulty')->asHtml() !!}</td>
				<td>{!! $hero->string('description')->asHtml() !!}</td>
			</tr>
			@endforeach

		</tbody>
	</table>

@endsection
