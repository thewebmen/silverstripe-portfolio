<?php

namespace TheWebmen\Articles\Filters;

use SilverStripe\ORM\DataList;
use TheWebmen\Articles\Interfaces\FilterInterface;
use WeDevelop\Portfolio\Models\Category;

final class CategoryFilter implements FilterInterface
{
    public function apply(array $items, DataList $dataList): DataList
    {
        $categories = $this->getActiveItems($items);

        if (count($categories) === 0) {
            return $dataList;
        }

        return $dataList->filter('Categories.ID', $categories->column('ID'));
    }

    public function getActiveItems(array $items): DataList
    {
        if (empty($items)) {
            return new DataList(Category::class);
        }

        return Category::get()->filter('Slug', $items);
    }
}
