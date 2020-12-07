<div class="card mt-5">
    <ul>
    @foreach($project->activities as $activity)
        <li class="{{ $loop->last ? '' : 'mb-1' }}">
            @include("projects.activities.{$activity->description}")
            <span class="text-gray-500">{{ $activity->created_at->diffForHumans(null, true) }}</span>
        </li>
    @endforeach
    </ul>
</div>