<div class="card flex flex-col" style="height: 220px;">
    <h3 class="font-normal text-xl py-4 -ml-5 border-l-4 border-blue-300 pl-4 mb-3">
        <a href="{{ $project->url() }}">{{ $project->title}}</a>
    </h3>
    <div class="text-gray-600 mb-4 flex-1">
        {{ Illuminate\Support\Str::limit($project->description) }}
    </div>
    @can('manage', $project)
    <div>
        <form action="{{ $project->url() }}" method="POST" class="text-right">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm">Delete</button>
            </form>
        </div>
    @endcan
</div>