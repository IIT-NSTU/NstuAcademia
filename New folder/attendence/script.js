function calculateAttendance() {
    const totalClasses = [39, 13, 52, 13, 52, 26, 26, 26, 26, 78];
    const attendedClasses = [38, 11, 48, 13, 46, 26, 26, 26, 24, 74];
    let percentages = totalClasses.map((total, i) => (attendedClasses[i] / total * 100).toFixed(2) + '%');
    
    let result = 'Attendance Percentage: <br>';
    percentages.forEach((percent, index) => {
        result += `Course ${index + 1}: ${percent} <br>`;
    });

    document.getElementById("attendanceResult").innerHTML = result;
}
