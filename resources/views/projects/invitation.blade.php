<div class="card flex flex-col mt-5">
    <h3 class="font-normal text-xl py-4 -ml-5 border-l-4 border-blue-300 pl-4 mb-3">
        Invite New Member
    </h3>
    <div>
    <form action="{{ $project->url(). '/invitation' }}" method="POST">
            @csrf
            <div class="mb-2">
                <input type="text" name="email" placeholder="Email Address" class="border rounded py-2 px-3 w-full">
            </div>
            <button type="submit" class="text-sm btn float-right">invite</button>
        </form>
    </div>

    @include('errors', ['bag' => 'invitation'])
</div>