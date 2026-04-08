<div class="col-md-3">
    <div class="card">
        <div class="card-header">
            <h5>Menu Admin</h5>
        </div>
        <div class="list-group list-group-flush">
            <a href="/dashboard" class="list-group-item list-group-item-action">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="/aspirasi" class="list-group-item list-group-item-action">
                <i class="fas fa-list"></i> Aspirasi
            </a>
            <form action="/logout" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="list-group-item list-group-item-action text-start w-100 border-0">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>
</div>