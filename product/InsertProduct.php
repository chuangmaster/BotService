<?php

require_once '../config.php';
require_once '../DBConect.php';
//$ptNo = $_REQUEST['ptNo'];
$ptNo = "SP170228001";
$ptName = $_REQUEST['ptName'];
$ptPrice = $_REQUEST['ptPrice'];
$ptCategory = $_REQUEST['ptCategory'];
$ptType = $_REQUEST['ptType'];
$ptImgUrl = $_REQUEST['ptImgUrl'];
$ptDesc = $_REQUEST['ptDesc'];
$ptStock = $_REQUEST['ptStock'];
if (empty($ptNo)) {
    echo 'ptNo資料格式不符合，新增失敗';
} else if (empty($ptName)) {
    echo 'ptName資料格式不符合，新增失敗';
} else if (empty($ptPrice)) {
    echo 'ptPrice資料格式不符合，新增失敗';
} else if (empty($ptImgUrl)) {
    echo 'ptImgUrl資料格式不符合，新增失敗';
} else if (empty($ptDesc)) {
    echo 'ptDesc資料格式不符合，新增失敗';
} else if (empty($ptStock)) {
    echo 'ptStock資料格式不符合，新增失敗';
} else {
    $insertSql = "INSERT INTO Product (ptNo,ptName,ptPrice,ptCategory,ptType,ptImgUrl,ptDesc,ptStock)
    VALUES ('{$ptNo}','{$ptName}','{$ptPrice}','{$ptCategory}','{$ptType}','{$ptImgUrl}','{$ptDesc}','{$ptStock}')";
    if ($conn->query($insertSql) === TRUE) {
        echo "New prodcut data created successfully";
    } else {
        echo "Error: " . $insertSql . "<br>" . $conn->error;
    }
}
$conn->close();