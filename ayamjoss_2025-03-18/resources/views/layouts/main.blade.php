<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... head content ... -->
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <!-- ... other navbar items ... -->
        
        @if(session()->has('idpelanggan'))
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-link nav-link" style="border: none; background: none;">
                    Logout
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="nav-link">Login</a>
        @endif
    </nav>

    <!-- ... rest of your layout ... -->
</body>
</html>