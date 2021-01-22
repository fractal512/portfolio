{{ __('New message from Contact Form.') }}
@foreach($formData as $key => $value)
{!! __( $key ) !!}: {{ $value }}
@endforeach
