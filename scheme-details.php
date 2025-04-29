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
                <button class="btn btn-primary" id="addTaskBtn">
                    <i class="fas fa-plus"></i> Add Task
                </button>
            </div>

            <div class="schemes-tabs">
                <button class="tab-btn active" data-tab="ongoing">Ongoing</button>
                <button class="tab-btn" data-tab="completed">Completed</button>
            </div>

            <div class="tab-content active" id="ongoing">
                <p>Ongoing tasks for this scheme will be displayed here.</p>
            </div>

            <div class="tab-content" id="completed">
                <p>Completed tasks for this scheme will be displayed here.</p>
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
    </script>
</body>

</html>