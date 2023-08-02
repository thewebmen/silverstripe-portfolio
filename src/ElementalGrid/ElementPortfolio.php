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
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\HasManyList;
use SilverStripe\Versioned\GridFieldArchiveAction;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
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
    /** @config */
    private static string $table_name = 'Element_Portfolio';

    /** @config */
    private static string $singular_name = 'Portfolio - Overview';

    /** @config */
    private static string $plural_name = 'Portfolio - Overview elements';

    /** @config */
    private static string $description = 'Show an overview of a portfolio in a grid element';

    /** @config */
    private static string $icon = 'font-icon-book-open';

    private const MODE_CUSTOM = 'custom';

    private const MODE_COLLECTION = 'collection';

    /** @config */
    private static array $db = [
        'Content' => 'HTMLText',
        'ShowMoreCasesButton' => 'Boolean',
        'MaxAmount' => 'Int(3)',
        'ShowMoreCasesButtonText' => 'Varchar(255)',
        'Mode' => 'Varchar(255)',
    ];

    /** @config */
    private static array $has_one = [
        'Collection' => Collection::class,
        'PortfolioPage' => PortfolioPage::class,
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

    /** @config */
    private static array $defaults = [
        'MaxAmount' => 10,
    ];

    public function getCMSFields(): FieldList
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $gridConfig = new GridFieldConfig_RelationEditor();
            $gridConfig->removeComponentsByType([
                GridFieldAddNewButton::class,
                GridFieldArchiveAction::class,
                GridFieldEditButton::class,
            ]);
            $gridConfig->addComponent(new GridFieldOrderableRows('CasesSort'));

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
                        HTMLEditorField::create('Content', 'Content'),
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
                        HeaderField::create('ShowMoreButton', _t(__CLASS__ . '.SHOWMOREBUTTON', "Show 'more cases' button"))->setHeadingLevel(2)->addExtraClass('mt-5'),
                        CheckboxField::create(
                            'ShowMoreCasesButton',
                            _t(__CLASS__ . '.SHOWMOREBUTTON', "Show 'more cases' button")
                        ),
                        Wrapper::create([
                            TextField::create(
                                'ShowMoreCasesButtonText',
                                _t(__CLASS__ . '.MOREBUTTONTEXT', "'More cases' button text")
                            ),
                            TreeDropdownField::create('PortfolioPageID', 'Portfolio page', SiteTree::class),
                        ])->displayIf('ShowMoreCasesButton')->isChecked()->end(),
                        NumericField::create(
                            'MaxAmount',
                            _t(__CLASS__ . '.MAXAMOUNT', 'Max. amount of cases shown')
                        ),
                    ]
                );
            } else {
                $fields->addFieldsToTab('Root.Main', [
                    new LiteralField('', 'Save the element first, in order to be able to make changes to the contents of this collection.'),
                ]);
            }
        });

        return parent::getCMSFields();
    }

    public function getType(): string
    {
        return self::$singular_name;
    }

    public function getCases(): ?DataList
    {
        if ($this->Mode === self::MODE_CUSTOM && $this->CasePages()) {
            return $this->CasePages()->Limit($this->MaxAmount)->Sort('CasesSort');
        }

        if ($this->Mode === self::MODE_COLLECTION && $this->Collection()->exists()) {
            return $this->Collection()->CasePages()->Limit($this->MaxAmount)->Sort('CasesSort');
        }

        return null;
    }
}
