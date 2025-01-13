<?php
include 'db.php'; // الاتصال بقاعدة البيانات
include 'header.php'; // تضمين الهيدر
if (!isset($_SESSION['user_id'])) {
    // إذا لم يكن المستخدم مسجلاً للدخول، توجيههم إلى صفحة تسجيل الدخول
    header("Location: login.php");
    exit();  // إنهاء التنفيذ بعد التوجيه
}
// الحصول على معرف المستخدم (يمكن أن يكون مستخرجا من جلسة تسجيل الدخول)
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 1; // مثال: ID المستخدم = 1

// جلب معلومات المستخدم
$user_query = "SELECT * FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();

// جلب منشورات المستخدم
$posts_query = "SELECT * FROM jobs WHERE user_id = ? ORDER BY created_at DESC";
$posts_stmt = $conn->prepare($posts_query);
$posts_stmt->bind_param("i", $user_id);
$posts_stmt->execute();
$posts_result = $posts_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #343a40;
        }
        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .profile-header h1 {
            margin-bottom: 10px;
            color: #007bff;
        }
        .profile-header p {
            color: #6c757d;
        }
        .post-card {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .post-card img {
            max-width: 120px;
            max-height: 120px;
            margin-right: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        .post-card-content {
            flex-grow: 1;
        }
        .post-card h3 {
            margin: 0 0 10px;
            color: #343a40;
        }
        .post-card p {
            margin: 5px 0;
            color: #6c757d;
        }
        .post-card-actions {
            text-align: right;
            margin-top: 10px;
        }
        .post-card-actions button {
            padding: 5px 10px;
            margin-left: 5px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .edit-btn {
            background-color: #ffc107;
            color: white;
        }
        .edit-btn:hover {
            background-color: #e0a800;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- معلومات المستخدم -->
        <div class="profile-header">
            <h1><?php echo htmlspecialchars($user['username']); ?>'s Profile</h1>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
        </div>

        <!-- منشورات المستخدم -->
        <h2>Posts</h2>
        <?php if ($posts_result->num_rows > 0): ?>
            <?php while ($post = $posts_result->fetch_assoc()): ?>
                <div class="post-card">
                    <!-- عرض الصورة إذا كانت موجودة -->
                    <?php if (!empty($post['image'])): ?>
                        <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image">
                    <?php endif; ?>
                    <div class="post-card-content">
                        <h3><?php echo htmlspecialchars($post['specialization']); ?></h3>
                        <p><?php echo htmlspecialchars($post['details']); ?></p>
                        <p><strong>Posted on:</strong> <?php echo htmlspecialchars($post['created_at']); ?></p>
                        <div class="post-card-actions">
                            <!-- زر التعديل -->
                            <button class="edit-btn" onclick="editPost(<?php echo $post['id']; ?>)">Edit</button>
                            <!-- زر الحذف -->
                            <button class="delete-btn" onclick="deletePost(<?php echo $post['id']; ?>)">Delete</button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No posts found.</p>
        <?php endif; ?>
    </div>

    <script>
        // دالة التعديل
        function editPost(postId) {
            window.location.href = `edit_post.php?post_id=${postId}`;
        }

        // دالة الحذف
        function deletePost(postId) {
            if (confirm("Are you sure you want to delete this post?")) {
                window.location.href = `delete_post.php?post_id=${postId}`;
            }
        }
    </script>
</body>
</html>