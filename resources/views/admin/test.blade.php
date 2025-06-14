<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Test Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h3>Admin Test Page</h3>
            </div>
            <div class="card-body">
                <p class="lead">If you can see this page, the basic Laravel setup is working correctly.</p>
                <p>This is a test page to verify that routes and views are functioning properly.</p>
                <hr>
                <a href="{{ url('/admin/login') }}" class="btn btn-primary">Go to Admin Login</a>
            </div>
        </div>
    </div>
</body>
</html>
