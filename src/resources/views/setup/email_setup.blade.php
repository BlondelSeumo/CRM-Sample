@extends('layouts.setup')

@section('contents')
    <app-email-setup-wizard appName="{{ config('app.name') }}"></app-email-setup-wizard>
@endsection
