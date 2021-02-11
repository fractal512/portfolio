@extends('layouts.app')

@section('pageTitle')
    {{ config('app.name') }} &#8211; {{ __('Portfolio') }}
@endsection

@section('content')
    <div class="container">
        <h1 class="text-center">{{ __('My works') }}</h1>
        <div class="row">
            @php $i = 0 @endphp
            @foreach($items as $item)
            <div class="col-md-6">
                <div class="card">
                    @if($meta[$i]['thumbnail'])
                    <a href="{{ url('/portfolio/'.$item->post_name.'/') }}">
                        <img class="img-responsive" src="{{ $meta[$i]['thumbnail']->guid }}" alt="{{ $meta[$i]['thumbnail']->post_title }}">
                    </a>
                    @endif
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
                                {{ $meta[$i]['wastetime'] }} {{ $meta[$i]['wastetimeunits'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @if( ($i + 1) % 2 == 0 )
        </div>
        <div class="row">
        @endif
            @php $i++ @endphp
            @endforeach
        </div>
        <div class="text-center">
            {{ $items->links() }}
        </div>
    </div>
@endsection
