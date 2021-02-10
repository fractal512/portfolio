@extends('layouts.app')

@section('pageTitle')
    {{ config('app.name') }} &#8211; {{ __('Portfolio') }} &#8211; {{ $item->post_title }}
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <h1 class="text-center">{{ $item->post_title }}</h1>
                <p class="text-center">
                    <time>
                        <i class="glyphicon glyphicon-calendar"></i>
                        {{ $item->post_date->format('d.m.Y') }}
                    </time>
                    <span class="sep">·</span>
                    <span class="author">
                        <i class="glyphicon glyphicon-user"></i>
                        {{ $user->display_name }}
                    </span>
                    <span class="sep">·</span>
                    <span class="waste-time">
                        <i class="glyphicon glyphicon-time"></i>
                        {{ $wastetime }} {{ $wastetimeunits }}
                    </span>
                </p>
                <div class="portfolio-description">{!! $item->post_content !!}</div>
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
                @if($demolink)
                    <p class="text-center">
                        <a href="{{ $demolink }}" target="_blank" class="btn btn-lg btn-primary">{{ __('Watch demo') }}</a>
                    </p>
                @endif
                <p class="text-center">
                    <span class="meta-categories">
                        <i class="glyphicon glyphicon-folder-open"></i>
                        {{ $categories }}
                    </span>
                    <span class="meta-tags">
                        <i class="glyphicon glyphicon-tag"></i>
                        {{ $tags }}
                    </span>
                </p>
                <div class="neighbour-links container-fluid">
                    <div class="row">
                        <div class="col-sm-6 neighbour-link">
                            @if($nextItem)
                                <a href="{{ url('/portfolio/'.$nextItem->post_name.'/') }}" class="btn btn-lg btn-default">&larr; {{ __('Next work') }}</a>
                            @endif
                        </div>
                        <div class="col-sm-6 neighbour-link text-right">
                            @if($previousItem)
                                <a href="{{ url('/portfolio/'.$previousItem->post_name.'/') }}" class="btn btn-lg btn-default">{{ __('Previous work') }} &rarr;</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
