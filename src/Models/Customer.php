<?php

namespace WeDevelop\Portfolio\Models;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordViewer;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\TextField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\ORM\DataObject;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\HasManyList;
use WeDevelop\Portfolio\ElementalGrid\ElementPortfolio;
use WeDevelop\Portfolio\Pages\CasePage;
use WeDevelop\Portfolio\Pages\PortfolioPage;

/**
 * @property string $Title
 * @property string $URL
 * @property string $FacebookURL
 * @property string $TwitterURL
 * @property string $LinkedInURL
 * @method CasePage|HasManyList CasePages()
 * @method Image Logo()
 */
class Customer extends DataObject
{
    private static string $table_name = 'WeDevelop_Portfolio_Customer';

    private static string $singular_name = 'Customer';

    private static string $plural_name = 'Customers';

    private static string $icon_class = 'font-icon-block-user';

    private static array $summary_fields = [
        'Title' => 'Name',
    ];

    private static array $db = [
        'Title' => 'Varchar(255)',
        'URL' => 'Varchar(255)',
        'FacebookURL' => 'Varchar(255)',
        'TwitterURL' => 'Varchar(255)',
        'LinkedInURL' => 'Varchar(255)',
    ];

    private static array $has_one = [
        'Logo' => Image::class,
    ];

    private static array $owns = [
        'Logo',
    ];

    private static array $has_many = [
        'CasePages' => CasePage::class,
    ];

    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        $fields->removeByName(
            [
                'URL',
                'CasePages',
                'Logo',
                'FacebookURL',
                'TwitterURL',
                'LinkedInURL',
            ]
        );

        $fields->renameField('Title', 'Name');

        $fields->addFieldsToTab('Root.Cases assigned to', [
            GridField::create('CasePages', 'Case pages', $this->CasePages(), new GridFieldConfig_RecordViewer()),
        ]);

        $fields->addFieldsToTab(
            'Root.Main',
            [
                UploadField::create('Logo', 'Logo')->setFolderName('Customer_Logos'),
                TextField::create('URL', 'URL'),
                HeaderField::create('', 'Social media'),
                TextField::create('FacebookURL', 'Facebook URL'),
                TextField::create('TwitterURL', 'Twitter URL'),
                TextField::create('LinkedInURL', 'LinkedIn URL'),
            ]
        );

        return $fields;
    }
}
