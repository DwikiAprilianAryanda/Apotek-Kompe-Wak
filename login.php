<?php include 'includes/header.php'; ?>

    <h1 class="page-title">Login Pengguna</h1>
    <p>Silakan masuk untuk melanjutkan pesanan Anda.</p>
    
    <form action="actions/login_process.php" method="POST">
        <div>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required>
        </div>
        <br>
        <div>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required>
        </div>
        <br>
        <button type="submit">Login</button>
    </form>

<?php include 'includes/footer.php'; ?>