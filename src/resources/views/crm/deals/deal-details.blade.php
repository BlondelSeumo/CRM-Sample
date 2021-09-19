@extends('layouts.crm')
@section('title', 'Deals | Details')
@section('contents')
    <deal-details @if(isset($id)) selected-url="{{route('deals.show', $id)}}" @endif/>
@endsection
