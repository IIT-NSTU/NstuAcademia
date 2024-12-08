<?php
include 'session_check.php'; // Ensure the user is logged in and session is active
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        /* Global styles */
        .bg-university-blue {
            background-color: #0033A0; /* Primary blue */
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="bg-white shadow w-full">
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
    <nav class="bg-university-blue w-full">
        <div class="container mx-auto nav-bar-container py-3 px-6">
            <!-- Navigation links -->
            <ul class="flex items-center space-x-4 text-white">
                <li><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                <li><a href="profile.php" class="nav-link">Profile</a></li>
                <li><a href="course_registration_notice.php" class="nav-link">Course Registration Notice</a></li>
                <li><a href="HallSeatNotice.php" class="nav-link">Hall Seat Notice</a></li>
                <li><a href="departments.php" class="nav-link">Departments</a></li>
                <li><a href="Events.php" class="nav-link">Events</a></li>
                <li><a href="Contact.php" class="nav-link">Contact</a></li>
                <li><a href="logout.php" class="nav-link bg-red-600 px-4 py-2 rounded hover:bg-red-700">Logout</a></li>
            </ul>
        </div>
    </nav>
</body>
</html>
