@extends('layouts.app')
@section('content')
    <div class="bg-white w-2/3 m-auto rounded-lg shadow p-16">
        <h1 class="text-xl font-bold mb-6 text-center">Create New Project</h1>
        <form action="/projects" method="POST">
            @include('projects.form', ['project' => new App\Project(), 'buttonText' => 'Create'])
        </form>
    </div>
@endsection