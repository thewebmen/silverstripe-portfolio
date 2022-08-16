<?php

namespace WeDevelop\Portfolio\Admins;

use SilverStripe\Admin\ModelAdmin;
use WeDevelop\Portfolio\Models\Collection;
use WeDevelop\Portfolio\Models\Customer;

class PortfolioAdmin extends ModelAdmin
{
    private static string $url_segment = 'portfolio';

    private static string $menu_title = 'Portfolio';

    private static string $menu_icon_class = 'font-icon-block-story-carousel';

    private static array $managed_models = [
        Customer::class,
        Collection::class,
    ];
}
