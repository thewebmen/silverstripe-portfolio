<?php

namespace WeDevelop\Portfolio\Models;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordViewer;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\ManyManyList;
use SilverStripe\Versioned\GridFieldArchiveAction;
use WeDevelop\Portfolio\ElementalGrid\ElementPortfolio;
use WeDevelop\Portfolio\Pages\CasePage;

/**
 * @property string $Title
 * @method CasePage|ManyManyList CasePages()
 * @method ElementPortfolio|ManyManyList ElementPortfolios()
 */
class Collection extends DataObject
{
    private static array $db = [
        'Title' => 'Varchar(255)',
    ];

    private static string $table_name = 'WeDevelop_Portfolio_Collection';

    private static string $singular_name = 'Collection';

    private static string $plural_name = 'Collections';

    private static string $icon_class = 'font-icon-rocket';

    private static array $summary_fields = [
        'Title',
    ];

    private static array $many_many = [
        'CasePages' => CasePage::class,
        'ElementPortfolios' => ElementPortfolio::class,
    ];

    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        $casesGridConfig = new GridFieldConfig_RelationEditor();
        $casesGridConfig->removeComponentsByType([
            GridFieldAddNewButton::class,
            GridFieldArchiveAction::class,
            GridFieldEditButton::class,
        ]);

        $elementalGridConfig = new GridFieldConfig_RecordViewer();

        $fields->removeByName([
            'CasePages',
            'ElementPortfolios',
        ]);

        if ($this->exists()) {
            $fields->addFieldsToTab('Root.Main', [
                GridField::create('CasePages', 'Cases', $this->CasePages(), $casesGridConfig)
            ]);

            $fields->addFieldsToTab('Root.Grid elements used on', [
                GridField::create('ElementPortfolios', 'Grid elements', $this->ElementPortfolios(), $elementalGridConfig)
            ]);
        } else {
            $fields->addFieldsToTab('Root.Main', [
                new LiteralField('', 'Save the collection first, in order to be able to make changes to the contents of this collection.')
            ]);
        }

        return $fields;
    }
}
