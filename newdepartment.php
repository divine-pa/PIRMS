<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Collect form data
  $dept_name = $_POST['deptName'] ?? '';
  $dept_head = $_POST['deptHead'] ?? '';
  $location = $_POST['location'] ?? '';
  $description = $_POST['description'] ?? '';
  $phone = $_POST['phone'] ?? '';
  $email = $_POST['email'] ?? '';
  $status = $_POST['status'] ?? 'Active';

  // Validate required fields
  if (empty($dept_name) || empty($dept_head)) {
    die("Error: Department Name and Department Head are required.");
  }  // Check if department already exists
  $check_sql = "SELECT department_id FROM department WHERE department_name = ?";
  $check_stmt = $pdo->prepare($check_sql);
  $check_stmt->execute([$dept_name]);

  if ($check_stmt->rowCount() > 0) {
    die("Error: A department with this name already exists in the system.");
  }

  // Insert into database - use description if provided, otherwise use location
  $final_description = !empty($description) ? $description : $location;
  $sql = "INSERT INTO department (department_name, description) VALUES (?, ?)";
  $stmt = $pdo->prepare($sql);

  if ($stmt->execute([$dept_name, $final_description])) {
    error_log("Department inserted: Name=$dept_name, Head=$dept_head, Description=$final_description");
    header("Location: department.php?msg=added");
    exit;
  } else {
    die("Error saving department: " . implode(" ", $stmt->errorInfo()));
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New Department</title>
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
    <!-- Header -->
    <div class="mb-4">
      <button
        type="button"
        class="btn btn-outline-link p-0 mb-3"
        onclick="window.location.href='department.php'">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="16"
          height="16"
          fill="black"
          class="bi bi-arrow-left me-2"
          viewBox="0 0 16 16">
          <path
            fill-rule="evenodd"
            d="M15 8a.5.5 0 0 1-.5.5H2.707l4.147 4.146a.5.5 0 0 1-.708.708l-5-5a.5.5 0 0 1 0-.708l5-5a.5.5 0 1 1 .708.708L2.707 7.5H14.5A.5.5 0 0 1 15 8z" />
        </svg>
        Back to Departments
      </button>
      <h1>Add New Department</h1>
      <p class="text-muted">
        Create a new department in the PIRMS system
      </p>
    </div>

    <!-- Success Alert -->
    <div
      id="successAlert"
      class="alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3 d-none"
      role="alert"
      style="z-index: 9999; width: 90%; max-width: 500px; animation: slideDown 0.4s ease-out;">
      <div class="d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-circle me-2" viewBox="0 0 16 16">
          <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
          <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z" />
        </svg>
        <strong>Success!</strong> Department added successfully!
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

    <!-- Form -->
    <form id="departmentForm" method="POST" action="newdepartment.php" novalidate>
      <!-- Department Info Card -->
      <div class="card mb-4">
        <div class="card-header d-flex align-items-center gap-3">
          <div class="header-icon" aria-hidden="true">D</div>
          <div>
            <h5 class="card-title mb-0">Department Information</h5>
            <small class="text-muted">Enter details about the new department</small>
          </div>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label for="deptName" class="form-label">Department Name <span class="text-danger">*</span></label>
            <input
              type="text"
              id="deptName"
              name="deptName"
              class="form-control"
              placeholder="e.g., Cybercrime Unit, Homicide Division"
              required />
            <div class="invalid-feedback">Department Name is required.</div>
          </div>

          <div class="mb-3">
            <label for="deptHead" class="form-label">Department Head <span class="text-danger">*</span></label>
            <input
              type="text"
              id="deptHead"
              name="deptHead"
              class="form-control"
              placeholder="e.g., Capt. Sarah Johnson"
              required />
            <div class="invalid-feedback">Department Head is required.</div>
          </div>

          <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input
              type="text"
              id="location"
              name="location"
              class="form-control"
              placeholder="e.g., Building A, Floor 3" />
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea
              id="description"
              name="description"
              class="form-control"
              rows="3"
              placeholder="e.g., Handles cybercrime investigations including fraud, identity theft, and digital forensics"></textarea>
          </div>

          <div class="mb-3">
            <label for="phone" class="form-label">Contact Phone</label>
            <input
              type="tel"
              id="phone"
              name="phone"
              class="form-control"
              placeholder="e.g., +1 (555) 123-4567" />
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Contact Email</label>
            <input
              type="email"
              id="email"
              name="email"
              class="form-control"
              placeholder="e.g., cybercrime@pirms.gov" />
          </div>

          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select id="status" name="status" class="form-select" aria-label="Status">
              <option selected>Active</option>
              <option>Inactive</option>
              <option>Suspended</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Action buttons -->
      <div class="d-flex gap-3">
        <button type="submit" class="btn btn-primary flex-grow-1">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="16"
            height="16"
            fill="currentColor"
            class="bi bi-save2 me-2"
            viewBox="0 0 16 16">
            <path
              d="M6.5 1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 1 0v-1a.5.5 0 0 0-.5-.5zM1 2.5A1.5 1.5 0 0 1 2.5 1h5.793a1.5 1.5 0 0 1 1.06.44l3.207 3.207a1.5 1.5 0 0 1 .44 1.06v7.793A1.5 1.5 0 0 1 11.5 15h-9A1.5 1.5 0 0 1 1 13.5v-11z" />
            <path
              d="M4.5 5a.5.5 0 0 1 .5.5V9h1a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5v-4a.5.5 0 0 1 .5-.5z" />
          </svg>
          Add Department
        </button>
        <button
          type="button"
          class="btn btn-outline-secondary flex-grow-1"
          onclick="window.location.href='department.php'">
          Cancel
        </button>
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
    const form = document.getElementById('departmentForm');

    form.addEventListener('submit', (e) => {
      console.log('Form submission detected');

      // Get form values for client-side validation
      const deptName = document.getElementById('deptName').value.trim();
      const deptHead = document.getElementById('deptHead').value.trim();

      console.log('deptName:', deptName);
      console.log('deptHead:', deptHead);

      // Only validate required fields
      if (!deptName || !deptHead) {
        e.preventDefault();
        alert('Please fill in Department Name and Department Head');
        return;
      }

      console.log('Form validation passed, submitting to server');
      // Allow form to submit to server - don't prevent default
    });
  </script>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>