<?php
include 'db.php'; // الاتصال بقاعدة البيانات

// التحقق من معرف المنشور
if (!isset($_GET['post_id'])) {
    die("Post ID is required.");
}

$post_id = intval($_GET['post_id']);

// جلب بيانات المنشور
$query = "SELECT * FROM jobs WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    die("Post not found.");
}

// معالجة النموذج عند الإرسال
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $specialization = $_POST['specialization'];
    $details = $_POST['details'];

    // معالجة الصورة إذا تم تحميلها
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = 'uploads/'; // مسار مجلد الصور
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $upload_path = $upload_dir . $image_name;

        // التحقق من صحة الملف وحفظه
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            // تحديث الصورة في قاعدة البيانات
            $update_query = "UPDATE jobs SET specialization = ?, details = ?, image = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("sssi", $specialization, $details, $upload_path, $post_id);
        } else {
            echo "<script>alert('Failed to upload image.');</script>";
        }
    } else {
        // تحديث بدون صورة
        $update_query = "UPDATE jobs SET specialization = ?, details = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ssi", $specialization, $details, $post_id);
    }

    if ($update_stmt->execute()) {
        echo "<script>alert('Post updated successfully.'); window.location.href = 'profile.php?user_id=" . $post['user_id'] . "';</script>";
    } else {
        echo "<script>alert('Failed to update post.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .container h1 {
            margin-bottom: 20px;
            color: #007bff;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Post</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="specialization">Specialization:</label>
                <input type="text" name="specialization" id="specialization" value="<?php echo htmlspecialchars($post['specialization']); ?>" required>
            </div>
            <div class="form-group">
                <label for="details">Details:</label>
                <textarea name="details" id="details" rows="5" required><?php echo htmlspecialchars($post['details']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="image">Update Image:</label>
                <input type="file" name="image" id="image">
            </div>
            <?php if (!empty($post['image'])): ?>
                <div class="form-group">
                    <p>Current Image:</p>
                    <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Current Image" style="max-width: 100%; border-radius: 8px;">
                </div>
            <?php endif; ?>
            <button type="submit">Update Post</button>
        </form>
    </div>
</body>
</html>