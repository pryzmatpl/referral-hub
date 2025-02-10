<?php
/**
 * This code is part of geltskan, marketed as Photos2Stl
 * All rights reserved to Prizm
 * 2024 08 01 20 13
 */

// Database configuration
$servername = "mysql_aimatch";
$username = env("DB_USERNAME");
$password = env("DB_PASSWORD");
$dbname = "prism";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read SQL file
$sqlFile = 'database.sql';
$sql = file_get_contents($sqlFile);

if ($sql === false) {
    die("Error reading SQL file.");
}

// Execute SQL commands
if ($conn->multi_query($sql)) {
    do {
        // Store first result set
        if ($result = $conn->store_result()) {
            // Fetch one and one row
            while ($row = $result->fetch_row()) {
                // Process each row if needed
            }
            // Free result set
            $result->free();
        }
    } while ($conn->more_results() && $conn->next_result());
    echo "SQL file executed successfully.";
} else {
    echo "Error executing SQL: " . $conn->error;
}

// Close connection
$conn->close();

