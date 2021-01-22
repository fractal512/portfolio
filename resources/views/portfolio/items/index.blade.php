@extends('layouts.app')

@section('pageTitle')
    {{ config('app.name') }} &#8211; {{ __('Portfolio') }}
@endsection

@section('content')
    <div class="container">
        <h1 class="text-center">{{ __('My works') }}</h1>
        <div class="row">
            <?php $i = 1; ?>
            @foreach($items as $item)
            <div class="col-md-6">
                <div class="card">
                    <a href="{{ url('/portfolio/'.$item->post_name.'/') }}"><img class="img-responsive" src="{{ $meta[$i-1]['thumbnail']->guid }}" alt="{{ $meta[$i-1]['thumbnail']->post_title }}"></a>
                    <div class="card-body">
                        <h3><a href="{{ url('/portfolio/'.$item->post_name.'/') }}">{{ $item->post_title }}</a></h3>
                        <div class="card-meta text-center">
                            <time class="text-muted">
                                <i class="glyphicon glyphicon-calendar"></i>
                                {{ $item->post_date->format('d.m.Y') }}
                            </time>
                            <span class="sep">·</span>
                            <span>
                                <i class="glyphicon glyphicon-time"></i>
                                {{ $meta[$i-1]['wastetime'] }} {{ $meta[$i-1]['wastetimeunits'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @if( $i % 2 == 0 )
        </div>
        <div class="row">
        @endif
            <?php $i++; ?>
            @endforeach
        </div>
        <div class="text-center">
            {{ $items->links() }}
        </div>
    </div>
@endsection
