<?php

namespace WeDevelop\Portfolio\Admins;

use SilverStripe\Admin\ModelAdmin;
use WeDevelop\Portfolio\Models\Collection;
use WeDevelop\Portfolio\Models\Customer;
use WeDevelop\Portfolio\Config;

class PortfolioAdmin extends ModelAdmin
{
    /** @config */
    private static string $url_segment = 'portfolio';

    /** @config */
    private static string $menu_title = 'Portfolio';

    /** @config */
    private static string $menu_icon_class = 'font-icon-block-story-carousel';

    /** @config */
    private static array $managed_models = [
        Customer::class,
        Collection::class,
    ];

    public function getManagedModels()
    {
        $models = parent::getManagedModels();

        if (!Config::isCustomerEnabled()) {
            unset($models[Customer::class]);
        }

        return $models;
    }
}
