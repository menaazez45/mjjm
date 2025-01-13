<?php
include 'db.php'; // الاتصال بقاعدة البيانات

// التحقق من معرف المنشور
if (!isset($_GET['post_id'])) {
    die("Post ID is required.");
}

$post_id = intval($_GET['post_id']);

// جلب المنشور لتأكيد وجوده
$query = "SELECT * FROM jobs WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    die("Post not found.");
}

// حذف المنشور
$delete_query = "DELETE FROM jobs WHERE id = ?";
$delete_stmt = $conn->prepare($delete_query);
$delete_stmt->bind_param("i", $post_id);

if ($delete_stmt->execute()) {
    echo "<script>alert('Post deleted successfully.'); window.location.href = 'profile.php?user_id=" . $post['user_id'] . "';</script>";
} else {
    echo "<script>alert('Failed to delete post.'); window.history.back();</script>";
}
?>
