<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Suspects</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>

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
      <li class="nav-item"><a href="./dashboard.php" class="nav-link text-white <?php echo is_active_page('dashboard.php') ? 'active bg-warning text-dark fw-bold' : ''; ?>" aria-current="page">Dashboard</a></li>
      <li class="nav-item"><a href="./cases.php" class="nav-link text-white <?php echo is_active_page('cases.php') ? 'active bg-warning text-dark fw-bold' : ''; ?>">Cases</a></li>
      <li class="nav-item"><a href="./evidence.php" class="nav-link text-white <?php echo is_active_page('evidence.php') ? 'active bg-warning text-dark fw-bold' : ''; ?>">Evidence</a></li>

      <li class="nav-item"><a href="./suspects.php" class="nav-link text-white <?php echo is_active_page('suspects.php') ? 'active bg-warning text-dark fw-bold' : ''; ?>">Suspects</a></li>
      <li class="nav-item"><a href="./officer.php" class="nav-link text-white <?php echo is_active_page('officer.php') ? 'active bg-warning text-dark fw-bold' : ''; ?>">Officers</a></li>
      <li class="nav-item"><a href="./department.php" class="nav-link text-white <?php echo is_active_page('department.php') ? 'active bg-warning text-dark fw-bold' : ''; ?>">Department</a></li>

      <li class="nav-item"><a href="./contact.php" class="nav-link text-white <?php echo is_active_page('contact.php') ? 'active bg-warning text-dark fw-bold' : ''; ?>">Contact</a></li>
      <li class="nav-item">
        <a href="./setting.php" class="nav-link text-white p-0 <?php echo is_active_page('setting.php') ? 'active bg-warning text-dark fw-bold rounded' : ''; ?>" aria-label="Settings">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
            <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0" />
            <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z" />
          </svg>
        </a>
      </li>
      <a href="./logout.php" class="btn btn-danger px-4 ms-3" type="button">Logout</a>
    </ul>

  </header>


  </section>

  <!--header end-->


  <div class="container mb-">
    <div class="text-left mb-4 mt-5">
      <h2 style="color: #081b34;">Suspect Database</h2>
      <p style="color:rgb(139, 140, 140);">View and manage information about suspects linked to active investigations.</p>
      <button onclick="location.href='newsuspect.php'" type="button" class="btn text-white fw-bold px-4 py-2 d-flex align-items-center float-end" style="background:#081b34;">
        <span class="me-2 fs-5">+</span> Add New Suspect
      </button>
    </div>
  </div>



  <div class="container py-4">
    <!-- Search Bar -->
    <div class="mb-4">
      <input type="text" id="suspectSearchInput" class="form-control form-control-lg" placeholder="Search by suspect name, case ID, or case title..." />
    </div>

    <!-- Suspect Cards Grid -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">

      <?php
      // Fetch using the singular table name 'suspect'
      $stmt = $pdo->query("SELECT * FROM suspect ORDER BY name ASC");
      $suspects = $stmt->fetchAll(PDO::FETCH_ASSOC);
      ?>

      <?php if (empty($suspects)): ?>
        <div class="col-12 text-center py-5">
          <h4 class="text-muted">No suspects found in the database.</h4>
        </div>
      <?php else: ?>

        <?php foreach ($suspects as $s): ?>
          <div class="col suspect-card">
            <div class="card h-100 shadow-sm">
              <div class="card-body d-flex flex-column">
                <div class="d-flex align-items-center mb-3">
                  <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 48px; height: 48px;">
                    <?php
                    // Initials logic
                    $parts = explode(' ', $s['name']);
                    echo strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
                    ?>
                  </div>
                  <div class="ms-3">
                    <h6 class="mb-0"><?php echo htmlspecialchars($s['name']); ?></h6>

                    <small class="text-muted">ID: <?php echo htmlspecialchars($s['suspect_id']); ?></small><br />

                    <small class="text-muted">Case Linked <br /><strong><?php echo htmlspecialchars($s['case_id'] ?? 'N/A'); ?></strong></small>
                  </div>
                </div>

                <div class="mb-2">
                  <small class="text-muted">Gender</small><br />
                  <span><?php echo htmlspecialchars($s['gender']); ?></span>
                </div>

                <div class="mb-3">
                  <small class="text-muted">Age</small><br />
                  <span><?php echo htmlspecialchars($s['age']); ?></span>
                </div>

                <div class="mb-3">
                  <?php
                  $badgeClass = 'bg-secondary';
                  if ($s['status'] == 'Arrested') $badgeClass = 'bg-danger';
                  if ($s['status'] == 'Under Investigation') $badgeClass = 'bg-primary';
                  if ($s['status'] == 'Released') $badgeClass = 'bg-success';
                  ?>
                  <span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($s['status']); ?></span>
                </div>

                <?php if (!empty($s['charges'])): ?>
                  <div class="mt-auto pt-2 border-top">
                    <small class="text-muted d-block mb-1">Charges:</small>
                    <small class="fw-bold"><?php echo htmlspecialchars($s['charges']); ?></small>
                  </div>
                <?php endif; ?>

              </div>
            </div>
          </div>
        <?php endforeach; ?>

      <?php endif; ?>

    </div>
  </div>



  <!-- end of suspects  card -->



  <div class="container mb-5"></div>

  <!-- Suspect statistics -->



  <!-- cases Statistics-->

  <!-- Summary Row -->
  <div class="row g-3">
    <div class="col-md-4">
      <div class="card text-center">
        <div class="card-body">
          <div class="display-6 mb-2">6</div>
          <!-- Clipboard Icon -->
          <div>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="red" class="bi bi-clipboard-data mb-1" viewBox="0 0 16 16">
              <path d="M0 4a2 2 0 0 1 2-2h4.5a.5.5 0 1 1 0 1H2a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1h-4.5a.5.5 0 1 1 0-1H14a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2z" />
              <path d="M10 6a.5.5 0 0 1 .5.5V9a.5.5 0 0 1-1 0V6.5a.5.5 0 0 1 .5-.5zm-2 2a.5.5 0 0 1 .5.5v1.5a.5.5 0 0 1-1 0V8.5a.5.5 0 0 1 .5-.5zm4 1a.5.5 0 0 1 .5.5v.5a.5.5 0 0 1-1 0v-.5a.5.5 0 0 1 .5-.5z" />
            </svg>
          </div>
          <p class="mb-0" style="color:rgb(139, 140, 140);">Total Cases</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-center">
        <div class="card-body">
          <div class="display-6 mb-2">3</div>
          <!-- Calendar Icon -->
          <div>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="blue" class="bi bi-calendar2-week mb-1" viewBox="0 0 16 16">
              <path d="M3.5 0a.5.5 0 0 0 0 1H4v1a2 2 0 0 0 2 2h3a2 2 0 0 0 2-2V1h.5a.5.5 0 0 0 0-1h-9zM12 4a1 1 0 0 1 1 1v1H3V5a1 1 0 0 1 1-1h8zm-2.5 3a.5.5 0 0 1 .5.5v1.5h-2v-1.5a.5.5 0 0 1 .5-.5zm-2 2a.5.5 0 0 1 .5.5v1.5a.5.5 0 0 1-1 0v-1.5a.5.5 0 0 1 .5-.5zm4 0a.5.5 0 0 1 .5.5v1.5a.5.5 0 0 1-1 0v-1.5a.5.5 0 0 1 .5-.5z" />
              <path d="M2.5 1v3h11V1a2 2 0 0 0-2-2h-7a2 2 0 0 0-2 2zm11 13a1 1 0 0 0 1-1V5.5V5A2 2 0 0 0 13.5 4H13v1h1A1 1 0 0 1 15 6v8a1 1 0 0 1-1 1h-11a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1V4h-.5A2 2 0 0 0 1 5v8a2 2 0 0 0 2 2h11z" />
            </svg>
          </div>
          <p class="mb-0" style="color:rgb(139, 140, 140);">Ongoing</p>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card text-center">
        <div class="card-body">
          <div class="display-6 mb-2">2</div>
          <!-- Person Icon -->
          <div>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="green" class="bi bi-person mb-1" viewBox="0 0 16 16">
              <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-7 8a7 7 0 1 1 14 0H1z" />
            </svg>
          </div>
          <p class="mb-0" style="color:rgb(139, 140, 140);">Closed</p>
        </div>
      </div>
    </div>
  </div>
  </div>

  <!--  end of cases Statistics-->

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




  <!--enf of footer section -->




  <script>
    document.getElementById('suspectSearchInput').addEventListener('input', function() {
      const filter = this.value.toLowerCase();
      const cards = document.querySelectorAll('.suspect-card');

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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>