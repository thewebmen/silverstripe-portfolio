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
    private static string $table_name = 'WeDevelop_Portfolio_CasePage';

    private static string $singular_name = 'Case page';

    private static string $description = 'A page that represents a portfolio case';

    private static string $plural_name = 'Cases page';

    private static string $icon_class = 'font-icon-p-article';

    private static array $allowed_children = [];

    private static bool $show_in_sitetree = false;

    private static bool $can_be_root = false;

    private static array $db = [
        'PublicationDate' => 'Datetime',
    ];

    private static array $has_one = [
        'Thumbnail' => Image::class,
        'Customer' => Customer::class,
    ];

    private static array $owns = [
        'Thumbnail',
    ];

    private static array $many_many = [
        'Categories' => Category::class,
    ];

    private static array $belongs_many_many = [
        'Collections' => Collection::class,
        'ElementPortfolios' => ElementPortfolio::class,
    ];

    private static array $default_sort = [
        'PublicationDate' => 'DESC',
    ];

    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab('Root.Main', [
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

        return $fields;
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
