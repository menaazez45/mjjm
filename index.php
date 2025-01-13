<?php
include 'db.php';
include 'header.php';

// التحقق من وجود الفئة المختارة وتنظيف المدخل
$filter = isset($_GET['specialization']) ? htmlspecialchars($_GET['specialization']) : '';

// جلب البيانات من قاعدة البيانات مع استخدام استعلامات مُعدّة
if ($filter) {
    $stmt = $conn->prepare(
        "SELECT jobs.*, users.username AS user_name, users.phone, jobs.image FROM jobs
        JOIN users ON jobs.user_id = users.id
        WHERE jobs.specialization = ? 
        ORDER BY jobs.created_at DESC"
    );
    $stmt->bind_param("s", $filter);
} else {
    $stmt = $conn->prepare(
        "SELECT jobs.*, users.username AS username, users.phone, jobs.image FROM jobs
        JOIN users ON jobs.user_id = users.id
        ORDER BY jobs.created_at DESC"
    );
}
$stmt->execute();
$result = $stmt->get_result();

// قائمة التخصصات
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
    "Business Analyst", "Software Tester", "Security Analyst", "Investment Banker", "Fund Manager",
    "Marketing Strategist", "UX/UI Designer", "Game Designer", "Data Analyst", "Voice Actor",
    "Copywriter", "Brand Manager", "Operations Manager", "Content Writer", "Lead Developer",
    "Network Administrator", "System Administrator", "Hardware Engineer", "Web Designer", 
    "Database Analyst", "Research Assistant", "Consulting Specialist", "Health Consultant", 
    "Nutritional Consultant", "Fitness Expert", "Life Coach", "Public Speaker", "Journalist", 
    "Author", "Lecturer", "Translator (Legal)", "Marketing Coordinator", "Visual Designer", 
    "Art Director", "Social Media Influencer", "Fitness Coach", "Personal Shopper", "Hairstylist",
    "Barber", "Nail Technician", "Makeup Artist", "Chef (Italian)", "Chef (French)", "Food Critic",
    "Interior Designer", "Customer Support Specialist", "Retail Manager", "Tourism Guide",
    "Photographer (Wedding)", "Photographer (Travel)", "Drone Operator", "Film Director", 
    "Video Producer", "Sound Technician", "DJ", "Sound Editor", "Motion Graphic Designer",
    "Fashion Designer", "Fashion Model", "Jewelry Designer", "Caterer", "Personal Assistant",
    "Freelancer", "Event Host", "Wedding Planner", "Legal Consultant", "Political Analyst", 
    "Investment Consultant", "Philanthropy Consultant", "Research Analyst", "Product Designer", 
    "Tech Support Specialist", "Customer Service Manager", "Editor", "Fashion Blogger", "Copy Editor",
    "Technical Writer", "Voice-over Artist", "Brand Ambassador", "Public Relations Consultant",
    "Interpreter", "Private Investigator", "Dietician", "Pharmaceutical Sales", "Travel Consultant"
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
    <meta name="google-site-verification" content="AimtbowMrhXk8HzLf1Xk1ygTE8Zl8eD0iVjCxM43rs4" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .job-card {
            display: flex;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            justify-content: space-between;
        }
        .job-card img {
            width: 100px; /* حجم الصورة الصغيرة */
            height: 100px;
            object-fit: cover; /* لضمان تناسق الصورة */
            border-radius: 5px;
            margin-right: 20px;
        }
        .job-card-details {
            flex-grow: 1;
        }
        select, button {
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .select-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .no-jobs {
            text-align: center;
            color: #555;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <h1>
        <?= $filter ? ucfirst($filter) . ' Listings' : 'All'; ?>
    </h1>

    <div class="select-container">
        <form action="jobs_list.php" method="GET">
            <label for="specialization">Choose a Specialization:</label>
            <select name="specialization" id="specialization">
                <option value="">All Specializations</option>
                <?php foreach ($specializations as $specialization): ?>
                    <option value="<?= htmlspecialchars($specialization); ?>" <?= ($specialization == $filter) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($specialization); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Filter</button>
        </form>
    </div>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="job-card">
                <!-- عرض صورة الوظيفة -->
                <img src="<?= htmlspecialchars($row['image']); ?>" alt="Job Image">

                <div class="job-card-details">
                    <h3><?= htmlspecialchars($row['specialization']); ?></h3>
                    <p><strong>Name:</strong> <?= htmlspecialchars($row['username']); ?></p>
                    <p><strong>Phone:</strong> <?= isset($row['phone']) ? htmlspecialchars($row['phone']) : 'N/A'; ?></p>
                    <p><strong>Details:</strong> <?= htmlspecialchars($row['details']); ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="no-jobs">No jobs found. Please try a different specialization.</p>
    <?php endif; ?>
</body>
</html>
