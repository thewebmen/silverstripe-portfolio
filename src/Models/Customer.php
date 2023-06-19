<?php

declare(strict_types=1);

namespace WeDevelop\Portfolio\Models;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordViewer;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\TextField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\ORM\DataObject;
use SilverStripe\Assets\Image;
use SilverStripe\ORM\HasManyList;
use WeDevelop\Portfolio\Pages\CasePage;

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
    /** @config */
    private static string $table_name = 'WeDevelop_Portfolio_Customer';

    /** @config */
    private static string $singular_name = 'Customer';

    /** @config */
    private static string $plural_name = 'Customers';

    /** @config */
    private static string $icon_class = 'font-icon-block-user';

    /** @config */
    private static array $summary_fields = [
        'Title' => 'Name',
    ];

    /** @config */
    private static array $db = [
        'Title' => 'Varchar(255)',
        'URL' => 'Varchar(255)',
        'FacebookURL' => 'Varchar(255)',
        'TwitterURL' => 'Varchar(255)',
        'LinkedInURL' => 'Varchar(255)',
    ];

    /** @config */
    private static array $has_one = [
        'Logo' => Image::class,
    ];

    /** @config */
    private static array $owns = [
        'Logo',
    ];

    /** @config */
    private static array $has_many = [
        'CasePages' => CasePage::class,
    ];

    public function getCMSFields(): FieldList
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
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

            $fields->renameField('Title', 'Name');
        });

        return parent::getCMSFields();
    }
}
