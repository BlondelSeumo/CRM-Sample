@extends('layouts.crm')
@section('title', trans('default.profile'))
@section('contents')
    <app-my-profile @if(env('MARKET_PLACE_VERSION')) :market-place-version="{{env('MARKET_PLACE_VERSION')}}" @endif/>
@endsection
