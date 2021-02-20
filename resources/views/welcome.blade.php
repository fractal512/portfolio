@extends('layouts.app')

@section('pageTitle')
{{ config('app.name') }} &#8211; {{ __("HTML coding, web programming &amp; website development") }}
@endsection

@section('content')
    <section class="jumbotron jumbotron-contact" style="background-color: transparent">
        <div class="container">
            <h1 class="text-center">{{ __('Contact me') }}</h1>
            <p class="text-center">{{ __('or') }}</p>
            <p class="text-center">
                <a href="{{ url('/portfolio') }}" class="btn btn-lg btn-primary">{{ __('Watch my works') }}</a>
                <a href="{{ url('/about') }}" class="btn btn-lg btn-default">{{ __('Read about me') }}</a>
            </p>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{!! $error !!}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                    {{ session()->get('success') }}
                </div>
            @endif
            <form method="POST" action="">
                {{ csrf_field() }}
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">{{ __("Name") }}:</label>
                            <input name="name"
                                   placeholder="{{ __("Your Name...") }}"
                                   id="name"
                                   type="text"
                                   class="form-control"
                                   minlength="3"
                                   value="{{ old('name') }}"
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="email">{{ __("E-mail") }}:</label>
                            <input name="email"
                                   type="email"
                                   class="form-control"
                                   id="email"
                                   placeholder="mail@example.com"
                                   value="{{ old('email') }}"
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="subject">{{ __("Subject") }}:</label>
                            <input name="subject"
                                   placeholder="{{ __("Subject...") }}"
                                   id="subject"
                                   type="text"
                                   class="form-control"
                                   minlength="3"
                                   value="{{ old('subject') }}"
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="message">{{ __("Message") }}:</label>
                            <textarea name="message"
                                      id="message"
                                      class="form-control"
                                      placeholder="{{ __("Message...") }}"
                                      rows="5">{{ old('message') }}</textarea>
                        </div>
                        <div class="form-group">
                            <br>
                            @php echo captcha_img('default', ['id' => 'captcha-img']); @endphp
                            <a href="#!" onclick="document.getElementById('captcha-img').src = '/captcha/default?' + Date.now()">
                                <img src="data:image/svg+xml;base64,PHN2ZyBhcmlhLWhpZGRlbj0idHJ1ZSIgZm9jdXNhYmxlPSJmYWxzZSIgZGF0YS1wcmVmaXg9ImZhcyIgZGF0YS1pY29uPSJzeW5jIiBjbGFzcz0ic3ZnLWlubGluZS0tZmEgZmEtc3luYyBmYS13LTE2IiByb2xlPSJpbWciIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgdmlld0JveD0iMCAwIDUxMiA1MTIiPjxwYXRoIGZpbGw9IiMzMDk3RDEiIGQ9Ik00NDAuNjUgMTIuNTdsNCA4Mi43N0EyNDcuMTYgMjQ3LjE2IDAgMCAwIDI1NS44MyA4QzEzNC43MyA4IDMzLjkxIDk0LjkyIDEyLjI5IDIwOS44MkExMiAxMiAwIDAgMCAyNC4wOSAyMjRoNDkuMDVhMTIgMTIgMCAwIDAgMTEuNjctOS4yNiAxNzUuOTEgMTc1LjkxIDAgMCAxIDMxNy01Ni45NGwtMTAxLjQ2LTQuODZhMTIgMTIgMCAwIDAtMTIuNTcgMTJ2NDcuNDFhMTIgMTIgMCAwIDAgMTIgMTJINTAwYTEyIDEyIDAgMCAwIDEyLTEyVjEyYTEyIDEyIDAgMCAwLTEyLTEyaC00Ny4zN2ExMiAxMiAwIDAgMC0xMS45OCAxMi41N3pNMjU1LjgzIDQzMmExNzUuNjEgMTc1LjYxIDAgMCAxLTE0Ni03Ny44bDEwMS44IDQuODdhMTIgMTIgMCAwIDAgMTIuNTctMTJ2LTQ3LjRhMTIgMTIgMCAwIDAtMTItMTJIMTJhMTIgMTIgMCAwIDAtMTIgMTJWNTAwYTEyIDEyIDAgMCAwIDEyIDEyaDQ3LjM1YTEyIDEyIDAgMCAwIDEyLTEyLjZsLTQuMTUtODIuNTdBMjQ3LjE3IDI0Ny4xNyAwIDAgMCAyNTUuODMgNTA0YzEyMS4xMSAwIDIyMS45My04Ni45MiAyNDMuNTUtMjAxLjgyYTEyIDEyIDAgMCAwLTExLjgtMTQuMThoLTQ5LjA1YTEyIDEyIDAgMCAwLTExLjY3IDkuMjZBMTc1Ljg2IDE3NS44NiAwIDAgMSAyNTUuODMgNDMyeiI+PC9wYXRoPjwvc3ZnPg==" alt="{{ __("Refresh") }}" title="{{ __("Refresh") }}" width="16" height="16">
                            </a>
                            <br>
                            <br>
                            <label for="email">{{ __("Code") }}:</label>
                            <input name="captcha"
                                   placeholder="{{ __("Code on the picture...") }}"
                                   id="captcha"
                                   type="text"
                                   class="form-control"
                                   required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ __("Send message") }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
