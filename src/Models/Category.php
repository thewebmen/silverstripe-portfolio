<?php

declare(strict_types=1);

namespace WeDevelop\Portfolio\Models;

use SilverStripe\CMS\Controllers\CMSPageEditController;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordViewer;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\ManyManyList;
use SilverStripe\View\Parsers\URLSegmentFilter;
use WeDevelop\Portfolio\Controllers\PortfolioPageController;
use WeDevelop\Portfolio\Pages\CasePage;
use WeDevelop\Portfolio\Pages\PortfolioPage;

/**
 * @property string $Title
 * @property string $Slug
 * @method PortfolioPage PortfolioPage()
 * @method CasePage|ManyManyList CasePages()
 * @property int PortfolioPageID
 */
class Category extends DataObject
{
    /** @config */
    private static array $db = [
        'Title' => 'Varchar(255)',
        'Slug' => 'Varchar(255)',
    ];

    /** @config */
    private static string $table_name = 'WeDevelop_Portfolio_Category';

    /** @config */
    private static string $singular_name = 'Category';

    /** @config */
    private static string $plural_name = 'Categories';

    /** @config */
    private static string $icon_class = 'font-icon-rocket';

    /** @config */
    private static array $summary_fields = [
        'Title' => 'Category name',
    ];

    /** @config */
    private static array $has_one = [
        'PortfolioPage' => PortfolioPage::class,
    ];

    /** @config */
    private static array $belongs_many_many = [
        'CasePages' => CasePage::class,
    ];

    public function getCMSFields(): FieldList
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $fields->removeByName([
                'CasePages',
                'PortfolioPageID',
            ]);

            $fields->addFieldsToTab('Root.Cases assigned to', [
                GridField::create('CasePages', 'Case pages', $this->CasePages(), new GridFieldConfig_RecordViewer()),
            ]);

            $fields->renameField('Title', _t(__CLASS__ . '.TITLE', 'Title'));
        });

        return parent::getCMSFields();
    }

    public function IsActive(): bool
    {
        /** @var PortfolioPageController $controller */
        $controller = Controller::curr();
        $URLFilters = $controller->getFiltersFromURL();
        $categories = $URLFilters['categories'];

        if (in_array($this->Slug, explode(',', $categories ?? ''), true)) {
            return true;
        }

        return false;
    }
    protected function onBeforeWrite(): void
    {
        if (empty($this->PortfolioPageID)) {
            $currentPageID = CMSPageEditController::curr()->currentPageID();
            $currentPage = \Page::get_by_id(CasePage::class, $currentPageID);

            if ($currentPage) {
                $this->PortfolioPageID = $currentPage->ParentID;
            }
        }

        $this->Slug = URLSegmentFilter::create()->filter(!empty($this->Slug) ? $this->Slug : $this->Title);

        parent::onBeforeWrite();
    }

    public function getFilterSlug(): string
    {
        return $this->Slug;
    }
}
