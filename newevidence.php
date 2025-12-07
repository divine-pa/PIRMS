<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 1. Generate Evidence ID (e.g., EVI-2025-9382)
  $year = date('Y');
  $random = rand(1000, 9999);
  $evidence_id = "EVI-$year-$random";

  // 2. Collect Data
  $type = $_POST['evidence_type'] ?? '';
  $desc = $_POST['description'] ?? '';
  $condition = $_POST['condition_status'] ?? '';
  $case_id = $_POST['case_id'] ?? '';
  $collected_by = $_POST['collected_by'] ?? '';
  $date = $_POST['date_collected'] ?? date('Y-m-d');
  $location = $_POST['location_found'] ?? '';
  $storage = $_POST['storage_location'] ?? '';
  $notes = $_POST['notes'] ?? '';

  // 3. Insert into DB
  $sql = "INSERT INTO evidence 
            (evidence_id, evidence_type, description, condition_status, case_id, collected_by, date_collected, location_found, storage_location, notes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

  $stmt = $pdo->prepare($sql);

  if ($stmt->execute([$evidence_id, $type, $desc, $condition, $case_id, $collected_by, $date, $location, $storage, $notes])) {
    error_log("Evidence inserted: ID=$evidence_id, Case=$case_id");
    header("Location: evidence.php?msg=added");
    exit;
  } else {
    die("Error: " . implode(" ", $stmt->errorInfo()));
  }
}

// Fetch cases from database for the form
try {
  $cases_stmt = $pdo->query("SELECT case_id, title FROM `case` ORDER BY case_id DESC");
  $cases = $cases_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $cases = [];
}

// Fetch officers from database for the form
try {
  $officers_stmt = $pdo->query("SELECT officer_id, name FROM officer ORDER BY name ASC");
  $officers = $officers_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $officers = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New Evidence</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

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







  <div class="container" style="max-width: 900px;">
    <div class="mb-4 mt-4">
      <h1>Add New Evidence</h1>
      <p class="text-muted">Register new evidence in the chain of custody system</p>
    </div>

    <form id="evidenceForm" method="POST" action="newevidence.php" novalidate>

      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0">Evidence Details</h5>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label">Evidence Type <span class="text-danger">*</span></label>
            <select class="form-select" name="evidence_type" required>
              <option value="" selected disabled>Select evidence type</option>
              <option>Physical Evidence</option>
              <option>Digital Evidence</option>
              <option>Documentary Evidence</option>
              <option>Weapon</option>
              <option>Drug Sample</option>
              <option>Other</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Evidence Description <span class="text-danger">*</span></label>
            <textarea class="form-control" name="description" rows="4" required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Evidence Condition</label>
            <select class="form-select" name="condition_status">
              <option>Good</option>
              <option>Fair</option>
              <option>Damaged</option>
            </select>
          </div>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0">Case Association</h5>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label">Associated Case <span class="text-danger">*</span></label>
            <select class="form-select" name="case_id" required>
              <option value="" selected disabled>Select case</option>
              <?php foreach ($cases as $case): ?>
                <option value="<?php echo htmlspecialchars($case['case_id']); ?>">
                  <?php echo htmlspecialchars($case['case_id'] . ' - ' . $case['title']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0">Collection Information</h5>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label">Collected By <span class="text-danger">*</span></label>
            <select class="form-select" name="collected_by" required>
              <option value="" selected disabled>Select officer</option>
              <?php foreach ($officers as $officer): ?>
                <option value="<?php echo htmlspecialchars($officer['officer_id']); ?>">
                  <?php echo htmlspecialchars($officer['name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">Collection Date</label>
              <input type="date" class="form-control" name="date_collected" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Collection Location</label>
              <input type="text" class="form-control" name="location_found" placeholder="e.g., Crime scene">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Storage Location</label>
            <input type="text" class="form-control" name="storage_location" placeholder="e.g., Evidence Room A">
          </div>

          <div class="mb-3">
            <label class="form-label">Chain of Custody Notes</label>
            <textarea class="form-control" name="notes" rows="3"></textarea>
          </div>
        </div>
      </div>

      <div class="d-flex gap-3 mb-5">
        <button type="submit" class="btn btn-primary flex-grow-1" style="background-color: #081b34;">Register Evidence</button>
        <button type="button" class="btn btn-outline-secondary flex-grow-1" onclick="window.location.href='evidence.php'">Cancel</button>
      </div>
    </form>
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

  <!--enf of footer section -->






  <script>
    // Initialize collection date to today
    document.getElementById('collectionDate').valueAsDate = new Date();

    // Form validation and submission
    const form = document.getElementById('evidenceForm');
    const successAlert = document.getElementById('successAlert');

    form.addEventListener('submit', function(e) {
      e.preventDefault();

      // Reset validation classes
      form.classList.remove('was-validated');
      let valid = true;

      // Check required selects and inputs
      const requiredFields = ['evidenceType', 'description', 'caseId', 'collectedBy', 'collectionDate'];
      requiredFields.forEach(id => {
        const el = document.getElementById(id);
        if (!el.value || el.value === '') {
          el.classList.add('is-invalid');
          valid = false;
        } else {
          el.classList.remove('is-invalid');
        }
      });

      if (!valid) {
        form.classList.add('was-validated');
        return;
      }

      // Simulate saving evidence data and log to console
      const evidenceData = {
        evidenceType: document.getElementById('evidenceType').value,
        description: document.getElementById('description').value,
        caseId: document.getElementById('caseId').value,
        collectedBy: document.getElementById('collectedBy').value,
        collectionDate: document.getElementById('collectionDate').value,
        collectionLocation: document.getElementById('collectionLocation').value,
        storageLocation: document.getElementById('storageLocation').value,
        condition: document.getElementById('condition').value,
        chainOfCustody: document.getElementById('chainOfCustody').value,
        evidenceId: `EVI-${new Date().getFullYear()}-${String(Math.floor(Math.random() * 9999) + 1).padStart(4, '0')}`,
        registeredDate: new Date().toISOString(),
      };

      console.log('New Evidence Data:', evidenceData);

      successAlert.classList.remove('d-none');

      setTimeout(() => {
        window.location.href = 'cases.php'; // Redirect after 2 sec
      }, 2000);
    });
  </script>




  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>