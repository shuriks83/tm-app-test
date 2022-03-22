@extends('layouts.app')

@section('content')


<style>
	.color-red {
		color: red;
	}
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
<a href="{{ route('home') }}">Домашняя</a>
<br><br>
            <div class="card">
                <div class="card-header">Мои ссылки</div>
                <div class="card-body">
					<table class="table">
						<tr>
							<th>#</th>
							<th>url</th>
							<th>short</th>
							<th>expires</th>
							<th>hits (14 days)</th>
							<th>created</th>
						</tr>
						@foreach ($links as $l)
						<tr>
							<th>{{ $loop->index }}</th>
							<th>{{ $l['long_url'] }}</th>
							<th><a href="/{{ $l['short'] }}" target="_blank">{{ $l['short'] }}</a></th>
							<th @class([
										'color-red' => date_create('now') > date_create($l->expires_at),
							])>{{ $l['expires_at'] }}</th>
							<th>{{ $l->hits->count() }}</th>
							<th>{{ $l['created_at'] }}</th>
						</tr>						
						@endforeach
					</table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
