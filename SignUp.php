<?php
// Database connection
function getDatabaseConnection()
{
    $host = "localhost"; // Database host
    $user = "root";      // Database username
    $password = "";      // Database password
    $database = "nstu_academia"; // Database name

    $conn = new mysqli($host, $user, $password, $database);

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    return $conn;
}

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getDatabaseConnection();

    // Retrieve form data
    $userType = $_POST['userType'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
    $idCardFile = null;

    // Handle ID Card upload for students/teachers
    if (isset($_FILES['idCard']) && ($_FILES['idCard']['error'] === UPLOAD_ERR_OK)) {
        $fileTmpPath = $_FILES['idCard']['tmp_name'];
        $fileName = basename($_FILES['idCard']['name']);
        $uploadDir = 'uploads/';
        $idCardFile = $uploadDir . uniqid() . '_' . $fileName;

        if (!move_uploaded_file($fileTmpPath, $idCardFile)) {
            $message = "Error uploading ID card file.";
        }
    }

    // Determine the target table
    $table = "";
    if ($userType === "student") {
        $table = "students";
    } elseif ($userType === "teacher") {
        $table = "teachers";
    } elseif ($userType === "hall_admin") {
        $table = "hall_admins";
    }

    if (!empty($table) && empty($message)) {
        // Insert data into the correct table
        if ($userType === "student" || $userType === "teacher") {
            $stmt = $conn->prepare("INSERT INTO $table (name, email, id_card, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $idCardFile, $password);
        } else {
            $stmt = $conn->prepare("INSERT INTO $table (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $password);
        }

        if ($stmt->execute()) {
            // Redirect the user to the forms.php after successful signup
            header("Location: forms.php?user_type=" . urlencode($userType));
            exit();
        } else {
            $message = "Error: " . $conn->error;
        }

        $stmt->close();
    } else {
        $message = "Invalid user type selected or file upload failed.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - NSTU Academia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            font-family: 'Inter', sans-serif;
        }

        .form-container {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .form-container h2 {
            color: #1e293b;
        }

        .form-container button {
            transition: all 0.3s ease-in-out;
        }

        .form-container button:hover {
            background: #2563eb;
        }

        .id-card {
            display: none;
        }
    </style>
    <script>
        function toggleIdCardField() {
            const userType = document.getElementById("userType").value;
            const idCardField = document.getElementById("idCardField");

            if (userType === "student" || userType === "teacher") {
                idCardField.style.display = "block";
            } else {
                idCardField.style.display = "none";
            }
        }

        function validatePasswords() {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirmPassword").value;

            if (password !== confirmPassword) {
                alert("Passwords do not match!");
                return false;
            }
            return true;
        }
    </script>
</head>

<body class="flex items-center justify-center min-h-screen bg-blue-600">
    <div class="w-full max-w-lg form-container">
        <h2 class="text-3xl font-bold text-center mb-4">Sign Up</h2>
        <p class="text-center text-gray-500 mb-6">Join NSTU Academia today!</p>

        <!-- Success Message -->
        <?php if (!empty($message)): ?>
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Form Starts -->
        <form method="POST" id="signupForm" class="space-y-6" enctype="multipart/form-data" onsubmit="return validatePasswords();">
            <!-- User Type -->
            <div>
                <label for="userType" class="block text-sm font-medium text-gray-700">User Type</label>
                <select id="userType" name="userType" onchange="toggleIdCardField()" required
                    class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">
                    <option value="" disabled selected>Select User Type</option>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                    <option value="hall_admin">Hall Administration</option>
                </select>
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" required placeholder="Enter your full name"
                    class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email address"
                    class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">
            </div>

            <!-- ID Card Field -->
            <div id="idCardField" class="id-card">
                <label for="idCard" class="block text-sm font-medium text-gray-700">ID Card (PDF/Image)</label>
                <input type="file" id="idCard" name="idCard" accept=".pdf, .jpg, .jpeg, .png"
                    class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password"
                    class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required placeholder="Confirm your password"
                    class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-blue-500 text-white py-3 rounded-lg font-semibold hover:bg-blue-600">Sign
                Up</button>
        </form>
    </div>
</body>
</html>
