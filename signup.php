<?php
session_start();
require_once __DIR__ . '/includes/db.php';

$error = '';
$success = '';
$showSuccess = false;

// Check if this is a success redirect from form submission
if (isset($_GET['success']) && $_GET['success'] === 'signup') {
    $showSuccess = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    // Basic Validation
    if ($username === '' || $password === '' || $confirm_password === '') {
        $error = 'All fields are required.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        // Check if username already exists in USER_ACCOUNT
        $stmt = $pdo->prepare("SELECT user_id FROM USER_ACCOUNT WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            $error = '⚠️ This username is already registered! Please use a different username or login if this is your account.';
        } else {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Generate Officer ID (OFF-XXXX)
            $officer_id = 'OFF-' . rand(1000, 9999);

            // Insert new user account
            $insertUserStmt = $pdo->prepare("
                INSERT INTO USER_ACCOUNT (username, password_hash, account_status) 
                VALUES (?, ?, 'Active')
            ");

            if ($insertUserStmt->execute([$username, $hashed_password])) {
                // Also create officer record in the officer list
                $insertOfficerStmt = $pdo->prepare("
                    INSERT INTO officer (officer_id, name, badge_number, status) 
                    VALUES (?, ?, ?, 'Active')
                ");

                // Use username as initial name and badge number
                if ($insertOfficerStmt->execute([$officer_id, $username, $username])) {
                    error_log("New account created: Username=$username, Officer ID=$officer_id");
                    // Redirect with success parameter
                    header("Location: signup.php?success=signup");
                    exit;
                } else {
                    // If officer creation fails, still redirect with success
                    error_log("Warning: Account created but officer record failed - Username=$username");
                    header("Location: signup.php?success=signup");
                    exit;
                }
            } else {
                $error = 'System error. Could not register account.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - PIRMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <section style="background-color: #081b34;">
        <header class="d-flex flex-wrap justify-content-center py-3 mb-4 ">
            <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="rgb(240, 173, 78)" class="bi bi-shield ms-4" viewBox="0 0 16 16">
                    <path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56" />
                </svg>
                <span class="fs-4 text-white ms-2">P.I.R.M.S</span>
            </a>
            <ul class="nav nav-pills align-items-center">
                <button onclick="location.href='login.php'" class="btn btn-outline-warning px-4 me-4" type="button">Back to Login</button>
            </ul>
        </header>
    </section>

    <div class="container-fluid min-vh-100 d-flex flex-column justify-content-center align-items-center py-5">

        <div class="mb-2 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" stroke="orange" stroke-width="2" class="mb-3" viewBox="0 0 16 16">
                <path d="M8 1C5.338 1.59 2.5 2.446 2.5 2.446C1.946 6.603 3.226 9.636 4.753 11.634c.629.838 1.394 1.618 2.287 2.233.346.244.652.42.893.533.108.054.203.091.293.118.09-.027.185-.064.293-.118.24-.113.547-.29.893-.533.893-.615 1.658-1.395 2.287-2.233C12.774 9.636 14.054 6.603 13.5 2.446c0 0-2.838-.856-5.5-1.446z" />
                <path d="M8 1C10.662 1.59 13.5 2.446 13.5 2.446C14.054 6.603 12.774 9.636 11.247 11.634c-.629.838-1.394 1.618-2.287 2.233-.346.244-.652.42-.893.533a1.209 1.209 0 0 1-.293.118.934.934 0 0 1-.293-.118c-.24-.113-.547-.29-.893-.533-.893-.615-1.658-1.395-2.287-2.233C3.226 9.636 1.946 6.603 2.5 2.446c0 0 2.838-.856 5.5-1.446z" />
            </svg>
        </div>

        <div class="text-center">
            <h3 class="fw-bold mb-1">Create Account</h3>
            <div class="mb-2">
                <span>Register for the Police Investigation Record Management System</span>
            </div>
        </div>

        <div class="container mb-3"></div>

        <div class="card mx-auto" style="max-width: 410px;">
            <div class="card-body">
                <h5 class="fw-bold mb-2">New Officer Registration</h5>
                <div class="mb-3 small text-muted">Please fill in your details below</div>

                <?php if ($showSuccess): ?>
                    <div id="successNotif" class="alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3" role="alert" style="z-index: 9999; width: 90%; max-width: 500px; animation: slideDown 0.4s ease-out;">
                        <div class="d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-circle me-2" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z" />
                            </svg>
                            <strong>Success!</strong> ✓ Account created successfully! You have been added to the officer list. Redirecting to login...
                        </div>
                    </div>
                    <style>
                        @keyframes slideDown {
                            from {
                                transform: translate(-50%, -100%);
                                opacity: 0;
                            }

                            to {
                                transform: translate(-50%, 0);
                                opacity: 1;
                            }
                        }

                        @keyframes fadeOut {
                            to {
                                opacity: 0;
                                transform: translate(-50%, -100%);
                            }
                        }
                    </style>
                    <script>
                        setTimeout(() => {
                            window.location.href = 'login.php';
                        }, 2000);
                    </script>
                <?php endif; ?>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger py-2 small">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="">

                    <label class="form-label fw-semibold">Username / Badge Number</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-badge" viewBox="0 0 16 16">
                                <path d="M6.5 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                <path d="M4.5 0A2.5 2.5 0 0 0 2 2.5V14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2.5A2.5 2.5 0 0 0 11.5 0h-7zM3 2.5A1.5 1.5 0 0 1 4.5 1h7A1.5 1.5 0 0 1 13 2.5v10.795a4.2 4.2 0 0 0-.776-.492C11.392 12.387 10.063 12 8 12s-3.392.387-4.224.803a4.2 4.2 0 0 0-.776.492V2.5z" />
                            </svg></span>
                        <input type="text" name="username" class="form-control" placeholder="Create a username" required />
                    </div>

                    <label class="form-label fw-semibold">Password</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock" viewBox="0 0 16 16">
                                <path d="M8 1a3 3 0 0 0-3 3v3H4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-5a2 2 0 0 0-2-2h-1V4a3 3 0 0 0-3-3z" />
                            </svg></span>
                        <input type="password" name="password" class="form-control" placeholder="Create a password" required />
                    </div>

                    <label class="form-label fw-semibold">Confirm Password</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                                <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z" />
                            </svg></span>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Repeat password" required />
                    </div>

                    <button class="btn w-100 fw-semibold mb-2" type="submit" style="background-color: #081b34; color: #fff;">Create Account</button>
                </form>

                <div class="mt-3 text-center small">
                    Already have an account?
                    <a href="login.php" class="text-primary text-decoration-underline fw-semibold">Login here</a>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-white pt-4 pb-4" style="background-color: #10233e;">
        <div class="container text-center">
            <div class="small text-white-50">
                © 2025 PIRMS - Police Investigation Record Management System.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>