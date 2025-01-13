<?php
include 'db.php';

// التحقق من صحة البيانات المرسلة
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['receiver_id'], $_POST['message']) && !empty($_POST['message'])) {
        $receiver_id = intval($_POST['receiver_id']);
        $message = htmlspecialchars(trim($_POST['message']));

        // حفظ الرسالة في قاعدة البيانات
        $stmt = $conn->prepare("INSERT INTO messages (receiver_id, sender_id, message, sent_at) VALUES (?, ?, ?, NOW())");
        $sender_id = 1; // استبدل هذا بمعرف المستخدم المُسجّل حالياً (إن وُجد نظام تسجيل دخول)
        $stmt->bind_param("iis", $receiver_id, $sender_id, $message);

        if ($stmt->execute()) {
            echo "Message sent successfully.";
        } else {
            echo "Failed to send message.";
        }
    } else {
        echo "All fields are required.";
    }
} else {
    echo "Invalid request.";
}
?>
