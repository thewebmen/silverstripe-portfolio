<?php

namespace WeDevelop\Portfolio;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\View\TemplateGlobalProvider;

class Config implements TemplateGlobalProvider
{
    use Configurable;

    public static function isCustomerEnabled(): bool
    {
        return self::config()->get('customer_enabled');
    }

    public static function get_template_global_variables(): array
    {
        return [
            'WeDevelopPortfolioConfigIsCustomerEnabled' => 'isCustomerEnabled',
        ];
    }
}
