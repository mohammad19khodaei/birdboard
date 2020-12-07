@extends('layouts.app')
@section('content')
    <header class="flex justify-between items-end py-3 mb-4">
        <p class="text-gray-600">
            <a href="/projects">Projects</a> / {{ $project->title }}
        </p>
        <div class="flex items-center">
            @foreach ($project->members as $member)
                <img 
                    src="{{ $member->gravatarUrl() }}" 
                    alt="{{ $member->email}}'s gravatar'"
                    class="rounded-full mr-2 w-10">
            @endforeach

            <img 
                    src="{{ $project->owner->gravatarUrl() }}" 
                    alt="{{ $project->owner->email}}'s gravatar'"
                    class="rounded-full mr-2 w-10">

            <a href="{{ $project->url() }}/edit" class="btn ml-4">Edit Project</a>
        </div>
    </header>
    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-2/3 px-3">
                <div class="mb-8">
                    <h2 class="text-gray-600 mb-3">Tasks</h2>
                    <!-- tasks -->
                    @foreach($project->tasks as $task)
                        <form action="{{ $task->url()  }}" method="POST">
                            @method('PATCH')
                            @csrf
                            <div class="card mb-3">
                                <div class="flex items-center">
                                    <input type="text" name="body" value="{{ $task->body }}" class="w-full {{ $task->completed ? 'text-gray-600' : ''}}">
                                    <input type="checkbox" name="completed" onclick="this.form.submit()" {{ $task->completed ? 'checked' : ''}}>
                                </div>
                            </div>

                        </form>
                    @endforeach
                    <div class="card">
                        <form action="{{ $project->url() . '/tasks'}}" method="post">
                            @csrf
                            <input type="text" placeholder="Add New Task..." class="w-full" name="body">
                        </form>
                    </div>


                </div>
                <div>
                    <h2 class="text-gray-600 mb-3">General Notes</h2>
                    <form action="{{ $project->url() }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <textarea name="notes" class="card w-full mb-3" style="min-height: 200px;">{{ $project->notes }}</textarea>
                        <input type="submit" value="save" class="btn float-right">
                    </form>
                    
                    @include('errors')
                </div>
            </div>
            <div class="lg:w-1/3 px-3">
                @include('projects.card')

                @include('projects.activities.card')


                @can('manage', $project)
                    @include('projects.invitation')
                @endcan
            </div>
        </div>
    </main>
@endsection