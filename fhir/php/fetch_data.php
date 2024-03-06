<?php

// 連接到資料庫
// 設定 MySQL 伺服器資訊
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fhir";
$port = "3306"; // 修改為你的 MySQL 伺服器端口號

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// 檢查連線是否成功
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 宣告變數
// 存放第一個數據表的查詢結果
$result1 = null;
// 存放第二個數據表的查詢結果
$result2 = null;
// 存放第三個數據表的查詢結果
$result3 = null;
// 存放第一個數據表的資料
$data1 = [];
// 存放第二個數據表的資料
$data2 = [];
// 存放第三個數據表的資料
$data3 = [];
// 存放 MedicationRequest 資源的陣列
$medicationRequests = [];
// 存放 FHIR-style JSON 格式的資料
$fhirData = [];

// 查詢第一個數據表的數據（medical_record 表）
// 查詢條件：IC_number = 'A2255'
$sql1 = "SELECT * FROM medical_record WHERE IC_number = 'A2255'";
$result1 = $conn->query($sql1);

// 檢查查詢結果是否存在資料
if ($result1->num_rows > 0) {
    // 將查詢結果轉換為關聯數組
    $data1 = $result1->fetch_assoc();
} else {
    // 未找到相應的醫療紀錄
    echo "未找到相應的醫療紀錄";
}

// 查詢第二個數據表的數據（icd_10 表）
// 查詢條件：IC_number = 'A2255'
$sql2 = "SELECT ICD_code, ICD_name FROM icd_10 WHERE IC_number = 'A2255'";
$result2 = $conn->query($sql2);

// 檢查查詢結果是否存在資料
if ($result2->num_rows > 0) {
    // 將查詢結果轉換為關聯數組
    $data2 = $result2->fetch_assoc();
} else {
    // 未找到相應的 ICD-10 數據
    echo "未找到相應的 ICD-10 數據";
}

// 查詢第三個數據表的數據（drug 表）
// 查詢條件：IC_number = 'A2255'
$sql3 = "SELECT drug_id, english_name, chinese_name, drug_unit, drug_usage, take_date, drug_total FROM drug WHERE IC_number = 'A2255'";
$result3 = $conn->query($sql3);

// 將查詢結果轉換為陣列
while ($row = $result3->fetch_assoc()) {
    $data3[] = $row;
}

// 遍歷每個藥物處方，將其添加到 MedicationRequest 資源中
$medicationRequests = [];
foreach ($data3 as $prescription) {
    $medicationRequest = [
        'resourceType' => 'MedicationRequest',
        'id' => uniqid(), // 使用唯一標識符
        'subject' => [
            'reference' => 'Patient/' . $data1['IC_number'],
        ],
        'medicationCodeableConcept' => [
            'coding' => [
                [
                    'system' => 'http://example.com/drug',
                    'code' => $prescription['drug_id'],
                    'display' => $prescription['english_name'],
                ],
            ],
        ],
        // 添加其他藥物處方相關的屬性
        'dosageInstruction' => [
            [
                'text' => $prescription['drug_usage'],
            ],
        ],
        'dispenseRequest' => [
            'quantity' => $prescription['drug_total'],
            'expectedSupplyDuration' => [
                'value' => 1,
                'unit' => 'weeks',
            ],
        ],
        // 可根據實際需求添加其他屬性
    ];

    $medicationRequests[] = $medicationRequest;
}

// 組織 FHIR-style JSON 數據
$fhirData = [
    'resourceType' => 'MedicationAdministration',
    'id' => '121',
    'subject' => [
        [
            'fullUrl' => 'urn:uuid:' . uniqid(),
            'resource' => [
                'resourceType' => 'Patient',
                'id' => $data1['IC_number'],
                'name' => [
                    [
                        'given' => [$data1['patient_name']],
                    ],
                ],
                'identifier' => [
                    [
                        'system' => 'http://example.com/patient-identifier',
                        'value' => $data1['IC_number'],
                    ],
                ],
            
            ],
        ],
        [
            'fullUrl' => 'urn:uuid:' . uniqid(),
            'resource' => [
                'resourceType' => 'Condition',
                'code' => [
                    'coding' => [
                        [
                            'system' => 'http://example.com/icd-10',
                            'code' => $data2['ICD_code'],
                            'display' => $data2['ICD_name'],
                        ],
                    ],
                ],
                'subject' => [
                    'reference' => 'Patient/' . $data1['IC_number'],
                ],
            ],
        ],
        // 將每個藥物處方添加到 Bundle 中
        ...$medicationRequests,
    ],
];

// 輸出 FHIR-style JSON 格式的數據
echo json_encode($fhirData, JSON_PRETTY_PRINT);

$conn->close();
