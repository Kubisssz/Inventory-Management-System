<?php
// Include database connection
include('db_connection.php');

// Handle item submission
if (isset($_POST['add_item'])) {
    $category_name = $_POST['category']; // Selected category name
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $brand = $_POST['brand'];
    $type = $_POST['type'];

    // Check if all fields are filled
    if (!empty($category_name) && !empty($item_name) && !empty($description) && !empty($quantity) && !empty($price) && !empty($brand) && !empty($type)) {
        // Fetch category_id based on category_name
        $sql = "SELECT id FROM categories WHERE category_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $category_name);
        $stmt->execute();
        $result = $stmt->get_result();
        $category = $result->fetch_assoc();

        if ($category) {
            $category_id = $category['id'];

            // Insert new item into the database
            $sql = "INSERT INTO items (category_id, item_name, description, quantity, price, brand, type) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issiiss", $category_id, $item_name, $description, $quantity, $price, $brand, $type);
            $stmt->execute();

            // Redirect to refresh the page
            header("Location: view_inventory.php?category=" . urlencode($category_name));
            exit();
        } else {
            echo "<p style='color: red;'>Error: Category not found!</p>";
        }
    } else {
        echo "<p style='color: red;'>Please fill in all fields!</p>";
    }
}

// Fetch categories from the database for the dropdown
$sql = "SELECT category_name FROM categories";
$result = $conn->query($sql);
$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row['category_name'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Your existing CSS styles */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            color: #fff;
        }

        .container {
            background: rgba(255, 255, 255, 0.1);
            padding: 40px 50px;
            border-radius: 20px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 90%;
            max-width: 700px;
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeIn 1s ease-out forwards;
            margin-top: 20px;
        }

        h1 {
            color: #fff;
            font-size: 32px;
            margin-bottom: 30px;
            font-weight: 600;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .add-item-form input,
        .add-item-form select {
            padding: 12px;
            font-size: 1rem;
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            width: 100%;
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.8);
            color: #333;
            outline: none;
            transition: all 0.3s ease;
        }

        .add-item-form input::placeholder,
        .add-item-form select::placeholder {
            color: #666;
        }

        .add-item-form input:focus,
        .add-item-form select:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 8px rgba(106, 17, 203, 0.5);
        }

        .add-item-form button {
            padding: 12px 24px;
            font-size: 1rem;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .add-item-form button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        .back-button {
            display: inline-block;
            text-decoration: none;
            padding: 12px 24px;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            border-radius: 30px;
            margin-top: 40px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .back-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
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

        /* Responsive Styles */
        @media screen and (max-width: 768px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 2rem;
            }

            .add-item-form input,
            .add-item-form select {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Add New Item</h1>

        <!-- Form to Add Item -->
        <form class="add-item-form" method="POST" action="add_item.php">
            <select name="category" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                <?php endforeach; ?>
            </select>

            <input type="text" name="item_name" placeholder="Item Name" required>
            <input type="text" name="description" placeholder="Description" required>
            <input type="number" name="quantity" placeholder="Quantity" required>
            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <input type="text" name="brand" placeholder="Brand" required>
            <input type="text" name="type" placeholder="Type" required>

            <button type="submit" name="add_item">Add Item</button>
        </form>

        <!-- Back Button -->
        <a href="Dashboard.php" class="back-button">Back to Main Menu</a>
    </div>

</body>
</html>