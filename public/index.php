<?php

use App\Kernel;

if (file_exists('maintenance'))
{
   switch (true) {
         case $_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/index.php' :
            readfile('maintenance.html');
            exit;
         case  file_exists($img=ltrim($_SERVER['REQUEST_URI'],'/')):
            break;
         default :
            header('Location: /',TRUE,307);
            exit;
   }
}

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
