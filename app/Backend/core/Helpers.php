<?php

namespace Nobaan\Backend\Core;


class Helpers {
    public static function require_with($page, array $vars)
    {
        extract($vars);
        require $page;
    }    
}




?>