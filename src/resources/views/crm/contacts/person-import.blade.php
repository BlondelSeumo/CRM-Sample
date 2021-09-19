@extends('layouts.crm')
@section('title', 'Contacts')
@section('contents')
    <form action="{{ route('person.import') }}" method="post" enctype="multipart/form-data">

        <input type="file" name="csv" id="">
        <input type="submit" value="Import">

    </form>
@endsection
