<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "nstu_academia"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $studentID = $_POST['studentID'];
    $studentName = $_POST['studentName'];
    $hallName = $_POST['hallName'];
    $roomNumber = $_POST['roomNumber'];
    $seatNumber = $_POST['seatNumber'];

    // File upload handling
    $boardingCard = $_FILES['boardingCard'];
    $targetDir = __DIR__ . "/uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true); // Create directory if it doesn't exist
    }
    $targetFile = $targetDir . date('Y-m-d-H-i-s') . '-' . basename($boardingCard["name"]);
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Validate file
    $allowedTypes = ['jpg', 'png', 'pdf'];
    if (!in_array($fileType, $allowedTypes)) {
        die("Only JPG, PNG, and PDF files are allowed.");
    }
    if ($boardingCard["size"] > 2 * 1024 * 1024) { // 2MB limit
        die("File size should not exceed 2MB.");
    }

    if (move_uploaded_file($boardingCard["tmp_name"], $targetFile)) {
        // Insert data into the database
        $sql = "INSERT INTO boarding_cards (student_id, student_name, hall_name, room_number, seat_number, file_path)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssssss", $studentID, $studentName, $hallName, $roomNumber, $seatNumber, $targetFile);
            if ($stmt->execute()) {
                echo "Boarding card submitted successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        echo "Error uploading the file.";
    }
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Boarding Card</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9fafb;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .submit-button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 4px;
            width: 100%;
        }

        .submit-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <!-- Include Navbar -->
    <?php include 'navbar.php'; ?>

    <div class="content">
        <div class="form-container">
            <h2 class="text-2xl font-bold text-center mb-6">Submit Boarding Card</h2>
            <form id="boardingCardForm" action="SubmitBoardingCard.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="studentID">Student ID</label>
                    <input type="text" id="studentID" name="studentID" placeholder="Enter your student ID" required>
                </div>
                <div class="form-group">
                    <label for="studentName">Student Name</label>
                    <input type="text" id="studentName" name="studentName" placeholder="Enter your name" required>
                </div>
                <div class="form-group">
                    <label for="hallName">Select Hall</label>
                    <select id="hallName" name="hallName" required>
                        <option value="">Select Hall</option>
                        <option value="Bangamata Sheikh Fazilatunnesa Mujib Hall">Bangamata Sheikh Fazilatunnesa Mujib Hall</option>
                        <option value="Bangabandhu Sheikh Mujibur Rahman Hall">Bangabandhu Sheikh Mujibur Rahman Hall</option>
                        <option value="Bibi Khadiza Hall">Bibi Khadiza Hall</option>
                        <option value="Abdus Salam Hall">Abdus Salam Hall</option>
                        <option value="Abdul Malek Ukil Hall">Abdul Malek Ukil Hall</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="roomNumber">Room Number</label>
                    <input type="text" id="roomNumber" name="roomNumber" placeholder="Enter your room number" required>
                </div>
                <div class="form-group">
                    <label for="seatNumber">Seat Number</label>
                    <select id="seatNumber" name="seatNumber" required>
                        <option value="">Select Seat</option>
                        <option value="1">Seat 1</option>
                        <option value="2">Seat 2</option>
                        <option value="3">Seat 3</option>
                        <option value="4">Seat 4</option>
                        <option value="5">Seat 5</option>
                        <option value="6">Seat 6</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="boardingCard">Upload Boarding Card</label>
                    <input type="file" id="boardingCard" name="boardingCard" accept=".pdf,.jpg,.png" required>
                </div>
                <button type="submit" class="submit-button">Submit Boarding Card</button>
            </form>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include 'footer.php'; ?>
</body>

</html>
