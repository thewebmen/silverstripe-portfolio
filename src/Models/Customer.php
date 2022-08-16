<?php

namespace WeDevelop\Portfolio\Models;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\TextField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\ORM\DataObject;
use SilverStripe\Assets\Image;
use WeDevelop\Portfolio\ElementalGrid\ElementPortfolio;
use WeDevelop\Portfolio\Pages\CasePage;
use WeDevelop\Portfolio\Pages\PortfolioPage;

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
        'URL' => 'Varchar',
        'FacebookURL' => 'Varchar',
        'TwitterURL' => 'Varchar',
        'LinkedInURL' => 'Varchar',
    ];

    private static array $has_one = [
        'Logo' => Image::class,
    ];

    private static array $owns = [
        'Logo',
    ];

    private static array $many_many = [
        'CasePages' => CasePage::class,
    ];

    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        $fields->removeByName(
            [
                'CasePagesID',
                'URL',
                'Logo',
                'FacebookURL',
                'TwitterURL',
                'LinkedInURL',
            ]
        );

        $fields->renameField('Title', 'Name');

        $fields->addFieldsToTab(
            'Root.Main',
            [
                UploadField::create('Logo', 'Logo')->setFolderName('Customer_Logos'),
                HeaderField::create('', 'Social media'),
                TextField::create('FacebookURL', 'Facebook URL'),
                TextField::create('TwitterURL', 'Twitter URL'),
                TextField::create('LinkedInURL', 'LinkedIn URL'),
            ]
        );

        return $fields;
    }
}
