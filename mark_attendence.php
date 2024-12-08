<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
include 'connection.php';

// Fetch departments
$departments_query = "SELECT Department_id, Department_name FROM department";
$departments_result = $conn->query($departments_query);

// Fetch courses
$courses_query = "SELECT Course_id, Course_Code, Course_Name FROM course";
$courses_result = $conn->query($courses_query);

// Fetch students
$students_query = "SELECT studentId, studentName FROM profiles";
$students_result = $conn->query($students_query);

if (!$students_result) {
    die("Failed to fetch students: " . $conn->error);
}

// Prepare a list of students for JavaScript use
$students_data = [];
while ($row = $students_result->fetch_assoc()) {
    $students_data[$row['studentId']] = $row['studentName'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Marking Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <main class="container mx-auto py-10">
        <div class="bg-white shadow-md rounded-lg p-6 max-w-full mx-auto border border-gray-300">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Mark Attendance</h2>

            <form id="attendanceForm" method="POST">
                <!-- Department Dropdown -->
                <div class="mb-4">
                    <label for="department" class="block text-gray-600 font-medium">Department</label>
                    <select id="department" name="department" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
                        <?php while ($row = $departments_result->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($row['Department_id']) ?>"><?= htmlspecialchars($row['Department_name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Course Code Dropdown -->
                <div class="mb-4">
                    <label for="course_code" class="block text-gray-600 font-medium">Course Code</label>
                    <select id="course_code" name="course_code" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
                        <option value="" disabled selected>Select a course code</option>
                        <?php while ($row = $courses_result->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($row['Course_Code']) ?>">
                                <?= htmlspecialchars($row['Course_Code']) ?> - <?= htmlspecialchars($row['Course_Name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Stipulated Classes Input -->
                <div class="mb-4">
                    <label for="stipulated_class" class="block text-gray-600 font-medium">Stipulated Classes</label>
                    <input type="number" id="stipulated_class" name="stipulated_class" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
                </div>

                <!-- Number of Classes Held Input -->
                <div class="mb-4">
                    <label for="no_of_classes_held" class="block text-gray-600 font-medium">Number of Classes Held</label>
                    <input type="number" id="no_of_classes_held" name="no_of_classes_held" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
                </div>

                <!-- Student ID Dropdown -->
                <div class="mb-4">
                    <label for="student_id" class="block text-gray-600 font-medium">Student ID</label>
                    <select id="student_id" name="student_id" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        <option value="" disabled selected>Select a student ID</option>
                        <?php foreach ($students_data as $student_id => $student_name): ?>
                            <option value="<?= htmlspecialchars($student_id) ?>"><?= htmlspecialchars($student_id) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Display Student Details -->
                <div id="studentDetails" class="mb-4 hidden">
                    <label class="block text-gray-600 font-medium">Student Name</label>
                    <p id="studentName" class="text-gray-700 font-semibold"></p>
                </div>

                <!-- Attendance Input -->
                <div id="attendanceInput" class="mb-4 hidden">
                    <label for="attendance" class="block text-gray-600 font-medium">Attendance</label>
                    <input type="number" id="attendance" name="attendance" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                </div>

                <!-- Submit Button -->
                <div class="mt-6 text-right">
                    <button type="submit" id="submitBtn" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 hidden">
                        Submit Attendance
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Fetch student data from PHP array
        const studentsData = <?= json_encode($students_data) ?>;

        // DOM elements
        const studentIdSelect = document.getElementById('student_id');
        const studentDetailsDiv = document.getElementById('studentDetails');
        const studentNameField = document.getElementById('studentName');
        const attendanceInput = document.getElementById('attendanceInput');
        const submitButton = document.getElementById('submitBtn');

        // Event listener for student ID selection
        studentIdSelect.addEventListener('change', (event) => {
            const selectedStudentId = event.target.value;

            if (studentsData[selectedStudentId]) {
                studentDetailsDiv.classList.remove('hidden');
                studentNameField.textContent = studentsData[selectedStudentId];
                attendanceInput.classList.remove('hidden');
                submitButton.classList.remove('hidden');
            } else {
                studentDetailsDiv.classList.add('hidden');
                attendanceInput.classList.add('hidden');
                submitButton.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
