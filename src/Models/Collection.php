<?php

declare(strict_types=1);

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
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use WeDevelop\Portfolio\ElementalGrid\ElementPortfolio;
use WeDevelop\Portfolio\Pages\CasePage;

/**
 * @property string $Title
 * @method CasePage|ManyManyList CasePages()
 * @method ElementPortfolio|ManyManyList ElementPortfolios()
 */
class Collection extends DataObject
{
    /** @config */
    private static array $db = [
        'Title' => 'Varchar(255)',
    ];

    /** @config */
    private static string $table_name = 'WeDevelop_Portfolio_Collection';

    /** @config */
    private static string $singular_name = 'Collection';

    /** @config */
    private static string $plural_name = 'Collections';

    /** @config */
    private static string $icon_class = 'font-icon-rocket';

    /** @config */
    private static array $summary_fields = [
        'Title',
    ];

    /** @config */
    private static array $has_many = [
        'ElementPortfolios' => ElementPortfolio::class,
    ];

    /** @config */
    private static array $many_many = [
        'CasePages' => CasePage::class,
    ];

    /** @config */
    private static array $many_many_extraFields = [
        'CasePages' => [
            'CasesSort' => 'Int',
        ],
    ];

    public function getCMSFields(): FieldList
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $casesGridConfig = new GridFieldConfig_RelationEditor();
            $casesGridConfig->removeComponentsByType([
                GridFieldAddNewButton::class,
                GridFieldArchiveAction::class,
                GridFieldEditButton::class,
            ]);
            $casesGridConfig->addComponent(new GridFieldOrderableRows('CasesSort'));

            $elementalGridConfig = new GridFieldConfig_RecordViewer();

            $fields->removeByName([
                'CasePages',
                'ElementPortfolios',
            ]);

            if ($this->exists()) {
                $fields->addFieldsToTab('Root.Main', [
                    GridField::create('CasePages', 'Cases', $this->CasePages(), $casesGridConfig),
                ]);

                $fields->addFieldsToTab('Root.Grid elements used on', [
                    GridField::create('ElementPortfolios', 'Grid elements', $this->ElementPortfolios(), $elementalGridConfig),
                ]);
            } else {
                $fields->addFieldsToTab('Root.Main', [
                    new LiteralField('', 'Save the collection first, in order to be able to make changes to the contents of this collection.'),
                ]);
            }
        });

        return parent::getCMSFields();
    }
}
