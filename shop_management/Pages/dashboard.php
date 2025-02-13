<?php
require_once "../includes/auth_check.php";
require_once "../includes/header.php";
?>

<div class="container">
    <h1 class="my-4">Welcome to my website- Cordero <?= htmlspecialchars($_SESSION['username']) ?></h1>
    <p>This is the dashboard - Cordero</p>
</div>

<?php require_once "../includes/footer.php"; ?>