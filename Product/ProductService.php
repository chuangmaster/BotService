<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/botservice/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/botservice/DBConect.php';

function getProduct($ptNo) {
    $conn = getConection();
    $sql = "SELECT * FROM Product WHERE ptNo='{$ptNo}'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $rows = $result->fetch_assoc();
    }
    $conn->close();
    return $rows;
}

function getNewProductID() {
    $conn = getConection();
    $sql = "SELECT COUNT(*)+1 AS nextNo FROM `Product` WHERE `CreateDate` BETWEEN CURRENT_DATE() AND CURRENT_DATE()+1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $rows = $result->fetch_assoc();
        $strlen = strlen($rows["nextNo"]);
        while ($strlen <= 3) {
            $nextNo = sprintf("%s%s", "0", $rows["nextNo"]);
            $strlen++;
        }
        $newPtID = "SP" . date("Ymd") . $nextNo;
    }
    $conn->close();
    return $newPtID;
}

