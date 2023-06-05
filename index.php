<?php
require_once 'vendor/autoload.php';

use Nobaan\Backend\Core\Helpers;


Helpers::require_with('app/Frontend/includes/header.php', array('Nobaan Products Page'));

require_once 'app/Frontend/content/home.php';

require_once 'app/Frontend/includes/footer.php';

?>

	
