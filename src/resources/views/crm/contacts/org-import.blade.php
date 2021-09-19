@extends('layouts.crm')
@section('title', 'Import')
@section('contents')
    <app-organization-import :valid-keys="{{$validKeys}}"/>
@endsection