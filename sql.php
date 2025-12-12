<?php
// show errors for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// DB connection settings
$servername = "localhost";
$username = "root";       // replace with real user
$password = "";           // replace with real password
$dbname   = "easy_prompt"; // set your database name
$port     = 3307;

// Create connection - include DB name so we can query tables
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . htmlspecialchars($conn->connect_error));
}

// Query users table
$sql = "SELECT * FROM `users`";
$result = $conn->query($sql);

// Start HTML output
echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Users</title>';
echo '<style>table{border-collapse:collapse;width:100%;}th,td{border:1px solid #ccc;padding:8px;text-align:left;}th{background:#eee;}</style>';
echo '</head><body>';
echo '<h2>Users</h2>';

if ($result && $result->num_rows > 0) {
    // print table header using field names
    echo '<table><thead><tr>';
    $fields = $result->fetch_fields();
    foreach ($fields as $f) {
        echo '<th>' . htmlspecialchars($f->name) . '</th>';
    }
    echo '</tr></thead><tbody>';

    // print rows
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        foreach ($fields as $f) {
            $col = $f->name;
            echo '<td>' . htmlspecialchars((string)($row[$col] ?? '')) . '</td>';
        }
        echo '</tr>';
    }

    echo '</tbody></table>';
} else {
    echo '<p>No users found.</p>';
}

echo '</body></html>';

// free and close
if ($result) { $result->free(); }
$conn->close();
?>