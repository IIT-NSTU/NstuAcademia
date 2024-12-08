function showRegistrationBox() {
    const department = document.getElementById("department").value;
    const year = document.getElementById("year").value;
    const semester = document.getElementById("semester").value;

    // Only show the registration box if all selections are made
    if (department && year && semester) {
        document.getElementById("registrationBox").style.display = "block";
        loadCourses();
    } else {
        document.getElementById("registrationBox").style.display = "none";
    }
}

function loadCourses() {
    const courses = [
        { sl: 1, code: "CSE101", title: "Introduction to Programming", credit: 3 },
        { sl: 2, code: "CSE102", title: "Data Structures", credit: 4 },
        { sl: 3, code: "EEE101", title: "Basic Electrical Engineering", credit: 3 },
        { sl: 4, code: "BBA101", title: "Principles of Management", credit: 3 }
    ];

    const coursesTable = document.getElementById("coursesTable");
    coursesTable.innerHTML = "";  // Clear the table before adding rows

    courses.forEach(course => {
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${course.sl}</td>
            <td>${course.code}</td>
            <td>${course.title}</td>
            <td>${course.credit}</td>
            <td><input type="checkbox" name="course" value="${course.code}"></td>
        `;

        coursesTable.appendChild(row);
    });
}

function submitCourses() {
    const selectedCourses = [];
    document.querySelectorAll("input[name='course']:checked").forEach(checkbox => {
        selectedCourses.push(checkbox.value);
    });

    const resultDiv = document.getElementById("registrationResult");
    if (selectedCourses.length > 0) {
        resultDiv.innerHTML = `<p>You have registered for the following courses: ${selectedCourses.join(", ")}</p>`;
    } else {
        resultDiv.innerHTML = "<p>Please select at least one course to register.</p>";
    }
}
