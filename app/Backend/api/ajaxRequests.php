<?php

require_once '../../../vendor/autoload.php';

use Nobaan\Backend\Core\Database;

if (isset($_POST['action']) && $_POST['action'] == 'get_all_products') {
        $db = new Database;
        $products = $db->query("SELECT * FROM products WHERE id>=:1", array('1' => '1'));
        if (!$products)
                die('false');
        die(json_encode($products));
}

if (isset($_POST['action']) && $_POST['action'] == 'check_number') {
        $db = new Database;
        $phone_number = $_POST['phone_number'];
        $uid = $_POST['product_uid'];
        $product = $db->query("SELECT * FROM products WHERE product_uid=?", array($uid))[0];
        if ($product->product_discount) {
                $phone_numbers = $db->redis_range();
                if (in_array($phone_number, $phone_numbers)) {
                        die(-1);
                }
                $db->redis_push($phone_number);
                die('success');
        }
        die('success');
}


?>