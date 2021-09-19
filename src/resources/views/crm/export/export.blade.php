@extends('layouts.crm')
@section('title', 'Export')
@section('contents')
    <app-data-export
        per-sheet="{{ $item_per_sheet }}"
        total-sheet="{{$total_sheet_number}}"
        url="{{$url}}"
        title="{{$title}}"
    />
@endsection





