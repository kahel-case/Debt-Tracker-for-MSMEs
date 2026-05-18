<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="lib/extra/extra-login.css">

    <title>Debt Tracker</title>

</head>

<body>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="notebook-card">
        <div class="notebook-title">Debt Tracker Login</div>
            <form action="login.php" method="post">
                <div class="mb-4">
                    <div class="label">Username</div>
                    <input type="text" name="username" class="form-control line-input" required>
                </div>
                <div class="mb-4">
                    <div class="label">Password</div>
                    <input type="password" name="password" class="form-control line-input" required>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-dark rounded-pill px-4" onclick="window.location.href='register.php'">Sign up</button>
                    <button type="submit" class="btn btn-dark rounded-pill px-4">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>