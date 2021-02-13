@extends('layouts.app')

@section('pageTitle')
    {{ config('app.name') }} &#8211; {{ __('Search Results') }}
@endsection

@section('content')
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{!! $error !!}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form class="form" method="get" action="{{ url('/search') }}">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-2">
                    <input name="s" class="form-control" type="text" placeholder="{{ __('Search for...') }}" aria-label="{{ __('Search') }}" value="{{ $data }}">
                </div>
                <div class="col-sm-2">
                    <button class="btn" type="submit">{{ __('Search') }}</button>
                </div>
            </div>
        </form>
        <h3 class="text-center">{{ __('Search Results for').' "'.$data.'"' }}:</h3>
        @if( $items->count() == 0 )
            <p class="text-center">{{ __('No results found.') }}</p>
        @else
        @foreach($items as $item)
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <h4><a href="{{ url('/portfolio/'.$item->post_name.'/') }}">{{ $item->post_title }}</a></h4>
                <p>{{ str_limit( strip_tags( $item->post_content ) ) }}</p>
            </div>
        </div>
        @endforeach
        <div class="text-center">
            {{ $items->links() }}
        </div>
        @endif
    </div>
@endsection
