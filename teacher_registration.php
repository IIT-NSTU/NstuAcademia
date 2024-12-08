<?php
// Database connection details
$host = '127.0.0.1';
$db = 'nstu_academia';
$user = 'root'; // Update with your MySQL username
$pass = ''; // Update with your MySQL password
$charset = 'utf8mb4';

// Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Create a connection
try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $eduMail = $_POST['eduMail'];
    $nid = $_POST['nid'];
    $bloodGroup = $_POST['bloodGroup'];
    $designation = $_POST['designation'];
    $isProvost = $_POST['isProvost'];
    $hallName = $isProvost === "Yes" ? $_POST['hallName'] : null;

    // Handle image upload
    $targetDir = "uploads/";
    $imageFileName = basename($_FILES["picture"]["name"]);
    $targetFilePath = $targetDir . $imageFileName;
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    $uploadOk = 1;

    // Check if image file is a valid image
    if (isset($_FILES["picture"]) && $_FILES["picture"]["error"] == 0) {
        $check = getimagesize($_FILES["picture"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
            echo "<script>alert('File is not an image.');</script>";
        }
    }

    // Check file size (5MB max)
    if ($_FILES["picture"]["size"] > 5000000) {
        $uploadOk = 0;
        echo "<script>alert('Sorry, your file is too large.');</script>";
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
        $uploadOk = 0;
        echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
    }

    // Check if $uploadOk is set to 0
    if ($uploadOk == 0) {
        echo "<script>alert('Sorry, your file was not uploaded.');</script>";
    } else {
        // Upload the file
        if (move_uploaded_file($_FILES["picture"]["tmp_name"], $targetFilePath)) {
            try {
                // Insert data into the teacher table
                $sql = "INSERT INTO teacher (Name, Age, Address, Edu_Mail, NID, Blood_Group, Designation, Is_Provost, Hall_Name, Picture) 
                        VALUES (:name, :age, :address, :eduMail, :nid, :bloodGroup, :designation, :isProvost, :hallName, :picture)";
                $stmt = $pdo->prepare($sql);

                // Bind parameters
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':age', $age);
                $stmt->bindParam(':address', $address);
                $stmt->bindParam(':eduMail', $eduMail);
                $stmt->bindParam(':nid', $nid);
                $stmt->bindParam(':bloodGroup', $bloodGroup);
                $stmt->bindParam(':designation', $designation);
                $stmt->bindParam(':isProvost', $isProvost);
                $stmt->bindParam(':hallName', $hallName);
                $stmt->bindParam(':picture', $targetFilePath);

                // Execute the statement
                $stmt->execute();
                echo "<script>alert('Teacher registered successfully!'); window.location.href='teacher_registration.php';</script>";
            } catch (PDOException $e) {
                echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
            }
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NstuAcademia - Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Font Awesome -->
    <style>
        .running-text {
            animation: runningText 15s linear infinite;
        }

        @keyframes runningText {
            0% {
                transform: translateX(100%);
            }
            100% {
                transform: translateX(-100%);
            }
        }

        .bg-university-blue {
            background-color: #0033A0; /* Bright university blue */
        }

        .bg-university-blue:hover {
            background-color: #00267a; /* Slightly darker blue on hover */
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center py-4 px-6">
            <div class="flex items-center">
                <img src="noakhali_science_and_technology_university_logo.jpeg" alt="University Logo" class="w-16 h-auto">
                <div class="ml-4">
                    <h1 class="text-xl font-bold">NstuAcademia</h1>
                    <p class="text-sm text-gray-600">
                        <span class="running-text inline-block whitespace-nowrap overflow-hidden">
                            A Hassle Free System for University Students
                        </span>
                    </p>
                </div>
            </div>
            <div class="flex flex-col items-center md:items-end text-gray-600">
                <p><i class="fas fa-phone-alt"></i> Phone: 02334496522</p>
                <p><i class="fas fa-fax"></i> Fax: 02334496523</p>
                <p><i class="fas fa-envelope"></i> Email: registrar@office.nstu.edu.bd</p>
            </div>
        </div>
    </header>

    <!-- Navbar -->
    <nav class="bg-university-blue shadow-lg">
        <div class="container mx-auto flex flex-wrap justify-between items-center py-3 px-6">
            <ul class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-6 w-full md:w-auto">
                <li><a href="Home.html" class="hover:text-gray-300">Home</a></li>
                <li><a href="AboutUs.html" class="hover:text-gray-300">About Us</a></li>
                <li><a href="CourseRegistrationNotice.html" class="hover:text-gray-300">Course Registration Notice</a></li>
                <li><a href="HallSeatNotice.html" class="hover:text-gray-300">Hall Seat Notice</a></li>
                <li><a href="department.html" class="hover:text-gray-300">Departments</a></li>
                <li><a href="Event.html" class="hover:text-gray-300">Events</a></li>
                <li><a href="Contactpage.html" class="hover:text-gray-300">Contact</a></li>
            </ul>
            <div class="search-container mt-4 md:mt-0 w-full md:w-auto">
                <input type="text" id="search-input" placeholder="Search..." class="custom-input">
                <button class="custom-button" id="search-button">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 10-14 0 7 7 0 0014 0z" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>
<body class="bg-gray-50">
    <div class="container mx-auto mt-10 p-6 bg-white rounded shadow-lg">
        <h2 class="text-2xl font-semibold mb-4 text-gray-700 text-center">Teacher Registration Form</h2>
        <form class="space-y-6" id="teacher-form" method="POST" action="teacher_registration.php" enctype="multipart/form-data">
            <!-- General Details -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" id="name" name="name" class="w-full px-4 py-2 mt-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your name" required>
            </div>
            <div>
                <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                <input type="number" id="age" name="age" class="w-full px-4 py-2 mt-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your age" required>
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <textarea id="address" name="address" class="w-full px-4 py-2 mt-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your address" required></textarea>
            </div>
            <div>
                <label for="eduMail" class="block text-sm font-medium text-gray-700">Edu Mail</label>
                <input type="email" id="eduMail" name="eduMail" class="w-full px-4 py-2 mt-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your edu mail" required>
            </div>
            <div>
                <label for="nid" class="block text-sm font-medium text-gray-700">NID</label>
                <input type="text" id="nid" name="nid" class="w-full px-4 py-2 mt-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your NID" required>
            </div>
            <div>
                <label for="bloodGroup" class="block text-sm font-medium text-gray-700">Blood Group</label>
                <select id="bloodGroup" name="bloodGroup" class="w-full px-4 py-2 mt-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Select Blood Group</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                </select>
            </div>
            <div>
                <label for="designation" class="block text-sm font-medium text-gray-700">Designation</label>
                <select id="designation" name="designation" class="w-full px-4 py-2 mt-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Select Designation</option>
                    <option value="Lecturer">Lecturer</option>
                    <option value="Assistant Professor">Assistant Professor</option>
                    <option value="Director">Director</option>
                </select>
            </div>
            <div>
                <label for="isProvost" class="block text-sm font-medium text-gray-700">Are you a provost of any hall?</label>
                <select id="isProvost" name="isProvost" class="w-full px-4 py-2 mt-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="toggleHallField()" required>
                    <option value="No">No</option>
                    <option value="Yes">Yes</option>
                </select>
            </div>
            <div id="hallField" style="display: none;">
                <label for="hallName" class="block text-sm font-medium text-gray-700">Hall Name</label>
                <input type="text" id="hallName" name="hallName" class="w-full px-4 py-2 mt-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter the hall name">
            </div>
            <div>
                <label for="picture" class="block text-sm font-medium text-gray-700">Upload Picture</label>
                <input type="file" id="picture" name="picture" class="w-full px-4 py-2 mt-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" accept="image/*" required>
            </div>
            <div class="text-center">
                <button type="submit" class="bg-blue-800 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Submit</button>
            </div>
        </form>
    </div>
    <footer class="bg-university-blue text-white py-6 mt-10">
        <div class="container mx-auto text-center">
            <p class="mb-4">&copy; 2024 NstuAcademia. All rights reserved.</p>
            <ul class="flex justify-center space-x-6">
                <li><a href="#" class="hover:text-gray-300">Privacy Policy</a></li>
                <li><a href="#" class="hover:text-gray-300">Terms of Service</a></li>
                <li><a href="#" class="hover:text-gray-300">Contact</a></li>
            </ul>
        </div>
    </footer>
    <script>
        function toggleHallField() {
            const isProvost = document.getElementById("isProvost").value;
            const hallField = document.getElementById("hallField");
            if (isProvost === "Yes") {
                hallField.style.display = "block";
            } else {
                hallField.style.display = "none";
            }
        }
    </script>
</body>
</html>
