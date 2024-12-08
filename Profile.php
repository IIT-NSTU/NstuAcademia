<?php
include 'connection.php';

// Fetch student ID from GET parameter
$student_id = isset($_GET['Id']) ? $_GET['Id'] : '';

if (empty($student_id)) {
    die("No student ID provided.");
}

// Fetch profile data based on the student ID
$sql = "SELECT * FROM profiles WHERE Id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();

if (!$profile) {
    die("No profile found for student ID: " . htmlspecialchars($student_id));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .profile-image {
            width: 8rem;
            height: 8rem;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <div class="flex items-center">
                <img src="nstu.jpg" alt="University Logo" class="w-10 h-10">
                <h1 class="text-xl font-bold text-gray-800 ml-3">NstuAcademia</h1>
            </div>
            <span class="text-gray-600">Student Profile</span>
        </div>
    </nav>

    <!-- Profile Section -->
    <div class="container mx-auto mt-10 p-6 bg-white shadow rounded-lg">
        <div class="flex flex-col md:flex-row items-center">
            <!-- Profile Image -->
            <img src="uploads/<?= htmlspecialchars($profile['profileImage']) ?>" 
                 alt="Profile Image" 
                 class="profile-image rounded-full border-4 border-gray-300 shadow-lg mb-6 md:mb-0 md:mr-6"
                 onerror="this.src='default-profile.png';">

            <!-- Profile Details -->
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-4">Welcome back, <?= htmlspecialchars($profile['studentName']) ?></h1>
                <table class="table-auto w-full text-left">
                    <tbody>
                        <tr>
                            <th class="font-semibold text-gray-700 py-2">Student Name:</th>
                            <td class="py-2"><?= htmlspecialchars($profile['studentName']) ?></td>
                        </tr>
                        <tr>
                            <th class="font-semibold text-gray-700 py-2">Father's Name:</th>
                            <td class="py-2"><?= htmlspecialchars($profile['fatherName']) ?></td>
                        </tr>
                        <tr>
                            <th class="font-semibold text-gray-700 py-2">Student ID:</th>
                            <td class="py-2"><?= htmlspecialchars($profile['studentId']) ?></td>
                        </tr>
                        <tr>
                            <th class="font-semibold text-gray-700 py-2">Department:</th>
                            <td class="py-2"><?= htmlspecialchars($profile['department']) ?></td>
                        </tr>
                        <tr>
                            <th class="font-semibold text-gray-700 py-2">Course Coordinator:</th>
                            <td class="py-2"><?= htmlspecialchars($profile['courseCoordinator']) ?></td>
                        </tr>
                        <tr>
                            <th class="font-semibold text-gray-700 py-2">Session:</th>
                            <td class="py-2"><?= htmlspecialchars($profile['session']) ?></td>
                        </tr>
                        <tr>
                            <th class="font-semibold text-gray-700 py-2">Hall Name:</th>
                            <td class="py-2"><?= htmlspecialchars($profile['hallName']) ?></td>
                        </tr>
                        <tr>
                            <th class="font-semibold text-gray-700 py-2">Seat Number:</th>
                            <td class="py-2"><?= htmlspecialchars($profile['seatNumber']) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>

    <div class="mt-6 text-center">
                <a href="forms.php?Id=<?= htmlspecialchars($student_id) ?>" 
                   class="bg-blue-500 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700">
                    Update Profile
                </a>
            </div>
</body>
</html>
