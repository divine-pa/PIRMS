<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/includes/db.php';

$user = get_logged_in_user();

$statsSql = "
SELECT
  (SELECT COUNT(*) FROM `CASE`) AS total_cases,
  (SELECT COUNT(*) FROM `CASE` WHERE status = 'Ongoing') AS active_cases,
  (SELECT COUNT(*) FROM OFFICER WHERE status = 'Active') AS active_officers,
  (SELECT COUNT(*) FROM `CASE` WHERE priority = 'High') AS high_priority_cases
";
$stats = $pdo->query($statsSql)->fetch();
if (!$stats) {
  $stats = [
    'total_cases' => 0,
    'active_cases' => 0,
    'active_officers' => 0,
    'high_priority_cases' => 0,
  ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>dashboard page
  </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>


  <!--header-->



  <section style="background-color: #081b34;">
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 ">
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
        <a href="logout.php" class="btn btn-warning px-4 ms-3" type="button">Logout</a>
      </ul>


    </header>

  </section>

  <!--header end-->



  <div class="container mb-7">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1>Dashboard</h1>
      <br>
      <a href="notificationpage.php" class="text-body position-relative" aria-label="Notifications">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
          <path d="M8 16a2 2 0 0 0 1.985-1.75H6.015A2 2 0 0 0 8 16zm.104-14.755a.5.5 0 0 1 .454.295l.002.003c.043.095.124.393.124 1.164v.5c0 .706.18 1.287.476 1.756.35.59.887 1.024 1.43 1.298.23.105.4.281.475.49.576 1.37.353 2.53-.206 3.364-.136.224-.233.448-.29.613v.003l-.005.01c-.006.011-.013.022-.02.034A1.383 1.383 0 0 1 10 13.5H6a1.383 1.383 0 0 1-.836-2.04c-.012-.02-.024-.038-.037-.058a.177.177 0 0 1-.005-.01v-.003c-.056-.165-.153-.39-.29-.615-.56-.833-.782-1.993-.206-3.363a.741.741 0 0 1 .476-.49c.543-.273 1.08-.707 1.43-1.298.296-.47.476-1.05.476-1.755v-.5c0-.77.08-1.07.124-1.165a.5.5 0 0 1 .43-.29z" />
        </svg>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
          3
          <span class="visually-hidden">unread notifications</span>
        </span>
      </a>




    </div>




    <!-- Quick Actions -->
    <div class="row g-3 mb-4">
      <div class="col-12 col-md-3">
        <button onclick="location.href='officer.php'" type="button" class="btn btn-outline-secondary w-100 py-3">
          <i class="bi bi-plus-lg me-2"></i> Officer
        </button>
      </div>
      <div class="col-12 col-md-3">
        <button onclick="location.href='cases.php'" type="button" class="btn btn-outline-secondary w-100 py-3">
          <i class="bi bi-search me-2"></i> Search Cases
        </button>
      </div>
      <div class="col-12 col-md-3">
        <button onclick="location.href='newsuspect.php'" type="button" class="btn btn-outline-secondary w-100 py-3">
          <i class="bi bi-plus-lg me-2"></i> Add Suspect
        </button>
      </div>
      <div class="col-12 col-md-3">

      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
      <div class="col-12 col-lg-3">
        <div class="card p-3 text-primary bg-primary bg-opacity-10">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <i class="bi bi-file-text fs-3"></i>
            <span class="text-success">+12%</span>
          </div>
          <h3><?php echo (int)$stats['total_cases']; ?></h3>
          <p>Total Cases</p>
        </div>
      </div>
      <div class="col-12 col-lg-3">
        <div class="card p-3 text-success bg-success bg-opacity-10">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <i class="bi bi-trending-up fs-3"></i>
            <span class="text-success">+5%</span>
          </div>
          <h3><?php echo (int)$stats['active_cases']; ?></h3>
          <p>Active Cases</p>
        </div>
      </div>
      <div class="col-12 col-lg-3">
        <div class="card p-3 text-purple bg-purple bg-opacity-10" style="--bs-purple:#6f42c1;">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <i class="bi bi-people fs-3"></i>
            <span class="text-success">+2</span>
          </div>
          <h3><?php echo (int)$stats['active_officers']; ?></h3>
          <p>Active Officers</p>
        </div>
      </div>
      <div class="col-12 col-lg-3">
        <div class="card p-3 text-danger bg-danger bg-opacity-10">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <i class="bi bi-exclamation-triangle fs-3"></i>
            <span class="text-success">+3</span>
          </div>
          <h3><?php echo (int)$stats['high_priority_cases']; ?></h3>
          <p>High Priority</p>
        </div>
      </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
      <div class="col-12 col-lg-6">
        <div class="card p-3">
          <h5>Cases by Status</h5>
          <p class="text-muted mb-3">Distribution of case statuses</p>
          <div style="width: 350px; height: 250px;">
            <canvas id="casesByStatusChart"></canvas>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-6">
        <div class="card p-3">
          <h5>Cases Over Time</h5>
          <p class="text-muted mb-3">Monthly case trends</p>
          <div style="width: 450px; height: 250px;">
            <canvas id="casesOverTimeChart"></canvas>
          </div>

        </div>
      </div>
    </div>



  </div>



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
  <!-- end of  Footer Section -->






  <script>
    // Data for charts
    const casesByStatus = {
      labels: ['Ongoing', 'Under Review', 'Closed'],
      datasets: [{
        data: [12, 5, 23],
        backgroundColor: ['#3b82f6', '#eab308', '#22c55e'],
        hoverOffset: 30,
      }, ],
    };

    const casesOverTime = {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
      datasets: [{
        label: 'Cases',
        data: [8, 12, 15, 10, 18, 14],
        fill: false,
        borderColor: '#ffcc00',
        tension: 0.4,
      }, ],
    };

    const officerWorkload = {
      labels: ['Det. Johnson', 'Det. Chen', 'Det. Rodriguez', 'Det. Martinez', 'Det. Williams'],
      datasets: [{
        label: 'Cases',
        data: [5, 3, 4, 2, 3],
        backgroundColor: '#0b1e39',
      }, ],
    };

    // Initialize charts
    const ctxStatus = document.getElementById('casesByStatusChart').getContext('2d');
    new Chart(ctxStatus, {
      type: 'pie',
      data: casesByStatus,
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom'
          },
        },
      },
    });

    const ctxOverTime = document.getElementById('casesOverTimeChart').getContext('2d');
    new Chart(ctxOverTime, {
      type: 'line',
      data: casesOverTime,
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true
          },
        },
      },
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>



</body>

</html>