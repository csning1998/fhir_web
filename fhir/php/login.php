<?php

// 設定資料庫連線資訊
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fhir";
$port = "3306";

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// 檢查連線是否成功
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 處理表單資料
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_gmail = isset($_POST['user_gmail']) ? $_POST['user_gmail'] : '';
    $user_password = isset($_POST['user_password']) ? $_POST['user_password'] : '';

    // 準備 SQL
    $sql = "SELECT * FROM users WHERE user_gmail = ? AND user_password = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) { // 檢查預處理指令成功與否
        // 將使用者輸入的資料連接到 SQL 指令的佔位符上
        $stmt->bind_param("ss", $user_gmail, $user_password);

        $stmt->execute(); // 執行 SQL 指令

        $result = $stmt->get_result(); // 獲取查詢結果

        // 檢查查詢結果是否存在資料
        if ($result->num_rows > 0) { 
            echo '<script>alert("登入成功");</script>';
            echo '<script>window.location.href = "/fhir/home.html";</script>';
            exit();
        }
    
        else {
            echo "登入失敗，請檢查帳號和密碼。";
        }
        // 關閉預處理語句
        $stmt->close();
    
    } else {
            // 預處理語句準備失敗
            echo "預處理語句準備失敗";
    }
}

// 關閉資料庫連線
$conn->close();