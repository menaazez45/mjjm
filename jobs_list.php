<?php
include 'db.php'; // الاتصال بقاعدة البيانات
include 'header.php'; // تضمين الهيدر

// التحقق من وجود الفئة المختارة
$filter = isset($_GET['specialization']) ? $_GET['specialization'] : '';

// جلب البيانات من قاعدة البيانات مع الانضمام إلى جدول المستخدمين
$sql = $filter 
    ? "SELECT jobs.*, users.username AS user_name, users.phone AS user_phone 
       FROM jobs 
       JOIN users ON jobs.user_id = users.id 
       WHERE jobs.specialization = ? 
       ORDER BY jobs.created_at DESC"
    : "SELECT jobs.*, users.username AS user_name, users.phone AS user_phone 
       FROM jobs 
       JOIN users ON jobs.user_id = users.id 
       ORDER BY jobs.created_at DESC";

$stmt = $conn->prepare($sql);
if ($filter) {
    $stmt->bind_param("s", $filter);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            background-color: #f8f9fa;
            color: #343a40;
        }
        h1 {
            text-align: center;
            margin: 20px 0;
            font-size: 2.5em;
            color: #007bff;
        }
        .select-container {
            text-align: center;
            margin: 20px 0;
        }
        .select-container form {
            display: inline-block;
        }
        .select-container select,
        .select-container button {
            padding: 10px 15px;
            margin: 5px;
            font-size: 16px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            outline: none;
        }
        .select-container button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .select-container button:hover {
            background-color: #0056b3;
        }
        .job-card {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: flex-start;
            gap: 20px;
        }
        .job-card img {
            flex: 0 0 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #ddd;
        }
        .job-card-content {
            flex: 1;
        }
        .job-card h3 {
            margin: 0 0 10px;
            color: #343a40;
        }
        .job-card p {
            margin: 5px 0;
            color: #6c757d;
        }
        .job-card p strong {
            color: #343a40;
        }
        footer {
            text-align: center;
            padding: 20px;
            background: #f1f1f1;
            margin-top: 40px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <h1>
        <?php echo $filter ? ucfirst($filter) . ' Listings' : 'All Jobs'; ?>
    </h1>

    <!-- اختيار الفئة -->
    <div class="select-container">
        <form action="jobs_list.php" method="GET">
            <label for="specialization">Choose a Specialization:</label>
            <select name="specialization" id="specialization">
                <option value="">All Specializations</option>
                <?php
                $specializations = [
                    "Accounting", "Actuary", "Architect", "Baker", "Bartender", "Carpenter", "Chef", 
                    "Civil Engineer", "Content Creator", "Consultant", "Customer Service", "Data Scientist", 
                    "Database Administrator", "Dentist", "Designer", "Doctor", "Electrician", "Engineer", 
                    "Event Planner", "Farmer", "Fitness Trainer", "Game Developer", "HR Specialist", "IT Support", 
                    "Investment Analyst", "Lawyer", "Legal Advisor", "Logistics Manager", "Manager", 
                    "Marketer", "Mechanic", "Mobile Developer", "Music Producer", "Network Engineer", 
                    "Nurse", "Painter", "Pharmacist", "Photographer", "Pilot", "Plumber", "Programmer", 
                    "Psychiatrist", "Psychologist", "Receptionist", "Researcher", "Sales Representative", 
                    "Scientist", "Security Guard", "SEO Specialist", "Social Media Manager", "Sound Engineer", 
                    "Statistician", "Stock Trader", "Surgeon", "Teacher", "Translator", "Veterinarian", "Waiter", 
                    "Web Developer", "Writer"
                ];
                foreach ($specializations as $specialization) {
                    $selected = $specialization === $filter ? "selected" : "";
                    echo "<option value=\"$specialization\" $selected>$specialization</option>";
                }
                ?>
            </select>
            <button type="submit">Filter</button>
        </form>
    </div>

    <!-- عرض الوظائف -->
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="job-card">
                <!-- الصورة -->
                <?php if (!empty($row['image'])): ?>
                    <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Job Image">
                <?php endif; ?>

                <!-- النص -->
                <div class="job-card-content">
                    <h3><?php echo htmlspecialchars($row['specialization']); ?></h3>
                    <p><strong>Posted by:</strong> <?php echo htmlspecialchars($row['user_name']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($row['user_phone']); ?></p>
                    <p><strong>Details:</strong> <?php echo htmlspecialchars($row['details']); ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align: center; color: #6c757d;">No jobs found for this category.</p>
    <?php endif; ?>

    <footer>
        &copy; <?php echo date("Y"); ?> Job Listings. All rights reserved.
    </footer>
</body>
</html>
