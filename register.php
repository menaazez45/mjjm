<?php
include 'db.php';

// بدء جلسة لحماية CSRF
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// التحقق من إرسال النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // التحقق من رمز CSRF
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token!");
    }

    // تعقيم المدخلات
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // التحقق من تطابق كلمات المرور
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // تشفير كلمة المرور
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // استخدام استعلامات مُعدة
        $stmt = $conn->prepare("INSERT INTO users (username, email, phone, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $phone, $hashed_password);

        if ($stmt->execute()) {
            echo "<script>alert('Account created successfully!'); window.location.href='login.php';</script>";
        } else {
            error_log("Database error: " . $stmt->error);
            echo "<script>alert('An error occurred. Please try again later.');</script>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Register a new account with secure and easy steps. Join now to access exclusive features.">
    <meta name="robots" content="index, follow">
    <title>Secure Registration Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            text-align: center;
            color: #333;
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            form.addEventListener('submit', function (event) {
                // التحقق من تطابق كلمات المرور
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirm_password').value;

                if (password !== confirmPassword) {
                    event.preventDefault();
                    alert('Passwords do not match!');
                }
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Create Account</h1>
        <form action="register.php" method="POST">
            <!-- إضافة رمز CSRF -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required pattern="\d{10,15}" title="Enter a valid phone number.">

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>