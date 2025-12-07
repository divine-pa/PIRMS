<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 1. Generate a random Suspect ID (since your DB doesn't do it auto)
  // Example result: "SUS-8392"
  $suspect_id = 'SUS-' . rand(1000, 9999);

  // 2. Collect form data
  $name = $_POST['name'] ?? '';
  $gender = $_POST['gender'] ?? '';
  $age = $_POST['age'] ?? 0;
  $date_of_birth = !empty($_POST['dateOfBirth']) ? $_POST['dateOfBirth'] : NULL;
  $address = $_POST['address'] ?? '';
  $physical_description = $_POST['description'] ?? '';
  $case_id = $_POST['caseId'] ?? '';
  $status = $_POST['status'] ?? '';
  $charges = $_POST['charges_data'] ?? '';

  // 3. Start a transaction (so both inserts succeed or both fail)
  try {
    $pdo->beginTransaction();

    // 4. Insert into SUSPECT table (WITHOUT case_id - we'll use the join table instead)
    $sql = "INSERT INTO suspect 
              (suspect_id, name, gender, age, date_of_birth, address, physical_description, status, charges) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$suspect_id, $name, $gender, $age, $date_of_birth, $address, $physical_description, $status, $charges]);

    // 5. Insert into CASE_SUSPECT join table to link suspect to case
    if (!empty($case_id)) {
      $joinSql = "INSERT INTO case_suspect (case_id, suspect_id) VALUES (?, ?)";
      $joinStmt = $pdo->prepare($joinSql);
      $joinStmt->execute([$case_id, $suspect_id]);
    }

    // 6. Commit the transaction
    $pdo->commit();

    // Log the insertion for debugging
    error_log("Suspect created: ID=$suspect_id, Name=$name, Case=$case_id");
    error_log("Join table entry created: case_id=$case_id, suspect_id=$suspect_id");

    header("Location: suspects.php?msg=added");
    exit;
  } catch (Exception $e) {
    // If anything fails, rollback both inserts
    $pdo->rollBack();
    die("Error saving suspect: " . $e->getMessage());
  }
}

// Fetch cases from database for the dropdown
$cases = [];
try {
  $stmt = $pdo->query("SELECT case_id, title FROM `case` ORDER BY date_started DESC");
  $cases = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  error_log("Error fetching cases: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New Suspect</title>
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

  <div class="container" style="max-width: 900px;">
    <!-- Header -->
    <div class="mb-4">
      <button type="button" class="btn btn-outline-link p-0 mb-3" onclick="window.location.href='suspects.php'">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" class="bi bi-arrow-left me-2" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M15 8a.5.5 0 0 1-.5.5H2.707l4.147 4.146a.5.5 0 0 1-.708.708l-5-5a.5.5 0 0 1 0-.708l5-5a.5.5 0 1 1 .708.708L2.707 7.5H14.5A.5.5 0 0 1 15 8z" />
        </svg>
        Back to Suspects
      </button>
      <h1>Add New Suspect</h1>
      <p class="text-muted">Register a new suspect in the investigation system</p>
    </div>

    <!-- Success Alert -->
    <div id="successAlert" class="alert alert-success d-none" role="alert">
      Suspect added successfully! Redirecting to suspects list...
    </div>

    <form id="suspectForm" method="POST" action="newsuspect.php" novalidate>
      <input type="hidden" name="charges_data" id="chargesHiddenInput">

      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0">Personal Information</h5>
          <small class="text-muted">Enter the suspect's personal details</small>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="name" name="name" placeholder="e.g., John Anderson" required />
            <div class="invalid-feedback">Full name is required.</div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <label for="gender" class="form-label">Gender</label>
              <select id="gender" name="gender" class="form-select">
                <option selected>Male</option>
                <option>Female</option>
                <option>Other</option>
              </select>
            </div>

            <div class="col-md-4">
              <label for="age" class="form-label">Age <span class="text-danger">*</span></label>
              <input type="number" id="age" name="age" class="form-control" placeholder="e.g., 34" required />
              <div class="invalid-feedback">Age is required.</div>
            </div>

            <div class="col-md-4">
              <label for="dateOfBirth" class="form-label">Date of Birth</label>
              <input type="date" id="dateOfBirth" name="dateOfBirth" class="form-control" />
            </div>
          </div>

          <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" id="address" name="address" class="form-control" placeholder="e.g., 123 Oak Street, Downtown District" />
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Physical Description <span class="text-danger">*</span></label>
            <textarea id="description" name="description" class="form-control" rows="4" placeholder="Provide physical description including height, build, hair color, distinguishing features..." required></textarea>
            <div class="invalid-feedback">Physical description is required.</div>
          </div>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0">Case Association</h5>
          <small class="text-muted">Link this suspect to an investigation case</small>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="caseId" class="form-label">Linked Case <span class="text-danger">*</span></label>
              <select id="caseId" name="caseId" class="form-select" required>
                <option value="" selected disabled>Select case</option>
                <?php foreach ($cases as $c): ?>
                  <option value="<?php echo htmlspecialchars($c['case_id']); ?>">
                    <?php echo htmlspecialchars($c['case_id'] . ' - ' . $c['title']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback">Please select a linked case.</div>
            </div>

            <div class="col-md-6">
              <label for="status" class="form-label">Status</label>
              <select id="status" name="status" class="form-select">
                <option selected>Under Investigation</option>
                <option>Arrested</option>
                <option>Released</option>
                <option>Convicted</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0">Charges</h5>
          <small class="text-muted">Add criminal charges associated with this suspect</small>
        </div>
        <div class="card-body">
          <div class="input-group mb-3">
            <input type="text" id="newCharge" class="form-control" placeholder="e.g., Burglary, Breaking and Entering" />
            <button type="button" class="btn btn-outline-primary" id="addChargeBtn">Add</button>
          </div>

          <div id="chargesList" class="d-flex flex-wrap gap-2">
            <p class="text-muted">No charges added yet. Add charges using the input above.</p>
          </div>
        </div>
      </div>

      <div class="d-flex gap-3">
        <button type="submit" class="btn btn-outline-primary flex-grow-1">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-save2 me-2" viewBox="0 0 16 16">
            <path d="M6.5 1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 1 0v-1a.5.5 0 0 0-.5-.5zM1 2.5A1.5 1.5 0 0 1 2.5 1h5.793a1.5 1.5 0 0 1 1.06.44l3.207 3.207a1.5 1.5 0 0 1 .44 1.06v7.793A1.5 1.5 0 0 1 11.5 15h-9A1.5 1.5 0 0 1 1 13.5v-11z" />
            <path d="M4.5 5a.5.5 0 0 1 .5.5V9h1a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5v-4a.5.5 0 0 1 .5-.5z" />
          </svg>
          Add Suspect
        </button>
        <button type="button" class="btn btn-outline-secondary flex-grow-1" onclick="window.location.href='suspects.php'">Cancel</button>
      </div>
    </form>
  </div>

  <div class="container mb-5"></div>

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
            Digital Record Management for Modern Policing.<br>
            Secure, efficient, and reliable.
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
    // Charges list management
    const chargesListDiv = document.getElementById('chargesList');
    const newChargeInput = document.getElementById('newCharge');
    const addChargeBtn = document.getElementById('addChargeBtn');
    let charges = [];

    function renderCharges() {
      chargesListDiv.innerHTML = '';
      if (charges.length === 0) {
        const p = document.createElement('p');
        p.className = 'text-muted';
        p.textContent = 'No charges added yet. Add charges using the input above.';
        chargesListDiv.appendChild(p);
        return;
      }
      charges.forEach((charge, index) => {
        const span = document.createElement('span');
        span.className = 'badge bg-secondary me-1 mb-1';
        span.style.fontSize = '0.9rem';
        span.innerHTML = `${charge} <span style="cursor:pointer; margin-left:5px;" onclick="removeCharge(${index})">&times;</span>`;
        chargesListDiv.appendChild(span);
      });
    }

    window.removeCharge = function(index) {
      charges.splice(index, 1);
      renderCharges();
    }

    addChargeBtn.addEventListener('click', () => {
      const charge = newChargeInput.value.trim();
      if (charge) {
        charges.push(charge);
        newChargeInput.value = '';
        renderCharges();
      }
    });

    newChargeInput.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        addChargeBtn.click();
      }
    });

    const form = document.getElementById('suspectForm');
    const chargesHiddenInput = document.getElementById('chargesHiddenInput');

    form.addEventListener('submit', function(e) {
      let valid = true;
      const requiredIds = ['name', 'age', 'description', 'caseId'];

      requiredIds.forEach(id => {
        const el = document.getElementById(id);
        if (!el.value || el.value.trim() === '') {
          el.classList.add('is-invalid');
          valid = false;
        } else {
          el.classList.remove('is-invalid');
        }
      });

      if (!valid) {
        e.preventDefault();
        e.stopPropagation();
        form.classList.add('was-validated');
        return;
      }

      chargesHiddenInput.value = charges.join(', ');
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>