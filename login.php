<?php
function base_url($path = '')
{
            $protocol = (!empty($_SERVER['HTTPS'])) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $project_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace('\\', '/', realpath(dirname(__FILE__) . '/..')));
            return $protocol . '://' . $host . $project_path . '/' . ltrim($path, '/');
}

$title = 'Login';
require_once 'config/database.php';

$site_title_from_db = 'Silmarils Cookies Dessert';
$site_logo_from_db = 'default_logo.svg';
if (isset($conn)) {
            $settings_stmt = $conn->prepare("SELECT site_title FROM website_settings WHERE id = 1");
            if ($settings_stmt) {
                        $settings_stmt->execute();
                        $settings_result = $settings_stmt->get_result();
                        $settings = $settings_result->fetch_assoc();
                        if ($settings) {
                                    if (!empty($settings['site_title'])) {
                                                $site_title_from_db = htmlspecialchars($settings['site_title']);
                                    }
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
<html lang="id">

<head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $title; ?> - <?php echo $site_title_from_db; ?></title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

            <style>
                        :root {
                                    --primary-color: #4a4de7;
                                    --secondary-color: #1e1e2f;
                                    --text-dark: #1f2937;
                                    --text-light: #6b7280;
                                    --background-light: #f9fafb;
                                    --white: #ffffff;
                        }

                        body {
                                    font-family: "Poppins", sans-serif;
                                    background-color: var(--background-light);
                                    display: flex;
                                    justify-content: center;
                                    align-items: center;
                                    min-height: 100vh;
                                    margin: 0;
                        }

                        .login-container {
                                    width: 100%;
                                    max-width: 1000px;
                                    min-height: 500px;
                                    background-color: var(--white);
                                    border-radius: 1.5rem;
                                    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                                    overflow: hidden;
                                    display: flex;
                        }

                        .login-branding {
                                    background: linear-gradient(135deg,
                                                            var(--primary-color),
                                                            var(--secondary-color));
                                    color: var(--white);
                                    padding: 4rem;
                                    display: flex;
                                    flex-direction: column;
                                    justify-content: center;
                                    align-items: center;
                                    text-align: center;
                                    flex-basis: 50%;
                        }

                        .login-branding .logo {
                                    max-width: 120px;
                                    margin-bottom: 1.5rem;
                                    filter: brightness(0) invert(1);
                        }

                        .login-branding h1 {
                                    font-weight: 700;
                                    font-size: 2.5rem;
                                    margin-bottom: 1rem;
                        }

                        .login-branding p {
                                    font-size: 1.1rem;
                                    opacity: 0.9;
                        }

                        .login-form-wrapper {
                                    padding: 3rem;
                                    flex-basis: 50%;
                                    display: flex;
                                    flex-direction: column;
                                    justify-content: center;
                        }

                        .login-form-wrapper h2 {
                                    font-weight: 700;
                                    color: var(--text-dark);
                                    font-size: 2rem;
                        }

                        .login-form-wrapper .text-muted {
                                    color: var(--text-light) !important;
                                    margin-bottom: 2rem;
                        }

                        .form-control {
                                    height: 50px;
                                    border: 1px solid #ddd;
                                    border-radius: 0.5rem;
                                    padding: 0 1.25rem;
                                    font-size: 0.95rem;
                        }

                        .form-control:focus {
                                    border-color: var(--primary-color);
                                    box-shadow: 0 0 0 3px rgba(74, 77, 231, 0.2);
                        }

                        .btn-login {
                                    background-color: var(--primary-color);
                                    border-color: var(--primary-color);
                                    padding: 0.75rem;
                                    border-radius: 0.5rem;
                                    font-weight: 600;
                                    transition: background-color 0.3s, transform 0.2s;
                        }

                        .forgot-password {
                                    font-size: 0.9rem;
                                    color: var(--primary-color);
                                    text-decoration: none;
                                    transition: color 0.3s;
                        }

                        .forgot-password:hover {
                                    color: var(--secondary-color);
                        }

                        .signup-link {
                                    font-size: 0.9rem;
                        }

                        .signup-link a {
                                    color: var(--primary-color);
                                    font-weight: 600;
                                    text-decoration: none;
                        }

                        .signup-link a:hover {
                                    text-decoration: underline;
                        }

                        .alert {
                                    border-radius: 0.5rem;
                                    padding: 0.8rem 1.25rem;
                        }

                        @media (max-width: 991.98px) {
                                    .login-container {
                                                flex-direction: column;
                                                max-width: 500px;
                                                min-height: auto;
                                    }

                                    .login-branding,
                                    .login-form-wrapper {
                                                flex-basis: 100%;
                                    }

                                    .login-branding {
                                                padding: 3rem 2rem;
                                                min-height: 250px;
                                    }

                                    .login-form-wrapper {
                                                padding: 2.5rem;
                                    }
                        }

                        @media (max-width: 575.98px) {
                                    body {
                                                padding: 1rem;
                                    }

                                    .login-container {
                                                border-radius: 1rem;
                                    }

                                    .login-form-wrapper {
                                                padding: 2rem 1.5rem;
                                    }

                                    .login-branding {
                                                padding: 3rem 2rem;
                                                min-height: 200px;
                                    }

                                    .login-branding h1 {
                                                font-size: 2rem;
                                    }
                        }

                        @media (max-width: 364px) {
                                    .login-branding {
                                                min-height: 100px;
                                    }

                        }
            </style>
</head>

<body>

            <div class="login-container">
                        <div class="login-branding">
                                    <h1><?php echo $site_title_from_db; ?></h1>
                        </div>

                        <div class="login-form-wrapper">
                                    <h2>Selamat Datang Di Inventory!</h2>
                                    <p class="text-muted">Silahkan masukkan Username dan Password Dengan Benar</p>

                                    <?php if (isset($login_message)): ?>
                                                <div class="alert <?php echo ($login_status === 'success') ? 'alert-success' : 'alert-danger'; ?>" role="alert">
                                                            <?php echo $login_message; ?>
                                                </div>
                                    <?php endif; ?>

                                    <form method="POST" action="">
                                                <div class="mb-3">
                                                            <label for="username" class="form-label visually-hidden">Username</label>
                                                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                                                </div>
                                                <div class="mb-3">
                                                            <label for="password" class="form-label visually-hidden">Password</label>
                                                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-login w-100">
                                                            Login
                                                </button>
                                    </form>
                        </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>