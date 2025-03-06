<?php
// Include database connection
include('db_connection.php');

// Handle stock update
if (isset($_POST['update_stock'])) {
    $item_id = $_POST['item_id'];
    $new_quantity = $_POST['new_quantity'];

    if (!empty($item_id) && is_numeric($new_quantity)) {
        // Update the quantity in the database
        $sql = "UPDATE items SET quantity = quantity + ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $new_quantity, $item_id);
        $stmt->execute();

        // Redirect back to inventory page after update
        header("Location: view_inventory.php");
        exit();
    }
}

// Fetch all categories
$sql = "SELECT id, category_name FROM categories";
$result = $conn->query($sql);
$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

// Fetch all items with their categories
$sql = "SELECT id, item_name, quantity, category_id FROM items";
$result = $conn->query($sql);
$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Stock Quantity</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        function filterItems() {
            var category = document.getElementById("category").value;
            var items = document.getElementById("item_id").options;
            
            for (var i = 1; i < items.length; i++) {
                if (items[i].getAttribute("data-category_id") === category || category === "") {
                    items[i].style.display = "block";
                } else {
                    items[i].style.display = "none";
                }
            }
            document.getElementById("item_id").value = ""; // Reset selection
        }
    </script>
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
            max-width: 600px;
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

        form input, form select, form button {
            padding: 12px;
            font-size: 1rem;
            border-radius: 30px;
            margin: 10px 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #333;
            outline: none;
            transition: all 0.3s ease;
        }

        form input::placeholder, form select::placeholder {
            color: #666;
        }

        form input:focus, form select:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 8px rgba(106, 17, 203, 0.5);
        }

        form button {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        form button:hover {
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
            margin-top: 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .back-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Update Stock Quantity</h1>

        <form method="POST" action="stock_in.php">
            <select id="category" onchange="filterItems()" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo $category['category_name']; ?></option>
                <?php endforeach; ?>
            </select>

            <select name="item_id" id="item_id" required>
                <option value="">Select Item</option>
                <?php foreach ($items as $item): ?>
                    <option value="<?php echo $item['id']; ?>" data-category_id="<?php echo $item['category_id']; ?>">
                        <?php echo $item['item_name']; ?> - Current Quantity: <?php echo $item['quantity']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="number" name="new_quantity" min="1" placeholder="Enter new quantity" required>
            <button type="submit" name="update_stock">Update Stock</button>
        </form>

        <a href="Dashboard.php" class="back-button">Back to Main Menu</a>
    </div>

</body>
</html>