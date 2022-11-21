<?php

declare(strict_types=1);

namespace WeDevelop\Portfolio\Services;

use SilverStripe\ORM\DataList;
use WeDevelop\Portfolio\Filters\CategoryFilter;

final class CaseFilterService
{
    public function __construct(
        private DataList $cases
    ) {
    }

    public function applyCategoryFilter(array $categories): void
    {
        $categoryFilter = new CategoryFilter();
        $this->cases = $categoryFilter->apply($categories, $this->cases);
    }

    public function getCases(): DataList
    {
        return $this->cases;
    }
}
