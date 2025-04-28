@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
        <span class="text-success"> {{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
