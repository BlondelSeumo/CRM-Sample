@extends('layouts.crm')
@section('title', 'Deals | Add/Edit Pipeline')
@section('contents')
    <app-edit-pipeline @if(isset($id)) selected-url="{{route('pipelines.show', $id)}}" @endif/>
@endsection
