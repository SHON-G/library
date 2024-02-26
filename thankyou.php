<?php
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if logout button is clicked
if (isset($_POST['logout'])) {
    // Unset all session variables
    session_unset();
    // Destroy the session
    session_destroy();
    // Redirect to login page
    header("Location: login.php");
    exit();
}

// Check if submit another response button is clicked
if (isset($_POST['submitAnother'])) {
    // Redirect to student.php page
    header("Location: student.php");
    exit();
}

// Include the database connection file
include 'connection.php';

// Check if download button is clicked
if (isset($_POST['download'])) {
    // Fetch data from database
    $sql = "SELECT * FROM students";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Error fetching data: " . mysqli_error($conn));
    }

    // Set headers for download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=data.csv');

    // Open output stream
    $output = fopen('php://output', 'w');

    // Write column headers to CSV file
    fputcsv($output, array(
        'Name',
        'Contact Number', 
        'Branch', 
        'Roll No', 
        'Division', 
        'Book Name', 
        'Email', 
        'Return Period', 
        'Return Datetime'
    ));

    // Write data to CSV file
    while ($row = mysqli_fetch_assoc($result)) {
        // Write each row to CSV file
        fputcsv($output, $row);
    }

    // Close output stream
    fclose($output);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            max-width: 500px;
            padding: 40px;
            border-radius: 10px;
            background: linear-gradient(to right top, #3a7bd5, #00d2ff, #2fa1d6);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            color: #fff;
        }
        h1 {
            margin-bottom: 30px;
            font-size: 36px;
        }
        form {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        button[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            padding: 12px 24px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            margin: 10px;
            transition: background-color 0.3s ease;
            font-size: 16px;
            outline: none;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Thank You</h1>
        <form method="post">
            <button type="submit" name="logout">Logout</button>
            <button type="submit" name="submitAnother">Submit Another Response</button>
            <button type="submit" name="download">Download Data</button>
        </form>
    </div>
</body>
</html>
