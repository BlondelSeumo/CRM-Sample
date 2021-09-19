@extends('layouts.crm')
@section('title', 'Import')
@section('contents')
    <app-person-import :valid-keys="{{$validKeys}}"/>
@endsection
