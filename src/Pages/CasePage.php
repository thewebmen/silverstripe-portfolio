<?php

namespace WeDevelop\Portfolio\Pages;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\ORM\ManyManyList;
use SilverStripe\TagField\TagField;
use WeDevelop\Portfolio\Controllers\PortfolioPageController;
use WeDevelop\Portfolio\ElementalGrid\ElementPortfolio;
use WeDevelop\Portfolio\Models\Category;
use WeDevelop\Portfolio\Models\Collection;
use WeDevelop\Portfolio\Models\Customer;
use WeDevelop\Portfolio\Config;

/**
 * @property DBDatetime $PublicationDate
 * @method Image Thumbnail()
 * @method Customer Customer()
 * @property int $CustomerID
 * @method Category|ManyManyList Categories()
 * @method Collection|ManyManyList Collections()
 */
class CasePage extends \Page
{
    /** @config */
    private static string $table_name = 'WeDevelop_Portfolio_CasePage';

    /** @config */
    private static string $singular_name = 'Portfolio - case page';

    /** @config */
    private static string $plural_name = 'Portfolio - case pages';

    /** @config */
    private static string $description = 'A page that represents a portfolio case';

    /** @config */
    private static string $icon_class = 'font-icon-block-banner';

    /** @config */
    private static array $allowed_children = [];

    /** @config */
    private static bool $show_in_sitetree = false;

    /** @config */
    private static bool $can_be_root = false;

    /** @config */
    private static array $db = [
        'PublicationDate' => 'Datetime',
        'CaseSort' => 'Int',
    ];

    /** @config */
    private static array $has_one = [
        'Thumbnail' => Image::class,
        'Customer' => Customer::class,
    ];

    /** @config */
    private static array $owns = [
        'Thumbnail',
    ];

    /** @config */
    private static array $many_many = [
        'Categories' => Category::class,
    ];

    /** @config */
    private static array $belongs_many_many = [
        'Collections' => Collection::class,
        'ElementPortfolios' => ElementPortfolio::class,
    ];

    /** @config */
    private static string $default_sort = 'PublicationDate DESC';

    public function getCMSFields(): FieldList
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $fields->addFieldsToTab('Root.ProjectSettings', [
                TagField::create(
                    'Categories',
                    _t('WeDevelop\Portfolio\Models\Category.PLURALNAME', 'Categories'),
                    Category::get()->filter('PortfolioPageID', $this->ParentID),
                    $this->Categories(),
                )->setCanCreate(false),
                DropdownField::create(
                    'CustomerID',
                    _t('WeDevelop\Portfolio\Models\Customer.SINGULARNAME', 'Customer'),
                    Customer::get()->map()->toArray()
                )->setHasEmptyDefault(true),
                UploadField::create('Thumbnail', _t(__CLASS__ . '.THUMBNAIL', 'Thumbnail')),
            ]);

            if (!Config::isCustomerEnabled()) {
                $fields->removeByName('CustomerID');
            }
        });

        return parent::getCMSFields();
    }

    public function getControllerName(): string
    {
        return PortfolioPageController::class;
    }

    protected function onBeforeWrite()
    {
        if (is_null($this->PublicationDate)) {
            $this->PublicationDate = DBDatetime::now()->getValue();
        }

        parent::onBeforeWrite();
    }
}
