@extends('layouts.app')

@section('content')

    <header class="flex justify-between items-end py-3">
        <h2 class="text-gray-600">Projects:</h2>
        <a href="/projects/create" class="btn">New Project</a>
    </header>
    <main class="lg:flex lg:flex-wrap pt-5 -mx-3">
        @forelse($projects as $project)
        <div class="lg:w-1/3 px-3 pb-6">
            @include('projects.card')
        </div>
        @empty
            <div>no projects yet</div>
        @endforelse
    </main>
@endsection