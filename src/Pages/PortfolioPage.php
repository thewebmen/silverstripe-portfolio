<?php

namespace WeDevelop\Portfolio\Pages;

use Restruct\Silverstripe\SiteTreeButtons\GridFieldAddNewSiteTreeItemButton;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Lumberjack\Forms\GridFieldConfig_Lumberjack;
use SilverStripe\Lumberjack\Forms\GridFieldSiteTreeAddNewButton;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\HasManyList;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use WeDevelop\Portfolio\Controllers\PortfolioPageController;
use WeDevelop\Portfolio\Models\Category;
use WeDevelop\Portfolio\Models\Customer;

/**
 * @property int $PageLength
 * @method CasePage|HasManyList Cases()
 * @method Category|HasManyList Categories()
 * @method Customer Customers()
 */
class PortfolioPage extends \Page
{
    /** @config */
    private static string $table_name = 'WeDevelop_Portfolio_PortfolioPage';

    /** @config */
    private static string $singular_name = 'Portfolio - overview page';

    /** @config */
    private static string $plural_name = 'Portfolio - overview pages';

    /** @config */
    private static string $description = 'A page with an overview of all cases in a portfolio';

    /** @config */
    private static string $icon_class = 'font-icon-page-multiple';

    /** @config */
    private static array $allowed_children = [
        '*' . CasePage::class,
    ];

    /** @config */
    private static string $default_child = CasePage::class;

    /** @config */
    private static array $db = [
        'PageLength' => 'Int',
    ];

    /** @config */
    private static array $defaults = [
        'PageLength' => 10,
    ];

    /** @config */
    private static array $has_many = [
        'Categories' => Category::class,
    ];

    public function getCMSFields(): FieldList
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $fields->addFieldsToTab(
                'Root.Categories',
                [
                    GridField::create(
                        'Categories',
                        _t('WeDevelop\Portfolio\Models\Category.PLURALNAME', 'Categories'),
                        $this->Categories(),
                        new GridFieldConfig_RecordEditor()
                    ),
                ]
            );

            $fields->insertBefore('Cases', NumericField::create('PageLength', 'Items per page'));
        });

        return parent::getCMSFields();
    }

    public function getLumberjackPagesForGridfield(): DataList
    {
        return CasePage::get()->filter([
            'ParentID' => $this->ID,
        ]);
    }

    public function getLumberjackGridFieldConfig()
    {
        return GridFieldConfig_Lumberjack::create()
            ->removeComponentsByType(GridFieldSiteTreeAddNewButton::class)
            ->addComponent((new GridFieldOrderableRows('CaseSort'))
                ->setRepublishLiveRecords(true))
            ->addComponent(new GridFieldAddNewSiteTreeItemButton('buttons-before-left'));
    }

    public function getTitle(): string
    {
        $controller = Controller::curr();
        $activeCategoryFilter = $controller->getRequest()->getVar('category');

        if ($activeCategoryFilter) {
            $category = Category::get()->filter('Slug', $activeCategoryFilter)->first();
        }

        return $category->Title ?? $this->getField('Title');
    }

    public function getCategories(): DataList
    {
        return Category::get()->filter(
            [
                'PortfolioPageID' => $this->ID,
            ]
        );
    }

    public function getControllerName(): string
    {
        return PortfolioPageController::class;
    }
}
