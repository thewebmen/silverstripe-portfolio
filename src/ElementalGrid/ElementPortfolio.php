<?php

namespace WeDevelop\Portfolio\ElementalGrid;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\HasManyList;
use SilverStripe\VersionedAdmin\Extensions\ArchiveRestoreAction;
use UncleCheese\DisplayLogic\Forms\Wrapper;
use WeDevelop\Portfolio\Models\Collection;
use WeDevelop\Portfolio\Pages\CasePage;

/**
 * @property int $CollectionID
 * @property string $Mode
 * @property boolean $ShowMoreCasesButton
 * @property int $MaxAmount
 * @property string $ShowMoreCasesButtonText
 * @method CasePage|HasManyList CasePages()
 * @method Collection Collection()
 */
class ElementPortfolio extends BaseElement
{
    private static string $table_name = 'Element_Portfolio';

    private static string $singular_name = 'Portfolio';

    private static string $plural_name = 'Portfolios';

    private static string $description = 'Show a collection of a portfolio in a grid element';

    private static string $icon = 'font-icon-p-list';

    private const MODE_CUSTOM = 'custom';

    private const MODE_COLLECTION = 'collection';

    private static array $has_one = [
        'Collection' => Collection::class,
    ];

    private static array $many_many = [
        'CasePages' => Collection::class,
    ];

    private static array $db = [
        'ShowMoreCasesButton' => 'Boolean',
        'MaxAmount' => 'Int(3)',
        'ShowMoreCasesButtonText' => 'Varchar(255)',
        'Mode' => 'Varchar(255)',
    ];

    private static array $defaults = [
        'MaxAmount' => 10,
    ];

    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        $gridConfig = new GridFieldConfig_RelationEditor();
        $gridConfig->removeComponentsByType([
            GridFieldAddNewButton::class,
            ArchiveRestoreAction::class,
            GridFieldEditButton::class,
        ]);

        $fields->addFieldsToTab('Root.Main', [
            DropdownField::create('Mode', 'Mode', [
                self::MODE_COLLECTION => 'Select by collection',
                self::MODE_CUSTOM => 'Select custom cases',
            ]),
        ]);

        $fields->removeByName(
            [
                'ShowMoreCasesButton',
                'MaxAmount',
                'Mode',
                'ShowMoreCasesButtonText',
                'PortfolioPageID',
            ]
        );

        if ($this->exists()) {
            $fields->addFieldsToTab(
                'Root.Main',
                [
                    Wrapper::create([
                        DropdownField::create('CollectionID', 'Collection', Collection::get()->map()->toArray()),
                    ])->displayIf('Mode')->isEqualTo('collection')->end(),
                    Wrapper::create([
                        GridField::create('Cases', 'Cases', CasePage::get(), $gridConfig),
                    ])->displayIf('Mode')->isEqualTo('custom')->end(),
                    NumericField::create(
                        'MaxAmount',
                        _t(__CLASS__ . '.MAXAMOUNT', 'Max. amount of articles shown')
                    ),
                    CheckboxField::create(
                        'ShowMoreArticlesButton',
                        _t(__CLASS__ . '.SHOWMOREBUTTON', "Show 'more articles' button")
                    ),
                    TextField::create(
                        'ShowMoreArticlesButtonText',
                        _t(__CLASS__ . '.SHOWMOREBUTTONTEXT', "Show 'more articles' button text")
                    )
                        ->displayIf('ShowMoreArticlesButton')
                        ->isChecked()
                        ->end(),
                ]
            );
        } else {
            $fields->addFieldsToTab('Root.Main', [
                new LiteralField('', 'Save the element first, in order to be able to make changes to the contents of this collection.')
            ]);
        }

        $this->extend('onAfterUpdateCMSFields', $fields);

        return $fields;
    }

    public function getType(): string
    {
        return self::$singular_name;
    }

    public function getCases(): ?DataList
    {
        if ($this->Mode === self::MODE_CUSTOM) {
            return $this->CasePages();
        }

        if ($this->Mode === self::MODE_COLLECTION) {
            return $this->Collection()->CasePages();
        }

        return null;
    }
}
