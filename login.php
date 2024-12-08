<?php
include 'config.php';

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getDatabaseConnection();

    // Retrieve form data
    $userType = $_POST['userType'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Determine the target table based on user type
    $table = $userType === "student" ? "students" :
            ($userType === "teacher" ? "teachers" :
            ($userType === "hall_admin" ? "hall_admins" : ""));

    if (!empty($table)) {
        $stmt = $conn->prepare("SELECT id, name, password FROM $table WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['user_type'] = $userType;

                header("Location: dashboard.php");
                exit();
            } else {
                $message = "Invalid credentials! Incorrect password.";
            }
        } else {
            $message = "Invalid credentials! User not found.";
        }

        $stmt->close();
    } else {
        $message = "Invalid user type selected.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NSTU Academia</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-blue-600">
    <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold text-center mb-4">Log In</h2>
        <?php if (!empty($message)): ?>
            <div class="bg-red-100 text-red-600 p-4 rounded mb-6">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        <form method="POST" class="space-y-6">
            <div>
                <label for="userType" class="block text-sm font-medium text-gray-700">Login As</label>
                <select id="userType" name="userType" required
                        class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
                    <option value="" disabled selected>Select User Type</option>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                    <option value="hall_admin">Hall Admin</option>
                </select>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email"
                       class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password"
                       class="w-full mt-1 p-3 border border-gray-300 rounded-lg">
            </div>
            <button type="submit"
                    class="w-full bg-blue-500 text-white py-3 rounded-lg font-semibold hover:bg-blue-600">Log In
            </button>
        </form>
    </div>
</body>
</html>
