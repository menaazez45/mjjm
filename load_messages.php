<?php
include 'db.php';

if (!isset($_GET['receiver_id']) || empty($_GET['receiver_id'])) {
    die("Invalid receiver ID.");
}

$receiver_id = intval($_GET['receiver_id']);
$sender_id = 1; // استبدل هذا بمعرف المستخدم الحالي (مثال فقط)

// جلب الرسائل
$stmt = $conn->prepare("
    SELECT m.message, m.sent_at, 
           s.username AS sender_name, 
           r.username AS receiver_name
    FROM messages m
    JOIN users s ON m.sender_id = s.id
    JOIN users r ON m.receiver_id = r.id
    WHERE (m.sender_id = ? AND m.receiver_id = ?) 
       OR (m.sender_id = ? AND m.receiver_id = ?)
    ORDER BY m.sent_at ASC
");
$stmt->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()): ?>
    <div class="message">
        <span class="sender"><?= htmlspecialchars($row['sender_name']); ?>:</span>
        <div class="text"><?= htmlspecialchars($row['message']); ?></div>
        <small><?= $row['sent_at']; ?></small>
    </div>
<?php endwhile; ?>
