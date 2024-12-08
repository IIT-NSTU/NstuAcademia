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

// Fetch blank seat status from `boarding_cards` table
$sql = "SELECT student_id, student_name, hall_name, room_number, seat_number, file_path 
        FROM boarding_cards";
$result = $conn->query($sql);

// Prepare data for rendering
$hallsData = [
    'Bangamata Sheikh Fazilatunnesa Mujib Hall' => [],
    'Bangabandhu Sheikh Mujibur Rahman Hall' => [],
    'Bibi Khadiza Hall' => [],
    'Abdus Salam Hall' => [],
    'Abdul Malek Ukil Hall' => []
];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (array_key_exists($row['hall_name'], $hallsData)) {
            $hallsData[$row['hall_name']][] = [
                'studentID' => $row['student_id'],
                'studentName' => $row['student_name'],
                'roomNumber' => $row['room_number'],
                'seatNumber' => $row['seat_number'],
                'boardingCardURL' => $row['file_path']
            ];
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blank Seat Status - Hall Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 1000px;
        }

        .download-button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 4px;
        }

        .download-button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .hall-section {
            margin-bottom: 40px;
        }

        .hall-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-2xl font-bold text-center mb-6">Blank Seat Status - Hall Admin</h2>

        <div id="hallsContainer">
            <?php foreach ($hallsData as $hallName => $data): ?>
                <div class="hall-section">
                    <div class="hall-title"><?= htmlspecialchars($hallName) ?></div>
                    <table>
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Room Number</th>
                                <th>Seat Number</th>
                                <th>Boarding Card</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($data) > 0): ?>
                                <?php foreach ($data as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['studentID']) ?></td>
                                        <td><?= htmlspecialchars($item['studentName']) ?></td>
                                        <td><?= htmlspecialchars($item['roomNumber']) ?></td>
                                        <td><?= htmlspecialchars($item['seatNumber']) ?></td>
                                        <td><a href="<?= htmlspecialchars($item['boardingCardURL']) ?>" target="_blank">View Boarding Card</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No data available</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div class="text-center mt-4">
                        <button class="download-button" onclick="downloadHallListPDF('<?= htmlspecialchars($hallName) ?>', <?= htmlspecialchars(json_encode($data)) ?>)">Download Hall List (PDF)</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    <script>
        // Function to download hall list as PDF
        function downloadHallListPDF(hallName, hallData) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            doc.setFontSize(18);
            doc.text(`${hallName} - Blank Seat Status`, 14, 20);
            doc.setFontSize(12);
            doc.text('Student Details:', 14, 30);

            let y = 40;
            if (hallData.length > 0) {
                hallData.forEach((item) => {
                    doc.text(`Student ID: ${item.studentID}, Student Name: ${item.studentName}, Room Number: ${item.roomNumber}, Seat Number: ${item.seatNumber}`, 14, y);
                    y += 10;
                });
            } else {
                doc.text('No data available', 14, y);
            }

            doc.save(`${hallName}_Blank_Seat_Status.pdf`);
        }
    </script>
</body>

</html>
