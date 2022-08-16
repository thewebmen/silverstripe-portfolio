<?php

namespace WeDevelop\Portfolio\ElementalGrid;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\HasManyList;
use SilverStripe\Versioned\GridFieldArchiveAction;
use SilverStripe\VersionedAdmin\Extensions\ArchiveRestoreAction;
use UncleCheese\DisplayLogic\Forms\Wrapper;
use WeDevelop\Portfolio\Models\Collection;
use WeDevelop\Portfolio\Pages\CasePage;
use WeDevelop\Portfolio\Pages\PortfolioPage;

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

    private static array $db = [
        'ShowMoreCasesButton' => 'Boolean',
        'MaxAmount' => 'Int(3)',
        'ShowMoreCasesButtonText' => 'Varchar(255)',
        'Mode' => 'Varchar(255)',
    ];

    private static array $has_one = [
        'Collection' => Collection::class,
        'PortfolioPage' => PortfolioPage::class,
    ];

    private static array $many_many = [
        'CasePages' => CasePage::class,
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
            GridFieldArchiveAction::class,
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
                'CasePages',
                'ShowMoreCasesButtonText',
                'PortfolioPageID',
                'CollectionID',
            ]
        );

        if ($this->exists()) {
            $fields->addFieldsToTab(
                'Root.Main',
                [
                    DropdownField::create('Mode', 'Cases selection mode', [
                        self::MODE_COLLECTION => 'Choose from collection',
                        self::MODE_CUSTOM => 'Choose custom',
                    ]),
                    Wrapper::create([
                        DropdownField::create('CollectionID', 'Collection', Collection::get()->map()->toArray()),
                    ])->displayIf('Mode')->isEqualTo(self::MODE_COLLECTION)->end(),
                    Wrapper::create([
                        GridField::create('CasePages', 'Cases', $this->CasePages(), $gridConfig)->addExtraClass('mt-5'),
                    ])->displayIf('Mode')->isEqualTo(self::MODE_CUSTOM)->end(),
                    HeaderField::create('Show more button')->setHeadingLevel(1)->addExtraClass('mt-5'),
                    CheckboxField::create(
                        'ShowMoreCasesButton',
                        _t(__CLASS__ . '.SHOWMOREBUTTON', "Show 'more cases' button")
                    ),
                    Wrapper::create([
                        TextField::create(
                            'ShowMoreCasesButtonText',
                            _t(__CLASS__ . '.MOREBUTTONTEXT', "'More cases' button text")
                        ),
                        TreeDropdownField::create('PortfolioPageID', 'Portfolio page', SiteTree::class)
                    ])->displayIf('ShowMoreCasesButton')->isChecked()->end(),
                    NumericField::create(
                        'MaxAmount',
                        _t(__CLASS__ . '.MAXAMOUNT', 'Max. amount of cases shown')
                    ),
                ]
            );
        } else {
            $fields->addFieldsToTab('Root.Main', [
                new LiteralField('', 'Save the element first, in order to be able to make changes to the contents of this collection.')
            ]);
        }

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
