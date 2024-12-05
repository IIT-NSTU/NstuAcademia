<?php
include 'config.php'; // Include session and database connection functions
checkSession(); // Ensure the user is logged in

$conn = getDatabaseConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - NSTU Academia</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Running text animation */
        .running-text {
            animation: runningText 15s linear infinite;
            white-space: nowrap;
        }

        @keyframes runningText {
            0% {
                transform: translateX(100%);
            }
            100% {
                transform: translateX(-100%);
            }
        }

        /* Navigation styles */
        .nav-bar-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: nowrap;
        }

        .nav-link {
            padding: 0.5rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease-in-out;
            white-space: nowrap;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 0.5rem;
        }

        /* Logout button */
        .logout-button {
            background-color: #ffcccc; /* Light red */
            color: #b30000; /* Dark red for text */
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease-in-out;
            font-weight: bold;
        }

        .logout-button:hover {
            background-color: #ff9999; /* Slightly darker red */
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="container mx-auto py-4 px-6 flex justify-between items-center">
            <!-- Logo and title -->
            <div class="flex items-center space-x-4">
                <img src="noakhali_science_and_technology_university_logo.jpeg" alt="University Logo" class="w-16 h-auto">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">NstuAcademia</h1>
                    <p class="text-sm text-gray-600 running-text">
                        A Hassle-Free System for University Students
                    </p>
                </div>
            </div>
            <!-- Contact information -->
            <div class="text-gray-600 text-right">
                <p class="font-semibold">📞 Phone: 02334496522</p>
                <p class="font-semibold">📠 Fax: 02334496523</p>
                <p class="font-semibold">✉️ Email: registrar@office.nstu.edu.bd</p>
            </div>
        </div>
    </header>

    <!-- Navigation Bar -->
    <nav class="bg-blue-900">
        <div class="container mx-auto nav-bar-container py-3 px-6">
            <!-- Navigation links -->
            <ul class="flex items-center space-x-4 text-white">
                <li><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                <li><a href="profile.php" class="nav-link">Profile</a></li>
                <li><a href="course_registration.php" class="nav-link">Course Registration Notice</a></li>
                <li><a href="HallSeatNotice.php" class="nav-link">Hall Seat Notice</a></li>
                <li><a href="departments.php" class="nav-link">Departments</a></li>
                <li><a href="Events.php" class="nav-link">Events</a></li>
                <li><a href="Contact.php" class="nav-link">Contact</a></li>
            </ul>
            <!-- Logout button -->
            <a href="logout.php" class="logout-button">Logout</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto mt-10 px-6">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h1 class="text-4xl font-bold mb-4">Welcome, <?= htmlspecialchars($_SESSION['name']) ?>!</h1>
            <p class="text-gray-700">Here you can access your dashboard and all university-related updates.</p>
        </div>
        <section class="mt-10">
            <h2 class="text-2xl font-bold mb-4">Announcements</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php
                $sql = "SELECT title, description FROM announcements";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='bg-white p-4 rounded-lg shadow-lg'>";
                        echo "<h3 class='text-xl font-bold mb-2'>" . htmlspecialchars($row['title']) . "</h3>";
                        echo "<p class='text-gray-700'>" . htmlspecialchars($row['description']) . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p class='text-gray-600'>No announcements available.</p>";
                }
                ?>
            </div>
        </section>
    </main>
</body>
</html>
