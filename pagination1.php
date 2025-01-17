<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagination Example</title>
    <style>
        a {
            margin-inline: 30px;
            text-decoration: none;
            padding: 5px 10px;
            border: 1px solid black;
            color: black;
        }

        .active {
            margin-top: 80px;
            background: gray;
            color: white;
            padding: 10px 10px;
            margin-inline: 20px;
        }

        table {
            text-align: center;
            width: 600px;
            margin: auto;
            border: 1px solid red;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid red;
            padding: 10px;
        }

        button {
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<?php
// Include the database connection file
include('admin/settings/database.php');

// Number of records per page
$limit = 5;

// Get the current page number from the URL
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = intval($_GET['page']); // Current page
} else {
    $page = 1; // Default to the first page
}

// Calculate the offset
$offset = ($page - 1) * $limit;

// Fetch records for the current page
$select = "SELECT * FROM itembox LIMIT :offset, :limit";
$pdox = $pdo->prepare($select);
$pdox->bindValue(':offset', $offset, PDO::PARAM_INT);
$pdox->bindValue(':limit', $limit, PDO::PARAM_INT);
$pdox->execute();
$fetch = $pdox->fetchAll(PDO::FETCH_ASSOC);

// Display the table
echo '<br><center><table>
<tr>
    <th>Name</th>
    <th>Title</th>
    <th>Roll No</th>
    <th>Delete</th>
    <th>Edit</th>
</tr>';

// Loop through the fetched data and display it
foreach ($fetch as $value) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($value['title']) . '</td>';
    echo '<td>' . htmlspecialchars($value['para']) . '</td>';
    echo '<td>' . htmlspecialchars($value['price']) . '</td>';
    echo '<td><button class="delete" data-id="' . htmlspecialchars($value['id'], ENT_QUOTES) . '">DELETE</button></td>';
    echo '<td><button class="edit" data-editx="' . htmlspecialchars($value['id'], ENT_QUOTES) . '">EDIT</button></td>';
    echo '</tr>';
}
echo '</table></center>';

// Count the total number of records
$query = "SELECT COUNT(*) FROM itembox";
$result = $pdo->query($query);
$total_records = $result->fetchColumn();

// Calculate total pages
$total_pages = ceil($total_records / $limit);

// Display pagination links
echo '<br><br><center><div>';
for ($i = 1; $i <= $total_pages; $i++) {
    if ($i == $page) { // Highlight the active page
        echo "<a href='?page=$i' class='active'>$i</a>";
    } else { // Non-active pages
        echo "<a href='?page=$i'>$i</a>";
    }
}
echo '</div></center>';
?>

</body>
</html>
