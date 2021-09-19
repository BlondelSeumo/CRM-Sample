@extends('layouts.crm')
@section('title', 'Proposals')
@section('contents')
    <app-add-template @if(isset($id)) selected-url="{{route('templates.show', $id)}}" @endif
                        @if(isset($action)) action="{{$action}}" @endif>
    </app-add-template>
@endsection
