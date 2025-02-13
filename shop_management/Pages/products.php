<?php
require_once "../includes/auth_check.php";
require_once "../Classes/Database.php";
require_once "../Classes/Product.php";
require_once "../includes/header.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

$database = new Database();
$product = new Product($database);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        if (isset($_POST['create'])) {
            $product->create(
                htmlspecialchars($_POST['name']),
                htmlspecialchars($_POST['description']),
                floatval($_POST['price'])
            );
        } elseif (isset($_POST['update'])) {
            $product->update(
                intval($_POST['id']),
                htmlspecialchars($_POST['name']),
                htmlspecialchars($_POST['description']),
                floatval($_POST['price'])
            );
        } elseif (isset($_POST['delete'])) {
            $product->delete(intval($_POST['id']));
        }
        header("Location: products.php");
        exit;
    } catch(Exception $e) {
        $error = "Operation failed: " . $e->getMessage();
    }
}

$products = $product->read();
?>

<div class="container">
    <h2 class="my-4">Manage Products</h2>
    
    <!-- Add Product Form -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-plus-circle me-2"></i>Add New Product
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="name" placeholder="Product Name" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="description" placeholder="Description" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control" name="price" step="0.01" placeholder="Price" required>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" name="create" class="btn btn-success w-100">
                            <i class="fas fa-save"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card shadow">
        <div class="card-header bg-secondary text-white">
            <i class="fas fa-boxes me-2"></i>Product List
        </div>
        <div class="card-body">
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $p): ?>
                        <tr>
                            <td><?= $p['id'] ?></td>
                            <td><?= htmlspecialchars($p['name']) ?></td>
                            <td><?= htmlspecialchars($p['description']) ?></td>
                            <td>$<?= number_format($p['price'], 2) ?></td>
                            <td>
                                <button class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" 
                                    data-bs-target="#editModal<?= $p['id'] ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <button type="submit" name="delete" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?= $p['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Product</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                            <div class="mb-3">
                                                <label>Product Name</label>
                                                <input type="text" class="form-control" name="name" 
                                                    value="<?= htmlspecialchars($p['name']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Description</label>
                                                <input type="text" class="form-control" name="description" 
                                                    value="<?= htmlspecialchars($p['description']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Price</label>
                                                <input type="number" class="form-control" name="price" 
                                                    value="<?= $p['price'] ?>" step="0.01" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" name="update" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once "../includes/footer.php"; ?>