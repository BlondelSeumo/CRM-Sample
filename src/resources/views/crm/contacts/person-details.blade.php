@extends('layouts.crm')
@section('title', 'Contacts')
@section('contents')
    <app-contact-person-details @if(isset($id)) selected-url="{{route('persons.show', $id)}}" @endif/>
@endsection
