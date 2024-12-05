<?php include 'navbarhome.php'; ?>


// Access session variables
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];
$user_email = $_SESSION['email'];
$user_type = $_SESSION['user_type'];
?>

<?php
// Include the connection file
include 'connection.php';

// Fetch registration data grouped by profile, year, and semester
$sql = "SELECT profiles.studentName, profiles.studentId, registration.Year, registration.Semester, 
               GROUP_CONCAT(course.Course_Name SEPARATOR ', ') AS Courses, registration.status, registration.id
        FROM registration
        INNER JOIN profiles ON registration.profiles_id = profiles.id
        INNER JOIN course ON registration.course_id = course.Course_id
        GROUP BY profiles.id, registration.Year, registration.Semester";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching registration data: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="container mx-auto py-4 px-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-700">Registration Applications</h1>
            <!-- Back to Home Button -->
            <a href="teacher_profile.php" class="text-blue-500 hover:underline">Back to Home</a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto mt-10 px-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-6">Manage Registrations</h2>

            <!-- Registration Table -->
            <table class="table-auto w-full border-collapse border border-gray-200">
                <thead class="bg-blue-500 text-white">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">Student Name</th>
                        <th class="border border-gray-300 px-4 py-2">Student ID</th>
                        <th class="border border-gray-300 px-4 py-2">Year</th>
                        <th class="border border-gray-300 px-4 py-2">Semester</th>
                        <th class="border border-gray-300 px-4 py-2">Courses</th>
                        <th class="border border-gray-300 px-4 py-2">Status</th>
                        <th class="border border-gray-300 px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['studentName']) ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['studentId']) ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['Year']) ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['Semester']) ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['Courses']) ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['status']) ?></td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <!-- Actions -->
                                    <a href="approve_registration.php?id=<?= htmlspecialchars($row['id']) ?>" 
                                       class="text-green-500 hover:underline">Approve</a>
                                    <a href="reject_registration.php?id=<?= htmlspecialchars($row['id']) ?>" 
                                       class="text-red-500 hover:underline ml-4">Reject</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 py-4">No registrations found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
