
	<form name="gamedata-form" method="GET" class="m-3">
		@csrf
		<div class="row">
			<div class="col-6 col-md">
				<label for="patch" class="text-light">Patch:</label>
				<select id="patch" name="patch" class="custom-select">
					@foreach ($patches as $p)
					<option {{ $p === $patch ? 'selected' : '' }}>{{ $p }}</option>
					@endforeach
				</select>
			</div>

			<div class="col-6 col-md">
				<label for="locale" class="text-light">Locale:</label>
				<select id="locale" name="locale" class="custom-select">
					@foreach ($locales as $country => $l)
					<option value="{{ $l }}" {{ $l === $locale ? 'selected' : '' }}>{{ ucfirst(strtolower($country)) }}</option>
					@endforeach
				</select>
			</div>

			<div class="col">
				<label for="submit" class="invisible">Submit</label>
				<br />
				<button class="btn btn-primary btn-sm mt-1" type="submit">Submit</button>
			</div>
		</div>
	</form>
