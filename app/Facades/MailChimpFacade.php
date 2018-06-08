<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MailChimpFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'mail-chimp';
    }
}
