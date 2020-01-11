<div class="card">
    <div class="card-body">
        <img class="rounded img-fluid" src="{{ str_replace( "_normal.", ".", $user->avatar) }}" alt="" width='200' height='200'>
        <h3 class="card-title mt-3">{{ $user->name }}</h3>
    </div>
</div>