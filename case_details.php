<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/includes/db.php';

$user   = get_logged_in_user();
$caseId = $_GET['case_id'] ?? '';

if ($caseId === '') {
    header('Location: cases.php');
    exit;
}

// Get case details
$caseStmt = $pdo->prepare("
    SELECT c.case_id, c.title, c.description, c.status, c.priority,
           c.date_started, c.progress_percent,
           o.name AS officer_name, o.badge_number
    FROM `CASE` c
    LEFT JOIN OFFICER o ON c.assigned_officer_id = o.officer_id
    WHERE c.case_id = ?
");
$caseStmt->execute([$caseId]);
$case = $caseStmt->fetch();

if (!$case) {
    header('Location: cases.php');
    exit;
}

// Suspects linked to case
$suspectsStmt = $pdo->prepare("
    SELECT s.suspect_id, s.name, s.status, s.charges
    FROM SUSPECT s
    JOIN CASE_SUSPECT cs ON s.suspect_id = cs.suspect_id
    WHERE cs.case_id = ?
");
$suspectsStmt->execute([$caseId]);
$suspects = $suspectsStmt->fetchAll();

// Evidence linked to case
$evidenceStmt = $pdo->prepare("
    SELECT evidence_id, evidence_type, description, date_collected, location_found
    FROM EVIDENCE
    WHERE case_id = ?
");
$evidenceStmt->execute([$caseId]);
$evidence = $evidenceStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>case detail page
    </title>
                  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<body>


    <!--header-->
         

<section  style="background-color: #081b34;">
        <header class="d-flex flex-wrap justify-content-center py-3 mb-4 ">
            <a href="./images/dog-img.jpg" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
              <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
              <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="rgb(240, 173, 78)" class="bi bi-shield" viewBox="0 0 16 16">
  <path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56"/>
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
      <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
      <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>
    </svg>
  </a>
</li>
    <button onclick="location.href='login.php'" class="btn btn-warning px-4 ms-3" type="button">Login</button>
  </ul>


          </header> 

</section>
     
     <!--header end-->

  
  <div class="container" style="max-width: 1024px;">
    <!-- Back Button -->
    <button class="btn btn-outline-secondary mb-4" onclick="location.href='cases.php'" >
      <i class="bi bi-arrow-left me-2"></i>Back to Cases
    </button>

    <!-- Header: Case Title and Actions -->
    <div class="d-flex justify-content-between align-items-start mb-4">
      <div>
        <h1 class="d-inline me-2">Assault Investigation</h1>
        <span class="badge bg-danger align-middle">High Priority</span>
        <p class="text-muted mt-2 mb-1">CASE-2025-084</p>
      </div>
     
    </div>

    <!-- 

    <!-- Case Overview (dynamic) -->
    <div class="container my-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h3 class="mb-1"><?php echo htmlspecialchars($case['title']); ?></h3>
          <p class="text-muted small mb-0">
            Case ID:
            <span class="badge bg-light text-dark"><?php echo htmlspecialchars($case['case_id']); ?></span>
          </p>
        </div>
        <button onclick="location.href='cases.php'" class="btn btn-outline-secondary btn-sm">Back to Cases</button>
      </div>

      <div class="row mb-4">
        <!-- Left: Case Info -->
        <div class="col-lg-8">
          <div class="card mb-4">
            <div class="card-header">
              Case Information
            </div>
            <div class="card-body">
              <h6>Description</h6>
              <p class="text-muted">
                <?php echo nl2br(htmlspecialchars($case['description'] ?? 'No description.')); ?>
              </p>
              <div class="row text-muted small">
                <div class="col-6 mb-3">
                  <div>Status</div>
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary">
                      <?php echo htmlspecialchars($case['status']); ?>
                    </span>
                  </div>
                </div>
                <div class="col-6 mb-3">
                  <div>Priority</div>
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-danger">
                      <?php echo htmlspecialchars($case['priority']); ?>
                    </span>
                  </div>
                </div>
                <div class="col-6 mb-3">
                  <div>Assigned Officer</div>
                  <div>
                    <?php if ($case['officer_name']): ?>
                      <?php echo htmlspecialchars($case['officer_name']); ?>
                      <span class="text-muted">(Badge: <?php echo htmlspecialchars($case['badge_number']); ?>)</span>
                    <?php else: ?>
                      <span class="text-muted">No officer assigned.</span>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="col-6 mb-3">
                  <div>Progress</div>
                  <div class="d-flex align-items-center gap-2">
                    <div class="progress flex-fill" style="height: 10px;">
                      <div class="progress-bar bg-warning" style="width: <?php echo (int)$case['progress_percent']; ?>%;"></div>
                    </div>
                    <span><?php echo (int)$case['progress_percent']; ?>%</span>
                  </div>
                </div>
                <div class="col-6 mb-3">
                  <div><i class="bi bi-calendar-event me-1"></i>Date Started</div>
                  <div><?php echo htmlspecialchars($case['date_started']); ?></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Right: Quick Summary -->
        <div class="col-lg-4">
          <div class="card mb-4">
            <div class="card-header">
              Summary
            </div>
            <div class="card-body">
              <p class="mb-2"><strong>Case Category:</strong> <span class="text-muted">Dynamic from DB (optional column)</span></p>
              <p class="mb-2"><strong>Number of Suspects:</strong> <span class="badge bg-secondary"><?php echo count($suspects); ?></span></p>
              <p class="mb-2"><strong>Evidence Count:</strong> <span class="badge bg-secondary"><?php echo count($evidence); ?></span></p>
              <p class="text-muted small mb-0">This summary area can be extended to show more metrics if needed.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Suspect List -->
      <div class="row mb-4">
        <div class="col-lg-6">
          <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
              <span>Suspects</span>
            </div>
            <div class="card-body">
              <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Charges</th>
                  </tr>
                </thead>
                <tbody>
                <?php if (!$suspects): ?>
                  <tr>
                    <td colspan="4" class="text-center text-muted small py-3">
                      No suspects linked to this case.
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($suspects as $s): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($s['suspect_id']); ?></td>
                      <td><?php echo htmlspecialchars($s['name']); ?></td>
                      <td><?php echo htmlspecialchars($s['status']); ?></td>
                      <td><?php echo htmlspecialchars($s['charges']); ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Evidence List -->
        <div class="col-lg-6">
          <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
              <span>Evidence</span>
            </div>
            <div class="card-body">
              <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Date Collected</th>
                    <th>Location</th>
                  </tr>
                </thead>
                <tbody>
                <?php if (!$evidence): ?>
                  <tr>
                    <td colspan="5" class="text-center text-muted small py-3">
                      No evidence recorded for this case.
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($evidence as $e): ?>
                    <tr>
                      <td><?php echo (int)$e['evidence_id']; ?></td>
                      <td><?php echo htmlspecialchars($e['evidence_type']); ?></td>
                      <td><?php echo htmlspecialchars($e['description']); ?></td>
                      <td><?php echo htmlspecialchars($e['date_collected']); ?></td>
                      <td><?php echo htmlspecialchars($e['location_found']); ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
</body>
</html>