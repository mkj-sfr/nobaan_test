<?php

require_once '../../../vendor/autoload.php';

use Nobaan\Backend\Core\Database;

if(isset($_POST['action']) && $_POST['action'] == 'get_all_products')
{
        $db = new Database;
        $products = $db->query("SELECT * FROM products WHERE id>=:1", array('1' => '1'));
        if(!$products) die('false'); 
        die(json_encode($products));
}


?>