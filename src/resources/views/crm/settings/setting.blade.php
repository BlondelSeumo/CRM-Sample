@extends('layouts.crm')
@section('title', trans('default.settings'))
@section('contents')
<app-setting-layout @if(env('MARKET_PLACE_VERSION')) :market-place-version="{{env('MARKET_PLACE_VERSION')}}" @endif
app-url="{{env('APP_URL')}}"/>
@endsection
