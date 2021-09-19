@extends('layouts.crm')
@section('title', 'Deals | Pipeline view')
@section('contents')
<app-kanban-view @if(isset($_GET['pipeline'])) pipeline="@php echo $_GET['pipeline']; @endphp " @endif @if(isset($_GET['highlights'])) highlights="@php echo $_GET['highlights']; @endphp " @endif>

</app-kanban-view>
@endsection
