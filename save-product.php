<?php

use Magento\Framework\App\Bootstrap;
include 'app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();

$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode('adminhtml');


if (!empty($_GET['id'])) {
    try {
        $product = $objectManager->get('Magento\Catalog\Model\Product')->load($_GET['id']);
        $product->setName($product->getName());
        $product->save();
    } catch (Exception $ex) {
        echo $ex;
    }
    exit;
}

$servername = "localhost";
$username = "wholesaleling_dbuser";
$password = "PunchBag386**";
$database = "wholesaleling_api";

$servername2 = "localhost";
$username2 = "wholesaleling_dbuser";
$password2 = "PunchBag386**";
$database2 = "wholesaleling_magento";

//$username2 = "uu79r73232863";
//$password2 = "devmg@12345";
//$database2 = "db73ng9mf9sq6b";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn2 = new mysqli($servername2, $username2, $password2);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->select_db($database);
$conn2->select_db($database2);

$result = $conn->query("SELECT entity_id, stock_code FROM parent_product WHERE imported = 2");
    foreach ($result as $row) {
//        $keyword = strtolower($row['stock_code']);
//        $sql = "DELETE FROM `url_rewrite` WHERE `request_path` LIKE '%".$keyword."%'";
//        $conn2->query($sql);
//        $ok = 0;
        try {
            $product = $objectManager->create('Magento\Catalog\Model\Product')->load($row['entity_id']);
            $product->setName($product->getName());
            $product->save();
            $ok = 1;
            echo "Updated ".$product->getName().": ".$row['entity_id']."<br/>";
        } catch (Exception $ex) {
            echo 'Failed:'.$row['entity_id']."<br/>";
//            echo "<Pre>";
//            echo $ex;
//            echo "</pre>";
            $ok = 0;
            $conn->query("UPDATE parent_product SET imported = 9 WHERE entity_id = ".$row['entity_id']);
        }

        if ($ok = 1) {
            $conn->query("UPDATE parent_product SET imported = 1 WHERE entity_id = ".$row['entity_id']);
        }

    }
$conn->close();
$conn2->close();

