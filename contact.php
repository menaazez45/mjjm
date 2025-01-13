<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Support</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            background: #fff;
            padding: 30px;
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .message-status {
            text-align: center;
            font-size: 18px;
            margin-top: 20px;
        }
        .options {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .options a {
            text-decoration: none;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 16px;
        }
        .whatsapp {
            background-color: #25d366;
        }
        .whatsapp:hover {
            background-color: #1ebc5e;
        }
        .email {
            background-color: #007bff;
        }
        .email:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Contact Support</h1>
        <div class="options">
            <a href="https://wa.me/01141199507" class="whatsapp">Contact via WhatsApp</a>
            <a href="mailto:peoblejops@gmail.com" class="email">Send Email</a>
        </div>
    </div>
</body>
</html>

<?php
// إغلاق الاتصال بقاعدة البيانات بعد الانتهاء
$conn->close();
?>
