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
        <h1 class="d-inline me-2">Vehicle Theft Ring</h1>
        <span class="badge bg-danger align-middle">High Priority</span>
        <p class="text-muted mt-2 mb-1">CASE-2025-082</p>
      </div>
     
    </div>

    <!-- Case Overview -->
    <div class="row mb-4">
      <!-- Left: Case Info -->
      <div class="col-lg-8">
        <div class="card mb-4">
          <div class="card-header">
            Case Information
          </div>
          <div class="card-body">
            <h6>Description</h6>
            <p class="text-muted"> A vehicle theft ring is like a shadowy crew that strikes quickly and skillfully under the cover of night, stealing cars they specifically target to make a fast profit. Imagine thieves who not only break into cars but also rewrite their histories, changing VINs and using shady online markets to sell the stolen vehicles far away, leaving victims bewildered and chasing shadows</p>
            <div class="row text-muted small">
              <div class="col-6 mb-3">
                <div>Status</div>
                <div class="d-flex align-items-center gap-2">
                  <span class="rounded-circle bg-success d-inline-block" style="width: 10px; height: 10px;"></span>
                  <span>completed</span>
                </div>
              </div>
              <div class="col-6 mb-3">
                <div>Progress</div>
                <div class="d-flex align-items-center gap-2">
                  <div class="progress flex-fill" style="height: 10px;">
                    <div class="progress-bar bg-success" style="width: 100%;"></div>
                  </div>
                  <span>100%</span>
                </div>
              </div>
              <div class="col-6 mb-3">
                <div><i class="bi bi-calendar-event me-1"></i>Date Started</div>
                <div>2025-10-18</div>
              </div>
              <div class="col-6 mb-3">
                <div><i class="bi bi-calendar-event me-1"></i>Last Updated</div>
                <div>2025-11-14</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Right: Assigned Officer -->
      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-header">
            Assigned Officer
          </div>
          <div class="card-body d-flex gap-3 align-items-start">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-weight: bold;">
              SJ
            </div>
            <div class="flex-grow-1">
              <h6 class="mb-1">Det. Michael Chen</h6>
              <div class="small text-muted"> DET 4522</div>
              <div class="small text-muted">Criminal Investigations</div>
            </div>
          </div>
        </div>
      </div>
    </div>
<!-- Tabs -->
<ul class="nav nav-tabs mb-3" id="caseTabs" role="tablist">
  <li class="nav-item" role="presentation">
    <button
      class="nav-link active"
      id="evidence-tab"
      data-bs-toggle="tab"
      data-bs-target="#evidence"
      type="button"
      role="tab"
      aria-controls="evidence"
      aria-selected="true"
    >
      Evidence
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button
      class="nav-link"
      id="suspects-tab"
      data-bs-toggle="tab"
      data-bs-target="#suspects"
      type="button"
      role="tab"
      aria-controls="suspects"
      aria-selected="false"
    >
      Suspects
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button
      class="nav-link"
      id="cases-tab"
      data-bs-toggle="tab"
      data-bs-target="#cases"
      type="button"
      role="tab"
      aria-controls="cases"
      aria-selected="false"
    >
      Case Log
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button
      class="nav-link"
      id="timeline-tab"
      data-bs-toggle="tab"
      data-bs-target="#timeline"
      type="button"
      role="tab"
      aria-controls="timeline"
      aria-selected="false"
    >
      Timeline
    </button>
  </li>
  

</ul>

<div class="tab-content" id="caseTabContent">

  <!-- Evidence -->
  <div class="tab-pane fade show active" id="evidence" role="tabpanel" aria-labelledby="evidence-tab">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>Evidence Log</span>
        <button onclick="location.href='newevidence.php'" class="btn btn-primary btn-sm">
          <i class="bi bi-plus me-2"></i>Add Evidence
        </button>
      </div>
      <div class="card-body p-0">
        <table class="table mb-0">
          <thead>
            <tr>
              <th>Evidence ID</th>
              <th>Type</th>
              <th>Location</th>
              <th>Collected By</th>
              <th>Date</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>EVI-001</td>
              <td>Security camera footage</td>
              <td>Evidence Room A</td>
              <td>Det. Johnson</td>
              <td>2025-10-15</td>
              <td><button class="btn btn-outline-secondary btn-sm" title="View"><i class="bi bi-file-text"></i></button></td>
            </tr>
            <tr>
              <td>EVI-002</td>
              <td>Fingerprint samples</td>
              <td>Forensics Lab</td>
              <td>Det. Johnson</td>
              <td>2025-10-16</td>
              <td><button class="btn btn-outline-secondary btn-sm" title="View"><i class="bi bi-file-text"></i></button></td>
            </tr>
            <tr>
              <td>EVI-003</td>
              <td>Witness statements</td>
              <td>Digital Archive</td>
              <td>Det. Johnson</td>
              <td>2025-10-17</td>
              <td><button class="btn btn-outline-secondary btn-sm" title="View"><i class="bi bi-file-text"></i></button></td>
            </tr>
            <tr>
              <td>EVI-004</td>
              <td>Stolen goods inventory</td>
              <td>Evidence Room B</td>
              <td>Det. Martinez</td>
              <td>2025-10-18</td>
              <td><button class="btn btn-outline-secondary btn-sm" title="View"><i class="bi bi-file-text"></i></button></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Suspects -->
  <div class="tab-pane fade" id="suspects" role="tabpanel" aria-labelledby="suspects-tab">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>Linked Suspects</span>
        <button onclick="location.href='newsuspect.php'" class="btn btn-primary btn-sm">
          <i class="bi bi-plus me-2"></i>Add Suspect
        </button>
      </div>
      <div class="card-body">
        <div class="list-group">
          <div class="list-group-item d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
              <div class="bg-secondary text-white rounded-circle d-flex justify-content-center align-items-center" style="width:40px; height:40px; font-weight:bold;">
                JA
              </div>
              <div>
                <h6 class="mb-1">John Anderson</h6>
                <small class="text-muted">SUS-001</small>
                <div class="mt-2">
                  <span class="badge bg-secondary me-1">Burglary</span>
                  <span class="badge bg-secondary me-1">Breaking and Entering</span>
                </div>
              </div>
            </div>
            <span class="badge bg-secondary">Under Investigation</span>
          </div>
          <div class="list-group-item d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
              <div class="bg-secondary text-white rounded-circle d-flex justify-content-center align-items-center" style="width:40px; height:40px; font-weight:bold;">
                MW
              </div>
              <div>
                <h6 class="mb-1">Marcus Williams</h6>
                <small class="text-muted">SUS-007</small>
                <div class="mt-2">
                  <span class="badge bg-secondary me-1">Burglary</span>
                  <span class="badge bg-secondary me-1">Conspiracy</span>
                </div>
              </div>
            </div>
            <span class="badge bg-secondary">Under Investigation</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Case Log -->
  <div class="tab-pane fade" id="cases" role="tabpanel" aria-labelledby="cases-tab">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>Case Log</span>
        <button onclick="location.href='newcaselogentry.php'" class="btn btn-primary btn-sm">
          <i class="bi bi-plus me-2"></i>Add Log Entry
        </button>
      </div>
      <div class="card-body p-0">
        <table class="table mb-0">
          <thead>
            <tr>
              <th>Log ID</th>
              <th>Description</th>
              <th>User</th>
              <th>Date</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>LOG-001</td>
              <td>Initial investigation started at the crime scene</td>
              <td>Det. Johnson</td>
              <td>2025-10-15</td>
              <td><button class="btn btn-outline-secondary btn-sm" title="View"><i class="bi bi-file-text"></i></button></td>
            </tr>
            <tr>
              <td>LOG-002</td>
              <td>Suspect identification completed and filed</td>
              <td>Det. Martinez</td>
              <td>2025-10-20</td>
              <td><button class="btn btn-outline-secondary btn-sm" title="View"><i class="bi bi-file-text"></i></button></td>
            </tr>
            <tr>
              <td>LOG-003</td>
              <td>Additional witness interview scheduled</td>
              <td>Det. Johnson</td>
              <td>2025-10-22</td>
              <td><button class="btn btn-outline-secondary btn-sm" title="View"><i class="bi bi-file-text"></i></button></td>
            </tr>
            <tr>
              <td>LOG-004</td>
              <td>Forensics lab results received</td>
              <td>Forensics Team</td>
              <td>2025-11-01</td>
              <td><button class="btn btn-outline-secondary btn-sm" title="View"><i class="bi bi-file-text"></i></button></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Timeline -->
  <div class="tab-pane fade" id="timeline" role="tabpanel" aria-labelledby="timeline-tab">
    <div class="card mb-4">
      <div class="card-header">Case Timeline</div>
      <div class="card-body">
        <ul class="list-unstyled">
          <li class="d-flex mb-4">
            <div style="width: 10px; height: 10px; background-color: #0d6efd; border-radius: 50%; margin-top: 7px;"></div>
            <div class="ms-3">
              <div>Case opened</div>
              <small class="text-muted">2025-10-15 • Det. Sarah Johnson</small>
            </div>
          </li>
          <li class="d-flex mb-4">
            <div style="width: 10px; height: 10px; background-color: #0d6efd; border-radius: 50%; margin-top: 7px;"></div>
            <div class="ms-3">
              <div>Evidence collected from crime scene</div>
              <small class="text-muted">2025-10-16 • Det. Sarah Johnson</small>
            </div>
          </li>
          <li class="d-flex mb-4">
            <div style="width: 10px; height: 10px; background-color: #0d6efd; border-radius: 50%; margin-top: 7px;"></div>
            <div class="ms-3">
              <div>Witness interviews conducted</div>
              <small class="text-muted">2025-10-17 • Det. Sarah Johnson</small>
            </div>
          </li>
          <li class="d-flex mb-4">
            <div style="width: 10px; height: 10px; background-color: #0d6efd; border-radius: 50%; margin-top: 7px;"></div>
            <div class="ms-3">
              <div>Suspect identified</div>
              <small class="text-muted">2025-10-20 • Det. Sarah Johnson</small>
            </div>
          </li>
          <li class="d-flex mb-4">
            <div style="width: 10px; height: 10px; background-color: #0d6efd; border-radius: 50%; margin-top: 7px;"></div>
            <div class="ms-3">
              <div>Additional evidence submitted</div>
              <small class="text-muted">2025-11-01 • Det. Martinez</small>
            </div>
          </li>
          <li class="d-flex">
            <div style="width: 10px; height: 10px; background-color: #0d6efd; border-radius: 50%; margin-top: 7px;"></div>
            <div class="ms-3">
              <div>Case status updated to 65% complete</div>
              <small class="text-muted">2025-11-14 • Det. Sarah Johnson</small>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>

  
</div>
 









  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>