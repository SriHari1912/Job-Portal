<?php
// job_portal.php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f0f0f0;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
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

        main {
            flex-grow: 1;
            padding: 20px;
            text-align: center;
            animation: fadeInUp 1s ease-in-out;
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .auth-options {
            margin: 5% 0;
        }

        .auth-options .highlight {
            background-color: #1166b0;
            color: white;
            padding: 10px 20px;
            margin: 10px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .auth-options .highlight:hover {
            background-color: #286090;
            transform: translateY(-5px);
        }

        .testimonials {
            margin-top: 200px 0;
            animation: fadeIn 1s ease-in-out;
        }

        .testimonials {
            margin-top: 200px 0;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .logo-container {
            margin-top: 20px;
            overflow: hidden;
            background-color: #ffffff;
            padding: 10px 0;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .logo-slider {
            display: flex;
            width: max-content;
            animation: scrollLogos 30s linear infinite;
        }

        .logo-slider img {
            height: 50px;
            margin: 0 20px;
            transition: transform 0.3s;
        }

        .logo-slider img:hover {
            transform: scale(1.1);
        }

        @keyframes scrollLogos {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        footer {
            display: flex;
            justify-content: center;
            background-color: #000000;
            color: white;
            padding: 10px 0;
            animation: slideUp 1s ease-in-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
            }

            to {
                transform: translateY(0);
            }
        }

        footer a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            transition: color 0.3s, transform 0.3s;
        }

        footer a:hover {
            text-decoration: underline;
            color: #5cb85c;
            transform: translateY(-3px);
        }

        .testimonial {
            font-size: 1.2em;
            margin: 20px;
            padding: 20px;
            border: 1px solid #0b9e00;
            border-radius: 5px;
            background-color: #000000;
            transition: opacity 1s ease-in-out;
        }

        .hidden {
            opacity: 0;
        }

        .company-name {
            color: #00b13e;
            font-weight: bold;
        }

        .story {
            color: #ffffff;
        }
    </style>
</head>

<body>
    <header>
        <div class="sidebar-menu" onclick="toggleMenu()">☰</div>
        <h1 class="title">Job Portal</h1>
        <div class="auth-buttons">
            <button onclick="location.href='register.php'">Sign Up</button>
            <button onclick="location.href='login.php'">Log In</button>
        </div>
    </header>

    <div class="sidebar" id="sidebar">
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Sign Up</a>
        <a href="adminlogin.php">Admin Login</a>
    </div>

    <main>
        <div class="auth-options">
            <h2>Sign Up / Log In</h2>
            <div class="buttons">
                <button class="highlight" onclick="location.href='register.php'">Sign Up</button>
                <button class="highlight" onclick="location.href='login.php'">Log In</button>
            </div>
        </div>

        <section class="testimonials">
            <h2>Success Stories</h2>
            <div id="testimonial" class="testimonial"></div>

            <div class="logo-container">
                <div class="logo-slider">
                    <img src="assets\images\wipro.png" alt="Wipro">
                    <img src="assets\images\microsoft.png" alt="Microsoft">
                    <img src="assets\images\google_png.png" alt="Google">
                    <img src="assets\images\amazon.png" alt="Amazon">
                    <img src="assets\images\ibm.png" alt="IBM">
                    <img src="assets\images\apple.png" alt="Apple">
                    <img src="assets\images\oracle.png" alt="Oracle">
                    <img src="assets\images\dell.png" alt="Dell">
                    <img src="assets\images\samsung.png" alt="Samsung">
                    <img src="assets\images\cisco.png" alt="Cisco">
                    <img src="assets\images\Tata_Consultancy_Services_Logo.svg.png" alt="TCS">
                    <img src="assets\images\infosys.png" alt="Infosys">
                    <img src="assets\images\accenture.png" alt="Accenture">
                    <img src="assets\images\capgemini.png" alt="Capgemini">
                    <img src="assets\images\hcl.png" alt="HCL">
                    <img src="assets\images\sap.png" alt="SAP">
                    <img src="assets\images\adobe.png" alt="Adobe">
                    <img src="assets\images\salesforce.png" alt="Salesforce">
                    <img src="assets\images\intel.png" alt="Intel">
                    <img src="assets\images\navidia.png" alt="NVIDIA">
                </div>
            </div>
        </section>
    </main>

    <footer>
        <a href="privacy.php">Privacy Policy</a>
        <a href="terms.php">Terms of Use</a>
        <a href="contact.php">Contact Us</a>
    </footer>

    <script>
        function toggleMenu() {
            var sidebar = document.getElementById("sidebar");
            sidebar.style.display = sidebar.style.display === "none" || sidebar.style.display === "" ? "flex" : "none";
        }

        var testimonials = [{
                company: "Wipro",
                story: "Wipro transformed from a vegetable oil company into a global IT services leader. Its success is built on expanding its services into consulting and technology, driving growth through innovation and a global delivery model."
            },
            {
                company: "Microsoft",
                story: "Microsoft became a tech giant with its software products like Windows and Office, and later expanded into cloud computing with Azure, which has been a major growth driver."
            },
            {
                company: "Google",
                story: "Google’s success stems from its dominance in search engines and advertising, as well as its innovation in products like Android, Google Cloud, and acquisitions such as YouTube."
            },
            {
                company: "Amazon",
                story: "Amazon started as an online bookstore and grew into the world’s largest e-commerce platform, with successful expansions into cloud computing (AWS), streaming (Amazon Prime), and hardware (Alexa)."
            },
            {
                company: "IBM",
                story: "IBM has evolved from hardware manufacturing to becoming a leader in cloud computing and AI. Its success is highlighted by its contributions to technology innovation and enterprise solutions."
            },
            {
                company: "Apple",
                story: "Apple’s success is driven by its innovative products like the iPhone, iPad, and Mac. Its emphasis on design, user experience, and a robust ecosystem of hardware, software, and services has cemented its market position."
            },
            {
                company: "Oracle",
                story: "Oracle is known for its database software and enterprise solutions. Its success is rooted in its strong focus on database technology, cloud computing, and acquisitions to expand its product offerings."
            },
            {
                company: "Dell",
                story: "Dell's success is attributed to its direct-to-consumer model, which allowed it to offer customized computing solutions and competitive pricing. The company's expansion into servers, storage, and IT services further fueled its growth."
            },
            {
                company: "Samsung",
                story: "Samsung’s success spans consumer electronics, semiconductors, and telecommunications. Its leadership in smartphone innovation, particularly with its Galaxy series, and advancements in display technology have been key drivers."
            },
            {
                company: "Cisco",
                story: "Cisco’s success lies in its networking hardware and software solutions. The company has been a major player in shaping the modern internet infrastructure and continues to lead in networking technologies."
            },
            {
                company: "Tata Consultancy Services (TCS)",
                story: "TCS grew from a small IT services firm into a global leader in IT consulting and services. Its success is attributed to its focus on client relationships, innovation, and a broad range of services."
            },
            {
                company: "Infosys",
                story: "Infosys became a major IT services provider by leveraging its global delivery model and focusing on technology consulting and outsourcing. Its commitment to innovation and client satisfaction has been pivotal."
            },
            {
                company: "Accenture",
                story: "Accenture’s success comes from its comprehensive consulting services, spanning strategy, technology, and operations. Its ability to adapt to changing market demands and focus on digital transformation has been key."
            },
            {
                company: "Capgemini",
                story: "Capgemini grew by providing consulting, technology services, and digital transformation solutions. Its success is marked by its global presence and strong focus on innovation and client solutions."
            },
            {
                company: "HCL Technologies",
                story: "HCL’s success is rooted in its focus on IT services and solutions, with a strong emphasis on technology innovation and a client-centric approach, contributing to its growth and market presence."
            },
            {
                company: "SAP",
                story: "SAP’s success is driven by its enterprise resource planning (ERP) software. The company’s focus on integrating business processes and expanding into cloud solutions has solidified its position in the enterprise software market."
            },
            {
                company: "Adobe",
                story: "Adobe became a leader in creative software with products like Photoshop and Illustrator. Its successful transition to a subscription model with Adobe Creative Cloud has been a major growth driver."
            },
            {
                company: "Salesforce",
                story: "Salesforce revolutionized customer relationship management (CRM) with its cloud-based platform. Its success is highlighted by its growth in cloud services and expansion into various enterprise solutions."
            },
            {
                company: "Intel",
                story: "Intel’s success is tied to its dominance in semiconductor technology. Innovations in microprocessors and chips have established Intel as a key player in the technology and computing industries."
            },
            {
                company: "NVIDIA",
                story: "NVIDIA’s success comes from its leadership in graphics processing units (GPUs) and advancements in AI and gaming technology. Its GPUs are widely used in gaming, professional visualization, and data centers."
            }
        ];

        var currentIndex = 0;

        function showTestimonial() {
            var testimonialElement = document.getElementById('testimonial');
            testimonialElement.classList.add('hidden');
            setTimeout(function() {
                testimonialElement.innerHTML = '<p class="story">' + testimonials[currentIndex].story + '</p><p class="company-name">- ' + testimonials[currentIndex].company + '</p>';
                testimonialElement.classList.remove('hidden');
            }, 1000);

            currentIndex = (currentIndex + 1) % testimonials.length;
        }

        showTestimonial();
        setInterval(showTestimonial, 5000);
    </script>
</body>

</html>