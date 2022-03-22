@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
					
					<br>
					<a href="{{ route('links') }}">Мои готовые ссылки</a>

					<br><br>
					<form id="form_short">
						url: <input id="url" type="url" name="url" required placeholder="http://example.com">
						<button id="btn_short" type="submit">Сократить</button><br><br>
						имя: <input id="shortname" type="text" name="shortname" maxlength="5">
						<br><br>
						срок: <input id="lifetime" type="number" name="lifetime" min="1" max="99999" step="1" placeholder="сек">
					</form>
					<br>
					Результат: <b><a href="" id="shortUrl" target="_blank"></a><b/>
					
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script>
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		})
		
		$('#form_short').on('submit', function(e) {
			e.preventDefault();			
			$('#form_short .input-error').remove()
			
			$.post('shortUrl', $(this).serialize(), function(data) {
				$('#shortUrl').attr('href', data).text(data)
			})
			.fail(function(response) {
				if (response.status == 422) {
					console.log(response.responseJSON);
					$('#success_message').fadeIn().html(response.responseJSON.message);
					
					// display errors on each form field
					$.each(response.responseJSON.errors, function (i, error) {
						var el = $(document).find('[name="'+i+'"]');
						el.after($('<span class="input-error" style="color: red;"><br>'+error[0]+'</span>'));
					});
				}
			});
		})
	</script>
@stop