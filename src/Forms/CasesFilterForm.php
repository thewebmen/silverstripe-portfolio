<?php

namespace WeDevelop\Portfolio\Forms;

use SilverStripe\Control\RequestHandler;
use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\View\Requirements;

class CasesFilterForm extends Form
{
    public function __construct(RequestHandler $controller = null, $name = self::DEFAULT_NAME)
    {
        $fields = new FieldList();

        $fields->push(
            CheckboxSetField::create(
                'category',
                _t('WeDevelop\Portfolio\Models\Category.SINGULARNAME', 'Category'),
                $controller->getCategories()->map('Slug', 'Title')->toArray()
            )
        );

        $actions = new FieldList(
            FormAction::create('doCasesFilterForm', 'Filter')
                ->setName('')
        );


        parent::__construct($controller, '', $fields, $actions);

        $formdata = [];

        foreach ($controller->getRequest()->getVars() as $key => $value) {
            if (strpos($value, ',') !== false) {
                $formdata[$key] = explode(',', $value);
            } else {
                $formdata[$key] = $value;
            }
        }

        $this->setHTMLID('WeDevelop_Portfolio_CasesFilterForm');
        $this->loadDataFrom($formdata);
        $this->setFormMethod('GET');
        $this->disableSecurityToken();
    }

    /**
     * @return DBHTMLText
     */
    public function forTemplate()
    {
        Requirements::javascript('wedevelopnl/silverstripe-portfolio:client/dist/main.js');

        return parent::forTemplate();
    }
}
