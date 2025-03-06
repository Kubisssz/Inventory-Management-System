<?php
// Include database connection
include('db_connection.php');

$search_query = isset($_GET['search']) ? $_GET['search'] : ''; // Fix undefined variable issue

if (isset($_POST['delete_item'])) {
    $item_id_to_delete = $_POST['item_id_to_delete'];

    if (!empty($item_id_to_delete)) {
        // Delete the item from the database
        $sql = "DELETE FROM items WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $item_id_to_delete);
        $stmt->execute();

        // Redirect back to the inventory page after deletion
        header("Location: view_inventory.php");
        exit();
    }
}

// Handle new category submission
if (isset($_POST['add_category'])) {
    $new_category = $_POST['new_category'];

    if (!empty($new_category)) {
        $sql = "INSERT INTO categories (category_name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $new_category);
        $stmt->execute();
        header("Location: view_inventory.php");
        exit();
    }
}

// Handle category removal
if (isset($_POST['remove_category'])) {
    if (isset($_POST['category_id_to_remove'])) {
        $category_id_to_remove = $_POST['category_id_to_remove'];

        if (!empty($category_id_to_remove)) {
            // Delete items in this category first
            $delete_items_sql = "DELETE FROM items WHERE category_id = ?";
            $stmt = $conn->prepare($delete_items_sql);
            $stmt->bind_param("i", $category_id_to_remove);
            $stmt->execute();

            // Delete the category
            $delete_category_sql = "DELETE FROM categories WHERE id = ?";
            $stmt = $conn->prepare($delete_category_sql);
            $stmt->bind_param("i", $category_id_to_remove);
            $stmt->execute();

            // Redirect back to the inventory page after deletion
            header("Location: view_inventory.php");
            exit();
        }
    } else {
        echo "Category ID to remove is not set.";
    }
}


// Fetch categories
$sql = "SELECT id, category_name FROM categories";
$result = $conn->query($sql);
$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[$row['id']] = $row['category_name']; // Use category_id as the key
}

// Fetch items for each category
$item_data = [];
foreach ($categories as $category_id => $category_name) {
    $sql = "SELECT id, item_name, description, quantity, price, brand, type FROM items WHERE category_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item_data[$category_id] = $result->fetch_all(MYSQLI_ASSOC); // Use category_id as the key
}

// Handle search functionality
$search_data = [];
if (!empty($search_query)) {
    $search_sql = "SELECT items.id, items.item_name, items.description, items.quantity, items.price, items.brand, items.type, categories.category_name 
                   FROM items 
                   LEFT JOIN categories ON items.category_id = categories.id 
                   WHERE items.item_name LIKE ? OR items.description LIKE ? OR items.brand LIKE ? OR items.type LIKE ? OR categories.category_name LIKE ?";
    $stmt = $conn->prepare($search_sql);
    $search_param = "%$search_query%";
    $stmt->bind_param("sssss", $search_param, $search_param, $search_param, $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
    $search_data = $result->fetch_all(MYSQLI_ASSOC);
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Inventory</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Original CSS Styles */
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
            max-width: 1200px;
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

        .add-category-container {
            margin-bottom: 30px;
        }

        .add-category-form input {
            padding: 12px;
            font-size: 1rem;
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            width: 250px;
            color: #333;
            background: rgba(255, 255, 255, 0.8);
            outline: none;
        }

        .add-category-form button {
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

        .add-category-form button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        .dropdown {
            margin-bottom: 30px;
        }

        select {
            font-size: 1rem;
            padding: 12px;
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.8);
            width: 250px;
            color: #333;
            cursor: pointer;
            font-weight: 500;
            outline: none;
            transition: all 0.3s ease;
        }

        select:hover {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
        }

        .category {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-bottom: 40px;
        }

        .category-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 1200px;
            padding: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        .category-title {
            font-size: 2rem;
            color: #fff;
            font-weight: 600;
            margin-bottom: 20px;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 1rem;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        th {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.05);
        }

        tr:nth-child(odd) {
            background: rgba(255, 255, 255, 0.1);
        }

        tr:hover {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
        }

        td {
            border-left: 1px solid rgba(255, 255, 255, 0.2);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
        }

        .no-items {
            text-align: center;
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border: 2px dashed rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            margin-top: 30px;
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

        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 2;
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
            .category-card {
                max-width: 100%;
            }

            .category-title {
                font-size: 1.6rem;
            }

            h1 {
                font-size: 2rem;
            }

            table {
                width: 100%;
            }
        }

        /* New Styles for Delete Button and Search Bar */
        .delete-button {
            background: #ff4d4d;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .delete-button:hover {
            background: #cc0000;
        }
        .search-container {
            margin-bottom: 50px;
        }
        .search-container input {
            padding: 12px;
            font-size: 1rem;
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            width: 300px;
            color: #333;
            background: rgba(255, 255, 255, 0.8);
            outline: none;
        }
        .search-container button {
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
        .search-container button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>View Inventory</h1>

        <!-- Form to add new category -->
        <div class="add-category-container">
            <form class="add-category-form" method="POST" action="view_inventory.php">
                <input type="text" name="new_category" placeholder="Enter new category name" required>
                <button type="submit" name="add_category">Add Category</button>
            </form>
        </div>

        <!-- Search Bar -->
        <div class="search-container">
            <form method="GET" action="view_inventory.php">
            <input type="text" name="search" placeholder="Search items..." value="<?php echo htmlspecialchars($search_query, ENT_QUOTES, 'UTF-8'); ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Dropdown Menu for Category Selection -->
        <div class="dropdown">
    <form action="view_inventory.php" method="GET">
        <select name="category" onchange="this.form.submit()">
            <option value="">Select Category</option>
            <?php foreach ($categories as $category_id => $category_name): ?>
                <option value="<?php echo $category_id; ?>" <?php if (isset($_GET['category']) && $_GET['category'] == $category_id) echo 'selected'; ?>>
                    <?php echo $category_name; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<?php 
// Display items of the selected category or search results
$selected_category = isset($_GET['category']) ? intval($_GET['category']) : null;

if (!empty($search_query)): ?>
    <div class="category">
        <div class="category-card">
            <!-- Static title for search results -->
            <h2 class="category-title">Search Results</h2>
            <?php if (!empty($search_data)): ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr class="sticky-header">
                                <th>Item Name</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Brand</th>
                                <th>Type</th>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($search_data as $item): ?>
                                <tr>
                                    <td><?php echo $item['item_name']; ?></td>
                                    <td><?php echo $item['description']; ?></td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td>RM<?php echo number_format($item['price'], 2); ?></td>
                                    <td><?php echo $item['brand']; ?></td>
                                    <td><?php echo $item['type']; ?></td>
                                    <td><?php echo $item['category_name']; ?></td>
                                    <td>
                                        <form method="POST" action="view_inventory.php" style="display:inline;">
                                            <input type="hidden" name="item_id_to_delete" value="<?php echo $item['id']; ?>">
                                            <button type="submit" name="delete_item" class="delete-button" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-items">
                    <h2>No items found for your search.</h2>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php elseif ($selected_category && isset($item_data[$selected_category])): ?>
    <div class="category">
        <div class="category-card">
            <!-- Display category name for selected category -->
            <h2 class="category-title"><?php echo $categories[$selected_category]; ?></h2>
            <?php if (!empty($item_data[$selected_category])): ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr class="sticky-header">
                                <th>Item Name</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Brand</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($item_data[$selected_category] as $item): ?>
                                <tr>
                                    <td><?php echo $item['item_name']; ?></td>
                                    <td><?php echo $item['description']; ?></td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td>RM<?php echo number_format($item['price'], 2); ?></td>
                                    <td><?php echo $item['brand']; ?></td>
                                    <td><?php echo $item['type']; ?></td>
                                    <td>
                                        <form method="POST" action="view_inventory.php" style="display:inline;">
                                            <input type="hidden" name="item_id_to_delete" value="<?php echo $item['id']; ?>">
                                            <button type="submit" name="delete_item" class="delete-button" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-items">
                    <h2>No items available in this category.</h2>
                </div>
            <?php endif; ?>

            <!-- Remove Category Form -->
            <form method="POST" action="view_inventory.php">
                <input type="hidden" name="category_id_to_remove" value="<?php echo $selected_category; ?>" />
                <button type="submit" name="remove_category" class="back-button" onclick="return confirm('Are you sure you want to remove this category? All related items will be deleted.')">
                    Remove Category
                </button>
            </form>
        </div>
    </div>
<?php else: ?>
    <p>Please select a category from the dropdown or use the search bar.</p>
<?php endif; ?>

        <a href="Dashboard.php" class="back-button">Back to Main Menu</a>
    </div>

</body>
</html>