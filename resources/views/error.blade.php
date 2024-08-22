@extends('layouts.app')

@section('title', 'Error')

@section('content')
    <div class="container mt-5">
        <div class="jumbotron">
            <h1 class="display-4">Error</h1>
            <p class="lead">An error occurred while processing your request. {{ $error }}</p>
            <hr class="my-4">

            <p>Please try again later.</p>
        </div>
    </div>
@endsection
