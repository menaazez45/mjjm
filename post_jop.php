<?php
include 'db.php';
include 'header.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $specialization = $_POST['specialization'];
    $details = $_POST['details'];

    $sql = "INSERT INTO jobs (name, specialization, details) VALUES ('$name', '$specialization', '$details')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Job posted successfully!');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Job</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }
        input, select, textarea, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Post a Job</h1>
        <form action="post_job.php" method="post">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="specialization">Specialization:</label>
            <select id="specialization" name="specialization" required>
                <?php
                $specializations = [
                    "Programmer", "Designer", "Writer", "Marketer", "Accountant", "Teacher", "Translator", "Engineer",
                    "Data Scientist", "Consultant", "Manager", "Photographer", "Doctor", "Nurse", "Pharmacist",
                    "Lawyer", "Legal Advisor", "Architect", "Web Developer", "Mobile Developer", "SEO Specialist",
                    "Content Creator", "Social Media Manager", "HR Specialist", "Sales Representative", "Mechanic",
                    "Electrician", "Plumber", "Carpenter", "Painter", "Chef", "Baker", "Bartender", "Waiter", 
                    "Event Planner", "Fitness Trainer", "Psychologist", "Counselor", "Veterinarian", "Farmer", 
                    "Security Guard", "Receptionist", "Customer Service", "IT Support", "Network Engineer", 
                    "Database Administrator", "Game Developer", "3D Artist", "Animator", "Video Editor", 
                    "Music Producer", "Sound Engineer", "Pilot", "Flight Attendant", "Logistics Manager", 
                    "Supply Chain Specialist", "Researcher", "Scientist", "Biotechnologist", "Chemical Engineer",
                    "Civil Engineer", "Mechanical Engineer", "Petroleum Engineer", "Environmental Scientist",
                    "Marine Biologist", "Teacher (Math)", "Teacher (Science)", "Teacher (English)", 
                    "Teacher (History)", "Teacher (Geography)", "Teacher (Physics)", "Teacher (Chemistry)", 
                    "Teacher (Biology)", "Teacher (Computer Science)", "Teacher (Physical Education)", 
                    "Teacher (Art)", "Teacher (Music)", "Teacher (Drama)", "Sports Coach", "Dentist", 
                    "Orthodontist", "Surgeon", "Radiologist", "Dermatologist", "Oncologist", "Optometrist", 
                    "Librarian", "Archivist", "Historian", "Economist", "Actuary", "Statistician", "Banker",
                    "Investment Analyst", "Stock Trader", "Auditor", "Financial Advisor", "Entrepreneur"
                ];

                foreach ($specializations as $specialization) {
                    echo "<option value=\"$specialization\">$specialization</option>";
                }
                ?>
            </select>

            <label for="details">Details:</label>
            <textarea id="details" name="details" rows="5" required></textarea>

            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
