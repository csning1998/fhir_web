<?php

// 連接到資料庫
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fhir";
$port = "3306";

$conn = new mysqli($servername, $username, $password, $dbname,$port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 從 GET 請求中獲取搜尋值
$searchValue = $_GET["searchValue"];

// 在這裡，你可以執行適當的 SQL 查詢來獲取資料，然後將結果返回到前端
// 這裡只是一個簡單的範例，假設有一個 patients 資料表
$sql = "SELECT * FROM patient WHERE IC_number = '$searchValue'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // 這裡返回一個包含 HTML 表格行的字串
        echo "<tr>

                <td><input type='checkbox' class='search-checkbox' data-ic-number='" . $row["IC_number"] . "'/></td>
                <td>" . $row["IC_number"] . "</td>
                <td>" . $row["patient_id"] . "</td>
                <td>" . $row["patient_name"] . "</td>
                <td>" . $row["patient_birth"] . "</td>
                <td>" . $row["patient_sex"] . "</td>
                <td>" . $row["patient_hospital"] . "</td>
                <td><a href='view_drug.html?IC_number=" . $row["IC_number"] . "'><button class='button1'>檢視</button></a></td>
              </tr>";
    }
} else {
    echo "未找到相應的資料";
}

$conn->close();
?>
</body>
</html>
