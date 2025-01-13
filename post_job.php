<?php
include 'db.php';
include 'header.php'; 
// تضمين الهيدر
if (!isset($_SESSION['user_id'])) {
    // إذا لم يكن المستخدم مسجلاً للدخول، توجيههم إلى صفحة تسجيل الدخول
    header("Location: login.php");
    exit();  // إنهاء التنفيذ بعد التوجيه
}
// احصل على معرف المستخدم الحالي، ربما من الجلسة أو من مصدر آخر
$user_id = $_SESSION['user_id'];  // فرضًا أنك تخزن معرف المستخدم في الجلسة

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $specialization = $_POST['specialization'];
    $details = $_POST['details'];

    // التعامل مع الصورة
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];
        $imageName = time() . "_" . basename($image['name']);
        $imagePath = "uploads/" . $imageName;

        // رفع الصورة إلى الخادم
        if (move_uploaded_file($image['tmp_name'], $imagePath)) {
            // إضافة البيانات إلى قاعدة البيانات مع الصورة
            $sql = "INSERT INTO jobs (specialization, details, user_id, image) 
                    VALUES ('$specialization', '$details', '$user_id', '$imagePath')";
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Posted successfully!');</script>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Error uploading image.";
        }
    } else {
        echo "Please upload a valid image.";
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
        }
        .container {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            margin: 80px auto;
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
        <form action="post_job.php" method="post" enctype="multipart/form-data">
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
                    "Investment Analyst", "Stock Trader", "Auditor", "Financial Advisor", "Entrepreneur",
                ];

                foreach ($specializations as $specialization) {
                    echo "<option value=\"$specialization\">$specialization</option>";
                }
                ?>
            </select>

            <label for="details">Details:</label>
            <textarea id="details" name="details" rows="5" required></textarea>

            <label for="image">Upload an Image:</label>
            <input type="file" id="image" name="image" accept="image/*">

            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
