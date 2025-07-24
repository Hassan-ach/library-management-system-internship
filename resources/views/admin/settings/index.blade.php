<body>
    <h1>Settings</h1>
    <!-- resources/views/admin/dashboard.blade.php -->
    <form action="{{ route('admin.maintenance') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-warning">
            @if(app()->isDownForMaintenance())
                <i class="fas fa-play-circle"></i> Mode Maintenance
            @else
                <i class="fas fa-pause-circle"></i> Activer le site
            @endif
        </button>
    </form>
</body>