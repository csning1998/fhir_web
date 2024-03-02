<?php
// 連接到資料庫
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fhir";
$port = "3306";  // 修改為你的 MySQL 伺服器端口號

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// 检查连接是否成功
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 检查是否有传入 IC_number 参数
if (isset($_GET['IC_number'])) {
    $icNumberParam = $_GET['IC_number'];

    // 查询第一张数据表的数据（medical_record 表）
    $sql1 = "SELECT * FROM medical_record WHERE IC_number = '$icNumberParam'";
    $result1 = $conn->query($sql1);

    // 将查询结果转换为关联数组
    $data1 = [];

    if ($result1->num_rows > 0) {
        $data1 = $result1->fetch_assoc();

        // 从 medical_record 中获取 IC_number
        $icNumber = $data1['IC_number'];

        // 使用 IC_number 查询 icd_10 表
        $sql2 = "SELECT ICD_code, ICD_name FROM icd_10 WHERE IC_number = '$icNumber'";
        $result2 = $conn->query($sql2);

        // 使用 IC_number 查询 drug 表
        $sql3 = "SELECT drug_id, english_name, chinese_name, drug_unit, drug_usage, take_date, drug_total FROM drug WHERE IC_number = '$icNumber'";
        $result3 = $conn->query($sql3);

        // 将查询结果转换为关联数组
        $data2 = [];
        $data3 = [];

        if ($result2->num_rows > 0) {
            $data2 = $result2->fetch_assoc();
        } else {
            echo "未找到相應的 ICD-10 數據";
        }

        while ($row = $result3->fetch_assoc()) {
            $data3[] = $row;
        }

        // 合并所有表格的数据
        $result = ['medical_record' => $data1, 'icd_10' => $data2, 'drug' => $data3];

        // 输出 JSON 格式的数据
        echo json_encode($result);
    } else {
        echo "未找到相應的醫療紀錄";
    }
} else {
    echo "缺少 IC_number 参数";
}

// 关闭数据库连接
$conn->close();
?>




