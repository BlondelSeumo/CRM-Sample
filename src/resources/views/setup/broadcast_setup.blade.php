@extends('layouts.setup')

@section('contents')
    <app-pusher-setup-wizard appName="{{ config('app.name') }}"></app-pusher-setup-wizard>
@endsection
