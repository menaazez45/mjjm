<?php
// عنوان البريد الإلكتروني المستلم
$to = "menaazez20097@gmail.com";

// موضوع الرسالة
$subject = "Test Email";

// نص الرسالة
$message = "Hello,\n\nThis is a test email sent using PHP's mail() function.";

// رأس الرسالة (يجب أن يحتوي على From و Reply-To على الأقل)
$headers = "From: menaazez27658@gmail.com\r\n";
$headers .= "Reply-To: sender@example.com\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// إرسال البريد الإلكتروني
if (mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully.";
} else {
    echo "Failed to send email.";
}
?>
