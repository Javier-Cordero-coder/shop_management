<?php
require_once __DIR__ . "/../Classes/Database.php";
require_once __DIR__ . "/../Classes/User.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

$database = new Database();
$user = new User($database);

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($user->register($username, $password)) {
        header("Location: login.php");
        exit;
    } else {
        $error = 'Registration failed. Username might already exist.';
    }
}

require_once "../includes/header.php";
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Register</h3>
                
                <?php if($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Register</button>
                        <a href="login.php" class="btn btn-outline-secondary">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once "../includes/footer.php"; ?>