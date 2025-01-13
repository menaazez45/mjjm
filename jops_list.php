<?php
include 'db.php'; // الاتصال بقاعدة البيانات

// التحقق من وجود الفئة المختارة
$filter = isset($_GET['specialization']) ? $_GET['specialization'] : '';

// جلب البيانات من قاعدة البيانات مع التصفية
if ($filter) {
    $sql = "SELECT * FROM jobs WHERE specialization = '$filter' ORDER BY created_at DESC";
} else {
    $sql = "SELECT * FROM jobs ORDER BY created_at DESC";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
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
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .job-card h3 {
            margin: 0 0 10px;
        }
        .job-card p {
            margin: 5px 0;
        }
        .job-card button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 3px;
        }
        .job-card button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>
        <?php echo $filter ? ucfirst($filter) . ' Listings' : 'All Jobs'; ?>
    </h1>

    <!-- عرض الوظائف -->
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="job-card">
                <h3><?php echo htmlspecialchars($row['specialization']); ?></h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($row['name']); ?></p>
                <p><strong>Details:</strong> <?php echo htmlspecialchars($row['details']); ?></p>
                <button onclick="contactUser('<?php echo htmlspecialchars($row['name']); ?>')">Contact</button>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No jobs found for this category.</p>
    <?php endif; ?>

    <script>
        function contactUser(userName) {
            alert('You are contacting ' + userName + '. This feature can be implemented with a chat system.');
        }
    </script>
</body>
</html>
