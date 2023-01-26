<?php

namespace Links\Exceptions;

use Links\App\View;

class ExceptionController {

    /**
     * @param object
     * @return void
     */

    public static function catchException(object $e) :void
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/logs/exceptions.log';
        file_put_contents($path, $e, FILE_APPEND | LOCK_EX);

        (new View())->showException(['message' => 'Произошла ошибка, повторите попытку позднее!', 'code' => '=(']);
    }
}
