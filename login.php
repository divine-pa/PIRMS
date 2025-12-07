<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

// If already logged in, redirect to dashboard
if (is_logged_in()) {
  header('Location: dashboard.php');
  exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');

  if ($username === '' || $password === '') {
    $error = 'Both username and password are required.';
  } else {
    $stmt = $pdo->prepare("
            SELECT user_id, username, account_status
            FROM USER_ACCOUNT
            WHERE username = ?
        ");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (!$user) {
      $error = 'Invalid username.';
    } elseif ($user['account_status'] !== 'Active') {
      $error = 'Your account is not active.';
    } else {
      $_SESSION['user_id'] = $user['user_id'];
      header('Location: dashboard.php');
      exit;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>


  <!--header-->


  <section style="background-color: #081b34;">
    <!-- <header class="d-flex flex-wrap justify-content-center py-3 mb-4 ">
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
        <li class="nav-item"><a href="./dashboard.php" class="nav-link text-white" aria-current="page">Dashboard</a></li>
        <li class="nav-item"><a href="./cases.php" class="nav-link text-white">Cases</a></li>
        <li class="nav-item"><a href="./evidence.php" class="nav-link text-white">Evidence</a></li>

        <li class="nav-item"><a href="./suspects.php" class="nav-link text-white">Suspects</a></li>
        <li class="nav-item"><a href="./officer.php" class="nav-link text-white">Officers</a></li>
        <li class="nav-item"><a href="./department.php" class="nav-link text-white">Department</a></li>

        <li class="nav-item"><a href="./contact.php" class="nav-link text-white">Contact</a></li>
        <li class="nav-item">
          <a href="./setting.php" class="nav-link text-white p-0" aria-label="Settings">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
              <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0" />
              <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z" />
            </svg>
          </a>
        </li>
        <button onclick="location.href='login.php'" class="btn btn-warning px-4 ms-3" type="button">Login</button>
      </ul>

    </header> -->

  </section>

  <!--header end-->




  <div class="container-fluid min-vh-100 d-flex flex-column justify-content-center align-items-center py-5">
    <!-- Outlined Shield Logo -->
    <div class="mb-2 text-center">
      <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" stroke="orange" stroke-width="2" class="mb-3" viewBox="0 0 16 16">
        <path d="M8 1C5.338 1.59 2.5 2.446 2.5 2.446C1.946 6.603 3.226 9.636 4.753 11.634c.629.838 1.394 1.618 2.287 2.233.346.244.652.42.893.533.108.054.203.091.293.118.09-.027.185-.064.293-.118.24-.113.547-.29.893-.533.893-.615 1.658-1.395 2.287-2.233C12.774 9.636 14.054 6.603 13.5 2.446c0 0-2.838-.856-5.5-1.446z" />
        <path d="M8 1C10.662 1.59 13.5 2.446 13.5 2.446C14.054 6.603 12.774 9.636 11.247 11.634c-.629.838-1.394 1.618-2.287 2.233-.346.244-.652.42-.893.533a1.209 1.209 0 0 1-.293.118.934.934 0 0 1-.293-.118c-.24-.113-.547-.29-.893-.533-.893-.615-1.658-1.395-2.287-2.233C3.226 9.636 1.946 6.603 2.5 2.446c0 0 2.838-.856 5.5-1.446z" />
      </svg>
    </div>
    <!-- Heading and subtitle -->
    <div class="text-center">
      <h3 class="fw-bold mb-1">System Login</h3>
      <div class="mb-2">
        <span>Access the Police Investigation Record Management System</span>
      </div>
    </div>


    <div class="container mb-3"></div>
    <!-- Login card -->
    <div class="card mx-auto" style="max-width: 410px;">
      <div class="card-body">
        <h5 class="fw-bold mb-2">Officer Login</h5>
        <div class="mb-1">Enter your credentials to access the system</div>
        <?php if (!empty($error)): ?>
          <div class="alert alert-danger py-2 small">
            <?php echo htmlspecialchars($error); ?>
          </div>
        <?php endif; ?>

        <form method="post" action="">
          <label class="form-label fw-semibold">Username / Badge Number</label>
          <div class="input-group mb-3">
            <span class="input-group-text bg-light"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person"
                viewBox="0 0 16 16">
                <path
                  d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5 6a5 5 0 0 1 10 0H3z" />
              </svg></span>
            <input type="text" name="username" class="form-control" placeholder="Enter your username or badge number" required />
          </div>
          <label class="form-label fw-semibold">Password</label>
          <div class="input-group mb-3">
            <span class="input-group-text bg-light"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock"
                viewBox="0 0 16 16">
                <path
                  d="M8 1a3 3 0 0 0-3 3v3H4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-5a2 2 0 0 0-2-2h-1V4a3 3 0 0 0-3-3z" />
              </svg></span>
            <input type="password" name="password" class="form-control" placeholder="Enter your password" required />
            <span class="input-group-text bg-light"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye"
                viewBox="0 0 16 16">
                <path
                  d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zm-8 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                <path
                  d="M8 5a3 3 0 0 1 0 6 3 3 0 0 1 0-6z" />
              </svg></span>
          </div>
          <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="rememberMe" />
              <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>
            <a href="forgotpassword.php" class="link-primary small mb-0 ms-2">Forgot Password?</a>
          </div>
          <button class="btn w-100 fw-semibold mb-2" type="submit" style="background-color: #081b34; color: #fff;">Login to PIRMS</button>

        </form>
        <div class="mt-2 text-center small">
          Don't have an account?
          <a href="signup.php" class="text-primary text-decoration-underline fw-semibold">Sign Up</a>
        </div>
      </div>
    </div>
  </div>


  <!-- Demo notice alert -->
  <div class="alert alert-warning mx-auto text-center mb-3" role="alert" style="max-width: 500px;">
    <span class="me-2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="var(--bs-warning)" class="bi bi-info-circle" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z" />
        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 .875-.252 1.02-.798l.088-.416c.066-.315.15-.386.465-.386h.551c.412 0 .504-.165.439-.493l-.738-3.468c-.194-.897-.7-1.319-1.399-1.319-.545 0-.876.252-1.021.798zm-1.229-.469c.566 0 .916.252.916.798 0 .291-.196.471-.496.471H7.11c-.487 0-.615-.271-.615-.798 0-.291.197-.471.497-.471zm.342 2.106-.775-.324c.04-.047.08-.098.119-.148l.318.472c.028.042.054.086.079.13l.259.457zm.491-4.505a.405.405 0 1 1-.81 0 .405.405 0 0 1 .81 0z" />
      </svg></span>
    Security Notice
    This system is for authorized law enforcement personnel only. All access attempts are logged and monitored. Unauthorized access is prohibited.
  </div>




  <div class="text-center mt-3">
    <a href="index.php" class="fw-semibold text-reset" style="text-decoration: none;">

    </a>
  </div>


  <div class="container mb-5"></div>
  <div class="container mb-5"></div>



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
            <div><span class="me-2">&#128205;</span>123, Lekki, Lagos</div>
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




  <!--enf of footer section -->



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>