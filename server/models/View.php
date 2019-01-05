<?php

namespace App\Models;


class View
{
    const viewsDirName = 'views';

    public static function render($viewName, $data, $uuid)
    {
        $filePath = sprintf("./%s/%s.php", self::viewsDirName, $viewName);
        require_once "$filePath";

        return true;
    }
}