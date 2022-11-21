<?php

namespace WeDevelop\Portfolio\Interfaces;

use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;

interface FilterInterface
{
    public function apply(array $items, DataList $dataList): DataList;

    public function getActiveItems(array $items): DataList|DataObject;
}
