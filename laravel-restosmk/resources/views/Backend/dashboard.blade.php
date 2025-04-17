<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Dashboard - Restoran SMK</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
</head>
<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-12">
                <h1 class="text-center">Admin Dashboard</h1>
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="row">
                            <div class="col-10">
                                <h3>Welcome, {{ Auth::user()->name }}</h3>
                            </div>
                            <div class="col-2 text-end">
                                <a href="{{ url('admin/logout') }}" class="btn btn-danger">Logout</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Users</h5>
                                        <p class="card-text">Manage users</p>
                                        <a href="#" class="btn btn-primary">Go to Users</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Orders</h5>
                                        <p class="card-text">Manage orders</p>
                                        <a href="#" class="btn btn-primary">Go to Orders</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Categories</h5>
                                        <p class="card-text">Manage categories</p>
                                        <a href="#" class="btn btn-primary">Go to Categories</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
