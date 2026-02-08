<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}
<<<<<<< HEAD
=======

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
