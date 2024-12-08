<?php
function getDatabaseConnection() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "nstu_academia";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

$isFirstUser = false;

$conn = getDatabaseConnection();
$sql = "SELECT COUNT(*) as user_count FROM users";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $isFirstUser = ($row['user_count'] == 0);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NstuAcademia</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        
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
        .gradient-bg {
            background: linear-gradient(135deg, #0033A0, #1e40af, #2563eb);
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .float {
            animation: float 3s infinite ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-100">

    <header class="bg-white shadow">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <div class="flex items-center space-x-4">
                <img src="noakhali_science_and_technology_university_logo.jpeg" alt="University Logo" class="w-16 h-auto">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">NstuAcademia</h1>
                    <p class="text-sm text-gray-600 running-text">
                        A Hassle-Free System for University Students
                    </p>
                </div>
            </div>
            <div class="text-gray-600 text-right">
                <p class="font-semibold">📞 Phone: 02334496522</p>
                <p class="font-semibold">📠 Fax: 02334496523</p>
                <p class="font-semibold">✉️ Email: registrar@office.nstu.edu.bd</p>
            </div>
        </div>
    </header>

    <nav class="gradient-bg w-full">
        <div class="container mx-auto flex justify-between items-center py-3 px-6 text-white">
            <ul class="flex items-center space-x-4">
                <li><a href="Home.php" class="hover:underline">Home</a></li>
                <li><a href="AboutUs.php" class="hover:underline">About Us</a></li>
                <li><a href="Contact.php" class="hover:underline">Contact</a></li>
            </ul>
            <div>
                <?php if ($isFirstUser): ?>
                    <a href="SignUp.php" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded">Sign Up</a>
                <?php else: ?>
                    <a href="login.php" class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-4 rounded">Log In</a>
                    <a href="SignUp.php" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <section class="gradient-bg text-white py-20">
        <div class="container mx-auto text-center">
            <h2 class="text-4xl font-extrabold mb-6">Welcome to NstuAcademia</h2>
            <p class="text-lg mb-8">
                Simplify your university life with our comprehensive system for students, teachers, and administrators.
            </p>
            <div class="mt-8">
                <img src="university_campus_image.jpeg" alt="University Campus" class="mx-auto w-64 h-auto rounded-lg shadow-lg">
            </div>
        </div>
    </section>

    <footer class="gradient-bg text-white py-8">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 NstuAcademia. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>