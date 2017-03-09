<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/botservice/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/botservice/DBConect.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/botservice/Product/ProductService.php';


$target_dir = $_SERVER['DOCUMENT_ROOT'] . $GLOBALS["Dir_img"];
if (!isset($_POST["submit"])) {
    $imageFileType = substr($_FILES["ptImgUrl"]["type"], 6);
    $imageFileName = $_FILES["ptImgUrl"]["name"];
    $ptNo = ProductService:: getNewProductID();
    $ptName = $_REQUEST['ptName'];
    $ptPrice = $_REQUEST['ptPrice'];
    $ptCategory = $_REQUEST['ptCategory'];
    $ptType = $_REQUEST['ptType'];    
    $ptDesc = $_REQUEST['ptDesc'];
    $ptStock = $_REQUEST['ptStock'];
    if ($_FILES["ptImgUrl"]["error"] > 0) {
        echo "檔案傳輸失敗...";
    } else {
        if (empty($ptNo)) {
            echo "無商品編碼資料，新增失敗";
        } else if (empty($ptName)) {
            echo "無商品名稱資料，新增失敗";
        } else if (empty($ptPrice)) {
            echo "無商品價格資料，新增失敗";
        } else if (empty($ptDesc)) {
            echo "無商品描述資料，新增失敗";
        } else if (empty($ptStock)) {
            echo "無商品庫存資料，新增失敗";
        } else if ($_FILES["ptImgUrl"]["size"] > 300 * 1024 * 1024) {
            echo "圖片大小超過3M，新增失敗";
        } else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "圖片格式不符，只允許JPG, JPEG, PNG, GIF，新增失敗";
        } else if (file_exists($target_dir . "{$ptNo}.{$imageFileType}")) {
            echo "此商品編號已經重複上傳...";
        } else {
            $isInsertSuccess = false;
            if (move_uploaded_file($_FILES["ptImgUrl"]["tmp_name"], $target_dir . "{$ptNo}.{$imageFileType}")) {
                $isInsertSuccess = true;
            }
            $conn = DBConect::getConnection();
            $insertSql = "INSERT INTO Product (ptNo,ptName,ptPrice,ptCategory,ptType,ptImgUrl,ptDesc,ptStock)
    VALUES ('{$ptNo}','{$ptName}','{$ptPrice}','{$ptCategory}','{$ptType}','{$imageFileName}.{$imageFileType}','{$ptDesc}','{$ptStock}')";
            if ($conn->query($insertSql)) {
                $isInsertSuccess = true;
            } else {
                $isInsertSuccess = false;
            }
            if ($isInsertSuccess) {
                echo "New prodcut data created successfully, Following is this data info:<br>"
                . "<img src='{$GLOBALS['URL_ROOT']}{$GLOBALS['Dir_img']}{$ptNo}.{$imageFileType}'><br>"
                . "Product PtNo is {$ptNo}<br>"
                . "Product Name is {$ptName}<br>"
                . "Product Description is {$ptDesc}<br>";
            } else {
                echo "Error: " . $insertSql . "<br>" . $conn->error;
            }
            $conn->close();
        }
    }
} else {
    echo "未有資料傳遞，新增失敗";
}

