<?php
include 'db.php';
include 'header.php';

// التحقق مما إذا كان المستخدم مسجلاً للدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// جلب الأشخاص الذين تواصلوا مع المستخدم
$sql = "
    SELECT DISTINCT 
        CASE 
            WHEN sender_id = '$user_id' THEN receiver_id 
            ELSE sender_id 
        END AS contact_id
    FROM chat_messages
    WHERE sender_id = '$user_id' OR receiver_id = '$user_id'
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Inbox</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .user {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .user:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }
        .user a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
        .user a:hover {
            color: #007bff;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Your Inbox</h1>

    <?php
    if ($result->num_rows > 0) {
        // عرض الأشخاص الذين تواصلوا مع المستخدم
        $contacts = [];
        while ($row = $result->fetch_assoc()) {
            $contacts[] = $row['contact_id'];
        }

        // جلب أسماء جميع المستخدمين دفعة واحدة
        $contacts_list = implode(',', $contacts);
        $sql2 = "SELECT id, username FROM users WHERE id IN ($contacts_list)";
        $result2 = $conn->query($sql2);

        while ($user = $result2->fetch_assoc()) {
            echo "<div class='user'>
                    <a href='chat.php?user_id={$user['id']}'>" . htmlspecialchars($user['username']) . "</a>
                  </div>";
        }
    } else {
        echo "<p>No one has contacted you yet.</p>";
    }
    ?>
</div>

</body>
</html>
