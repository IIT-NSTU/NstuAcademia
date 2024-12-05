<?php
include 'session_check.php';
?>

<?php include 'navbarhome.php'; ?>
<?php
// Include the connection file
include 'connection.php';

// Enable detailed error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$courses = [];
$departments = [];
$message = ""; // Initialize message to display alerts

// Fetch all available departments from the department table
try {
    $dept_stmt = $conn->prepare("SELECT Department_Id, Department_Name FROM department");
    $dept_stmt->execute();
    $dept_result = $dept_stmt->get_result();

    while ($row = $dept_result->fetch_assoc()) {
        $departments[] = $row; // Store Department_Id and Department_Name
    }
} catch (Exception $e) {
    echo "<script>alert('Error fetching departments: " . $e->getMessage() . "');</script>";
}

// Fetch courses when "Fetch Courses" is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fetch_courses'])) {
    $department_id = $_POST['department'];
    $year = $_POST['year'];
    $semester = $_POST['semester'];

    try {
        if (!empty($department_id) && !empty($year) && !empty($semester)) {
            $stmt = $conn->prepare("SELECT Course_id, Course_Code, Course_Name FROM course WHERE Department_Id = ? AND Year = ? AND Semester = ?");
            $stmt->bind_param("iii", $department_id, $year, $semester);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $courses[] = $row; // Store fetched courses
            }
        } else {
            $message = "Please select a department, year, and semester.";
        }
    } catch (Exception $e) {
        $message = "Error fetching courses: " . $e->getMessage();
    }
}

// Save registration details when "Register" is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_courses'])) {
    $student_id = $_POST['student_id'];
    $selected_courses = $_POST['selected_courses'] ?? [];
    $year = $_POST['year'];
    $semester = $_POST['semester'];

    try {
        if (!empty($student_id) && !empty($selected_courses)) {
            // Check if the student exists in the profiles table
            $stmt = $conn->prepare("SELECT id FROM profiles WHERE studentId = ?");
            $stmt->bind_param("i", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $profile_id = $result->fetch_assoc()['id']; // Get the profiles.id

                // Insert selected courses into the registration table
                foreach ($selected_courses as $course_id) {
                    $reg_stmt = $conn->prepare("INSERT INTO registration (profiles_id, course_id, year, semester, status) VALUES (?, ?, ?, ?, 'registered')");
                    $reg_stmt->bind_param("iiis", $profile_id, $course_id, $year, $semester);
                    $reg_stmt->execute();
                }

                $message = "Registration successful! Courses have been added.";
            } else {
                $message = "Student ID not found.";
            }
        } else {
            $message = "Student ID and selected courses are required.";
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<header class="bg-white shadow">
    <div class="container mx-auto flex flex-col md:flex-row justify-between items-center py-4 px-6">
        <div class="flex items-center mb-4 md:mb-0">
            <h1 class="text-xl font-bold">NstuAcademia</h1>
        </div>
        <a href="fetch_attendence.php" class="text-blue-600 underline">Fetch Attendence</a>
    </div>
</header>

<main class="container mx-auto mt-10 px-6">
    <h1 class="text-3xl font-bold mb-6">Course Registration</h1>

    <?php if (!empty($message)): ?>
        <div class="bg-green-100 text-green-700 p-4 rounded mb-6"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="student_id" class="block mb-2">Student ID:</label>
        <input type="text" id="student_id" name="student_id" class="w-full border rounded-md p-2 mb-4" placeholder="Enter Student ID" required>

        <label for="department" class="block mb-2">Select Department:</label>
        <select id="department" name="department" class="w-full border rounded-md p-2 mb-4" required>
            <option value="">--Select Department--</option>
            <?php foreach ($departments as $dept) : ?>
                <option value="<?= htmlspecialchars($dept['Department_Id']) ?>"><?= htmlspecialchars($dept['Department_Name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="year" class="block mb-2">Select Year:</label>
        <select id="year" name="year" class="w-full border rounded-md p-2 mb-4" required>
            <option value="">--Select Year--</option>
            <option value="1">Year 1</option>
            <option value="2">Year 2</option>
            <option value="3">Year 3</option>
            <option value="4">Year 4</option>
        </select>

        <label for="semester" class="block mb-2">Select Semester:</label>
        <select id="semester" name="semester" class="w-full border rounded-md p-2 mb-4" required>
            <option value="">--Select Semester--</option>
            <option value="1">Semester 1</option>
            <option value="2">Semester 2</option>
        </select>

        <button type="submit" name="fetch_courses" class="bg-blue-500 text-white px-4 py-2 rounded-md mb-4">Fetch Courses</button>
    </form>

    <?php if (!empty($courses)): ?>
        <form method="POST" action="">
            <input type="hidden" name="student_id" value="<?= htmlspecialchars($_POST['student_id']) ?>">
            <input type="hidden" name="year" value="<?= htmlspecialchars($_POST['year']) ?>">
            <input type="hidden" name="semester" value="<?= htmlspecialchars($_POST['semester']) ?>">

            <h2 class="text-xl font-bold mb-4">Available Courses</h2>
            <?php foreach ($courses as $course): ?>
                <div class="mb-2">
                    <label>
                        <input type="checkbox" name="selected_courses[]" value="<?= htmlspecialchars($course['Course_id']) ?>">
                        <?= htmlspecialchars($course['Course_Code']) ?> - <?= htmlspecialchars($course['Course_Name']) ?>
                    </label>
                </div>
            <?php endforeach; ?>

            <button type="submit" name="register_courses" class="bg-green-500 text-white px-4 py-2 rounded-md mt-4">Register</button>
        </form>
    <?php endif; ?>
</main>
</body>
</html>
