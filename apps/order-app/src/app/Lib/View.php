<?php

namespace App\Lib;

class View {
    public function __construct() {
       //
    }

    public function render($path, $vars = []) {
        extract($vars);
        $path = 'app/Views/'.$path;
        if (file_exists($path)) {
            ob_start();
            require $path;
            $content = ob_get_clean();
            echo $content;
        }
    }
}
