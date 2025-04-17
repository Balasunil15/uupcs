<?php
session_start();
if (empty($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>UUPCS Dashboard</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <!-- Added Bootstrap 5.3.2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Added jQuery and Bootstrap Bundle JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
  <div class="sidebar">
    <div class="logo">
      <i class="fas fa-city"></i>
      <span class="logo-name">UUPCS</span>
    </div>
    <nav>
      <ul>
        <li>
          <a href="engineer-dashboard.php">
            <i class="fas fa-chart-bar"></i>
            <span class="nav-item">Dashboard</span>
          </a>
        </li>
        <li>
          <a href="engineer-profile.php">
            <i class="fas fa-user-circle"></i>
            <span class="nav-item">Profile</span>
          </a>
        </li>
        <li>
          <a href="engineer-schemes.php" class="active">
            <i class="fas fa-file-contract"></i>
            <span class="nav-item">My Schemes</span>
          </a>
        </li>
      </ul>
      <div class="sidebar-footer">
        <a href="login.html" class="back-btn">
          <i class="fas fa-sign-out-alt"></i>
          <span class="nav-item">Logout</span>
        </a>
      </div>
    </nav>
  </div>

  <div class="main-content">
    <header>
      <div class="header-content">
        <div class="toggle">
          <i class="fas fa-bars menu-icon"></i>
        </div>
        <h1>My Schemes</h1>
      </div>
      <div class="user-wrapper">
        <!-- Changed notification element to a Bootstrap trigger button -->
        <button type="button" class="btn notification-btn" data-bs-toggle="modal" data-bs-target="#notificationModal">
          <i class="fas fa-bell"></i>
          <span class="notification-badge">3</span>
        </button>
        <div class="dropdown">
          <button class="btn btn-secondary dropdown-toggle rounded-circle" type="button" id="userDropdown"
            data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="#" id="changePassword">Change Password</a></li>
            <li><a class="dropdown-item" href="#" id="logoutBtn">Logout</a></li>
          </ul>
        </div>
      </div>
    </header>

    <div class="tasks-container">
      <div class="tasks-header">
        <h2>Assigned Schemes</h2>
        <button class="add-task-btn" id="addTaskBtn">
          <i class="fas fa-plus"></i> Add Task
        </button>
      </div>

      <div class="schemes-tabs">
        <button class="tab-btn active" data-tab="tasks">Tasks</button>
        <button class="tab-btn" data-tab="resources">Resources</button>
      </div>

      <div class="tab-content active" id="tasks">
        <div class="schemes-tabs">
          <button class="tab-btn task-tab active" data-tab="ongoing-tasks">
            Ongoing
          </button>
          <button class="tab-btn task-tab" data-tab="completed-tasks">
            Completed
          </button>
        </div>

        <div class="tab-content task-content active" id="ongoing-tasks">
          <div class="schemes-table">
            <table>
              <thead>
                <tr>
                  <th>TID</th>
                  <th>Description</th>
                  <th>Deadline</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr onclick="openModal(this)" style="cursor: pointer">
                  <td>T001</td>
                  <td>Initial Survey</td>
                  <td>2024-03-01</td>
                  <td><span class="task-status pending">Pending</span></td>
                </tr>
                <tr onclick="openModal(this)" style="cursor: pointer">
                  <td>T002</td>
                  <td>Site Preparation</td>
                  <td>2024-03-15</td>
                  <td>
                    <span class="task-status in-progress">In Progress</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="tab-content task-content" id="completed-tasks">
          <div class="schemes-table">
            <table>
              <thead>
                <tr>
                  <th>TID</th>
                  <th>Description</th>
                  <th>Deadline</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr onclick="openModal(this)" style="cursor: pointer">
                  <td>T003</td>
                  <td>Site Preparation</td>
                  <td>2024-02-15</td>
                  <td>
                    <span class="task-status completed">Completed</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="tab-content" id="resources">
        <div class="schemes-table">
          <table>
            <thead>
              <tr>
                <th>RID</th>
                <th>Resource Name</th>
                <th>Quantity</th>
                <th>Image</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>R001</td>
                <td>Cement Bags</td>
                <td>500</td>
                <td>
                  <img src="assets/cement.jpg" alt="Cement" class="resource-image" />
                </td>
              </tr>
              <tr>
                <td>R002</td>
                <td>Steel Rods</td>
                <td>1000</td>
                <td>
                  <img src="assets/steel.jpg" alt="Steel" class="resource-image" />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Task Modal -->
  <div id="taskModal" class="modal">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2>Add New Task</h2>
      <form class="scheme-form">
        <div class="form-group">
          <label for="task-description">Task Description</label>
          <input type="text" id="task-description" placeholder="Enter task description" required />
        </div>

        <div class="form-group">
          <label for="task-deadline">Deadline</label>
          <input type="date" id="task-deadline" required />
        </div>

        <button type="submit" class="announce-btn">
          <i class="fas fa-plus"></i> Add Task
        </button>
      </form>
    </div>
  </div>

  <!-- Task Details Modal -->
  <div id="taskDetailModal" class="modal">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2 id="modalTitle"></h2>
      <div class="modal-info">
        <p><strong>Status:</strong> <span id="modalStatus"></span></p>
        <p><strong>Deadline:</strong> <span id="modalDeadline"></span></p>
        <div class="modal-description">
          <h3>Description</h3>
          <p id="modalDescription"></p>
        </div>
      </div>
    </div>
  </div>

  <script>
    const toggle = document.querySelector(".toggle");
    const sidebar = document.querySelector(".sidebar");

    toggle.addEventListener("click", () => {
      sidebar.classList.toggle("close");
    });

    function handleSubmit(event) {
      event.preventDefault();
      document.querySelector(".loading-overlay").style.display = "flex";

      // Simulate form submission
      setTimeout(() => {
        document.querySelector(".loading-overlay").style.display = "none";
        // Add success message or redirect
      }, 1500);
    }

    // Logout script
    $('#logoutBtn').click(function (e) {
      e.preventDefault();

      $.ajax({
        url: 'backend.php',
        type: 'POST',
        data: { action: "logout" },
        dataType: 'json',
        success: function (response) {
          if (response.redirect) {
            window.location.href = response.redirect;
          } else {
            alert("Logout failed. Please try again.");
          }
        },
        error: function () {
          alert("An error occurred. Please try again.");
        }
      });
    });
  </script>
</body>

</html>