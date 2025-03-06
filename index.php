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
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            color: #fff;
        }

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

        h1 {
            color: #fff;
            font-size: 32px;
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
            margin: 20px 0;
        }

        a {
            display: block;
            text-decoration: none;
            padding: 15px 20px;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            border-radius: 30px;
            font-weight: 500;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        a::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0));
            transform: translate(-50%, -50%) scale(0);
            transition: transform 0.5s ease;
            border-radius: 50%;
        }

        a:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
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

        .logo {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            padding: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: float 3s ease-in-out infinite;
        }

        .logo img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }

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
    <img src="logo.png" alt="Logo" class="logo"> 
    <div class="container">
        <h1>Welcome to the Inventory System</h1>
        <ul>
            <li><a href="view_inventory.php">View Inventory</a></li>
            <li><a href="add_item.php">Add New Item</a></li>
            <li><a href="stock_in.php">Stock In</a></li>
            <li><a href="stock_out.php">Stock Out</a></li>
        </ul>
        <p>Choose an option from the menu to manage the inventory or update stock.</p>
    </div>
</body>
</html>