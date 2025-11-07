<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Registration System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <style>
    /* ===== GENERAL PAGE STYLE ===== */
    body {
      background: linear-gradient(135deg, #4A00E0, #8E2DE2);
      font-family: 'Poppins', sans-serif;
      color: #333;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: fadeInPage 1.5s ease;
    }

    /* ===== CARD ===== */
    .card {
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
      padding: 30px;
      width: 100%;
      max-width: 900px;
      animation: slideIn 0.8s ease forwards;
      transform-style: preserve-3d;
      transition: transform 0.5s ease;
    }

    .card:hover {
      transform: rotateY(3deg) rotateX(3deg);
    }

    /* ===== NAV TABS ===== */
    .nav-tabs .nav-link.active {
      background-color: #8E2DE2;
      color: #fff;
      border: none;
    }
    .nav-tabs .nav-link {
      color: #8E2DE2;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    .nav-tabs .nav-link:hover {
      background-color: #E0C3FC;
      color: #4A00E0;
    }

    /* ===== BUTTONS ===== */
    .btn-success {
      background-color: #8E2DE2;
      border: none;
      transition: all 0.3s ease;
    }
    .btn-success:hover {
      background-color: #4A00E0;
      transform: scale(1.05);
    }
    .btn-warning:hover, .btn-secondary:hover {
      transform: scale(1.05);
    }

    /* ===== FORM FIELDS ===== */
    .form-control, .form-select {
      border-radius: 10px;
      transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
      box-shadow: 0 0 10px rgba(142, 45, 226, 0.5);
      border-color: #8E2DE2;
    }

    input:hover, select:hover, textarea:hover {
      box-shadow: 0 0 12px rgba(142, 45, 226, 0.3);
    }

    .form-label {
      transition: color 0.3s ease;
    }

    /* ===== MODALS ===== */
    .modal-content {
      border-radius: 15px;
      animation: popIn 0.6s ease;
    }
    .modal-header.bg-success {
      background: linear-gradient(90deg, #00C9A7, #92FE9D);
    }
    .modal-header.bg-danger {
      background: linear-gradient(90deg, #FF416C, #FF4B2B);
    }

    /* ===== SECTION TITLES ===== */
    h5 {
      color: #4A00E0;
      font-weight: 600;
      border-left: 5px solid #8E2DE2;
      padding-left: 10px;
      margin-top: 20px;
    }

    /* ===== ANIMATIONS ===== */
    @keyframes fadeInPage {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideIn {
      from { transform: translateY(-20px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    @keyframes popIn {
      from { transform: scale(0.8); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }

    /* ===== BUTTON RIPPLE EFFECT ===== */
    .btn {
      position: relative;
      overflow: hidden;
    }
    .btn::after {
      content: "";
      position: absolute;
      background: rgba(255, 255, 255, 0.4);
      border-radius: 50%;
      width: 100px;
      height: 100px;
      top: 50%;
      left: 50%;
      transform: scale(0) translate(-50%, -50%);
      transform-origin: center;
      transition: transform 0.5s ease;
    }
    .btn:active::after {
      transform: scale(2.5) translate(-50%, -50%);
    }

  </style>
</head>
<body>
  <div class="container">
    <div class="card">
      <h2 class="text-center mb-4">User Registration System</h2>

      <!-- Tabs Navigation -->
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab" aria-controls="register" aria-selected="true">Register</button>
        </li>
        <li class="nav-item" role="presentation">
          <a href="view_users.php" class="nav-link" id="view-tab" type="button" role="tab" aria-controls="view" aria-selected="false">View Users</a>
        </li>
      </ul>

      <!-- Tab Content -->
      <div class="tab-content p-4" id="myTabContent">
        <div class="tab-pane fade show active" id="register" role="tabpanel" aria-labelledby="register-tab">
          <form id="registrationForm" novalidate>

            <h5 class="mb-3">Personal Information</h5>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="firstName" class="form-label">First Name *</label>
                <input type="text" class="form-control" id="firstName" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="lastName" class="form-label">Last Name *</label>
                <input type="text" class="form-control" id="lastName" required>
              </div>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email *</label>
              <input type="email" class="form-control" id="email" required>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password *</label>
              <input type="password" class="form-control" id="password" required>
            </div>

            <div class="mb-3">
              <label for="confirmPassword" class="form-label">Confirm Password *</label>
              <input type="password" class="form-control" id="confirmPassword" required>
            </div>

            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="birthDate" class="form-label">Birth Date *</label>
                <input type="date" class="form-control" id="birthDate" required>
              </div>
              <div class="col-md-4 mb-3">
                <label for="birthTime" class="form-label">Birth Time *</label>
                <input type="time" class="form-control" id="birthTime" required>
              </div>
              <div class="col-md-4 mb-3">
                <label for="birthMonth" class="form-label">Birth Month *</label>
                <select class="form-select" id="birthMonth" required>
                  <option value="" disabled selected>Select Month</option>
                  <option>January</option><option>February</option><option>March</option>
                  <option>April</option><option>May</option><option>June</option>
                  <option>July</option><option>August</option><option>September</option>
                  <option>October</option><option>November</option><option>December</option>
                </select>
              </div>
            </div>

            <div class="mb-3">
              <label for="bio" class="form-label">Bio *</label>
              <textarea class="form-control" id="bio" rows="3" required></textarea>
            </div>

            <h5 class="mt-4 mb-3">Address Information</h5>
            <div class="mb-3">
              <label for="address" class="form-label">Address *</label>
              <textarea class="form-control" id="address" rows="2" required></textarea>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="city" class="form-label">City *</label>
                <input type="text" class="form-control" id="city" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="state" class="form-label">State/Province *</label>
                <input type="text" class="form-control" id="state" required>
              </div>
            </div>

            <div class="mb-3">
              <label for="country" class="form-label">Country *</label>
              <select class="form-select" id="country" required>
                <option value="" disabled selected>Select Country</option>
                <option>United States</option>
                <option>United Kingdom</option>
                <option>Canada</option>
                <option>Australia</option>
                <option>India</option>
                <option>Other</option>
              </select>
            </div>

            <!-- Newsletter -->
            <div class="mb-4 form-check">
              <input type="checkbox" class="form-check-input" id="newsletter" value="1">
              <label class="form-check-label" for="newsletter">Subscribe to our newsletter</label>
            </div>

            <!-- Form Buttons -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
              <button type="button" class="btn btn-secondary me-md-2" id="cancelBtn">Cancel</button>
              <button type="reset" class="btn btn-warning me-md-2">Reset Form</button>
              <button type="submit" class="btn btn-success" id="registerBtn">Register</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Success Modal -->
  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="successModalLabel">Success!</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          Registration successful! Thank you for registering.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <a href="view_users.php" class="btn btn-primary">View Users</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Error Modal -->
  <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="errorModalLabel">Error!</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="errorMessage">Please fill all required fields properly.</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const form = document.getElementById("registrationForm");

      form.addEventListener("submit", function(event) {
        event.preventDefault();

        if (!form.checkValidity()) {
          const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
          errorModal.show();
          return;
        }

        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
        form.reset();
      });

      document.getElementById("cancelBtn").addEventListener("click", () => {
        if (confirm("Are you sure you want to cancel registration?")) {
          form.reset();
        }
      });
    });
  </script>
</body>
</html>
