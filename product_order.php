<?php
require_once 'vendor/autoload.php';

use Nobaan\Backend\Core\Database;
use Nobaan\Backend\Core\Helpers;


if (isset($_GET['product_uid'])) {
    $uid = $_GET['product_uid'];

    $db = new Database;
    $product = $db->query("SELECT * FROM products WHERE product_uid=?", array($uid))[0];
    $product_price = $product->product_price;
    if ($product->product_discount)
        $product_price = $product->product_price * (100 - $product->product_discount) / 100;
}
Helpers::require_with('app/Frontend/includes/header.php', array('Checkout Page'));

require_once 'app/Frontend/content/checkout.php';

require_once 'app/Frontend/includes/footer.php';
?>