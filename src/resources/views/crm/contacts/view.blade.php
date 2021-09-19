@extends('layouts.crm')
@section('title', 'Organization view')
@section('contents')
    <app-organization-view @if(isset($id)) selected-url="{{route('organizations.show', $id)}}" @endif/>
@endsection
