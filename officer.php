<?php
session_start();
require_once 'includes/db.php';

// 1. Fetch all officers
try {
  $stmt = $pdo->query("SELECT * FROM officer ORDER BY name ASC");
  $officers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $officers = [];
  $error = "Error fetching officers: " . $e->getMessage();
}

// 2. Calculate Statistics dynamically
$totalOfficers = count($officers);
$activeOfficers = 0;
$totalYears = 0;

foreach ($officers as $off) {
  if ($off['status'] === 'Active') {
    $activeOfficers++;
  }
  $totalYears += $off['years_of_service'];
}

$avgYears = $totalOfficers > 0 ? round($totalYears / $totalOfficers, 1) : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Officers</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

  <section style="background-color: #081b34;">
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4">
      <a href="./images/dog-img.jpg" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="rgb(240, 173, 78)" class="bi bi-shield ms-4" viewBox="0 0 16 16">
          <path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56" />
        </svg>
        <span class="fs-4 text-white ms-2">P.I.R.M.S</span>
      </a>
      <ul class="nav nav-pills align-items-center">
        <li class="nav-item"><a href="./dashboard.php" class="nav-link text-white">Dashboard</a></li>
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

  <div class="container mb-0">
    <div class="text-left mb-4 mt-5">
      <h2 style="color: #081b34;">Officers Directory</h2>
      <p style="color:rgb(139, 140, 140);">View all police officers and detectives handling investigations.</p>
    </div>
  </div>

  <div class="container py-4">
    <div class="mb-4">
      <input type="text" id="officerSearchInput" class="form-control form-control-lg" placeholder="Search by name, rank, department, or badge number..." />
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4">

      <?php if (empty($officers)): ?>
        <div class="col-12 text-center py-5">
          <h4 class="text-muted">No officers found in the database.</h4>
        </div>
      <?php else: ?>
        <?php foreach ($officers as $off): ?>
          <div class="col officer-card">
            <div class="card h-100 shadow-sm">
              <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                  <div class="rounded-circle bg-primary text-white text-center fw-bold d-flex align-items-center justify-content-center me-3" style="width:44px; height:44px;">
                    <?php
                    // Initials
                    $parts = explode(' ', $off['name']);
                    echo strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
                    ?>
                  </div>
                  <div>
                    <div class="fw-bold"><?php echo htmlspecialchars($off['name']); ?></div>
                    <div><small class="text-muted">Badge: <?php echo htmlspecialchars($off['badge_number']); ?></small></div>
                  </div>
                </div>

                <div class="mb-1">
                  <?php
                  $rankClass = 'bg-secondary';
                  if ($off['rank'] == 'Detective') $rankClass = 'bg-success';
                  if ($off['rank'] == 'Senior Detective') $rankClass = 'bg-primary';
                  if ($off['rank'] == 'Lieutenant') $rankClass = 'bg-dark'; // Changed purple to dark for standard bootstrap
                  ?>
                  <span class="badge <?php echo $rankClass; ?> mb-1"><?php echo htmlspecialchars($off['rank']); ?></span>

                  <?php
                  $statusClass = 'bg-secondary';
                  if ($off['status'] == 'Active') $statusClass = 'bg-success';
                  if ($off['status'] == 'Inactive') $statusClass = 'bg-danger';
                  if ($off['status'] == 'On Leave') $statusClass = 'bg-warning text-dark';
                  ?>
                  <span class="badge <?php echo $statusClass; ?> mb-1"><?php echo htmlspecialchars($off['status']); ?></span>
                </div>

                <div class="mb-1">
                  <strong>Department ID</strong><br />
                  <small class="text-muted">Dept #<?php echo htmlspecialchars($off['department_id']); ?></small>
                </div>

                <div class="d-flex justify-content-between mb-1">
                  <div>
                    <small class="text-muted">Assigned Cases</small><br />
                    <span>--</span>
                  </div>
                  <div class="text-end">
                    <small class="text-muted">Years of Service</small><br />
                    <span><?php echo htmlspecialchars($off['years_of_service']); ?></span>
                  </div>
                </div>
                <div class="mb-3">
                  <small class="text-muted">Specialization</small><br />
                  <span><?php echo htmlspecialchars($off['specialization']); ?></span>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>

    </div>
  </div>

  <div class="container py-4">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3">
      <div class="col">
        <div class="card text-center shadow-sm h-100">
          <div class="card-body">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="lightblue" class="mb-2" viewBox="0 0 16 16">
              <path d="M5.5 1a1 1 0 0 0-1 1v1H2.5A1.5 1.5 0 0 0 1 4.5v10A1.5 1.5 0 0 0 2.5 16h11a1.5 1.5 0 0 0 1.5-1.5v-10A1.5 1.5 0 0 0 13.5 3H11V2a1 1 0 0 0-1-1h-4z" />
            </svg>
            <div class="display-5 fw-bold mb-2"><?php echo $totalOfficers; ?></div>
            <div style="color:rgb(139, 140, 140);">Total Officers</div>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card text-center shadow-sm h-100">
          <div class="card-body">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="pink" class="mb-2" viewBox="0 0 16 16">
              <path d="M2 10h12v1H2v-1zM4.5 5h7a1 1 0 0 1 1 1v5H3.5V6a1 1 0 0 1 1-1z" />
            </svg>
            <div class="display-5 fw-bold mb-2"><?php echo $activeOfficers; ?></div>
            <div style="color:rgb(139, 140, 140);">Active Officers</div>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card text-center shadow-sm h-100">
          <div class="card-body">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#1D4ED8" class="mb-2" viewBox="0 0 16 16">
              <path d="M4 13h8v1H4v-1zm0-9h8v1H4V4zm0 4h8v1H4V8z" />
            </svg>
            <div class="display-5 fw-bold mb-2">--</div>
            <div style="color:rgb(139, 140, 140);">Total Assignments</div>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card text-center shadow-sm h-100">
          <div class="card-body">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="pink" class="mb-2" viewBox="0 0 16 16">
              <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1z" />
            </svg>
            <div class="display-5 fw-bold mb-2"><?php echo $avgYears; ?></div>
            <div>Avg. Years Service</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="text-white pt-7" style="background-color: #10233e;">
    <div class="container">
      <div class="row pb-4">
        <div class="col-md-4 mb-4 mb-md-0 d-flex flex-column align-items-md-start align-items-center">
          <div class="mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="rgb(240,173,78)" class="bi bi-shield" viewBox="0 0 16 16">
              <path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56" />
            </svg>
            <span class="ms-2 fw-semibold">PIRMS</span>
          </div>
          <div class="text-white-50 small mt-1 text-md-start text-center">
            Digital Record Management for Modern Policing.<br>Secure, efficient, and reliable.
          </div>
        </div>

        <div class="col-md-4 mb-4 mb-md-0 text-md-center text-center">
          <span class="fw-semibold mb-2 d-block">Contact Information</span>
          <div class="small text-white-50">
            <div class="mb-1"><span class="me-2">&#128222;</span>+234 913 435 2139</div>
            <div class="mb-1"><span class="me-2">&#9993;</span>contact@pirms.com</div>
            <div><span class="me-2">&#128205;</span>123, Lekki, Lagos</div>
          </div>
        </div>

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

  <script>
    document.getElementById('officerSearchInput').addEventListener('input', function() {
      const filter = this.value.toLowerCase();
      const cards = document.querySelectorAll('.officer-card');

      cards.forEach(card => {
        const text = card.innerText.toLowerCase();
        if (text.includes(filter)) {
          card.style.display = '';
        } else {
          card.style.display = 'none';
        }
      });
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>