<?php
include 'db.php';
session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$schemeId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT * FROM schemes WHERE id = ? AND assigned_engineer_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $schemeId, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$scheme = $result->fetch_assoc();

if (!$scheme) {
    die("Scheme not found or access denied.");
}

// After fetching scheme details, add this query to fetch tasks
$tasksQuery = "SELECT * FROM tasks WHERE scheme_id = ? AND engineer_id = ? AND status = 'ongoing'";
$taskStmt = $conn->prepare($tasksQuery);
$taskStmt->bind_param("ii", $schemeId, $_SESSION['user_id']);
$taskStmt->execute();
$tasks = $taskStmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Scheme Details</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
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
        </nav>
    </div>

    <div class="main-content">
        <header>
            
                <div class="toggle">
                    <i class="fas fa-bars menu-icon"></i>
                </div>
                <marquee>
                    <h1>SCHEME : <?php echo htmlspecialchars($scheme['title']); ?></h1>
                </marquee>
            
        </header>

        <div class="tasks-container">
            <div class="tasks-header">
                <h2>Scheme Tasks</h2>
                <button class="btn btn-primary" id="addTaskBtn" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                    <i class="fas fa-plus"></i> Add Task
                </button>
            </div>

            <div class="schemes-tabs">
                <button class="tab-btn active" data-tab="ongoing">Ongoing</button>
                <button class="tab-btn" data-tab="completed">Completed</button>
            </div>

            <div class="tab-content active" id="ongoing">
                <?php if ($tasks->num_rows > 0): ?>
                    <table class="table" id="tasksTable">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($task = $tasks->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($task['description']); ?></td>
                                    <td>
                                        <button class="btn btn-primary request-resources" data-task-id="<?php echo $task['id']; ?>">
                                            Request Resources
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No ongoing tasks found.</p>
                <?php endif; ?>
            </div>

            <div class="tab-content" id="completed">
                <p>Completed tasks for this scheme will be displayed here.</p>
            </div>
        </div>
    </div>

    <!-- Add Task Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTaskModalLabel">Add Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addTaskForm">
                        <div class="mb-3">
                            <label for="taskDescription" class="form-label">Task Description</label>
                            <textarea class="form-control" id="taskDescription" rows="3" placeholder="Enter task description" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Task</button>
                    </form>
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

        const tabs = document.querySelectorAll(".tab-btn");
        const tabContents = document.querySelectorAll(".tab-content");

        tabs.forEach((tab) => {
            tab.addEventListener("click", () => {
                // Remove active class from all tabs and contents
                tabs.forEach((t) => t.classList.remove("active"));
                tabContents.forEach((content) => content.classList.remove("active"));

                // Add active class to the clicked tab and corresponding content
                tab.classList.add("active");
                const target = tab.getAttribute("data-tab");
                document.getElementById(target).classList.add("active");
            });
        });

        // Handle Add Task form submission
        document.getElementById("addTaskForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const taskDescription = document.getElementById("taskDescription").value;

            if (taskDescription.trim() === "") {
                alert("Task description cannot be empty.");
                return;
            }

            $.ajax({
                url: 'backend.php',
                type: 'POST',
                data: {
                    action: 'addTask',
                    description: taskDescription,
                    schemeId: <?php echo $schemeId; ?>,
                    engineerId: <?php echo $_SESSION['user_id']; ?>
                },
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        alert('Task added successfully');
                        document.getElementById("addTaskForm").reset();
                        const addTaskModal = bootstrap.Modal.getInstance(document.getElementById("addTaskModal"));
                        addTaskModal.hide();
                        // Optionally reload the tasks list here
                    } else {
                        alert('Error: ' + result.message);
                    }
                },
                error: function() {
                    alert('Error occurred while adding task');
                }
            });
        });

        // Initialize DataTable
        $(document).ready(function() {
            $('#tasksTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
            });
        });
    </script>
</body>

</html>