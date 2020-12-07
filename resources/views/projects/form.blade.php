@csrf
<div class=" mb-6">
    <label for="title" class="mb-2 inline-block">Title</label>
    <input type="text" name="title" id="title" 
            class="border-2 w-full rounded p-2" 
            placeholder="New Project Name..."
            value="{{ $project->title }}"
    >
</div>
<div class="mb-6">
    <label for="description" class="block mb-2">Description</label>
    <textarea name="description" id="description" 
                class="border-2 w-full rounded p-2"
                placeholder="New Project Description..."  
                cols="30" rows="10">{{ $project->description }}</textarea>
</div>
<div>
    <input type="submit" value="{{ $buttonText }}" class="btn mr-2">
    <a href="{{ $project->url() }}">Cancel</a>
</div>

@if($errors->any)
    <ul class="mt-6">
        @foreach($errors->all() as $error)
            <li class="text-sm text-red-500">{{ $error }}</li>
        @endforeach
    </ul>
@endif
