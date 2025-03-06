<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory System</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            min-height: 100vh;
            color: #fff;
        }

        /* Header */
        header {
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        header h1 {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            color: #fff;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        header img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        /* Main Content */
        .container {
            background: rgba(255, 255, 255, 0.1);
            padding: 40px 50px;
            border-radius: 20px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 450px;
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeIn 1s ease-out forwards;
        }

        h2 {
            color: #fff;
            font-size: 28px;
            margin-bottom: 30px;
            font-weight: 600;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }

        li {
            margin: 15px 0;
        }

        a {
            display: block;
            text-decoration: none;
            padding: 15px 20px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 15px;
            font-weight: 500;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        a::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0));
            transform: translate(-50%, -50%) scale(0);
            transition: transform 0.5s ease;
            border-radius: 50%;
        }

        a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        a:hover::before {
            transform: translate(-50%, -50%) scale(1);
        }

        p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            margin-top: 20px;
            font-weight: 400;
        }

        /* Footer */
        footer {
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 15px;
            text-align: center;
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.2);
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        footer p {
            margin: 0;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
        }

        footer a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.3s ease;
        }

        footer a:hover {
            opacity: 0.8;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <img src="logo.png" alt="Logo">
        <h1>Inventory Management System</h1>
    </header>

    <!-- Main Content -->
    <div class="container">
        <h2>Dashboard</h2>
        <ul>
            <li><a href="view_inventory.php">View Inventory</a></li>
            <li><a href="add_item.php">Add New Item</a></li>
            <li><a href="stock_in.php">Stock In</a></li>
            <li><a href="stock_out.php">Stock Out</a></li>
        </ul>
        <p>Choose an option from the menu to manage the inventory or update stock.</p>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2023 Your Organization. All rights reserved. | <a href="#">Privacy Policy</a></p>
    </footer>
</body>
</html>