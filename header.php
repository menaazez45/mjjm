<?php
session_start();

$isLoggedIn = isset($_SESSION['user_id']); // التحقق مما إذا كان المستخدم مسجل الدخول
$username = $isLoggedIn ? $_SESSION['username'] : ''; // اسم المستخدم إذا كان مسجل الدخول
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Job Listings Platform'; ?></title>
    <style>
        /* تصميم عام للصفحة */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 15px 20px;
            text-align: center;
        }
        header h1 {
            margin: 0;
            font-size: 24px;
        }
        
        /* تصميم القائمة الجانبية */
        nav {
            height: 100%;
            width: 0;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #333;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }

        nav a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 25px;
            color: white;
            display: block;
            transition: 0.3s;
        }

        nav a:hover {
            background-color: #007bff;
        }

        /* زر لإظهار القائمة */
        .menu-btn {
            font-size: 30px;
            cursor: pointer;
            color: white;
            padding: 10px 15px;
            background-color: #0056b3;
            border: none;
            margin: 15px;
            border-radius: 5px;
        }

        .menu-btn:hover {
            background-color: #007bff;
        }

        /* عند النقر لفتح القائمة */
        nav.open {
            width: 250px;
        }

        /* تصميم القائمة عندما تكون على الشاشات الكبيرة */
        @media screen and (min-width: 600px) {
            nav {
                position: static;
                width: auto;
                height: auto;
                padding-top: 0;
            }

            .menu-btn {
                display: none;
            }

            nav a {
                display: inline-block;
                padding: 15px 20px;
            }
        }

        /* النص في أعلى الصفحة */
        .welcome {
            color: white;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <header>
        <h1>People Listings Platform</h1>
    </header>

    <!-- زر لإظهار القائمة الجانبية في الموبايل -->
    <button class="menu-btn" onclick="toggleNav()">☰ Menu</button>

    <!-- القائمة الجانبية -->
    <nav id="sidebar">
        <a href="index.php">Home</a>
        <a href="contact.php">Contact</a>
        <?php if ($isLoggedIn): ?>
            <span class="welcome">Welcome, <?php echo htmlspecialchars($username); ?>!</span>
            <a href="profile.php">Profile</a>
            <a href="post_job.php">Post</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>

    <script>
        // وظيفة لفتح وإغلاق القائمة الجانبية على الهواتف
        function toggleNav() {
            var nav = document.getElementById('sidebar');
            nav.classList.toggle('open');
        }
    </script>
</body>
</html>