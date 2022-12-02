<?php

namespace WeDevelop\Portfolio;

use SilverStripe\Core\Config\Configurable;

class Config
{
    use Configurable;

    public static function isCustomerEnabled()
    {
        return self::config()->get('customer_enabled');
    }
}
