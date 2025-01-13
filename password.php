<?php
include 'db.php'; // تضمين الاتصال بقاعدة البيانات
include 'header.php'; // تضمين الهيدر

// التحقق إذا كان النموذج قد أرسل
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // التحقق من وجود البريد الإلكتروني في قاعدة البيانات
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // إذا كان البريد الإلكتروني موجودًا في قاعدة البيانات
        $user = $result->fetch_assoc();
        $user_id = $user['id'];
        $username = $user['username'];

        // توليد رمز التحقق
        $reset_token = bin2hex(random_bytes(32));

        // تخزين الرمز في قاعدة البيانات
        $stmt = $conn->prepare("UPDATE users SET reset_token = ? WHERE id = ?");
        $stmt->bind_param("si", $reset_token, $user_id);
        $stmt->execute();

        // إرسال البريد الإلكتروني للمستخدم
        $reset_link = "http://yourwebsite.com/reset_password.php?token=" . $reset_token;
        $subject = "Reset Your Password";
        $message = "Hi $username,\n\nTo reset your password, please click the link below:\n\n$reset_link\n\nIf you did not request a password reset, please ignore this email.";
        $headers = "From: no-reply@yourwebsite.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "<script>alert('We have sent a password reset link to your email. Please check your inbox.');</script>";
        } else {
            echo "<script>alert('There was an error sending the email. Please try again later.');</script>";
        }
    } else {
        echo "<script>alert('This email address is not registered.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            margin: 80px auto;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Forgot Password</h1>
        <form action="password.php" method="post">
            <label for="email">Enter your email address:</label>
            <input type="email" id="email" name="email" required>

            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
