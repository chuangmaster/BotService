<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/botservice/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/botservice/DBConect.php';

class ProductService {

    /**
     * Get Product No
     * @param String $ptNo product No
     * @return Product 
     */
    public static function getProduct($ptNo) {
        $conn = DBConect::getConnection();
        $sql = "SELECT * FROM Product WHERE ptNo='{$ptNo}'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $rows = $result->fetch_assoc();
        } else {
            $rows = null;
        }
        $conn->close();
        return $rows;
    }

    public static function getNewProductID() {
        $conn = DBConect::getConnection();
        $sql = "SELECT COUNT(*)+1 AS nextNo FROM `Product` WHERE `CreateDate` BETWEEN CURRENT_DATE() AND CURRENT_DATE()+1";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $rows = $result->fetch_assoc();
            $newPtID = "SP" . (date("Ymd")*1000 + $rows["nextNo"]);
        }
        $conn->close();
        return $newPtID;
    }

}
