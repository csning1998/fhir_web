<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fhir";
$port = "3306";

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_gmail = isset($_POST['user_gmail']) ? $_POST['user_gmail'] : '';
    $user_password = isset($_POST['user_password']) ? $_POST['user_password'] : '';

    $sql = "SELECT * FROM users WHERE user_gmail = ? AND user_password = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ss", $user_gmail, $user_password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo '<script>alert("登入成功");</script>';
            echo '<script>window.location.href = "/fhir/home.html";</script>';
            exit();
        } else {
            echo "登入失敗，請檢查帳號和密碼。";
        }

        $stmt->close();
    } else {
        echo "預處理語句準備失敗";
    }
}

$conn->close();
