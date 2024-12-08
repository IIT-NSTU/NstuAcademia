<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$servername = "localhost";
$username = "root";  
$password = "";
$dbname = "nstu_academia"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form values
    $studentName = $_POST['studentName'];
    $studentID = $_POST['studentID'];
    $semester = $_POST['semester'];
    $hallPreference = $_POST['hallPreference'];
    $floor = $_POST['floor'];
    $hallRoom = $_POST['hallRoom'];
    $seatNumber = $_POST['seatNumber'];
    $specialNote = $_POST['specialNote'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO hall_applications (student_name, student_id, semester, hall_preference, floor, hall_room, seat_number, special_note) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $studentName, $studentID, $semester, $hallPreference, $floor, $hallRoom, $seatNumber, $specialNote);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NstuAcademia - Submit Boarding Card</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
        /* Custom styles for colors */
        .bg-university-blue {
            background-color: #0033A0;
        }
        .bg-university-blue-hover {
            background-color: #00267a;
        }
        .custom-input {
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            border: 1px solid #d1d5db;
            outline: none;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding-left: 2.5rem;
            transition: box-shadow 0.3s;
            position: relative;
            background-color: #f9f9f9;
        }
        .custom-input:focus {
            box-shadow: 0 0 0 3px rgba(0, 51, 160, 0.5);
        }
        .custom-input::placeholder {
            color: #9ca3af;
        }
        .custom-button {
            background-color: #414f74;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 2.5rem;
            margin-left: 1rem;
        }
        .custom-button:hover {
            background-color: #00267a;
        }
        .custom-button:focus {
            box-shadow: 0 0 0 3px rgba(73, 80, 99, 0.5);
            outline: none;
        }
        .form-container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 700px;
            margin: 40px auto;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }
        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
        .submit-button {
            background-color: #007bff;
            color: white;
            padding: 12px;
            border: none;
            cursor: pointer;
            font-size: 18px;
            border-radius: 8px;
            width: 100%;
            transition: background-color 0.3s;
        }
        .submit-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>
   
    <!-- Hall Seat Application Form -->
    <section class="container mx-auto py-12 px-4 md:px-0">
        <h2 class="text-3xl font-bold text-blue-900 text-center mb-8">Hall Seat Application</h2>
        <form id="hallApplicationForm" class="form-container" action="HallApplication.php" method="POST">

            <div class="form-group">
                <label for="studentName" class="text-gray-700">Student Name</label>
                <input type="text" id="studentName" name="studentName" placeholder="Enter your name" required>
            </div>

            <div class="form-group">
                <label for="studentID" class="text-gray-700">Student ID</label>
                <input type="text" id="studentID" name="studentID" placeholder="Enter your student ID" required>
            </div>

            <div class="form-group">
                <label for="semester" class="text-gray-700">Semester</label>
                <select id="semester" name="semester" required>
                    <option value="">Select Semester</option>
                    <option value="1st Semester">1st Semester</option>
                    <option value="2nd Semester">2nd Semester</option>
                    <option value="3rd Semester">3rd Semester</option>
                    <option value="4th Semester">4th Semester</option>
                    <option value="5th Semester">5th Semester</option>
                    <option value="6th Semester">6th Semester</option>
                    <option value="7th Semester">7th Semester</option>
                    <option value="8th Semester">8th Semester</option>
                </select>
            </div>

            <div class="form-group">
                <label for="hallPreference" class="text-gray-700">Hall Preference</label>
                <select id="hallPreference" name="hallPreference" required>
                    <option value="">Select Hall</option>
                    <option value="Bangamata Sheikh Fazilatunnesa Mujib Hall">Bangamata Sheikh Fazilatunnesa Mujib Hall</option>
                    <option value="Bangabandhu Sheikh Mujibur Rahman Hall">Bangabandhu Sheikh Mujibur Rahman Hall</option>
                    <option value="Bibi Khadiza Hall">Bibi Khadiza Hall</option>
                    <option value="Abdus Salam Hall">Abdus Salam Hall</option>
                    <option value="Abdul Malek Ukil Hall">Abdul Malek Ukil Hall</option>
                </select>
            </div>

            <div class="form-group">
                <label for="floor" class="text-gray-700">Floor</label>
                <select id="floor" name="floor" required>
                    <option value="">Select Floor</option>
                    <option value="1st Floor">1st Floor</option>
                    <option value="2nd Floor">2nd Floor</option>
                    <option value="3rd Floor">3rd Floor</option>
                    <option value="4th Floor">4th Floor</option>
                    <option value="5th Floor">5th Floor</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="hallRoom" class="text-gray-700">Hall Room</label>
                <select id="hallRoom" name="hallRoom" required>
                    <option value="">Select Hall Room</option>
                    <option value="Room 101">Room 101</option>
                    <option value="Room 102">Room 102</option>
                    <option value="Room 103">Room 103</option>
                    <option value="Room 104">Room 104</option>
                    <option value="Room 105">Room 105</option>
                    <option value="Room 106">Room 106</option>
                    <option value="Room 107">Room 107</option>
                    <option value="Room 108">Room 108</option>
                    <option value="Room 109">Room 109</option>
                    <option value="Room 110">Room 110</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="seatNumber" class="text-gray-700">Seat Number</label>
                <select id="seatNumber" name="seatNumber" required>
                    <option value="">Select Seat Number</option>
                    <option value="Seat 1">Seat 1</option>
                    <option value="Seat 2">Seat 2</option>
                    <option value="Seat 3">Seat 3</option>
                    <option value="Seat 4">Seat 4</option>
                    <option value="Seat 5">Seat 5</option>
                    <option value="Seat 6">Seat 6</option>
                </select>
            </div>
            

            <div class="form-group">
                <label for="specialNote" class="text-gray-700">Special Note</label>
                <textarea id="specialNote" name="specialNote" rows="4" placeholder="Enter any special notes or requirements" required></textarea>
            </div>

            <button type="submit" class="submit-button">Submit Application</button>
        </form>
    </section>

</body>

</html>
