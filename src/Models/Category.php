<?php

namespace WeDevelop\Portfolio\Models;

use SilverStripe\CMS\Controllers\CMSPageEditController;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordViewer;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\ManyManyList;
use SilverStripe\View\Parsers\URLSegmentFilter;
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
    private static array $db = [
        'Title' => 'Varchar(255)',
        'Slug' => 'Varchar(255)',
    ];

    private static string $table_name = 'WeDevelop_Portfolio_Category';

    private static string $singular_name = 'Category';

    private static string $plural_name = 'Categories';

    private static string $icon_class = 'font-icon-rocket';

    private static array $summary_fields = [
        'Title' => 'Category name',
    ];

    private static array $has_one = [
        'PortfolioPage' => PortfolioPage::class,
    ];

    private static array $belongs_many_many = [
        'CasePages' => CasePage::class,
    ];

    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        $fields->removeByName([
            'CasePages',
            'PortfolioPageID',
        ]);

        $fields->addFieldsToTab('Root.Cases assigned to', [
            GridField::create('CasePages', 'Case pages', $this->CasePages(), new GridFieldConfig_RecordViewer()),
        ]);

        $fields->renameField('Title', _t(__CLASS__ . '.TITLE', 'Title'));

        return $fields;
    }

    protected function onBeforeWrite(): void
    {
        $currentPageID = CMSPageEditController::curr()->currentPageID();
        $currentPage = \Page::get_by_id(CasePage::class, $currentPageID);

        if ($currentPage) {
            $this->PortfolioPageID = $currentPage->ParentID;
        }

        $this->Slug = URLSegmentFilter::create()->filter($this->Title);

        parent::onBeforeWrite();
    }
}
