<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/includes/db.php';

$user = get_logged_in_user();
$caseId = $_GET['case_id'] ?? '';

if ($caseId === '') {
  header('Location: cases.php');
  exit;
}

// Handle progress update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_progress'])) {
  $new_progress = (int)$_POST['progress_percent'];
  $new_status = $_POST['status'];

  $updateStmt = $pdo->prepare("
        UPDATE `CASE` 
        SET progress_percent = ?, status = ?
        WHERE case_id = ?
    ");

  if ($updateStmt->execute([$new_progress, $new_status, $caseId])) {
    header("Location: case_details.php?case_id=" . urlencode($caseId) . "&msg=updated");
    exit;
  }
}

// Get case details
$caseStmt = $pdo->prepare("
    SELECT c.case_id, c.title, c.description, c.status, c.priority,
           c.date_started, c.progress_percent,
           o.name AS officer_name, o.badge_number, o.rank, o.specialization
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

// Check for success message
$showSuccess = isset($_GET['msg']) && $_GET['msg'] === 'updated';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Case Details - <?php echo htmlspecialchars($case['title']); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

  <!-- Header -->
  <section style="background-color: #081b34;">
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4">
      <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
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
        <a href="logout.php" class="btn btn-warning px-4 ms-3">Logout</a>
      </ul>
    </header>
  </section>

  <!-- Success Notification -->
  <?php if ($showSuccess): ?>
    <div id="successNotification" class="alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3" role="alert" style="z-index: 9999; width: 90%; max-width: 500px;">
      <strong>Success!</strong> Case updated successfully.
    </div>
    <script>
      setTimeout(() => {
        const notif = document.getElementById('successNotification');
        if (notif) notif.remove();
      }, 3000);
    </script>
  <?php endif; ?>

  <div class="container" style="max-width: 1024px;">
    <!-- Back Button -->
    <button class="btn btn-outline-secondary mb-4" onclick="location.href='cases.php'">
      <i class="bi bi-arrow-left me-2"></i>Back to Cases
    </button>

    <!-- Header: Case Title and Priority -->
    <div class="d-flex justify-content-between align-items-start mb-4">
      <div>
        <h1 class="d-inline me-2"><?php echo htmlspecialchars($case['title']); ?></h1>
        <?php
        $priorityClass = 'bg-secondary';
        if ($case['priority'] === 'High') $priorityClass = 'bg-danger';
        elseif ($case['priority'] === 'Medium') $priorityClass = 'bg-warning text-dark';
        ?>
        <span class="badge <?php echo $priorityClass; ?> align-middle"><?php echo htmlspecialchars($case['priority']); ?> Priority</span>
        <p class="text-muted mt-2 mb-1"><?php echo htmlspecialchars($case['case_id']); ?></p>
      </div>
    </div>

    <!-- Case Overview -->
    <div class="row mb-4">
      <!-- Left: Case Info -->
      <div class="col-lg-8">
        <div class="card mb-4">
          <div class="card-header">Case Information</div>
          <div class="card-body">
            <h6>Description</h6>
            <p class="text-muted"><?php echo nl2br(htmlspecialchars($case['description'] ?? 'No description.')); ?></p>

            <div class="row text-muted small">
              <div class="col-6 mb-3">
                <div>Status</div>
                <div class="d-flex align-items-center gap-2">
                  <?php
                  $statusColor = 'bg-secondary';
                  if ($case['status'] === 'Ongoing') $statusColor = 'bg-primary';
                  elseif ($case['status'] === 'Closed') $statusColor = 'bg-success';
                  ?>
                  <span class="badge <?php echo $statusColor; ?>"><?php echo htmlspecialchars($case['status']); ?></span>
                </div>
              </div>
              <div class="col-6 mb-3">
                <div>Progress</div>
                <div class="d-flex align-items-center gap-2">
                  <div class="progress flex-fill" style="height: 10px;">
                    <div class="progress-bar bg-primary" style="width: <?php echo (int)$case['progress_percent']; ?>%;"></div>
                  </div>
                  <span><?php echo (int)$case['progress_percent']; ?>%</span>
                </div>
              </div>
              <div class="col-6 mb-3">
                <div><i class="bi bi-calendar-event me-1"></i>Date Started</div>
                <div><?php echo htmlspecialchars($case['date_started']); ?></div>
              </div>
            </div>

            <!-- Update Progress Form -->
            <hr>
            <h6 class="mb-3">Update Case Progress</h6>
            <form method="POST" action="">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Status</label>
                  <select name="status" class="form-select" required>
                    <option value="Ongoing" <?php echo $case['status'] === 'Ongoing' ? 'selected' : ''; ?>>Ongoing</option>
                    <option value="Under Review" <?php echo $case['status'] === 'Under Review' ? 'selected' : ''; ?>>Under Review</option>
                    <option value="Closed" <?php echo $case['status'] === 'Closed' ? 'selected' : ''; ?>>Closed</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Progress (%)</label>
                  <input type="number" name="progress_percent" class="form-control" min="0" max="100" value="<?php echo (int)$case['progress_percent']; ?>" required>
                </div>
              </div>
              <button type="submit" name="update_progress" class="btn btn-primary mt-3">Update Progress</button>
            </form>
          </div>
        </div>
      </div>

      <!-- Right: Assigned Officer -->
      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-header">Assigned Officer</div>
          <div class="card-body d-flex gap-3 align-items-start">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-weight: bold;">
              <?php
              if ($case['officer_name']) {
                $parts = explode(' ', $case['officer_name']);
                echo strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
              } else {
                echo 'N/A';
              }
              ?>
            </div>
            <div class="flex-grow-1">
              <h6 class="mb-1"><?php echo htmlspecialchars($case['officer_name'] ?? 'Unassigned'); ?></h6>
              <?php if ($case['officer_name']): ?>
                <div class="small text-muted"><?php echo htmlspecialchars($case['badge_number']); ?></div>
                <div class="small text-muted"><?php echo htmlspecialchars($case['rank'] ?? ''); ?></div>
                <div class="small text-muted"><?php echo htmlspecialchars($case['specialization'] ?? ''); ?></div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Suspects & Evidence Section -->
    <div class="row mb-4">
      <div class="col-lg-6">
        <div class="card h-100">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Linked Suspects</span>
          </div>
          <div class="card-body">
            <?php if (empty($suspects)): ?>
              <p class="text-muted">No suspects linked to this case.</p>
            <?php else: ?>
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Charges</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($suspects as $s): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($s['suspect_id']); ?></td>
                      <td><?php echo htmlspecialchars($s['name']); ?></td>
                      <td><span class="badge bg-secondary"><?php echo htmlspecialchars($s['status']); ?></span></td>
                      <td><?php echo htmlspecialchars($s['charges']); ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card h-100">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Evidence</span>
          </div>
          <div class="card-body">
            <?php if (empty($evidence)): ?>
              <p class="text-muted">No evidence recorded for this case.</p>
            <?php else: ?>
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Date</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($evidence as $e): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($e['evidence_id']); ?></td>
                      <td><?php echo htmlspecialchars($e['evidence_type']); ?></td>
                      <td><?php echo htmlspecialchars($e['date_collected']); ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>