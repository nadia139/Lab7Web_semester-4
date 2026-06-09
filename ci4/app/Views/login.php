<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <link rel="stylesheet" href="<?= base_url('/css/style.css'); ?>">
</head>
<body>
    <div class="login-container">
        <h2>Login Admin</h2>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
        <?php endif; ?>
        <form action="" method="post">
            <p>
                <label>Email</label>
                <input type="email" name="email" required>
            </p>
            <p>
                <label>Password</label>
                <input type="password" name="password" required>
            </p>
            <p>
                <button type="submit" class="btn btn-primary">Login</button>
            </p>
        </form>
    </div>
</body>
</html>