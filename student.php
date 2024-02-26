<?php
session_start();
include 'connection.php';

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Define variable to hold error message
$error = "";

// Check if form is submitted
if (isset($_POST['submit'])) {
    $name = $_POST['Name'];
    $contact_number = $_POST['ContactNumber'];
    $branch = $_POST['Branch'];
    $roll_no = $_POST['RollNo'];
    $division = $_POST['Division'];
    $book_name = $_POST['BookName'];
    $email = $_POST['Email'];
    $return_period = $_POST['ReturnPeriod'];
    $return_datetime = $_POST['return_datetime'];

    // Insert data into database
    $sql = "INSERT INTO students (name, contact_number, branch, roll_no, division, book_name, email, return_period, return_datetime)
            VALUES ('$name', '$contact_number', '$branch', '$roll_no', '$division', '$book_name', '$email', '$return_period', '$return_datetime')";

    if (mysqli_query($conn, $sql)) {
        // Schedule reminder email
        $reminder_time = date('Y-m-d H:i:s', strtotime("+$return_period"));
        scheduleReminder($email, $reminder_time);
        
        header("Location: thankyou.php");
        exit();
    } else {
        $error = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Function to schedule reminder email
function scheduleReminder($email, $reminder_time) {
    // Set up mail parameters
    $to = $email;
    $subject = "Library Book Return Reminder";
    $message = "Dear Student,\n\nThis is a reminder that your book return period is over. Please return the book to the library as soon as possible.\n\nRegards,\nLibrary Management System";
    $headers = "From: librarysystem6@.com";

    // Schedule reminder email using cron job or alternative method
    // For demonstration purpose, we are using a log file to simulate scheduling
    $log_message = "Reminder email scheduled for: $reminder_time\n";
    file_put_contents('reminder_log.txt', $log_message, FILE_APPEND);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information - Library Management System</title>
    <style>
        /* Inline CSS styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }
        main {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1, h2 {
            color: #333;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: flex-start;
        }
        .form-group {
            width: calc(50% - 10px);
            margin-bottom: 20px;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="datetime-local"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        .form-group input[type="text"]:focus,
        .form-group input[type="email"]:focus,
        .form-group input[type="datetime-local"]:focus {
            outline: none;
            border-color: #4caf50;
        }
        button[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Library Management System</h1>
    </header>
    <main>
        <h2>Enter Student Information</h2>
        <?php if (isset($error) && !empty($error)) { ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php } ?>
        <form id="studentInfoForm" method="post">
            <div class="form-group">
                <input type="text" name="Name" placeholder="Name" required>
            </div>
            <div class="form-group">
                <input type="text" name="ContactNumber" placeholder="Contact Number" required>
            </div>
            <div class="form-group">
                <input type="text" name="Branch" placeholder="Branch" required>
            </div>
            <div class="form-group">
                <input type="text" name="RollNo" placeholder="Roll No." required>
            </div>
            <div class="form-group">
                <input type="text" name="Division" placeholder="Division" required>
            </div>
            <div class="form-group">
                <input type="text" name="BookName" placeholder="Book Name" required>
            </div>
            <div class="form-group">
                <input type="email" name="Email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="text" name="ReturnPeriod" placeholder="Return Period (e.g., 7 days)" required>
            </div>
            <div class="form-group">
                <input type="datetime-local" name="return_datetime" required>
            </div>
            <button type="submit" name="submit">Submit</button>
        </form>
    </main>
</body>
</html>
