@extends('layouts.crm')
@section('title', 'Deals | Import/Deals')
@section('contents')
    <app-deal-import :valid-keys="{{$validKeys}}"></app-deal-import>
@endsection
