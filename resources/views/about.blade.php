@extends('layouts.app')

@section('pageTitle', config('app.name') . ' &#8211; About')

@section('content')
    <section class="jumbotron" style="background-color: transparent">
        <div class="container">
            <h1 class="text-center">{{ $item->post_title }}</h1>
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <p class="lead text-muted text-center">{{ $item->post_content }}</p>
                </div>
            </div>
        </div>
    </section>
@endsection
