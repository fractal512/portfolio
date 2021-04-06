@extends('layouts.app')

@section('pageTitle')
    {{ config('app.name') }} &#8211; {{ __("About") }}
@endsection

@section('content')
    <section class="jumbotron" style="background-color: transparent">
        <div class="container">
            <h1 class="text-center">{{ $item->post_title }}</h1>
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="lead text-muted text-center">
                        {!! $item->post_content !!}
                        @if($images)
                            <div id="myCarousel" class="carousel slide" data-interval="false" data-ride="carousel">
                                <!-- Indicators -->
                                <ol class="carousel-indicators">
                                    @php $i = 0 @endphp
                                    @foreach($images as $image)
                                        @if($i == 0)
                                            <li data-target="#myCarousel" data-slide-to="{{ $i }}" class="active"></li>
                                        @else
                                            <li data-target="#myCarousel" data-slide-to="{{ $i }}"></li>
                                        @endif
                                        @php $i++ @endphp
                                    @endforeach
                                </ol>

                                <!-- Wrapper for slides -->
                                <div class="carousel-inner">
                                    @php $i = 0 @endphp
                                    @foreach($images as $image)
                                        @if($i == 0)
                                            <div class="item active">
                                                <img src="http://fractal512.pp.ua/wp-content/uploads/photo-gallery{{ $image->image_url }}" alt="">
                                            </div>
                                        @else
                                            <div class="item">
                                                <img src="http://fractal512.pp.ua/wp-content/uploads/photo-gallery{{ $image->image_url }}" alt="">
                                            </div>
                                        @endif
                                        @php $i++ @endphp
                                    @endforeach
                                </div>

                                <!-- Left and right controls -->
                                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                    <span class="glyphicon glyphicon-chevron-left"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                    <span class="glyphicon glyphicon-chevron-right"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        @endif
                        <div class="form-links text-muted text-center">
                            <a href="{{ url('/portfolio') }}" class="btn btn-lg btn-primary">{{ __('Watch my works') }}</a>
                            <a href="{{ url('/') }}" class="btn btn-lg btn-primary">{{ __('Contact me') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
