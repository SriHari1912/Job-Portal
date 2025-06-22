<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>

    <!-- Link to your CSS file -->
    <link rel="stylesheet" href="style.css">

    <!-- Font Awesome CSS (Ensure you have an internet connection or the file locally hosted) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 0;
            /* Removed padding */
            margin: 0;
            /* Ensure there's no margin around the body as well */
        }


        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #000000;
            color: white;
            animation: slideDown 1s ease-in-out;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
            }

            to {
                transform: translateY(0);
            }
        }

        .title {
            text-align: center;
            flex-grow: 1;
            font-size: 24px;
            animation: bounceIn 1.5s ease-in-out;
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.5);
                opacity: 0;
            }

            60% {
                transform: scale(1.2);
                opacity: 1;
            }

            100% {
                transform: scale(1);
            }
        }

        .auth-buttons button {
            margin-left: 10px;
            padding: 10px;
            background-color: #5cb85c;
            color: white;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .auth-buttons button:hover {
            background-color: #4cae4c;
            transform: scale(1.1);
        }

        .sidebar-menu {
            cursor: pointer;
            font-size: 24px;
            margin-right: 10px;
            transition: transform 0.3s;
        }

        .sidebar-menu:hover {
            transform: rotate(90deg);
            color: #5cb85c;
        }

        .sidebar {
            display: none;
            flex-direction: column;
            position: absolute;
            top: 50px;
            left: 0;
            background-color: #000000;
            width: 200px;
            padding: 20px;
            border-radius: 5px;
            animation: slideInLeft 0.5s ease-in-out;
        }

        @keyframes slideInLeft {
            from {
                transform: translateX(-100%);
            }

            to {
                transform: translateX(0);
            }
        }

        .sidebar a {
            color: white;
            padding: 10px 0;
            text-decoration: none;
            transition: background-color 0.3s, padding-left 0.3s;
            border-radius: 5px;
        }

        .sidebar a:hover {
            background-color: #575757;
            padding-left: 10px;
            color: #5cb85c;
            border-radius: 5px;
        }

        h1 {
            text-align: center;
        }

        .contact-info {
            text-align: center;
            margin-top: 20px;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            margin-top: 20px;

        }

        .social-icons a {
            margin: 0 15px;
            text-decoration: none;
            font-size: 32px;
            /* Adjust size as needed */
            background: linear-gradient(45deg, green, red);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Hover Effect to show the gradient color more clearly */
        .social-icons a:hover {
            background: linear-gradient(45deg, blue, pink);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body>
    <header>
        <div class="sidebar-menu" onclick="toggleMenu()">☰</div>
        <h1 class="title">Skill Bridge</h1>
        <div class="auth-buttons">

        </div>
    </header>

    <div class="sidebar" id="sidebar">
        <a href="dashboard.php">Home</a>
        <a href="user_profile.php">Profile</a>
        <a href="job_listing.php">Job Listing</a>
        <a href="register.php">Notifications</a>
        <a href="resume_builder.php">Resume Builder</a>
        <a href="view_resume.php">Your Resume</a>
        <a href="tracking.php">Application Tracking</a>
        <a href="contactus.php">Contact Us</a>
        <a href="logout.php">Logout</a>
    </div>
    <h1>Contact Us</h1>
    <div class="contact-info">
        <p>If you have any questions, feel free to reach out to us through our social media channels or email us directly!</p>
    </div>

    <div class="social-icons">
        <a href="https://www.instagram.com/yourprofile" target="_blank" title="Instagram">
            <i class="fab fa-instagram"></i>
        </a>
        <a href="https://twitter.com/yourprofile" target="_blank" title="Twitter">
            <i class="fab fa-twitter"></i>
        </a>
        <a href="https://wa.me/yourwhatsappnumber" target="_blank" title="WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
        <a href="mailto:youremail@example.com" title="Email">
            <i class="fas fa-envelope"></i>
        </a>
    </div>
</body>
<script>
    // Toggle the profile update form
    document.getElementById('toggle-update-form').addEventListener('click', function() {
        var form = document.getElementById('update-profile-form');
        form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
    });

    function toggleMenu() {
        const sidebar = document.getElementById('sidebar');
        sidebar.style.display = sidebar.style.display === 'none' || sidebar.style.display === '' ? 'flex' : 'none';
    }
</script>

</html>