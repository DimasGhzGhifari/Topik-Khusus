<?php

namespace App;

use Illuminate\Foundation\Application;

class Kernel
{
    protected static ?Application $app = null;

    public static function setApplication(Application $app): void
    {
        self::$app = $app;
    }

    public static function getApplication(): ?Application
    {
        return self::$app;
    }
}
