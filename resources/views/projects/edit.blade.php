@extends('layouts.app')
@section('content')
    <div class="bg-white w-2/3 m-auto rounded-lg shadow p-16">
        <h1 class="text-xl font-bold mb-6 text-center">Update Project</h1>
        <form action="{{ $project->url() }}" method="POST">
            @method("PATCH")
            @include('projects.form', ['buttonText' => 'Update'])
        </form>
    </div>
@endsection