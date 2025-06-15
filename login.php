<?php
$title = 'LOGIN';
require_once 'config/database.php';
$site_title_from_db = 'Silmarils Cookies Dessert';
if (isset($conn)) {
            $settings_stmt = $conn->prepare("SELECT site_title FROM website_settings WHERE id = 1");
            if ($settings_stmt) {
                        $settings_stmt->execute();
                        $settings_result = $settings_stmt->get_result();
                        $settings = $settings_result->fetch_assoc();
                        if ($settings && !empty($settings['site_title'])) {
                                    $site_title_from_db = htmlspecialchars($settings['site_title']);
                        }
            }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user && password_verify($password, $user['password'])) {
                        session_start();
                        $_SESSION['user_id'] = $user['id'];
                        header('Location: index.php');
                        exit;
            } else {
                        $error = "Username atau password salah";
            }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $title; ?> | <?= $site_title_from_db ?></title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>
            <div class="login-card">
                        <h2 class="text-center">LOGIN</h2>
                        <h5 class="text-center mb-5"><?php echo $site_title_from_db ?></h5>
                        <?php if (isset($error)) : ?>
                                    <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form method="POST">
                                    <div class="mb-3">
                                                <label for="username" class="form-label">Username</label>
                                                <input type="text" class="form-control" id="username" name="username" required>
                                    </div>
                                    <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
            </div>
</body>

</html>