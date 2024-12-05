<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'nstu_academia';

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch years and semesters from the course table
$course_query = "SELECT DISTINCT Year, Semester FROM course";
$course_result = $conn->query($course_query);
if (!$course_result) {
    die("Error fetching course data: " . $conn->error);
}

// Initialize attendance data
$attendance_data = [];
$overall_percentage = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_year = $_POST['year'];
    $selected_semester = $_POST['semester'];

    // Fetch Course_id for the selected Year and Semester
    $course_query = "SELECT Course_id FROM course WHERE Year = ? AND Semester = ?";
    $stmt = $conn->prepare($course_query);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $selected_year, $selected_semester);
    $stmt->execute();
    $course_result = $stmt->get_result();
    $course_ids = $course_result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    if (count($course_ids) > 0) {
        // Fetch attendance data for the retrieved Course_ids
        $course_id_placeholders = implode(',', array_fill(0, count($course_ids), '?'));
        $attendance_query = "
            SELECT 
                a.profiles_id AS studentid,
                c.Course_Code,
                c.Year,
                c.Semester,
                a.stipulated_class,
                a.no_of_classes_held,
                a.attendance
            FROM attendance a
            JOIN course c ON a.Course_id = c.Course_id
            WHERE c.Course_id IN ($course_id_placeholders)";

        $stmt = $conn->prepare($attendance_query);

        if (!$stmt) {
            die("Prepare failed for attendance query: " . $conn->error);
        }

        $course_ids_values = array_column($course_ids, 'Course_id');
        $stmt->bind_param(str_repeat('i', count($course_ids)), ...$course_ids_values);
        $stmt->execute();
        $result = $stmt->get_result();
        $attendance_data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // Calculate overall percentage
        $total_classes_held = array_sum(array_column($attendance_data, 'no_of_classes_held'));
        $total_classes_attended = array_sum(array_column($attendance_data, 'attendance'));
        $overall_percentage = ($total_classes_held > 0) ? ($total_classes_attended / $total_classes_held) * 100 : 0;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <!-- Header -->
    <header class="bg-white shadow">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <div class="flex items-center">
                <img src="noakhali_science_and_technology_university_logo.jpeg" alt="Logo" class="w-16">
                <div class="ml-3">
                    <h1 class="text-xl font-bold">NstuAcademia</h1>
                    <p class="text-sm text-gray-600">A Hassle-Free System for University Students</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Attendance Page Content -->
    <div class="container mx-auto mt-6 px-6">
        <h1 class="text-2xl font-bold mb-4">Student Attendance</h1>
        
        <!-- Semester and Year Selector -->
        <form method="POST" class="flex space-x-4 mb-6">
            <select name="semester" class="border rounded p-2" required>
                <option value="" disabled selected>--Select Semester--</option>
                <?php if ($course_result->num_rows > 0): ?>
                    <?php $course_result->data_seek(0); // Reset pointer ?>
                    <?php while ($row = $course_result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['Semester']) ?>">
                            <?= htmlspecialchars($row['Semester']) ?>
                        </option>
                    <?php endwhile; ?>
                <?php else: ?>
                    <option value="" disabled>No semesters available</option>
                <?php endif; ?>
            </select>
            <select name="year" class="border rounded p-2" required>
                <option value="" disabled selected>--Select Year--</option>
                <?php if ($course_result->num_rows > 0): ?>
                    <?php $course_result->data_seek(0); // Reset pointer ?>
                    <?php while ($row = $course_result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['Year']) ?>">
                            <?= htmlspecialchars($row['Year']) ?>
                        </option>
                    <?php endwhile; ?>
                <?php else: ?>
                    <option value="" disabled>No years available</option>
                <?php endif; ?>
            </select>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Show Attendance</button>
        </form>

        <!-- Attendance Table -->
        <div class="overflow-x-auto">
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border px-4 py-2">Student ID</th>
                        <th class="border px-4 py-2">Course Code</th>
                        <th class="border px-4 py-2">Stipulated Classes</th>
                        <th class="border px-4 py-2">Classes Held</th>
                        <th class="border px-4 py-2">Classes Attended</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($attendance_data) > 0): ?>
                        <?php foreach ($attendance_data as $row): ?>
                            <tr>
                                <td class="border px-4 py-2"><?= htmlspecialchars($row['studentid']) ?></td>
                                <td class="border px-4 py-2"><?= htmlspecialchars($row['Course_Code']) ?></td>
                                <td class="border px-4 py-2"><?= htmlspecialchars($row['stipulated_class']) ?></td>
                                <td class="border px-4 py-2"><?= htmlspecialchars($row['no_of_classes_held']) ?></td>
                                <td class="border px-4 py-2"><?= htmlspecialchars($row['attendance']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td class="border px-4 py-2 text-center" colspan="5">No data found for the selected Year and Semester.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Attendance Percentage Check -->
        <div class="mt-6 text-center">
            <?php if ($overall_percentage > 0): ?>
                <p class="text-lg font-semibold">
                    Overall Attendance: <span class="<?= $overall_percentage >= 75 ? 'text-green-500' : 'text-red-500' ?>">
                        <?= number_format($overall_percentage, 2) ?>%
                    </span>
                </p>
                <?php if ($overall_percentage >= 75): ?>
                    <a href="payment.php" class="mt-4 inline-block bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-700">
                        Proceed to Pay
                    </a>
                <?php else: ?>
                    <p class="mt-4 text-red-500 font-bold">Your attendance is below 75%. Please contact your course coordinator.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <footer class="bg-gray-100 text-center py-4">
        <p>&copy; 2024 NstuAcademia. All rights reserved.</p>
    </footer>
</body>
</html>
