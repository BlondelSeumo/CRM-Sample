@extends('layouts.crm')
@section('title', 'Proposals')
@section('contents')
    <app-send-proposals @if(isset($id)) selected-url="{{route('proposals.show', $id)}}" @endif
    @if(isset($action)) action="{{$action}}" @endif>
    </app-send-proposals>
@endsection
