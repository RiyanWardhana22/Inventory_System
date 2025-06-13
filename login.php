<?php
require_once 'config/database.php';

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
            <title>INVENTORYWEB | LOGIN</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                        body {
                                    background-color: #f8f9fa;
                        }

                        .login-container {
                                    max-width: 400px;
                                    margin: 100px auto;
                                    padding: 20px;
                                    background-color: #fff;
                                    border-radius: 5px;
                                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        }
            </style>
</head>

<body>
            <div class="container">
                        <div class="login-container">
                                    <h2 class="text-center mb-4">INVENTORYWEB | LOGIN</h2>
                                    <?php if (isset($error)): ?>
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
            </div>
</body>

</html>