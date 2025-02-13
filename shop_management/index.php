<?php
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/Classes/Database.php";
require_once __DIR__ . "/Classes/Product.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $database = new Database();
    $product = new Product($database);
    $products = $product->read();
} catch(PDOException $e) {
    $error = "Error connecting to database: " . $e->getMessage();
} catch(Exception $e) {
    $error = "Error loading products: " . $e->getMessage();
}
?>

<div class="container">
    <h1 class="my-4 text-center">Welcome to Shop Management System - Cordero</h1>
    
    <?php if(isset($_SESSION['user_id'])): ?>
        <div class="alert alert-info text-center">
            You're logged in as <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
            <div class="mt-2">
                <a href="Pages/dashboard.php" class="btn btn-primary me-2">Dashboard</a>
                <a href="Pages/products.php" class="btn btn-success">Manage Products</a>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center mb-5">
            <h3>Get Started</h3>
            <a href="Pages/login.php" class="btn btn-lg btn-primary me-2">Login</a>
            <a href="Pages/register.php" class="btn btn-lg btn-outline-secondary">Register</a>
        </div>
    <?php endif; ?>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php else: ?>
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-box-open me-2"></i>Our Products</h4>
            </div>
            <div class="card-body">
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php foreach ($products as $product): ?>
                        <div class="col">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                    <p class="card-text text-muted">
                                        <?= htmlspecialchars($product['description']) ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h5 text-primary">
                                            $<?= number_format($product['price'], 2) ?>
                                        </span>
                                        <?php if(isset($_SESSION['user_id'])): ?>
                                            <small class="text-muted">ID: <?= $product['id'] ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . "/includes/footer.php"; ?>