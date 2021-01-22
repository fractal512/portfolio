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
                            <button type="submit" class="btn btn-primary">{{ __("Send message") }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
