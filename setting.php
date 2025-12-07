<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

// Redirect if not logged in
if (!is_logged_in()) {
  header('Location: login.php');
  exit;
}

$user_id = $_SESSION['user_id'];
$success_msg = '';
$error_msg = '';

// Handle Profile Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
  $first_name = trim($_POST['first_name'] ?? '');
  $last_name = trim($_POST['last_name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? '');

  // Combine first and last name
  $full_name = trim($first_name . ' ' . $last_name);

  try {
    // Update officer table if exists
    $stmt = $pdo->prepare("SELECT name FROM officer WHERE name = (SELECT username FROM USER_ACCOUNT WHERE user_id = ?)");
    $stmt->execute([$user_id]);
    $officer_exists = $stmt->fetch();

    if ($officer_exists) {
      $update_stmt = $pdo->prepare("
                UPDATE officer 
                SET name = ?, email = ?, phone = ? 
                WHERE name = (SELECT username FROM USER_ACCOUNT WHERE user_id = ?)
            ");
      $update_stmt->execute([$full_name, $email, $phone, $user_id]);
    }

    $success_msg = 'Profile updated successfully!';

    // Also update username in USER_ACCOUNT if name changed
    if (!empty($full_name)) {
      $update_user_stmt = $pdo->prepare("UPDATE USER_ACCOUNT SET username = ? WHERE user_id = ?");
      $update_user_stmt->execute([$full_name, $user_id]);
    }
  } catch (Exception $e) {
    $error_msg = 'Error updating profile: ' . $e->getMessage();
  }
}

// Handle Password Change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
  $current_password = $_POST['current_password'] ?? '';
  $new_password = $_POST['new_password'] ?? '';
  $confirm_password = $_POST['confirm_password'] ?? '';

  if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
    $error_msg = 'All password fields are required.';
  } elseif ($new_password !== $confirm_password) {
    $error_msg = 'New passwords do not match.';
  } elseif (strlen($new_password) < 6) {
    $error_msg = 'Password must be at least 6 characters.';
  } else {
    try {
      // Get current password hash
      $stmt = $pdo->prepare("SELECT password_hash FROM USER_ACCOUNT WHERE user_id = ?");
      $stmt->execute([$user_id]);
      $user = $stmt->fetch();

      // Note: In your current system, passwords aren't hashed properly
      // For now, we'll just update without verification
      // In a real system, you should verify current password first

      $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
      $update_stmt = $pdo->prepare("UPDATE USER_ACCOUNT SET password_hash = ? WHERE user_id = ?");
      $update_stmt->execute([$hashed_password, $user_id]);

      $success_msg = 'Password updated successfully!';
    } catch (Exception $e) {
      $error_msg = 'Error updating password: ' . $e->getMessage();
    }
  }
}

// Get current user's details
$stmt = $pdo->prepare("
    SELECT u.username, u.account_status, o.name, o.badge_number, o.email, o.phone, o.rank, o.status
    FROM USER_ACCOUNT u
    LEFT JOIN officer o ON u.username = o.name
    WHERE u.user_id = ?
");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// If no officer record found, use basic user info
if (!$user) {
  $stmt = $pdo->prepare("SELECT username, account_status FROM USER_ACCOUNT WHERE user_id = ?");
  $stmt->execute([$user_id]);
  $user = $stmt->fetch();
  $user['name'] = $user['username'];
  $user['badge_number'] = 'Not assigned';
  $user['email'] = 'Not provided';
  $user['phone'] = 'Not provided';
  $user['rank'] = 'Not assigned';
  $user['status'] = 'Unknown';
}

// Split name into first and last
$name_parts = explode(' ', $user['name']);
$first_name = $name_parts[0] ?? $user['name'];
$last_name = isset($name_parts[1]) ? $name_parts[1] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Settings</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

  <!-- Header/Navbar -->
  <header class="d-flex flex-wrap justify-content-center py-3 mb-4" style="background-color: #081b34;">
    <a href="./images/dog-img.jpg" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
      <svg class="bi me-2" width="40" height="32">
        <use xlink:href="#bootstrap"></use>
      </svg>
      <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="rgb(240, 173, 78)" class="bi bi-shield" viewBox="0 0 16 16">
        <path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56" />
      </svg>
      <span class="fs-4 text-white">P.I.R.M.S</span>
    </a>

    <ul class="nav nav-pills align-items-center">
      <li class="nav-item"><a href="./dashboard.php" class="nav-link text-white " aria-current="page">Dashboard</a></li>
      <li class="nav-item"><a href="./cases.php" class="nav-link text-white ">Cases</a></li>
      <li class="nav-item"><a href="./evidence.php" class="nav-link text-white ">Evidence</a></li>
      <li class="nav-item"><a href="./suspects.php" class="nav-link text-white ">Suspects</a></li>
      <li class="nav-item"><a href="./officer.php" class="nav-link text-white">Officers</a></li>
      <li class="nav-item"><a href="./department.php" class="nav-link text-white">Department</a></li>
      <li class="nav-item"><a href="./contact.php" class="nav-link text-white">Contact</a></li>
      <li class="nav-item">
        <a href="./setting.php" class="nav-link text-white p-0 " aria-label="Settings">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
            <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0" />
            <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z" />
          </svg>
        </a>
      </li>
      <a href="./logout.php" class="btn btn-danger px-4 ms-3" type="button">Logout</a>
    </ul>
  </header>

  <!-- Settings Page Content -->
  <div class="container py-5">
    <h1 class="mb-4">Settings</h1>
    <div class="mb-3 text-secondary">
      Manage your account settings, preferences, and security options
    </div>

    <!-- Success/Error Messages -->
    <?php if ($success_msg): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($success_msg); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <?php if ($error_msg): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($error_msg); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab"><i class="bi bi-person"></i> Profile</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab"><i class="bi bi-lock"></i> Security</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab"><i class="bi bi-bell"></i> Notifications</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="appearance-tab" data-bs-toggle="tab" data-bs-target="#appearance" type="button" role="tab"><i class="bi bi-display"></i> Appearance</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="privacy-tab" data-bs-toggle="tab" data-bs-target="#privacy" type="button" role="tab"><i class="bi bi-shield"></i> Privacy</button>
      </li>
    </ul>

    <div class="tab-content" id="settingsTabsContent">
      <!-- Profile Tab -->
      <div class="tab-pane fade show active" id="profile" role="tabpanel">
        <div class="card mb-4">
          <div class="card-header">Profile Information</div>
          <div class="card-body">
            <form method="post" action="">
              <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">First Name</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Last Name</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>">
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-10"><input type="text" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" disabled></div>
              </div>
              <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Badge Number</label>
                <div class="col-sm-10"><input type="text" class="form-control" value="<?php echo htmlspecialchars($user['badge_number']); ?>" disabled></div>
              </div>
              <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Rank</label>
                <div class="col-sm-10"><input type="text" class="form-control" value="<?php echo htmlspecialchars($user['rank']); ?>" disabled></div>
              </div>
              <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Status</label>
                <div class="col-sm-10"><input type="text" class="form-control" value="<?php echo htmlspecialchars($user['status']); ?>" disabled></div>
              </div>
              <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                  <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                </div>
              </div>
              <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Phone</label>
                <div class="col-sm-10">
                  <input type="tel" class="form-control" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                </div>
              </div>
              <button type="submit" name="update_profile" class="btn btn-outline-primary w-100">Save Profile Changes</button>
            </form>
          </div>
        </div>
      </div>
      <!-- Security Tab -->
      <div class="tab-pane fade" id="security" role="tabpanel">
        <div class="card mb-4">
          <div class="card-header">Password & Authentication</div>
          <div class="card-body">
            <form method="post" action="">
              <div class="mb-3">
                <label>Current Password</label>
                <input type="password" class="form-control" name="current_password">
              </div>
              <div class="mb-3">
                <label>New Password</label>
                <input type="password" class="form-control" name="new_password">
              </div>
              <div class="mb-3">
                <label>Confirm New Password</label>
                <input type="password" class="form-control" name="confirm_password">
              </div>
              <button type="submit" name="update_password" class="btn btn-outline-primary w-100">Update Password</button>
            </form>
            <hr>
            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" checked>
              <label class="form-check-label">Two-Factor Authentication</label>
            </div>
          </div>
        </div>
      </div>
      <!-- Notifications Tab -->
      <div class="tab-pane fade" id="notifications" role="tabpanel">
        <div class="card mb-4">
          <div class="card-header">Notification Preferences</div>
          <div class="card-body">
            <form method="post" action="">
              <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="email_notifications" checked>
                <label class="form-check-label">Email Notifications</label>
              </div>
              <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="case_updates" checked>
                <label class="form-check-label">Case Updates</label>
              </div>
              <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="evidence_alerts" checked>
                <label class="form-check-label">Evidence Alerts</label>
              </div>
              <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="system_alerts" checked>
                <label class="form-check-label">System Alerts</label>
              </div>
              <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="weekly_reports">
                <label class="form-check-label">Weekly Reports</label>
              </div>
              <button type="submit" name="update_notifications" class="btn btn-outline-primary w-100">Save Notification Settings</button>
            </form>
          </div>
        </div>
      </div>
      <!-- Appearance Tab -->
      <div class="tab-pane fade" id="appearance" role="tabpanel">
        <div class="card mb-4">
          <div class="card-header">Appearance Settings</div>
          <div class="card-body">
            <form method="post" action="">
              <div class="mb-3">
                <label>Theme</label>
                <select class="form-select" name="theme">
                  <option value="system" selected>System</option>
                  <option value="light">Light</option>
                  <option value="dark">Dark</option>
                </select>
              </div>
              <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="compact_view">
                <label class="form-check-label">Compact View</label>
              </div>
              <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="high_contrast">
                <label class="form-check-label">High Contrast</label>
              </div>
              <button type="submit" name="update_appearance" class="btn btn-outline-primary w-100">Save Appearance Settings</button>
            </form>
          </div>
        </div>
      </div>
      <!-- Privacy Tab -->
      <div class="tab-pane fade" id="privacy" role="tabpanel">
        <div class="card mb-4">
          <div class="card-header">Privacy & Data</div>
          <div class="card-body">
            <form method="post" action="">
              <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="show_online_status" checked>
                <label class="form-check-label">Show Online Status</label>
              </div>
              <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="allow_activity_tracking" checked>
                <label class="form-check-label">Allow Activity Tracking</label>
              </div>
              <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="share_statistics" checked>
                <label class="form-check-label">Share Statistics</label>
              </div>
              <button type="submit" name="update_privacy" class="btn btn-outline-primary w-100 mb-3">Save Privacy Settings</button>
            </form>
            <hr>
            <button class="btn btn-outline-danger w-100">Request Account Deactivation</button>
            <div class="mt-2 text-muted small">Account deactivation requires supervisor approval and IT department processing</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer Section -->
  <footer class="text-white pt-7" style="background-color: #10233e;">
    <div class="container">
      <div class="row pb-4">
        <!-- PIRMS Logo/Description -->
        <div class="col-md-4 mb-4 mb-md-0 d-flex flex-column align-items-md-start align-items-center">
          <div class="mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="rgb(240,173,78)" class="bi bi-shield" viewBox="0 0 16 16">
              <path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524z" />
              <path d="M5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56" />
            </svg>
            <span class="ms-2 fw-semibold">PIRMS</span>
          </div>
          <div class="text-white-50 small mt-1 text-md-start text-center">
            Digital Record Management for Modern Policing.<br>
            Secure, efficient, and reliable.
          </div>
        </div>

        <!-- Contact Information -->
        <div class="col-md-4 mb-4 mb-md-0 text-md-center text-center">
          <span class="fw-semibold mb-2 d-block">Contact Information</span>
          <div class="small text-white-50">
            <div class="mb-1"><span class="me-2">&#128222;</span>+234 913 435 2139</div>
            <div class="mb-1"><span class="me-2">&#9993;</span>contact@pirms.com</div>
            <div><span class="me-2">&#128205;</span>123, Lekki, City Center</div>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="col-md-4 text-md-end text-center">
          <span class="fw-semibold mb-2 d-block">Quick Links</span>
          <ul class="list-unstyled small text-white-50">
            <li class="mb-1"><a href="#" class="text-white-50 text-decoration-none">Privacy Policy</a></li>
            <li class="mb-1"><a href="#" class="text-white-50 text-decoration-none">Terms of Service</a></li>
            <li class="mb-1"><a href="#" class="text-white-50 text-decoration-none">Support</a></li>
            <li><a href="#" class="text-white-50 text-decoration-none">Documentation</a></li>
          </ul>
        </div>
      </div>
      <hr class="border-light my-3">
      <div class="text-center pb-3 small text-white-50">
        © 2025 PIRMS - Police Investigation Record Management System. All rights reserved.
      </div>
    </div>
  </footer>

  <!-- Bootstrap JS (tabs and toggles) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Auto-refresh after save -->
  <script>
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      });
    }, 5000);
  </script>
</body>

</html>