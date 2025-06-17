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
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
            <style>
                        :root {
                                    --primary: #4361ee;
                                    --primary-light: #3a0ca3;
                                    --secondary: #f72585;
                                    --dark: #212529;
                                    --light: #f8f9fa;
                                    --gray: #6c757d;
                        }

                        body {
                                    font-family: 'Poppins', sans-serif;
                                    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                                    min-height: 100vh;
                                    display: flex;
                                    justify-content: center;
                                    align-items: center;
                                    padding: 20px;
                        }

                        .login-container {
                                    max-width: 450px;
                                    width: 100%;
                                    background: white;
                                    border-radius: 20px;
                                    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
                                    overflow: hidden;
                                    animation: fadeIn 0.5s ease-in-out;
                        }

                        .login-header {
                                    background: linear-gradient(to right, var(--primary), var(--primary-light));
                                    color: white;
                                    padding: 30px;
                                    text-align: center;
                        }

                        .login-header h2 {
                                    font-weight: 600;
                                    margin-bottom: 5px;
                        }

                        .login-header p {
                                    opacity: 0.9;
                                    font-size: 0.9rem;
                        }

                        .login-body {
                                    padding: 30px;
                        }

                        .form-control {
                                    height: 50px;
                                    border-radius: 10px;
                                    border: 1px solid #e0e0e0;
                                    padding-left: 45px;
                                    transition: all 0.3s;
                        }

                        .form-control:focus {
                                    border-color: var(--primary);
                                    box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
                        }

                        .input-group-text {
                                    position: absolute;
                                    left: 15px;
                                    top: 50%;
                                    transform: translateY(-50%);
                                    z-index: 4;
                                    color: var(--gray);
                                    background: transparent;
                                    border: none;
                        }

                        .btn-login {
                                    background: linear-gradient(to right, var(--primary), var(--primary-light));
                                    border: none;
                                    height: 50px;
                                    border-radius: 10px;
                                    font-weight: 500;
                                    letter-spacing: 0.5px;
                                    transition: all 0.3s;
                        }

                        .btn-login:hover {
                                    transform: translateY(-2px);
                                    box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
                        }

                        .alert {
                                    border-radius: 10px;
                        }

                        .brand-logo {
                                    width: 80px;
                                    height: 80px;
                                    background: white;
                                    border-radius: 50%;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    margin: -50px auto 20px;
                                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                        }

                        .brand-logo img {
                                    max-width: 50px;
                        }

                        @keyframes fadeIn {
                                    from {
                                                opacity: 0;
                                                transform: translateY(20px);
                                    }

                                    to {
                                                opacity: 1;
                                                transform: translateY(0);
                                    }
                        }

                        @media (max-width: 576px) {
                                    .login-container {
                                                border-radius: 15px;
                                    }

                                    .login-header {
                                                padding: 25px;
                                    }

                                    .login-body {
                                                padding: 25px;
                                    }
                        }
            </style>
</head>

<body>
            <div class="login-container">
                        <div class="login-header">
                                    <h2>Selamat Datang</h2>
                                    <p><?php echo $site_title_from_db ?></p>
                        </div>
                        <div class="login-body">
                                    <?php if (isset($error)) : ?>
                                                <div class="alert alert-danger alert-dismissible fade show">
                                                            <?php echo $error; ?>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>
                                    <?php endif; ?>

                                    <form method="POST">
                                                <div class="mb-4 position-relative">
                                                            <label for="username" class="form-label">Username</label>
                                                            <div class="position-relative">
                                                                        <i class="fas fa-user input-group-text"></i>
                                                                        <input type="text" class="form-control ps-45" id="username" name="username" placeholder="Masukkan username" required>
                                                            </div>
                                                </div>

                                                <div class="mb-4 position-relative">
                                                            <label for="password" class="form-label">Password</label>
                                                            <div class="position-relative">
                                                                        <i class="fas fa-lock input-group-text"></i>
                                                                        <input type="password" class="form-control ps-45" id="password" name="password" placeholder="Masukkan password" required>
                                                            </div>
                                                </div>

                                                <button type="submit" class="btn btn-primary btn-login w-100 mb-3">
                                                            <i class="fas fa-sign-in-alt me-2"></i> Login
                                                </button>
                                    </form>
                        </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                        document.querySelectorAll('.form-control').forEach(input => {
                                    input.addEventListener('focus', function() {
                                                this.parentElement.querySelector('.input-group-text').style.color = 'var(--primary)';
                                    });

                                    input.addEventListener('blur', function() {
                                                this.parentElement.querySelector('.input-group-text').style.color = 'var(--gray)';
                                    });
                        });
            </script>
</body>

</html>