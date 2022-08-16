<?php

namespace WeDevelop\Portfolio\Controllers;

use SilverStripe\ORM\DataList;
use SilverStripe\ORM\PaginatedList;
use WeDevelop\Portfolio\Forms\CasesFilterForm;
use WeDevelop\Portfolio\Pages\CasePage;
use WeDevelop\Portfolio\Pages\PortfolioPage;
use WeDevelop\Portfolio\Services\CaseFilterService;

/**
 * @method PortfolioPage data()
 */
class PortfolioPageController extends \PageController
{
    protected DataList $cases;

    public function getThemes(): ?DataList
    {
        return $this->data()->hasMethod('getThemes') ? $this->data()->getThemes() : null;
    }

    public function getTypes(): ?DataList
    {
        return $this->data()->hasMethod('getTypes') ? $this->data()->getTypes() : null;
    }

    public function CasesFilterForm(): CasesFilterForm
    {
        return new CasesFilterForm($this);
    }

    public function index()
    {
        return $this;
    }

    protected function getCasesDataList(): ?DataList
    {
        $cases = CasePage::get()->filter('ParentID', $this->data()->ID);

        $this->extend('updateCasesDataList', $cases);

        return $cases;
    }

    public function init(): ?DataList
    {
        parent::init();

        $this->cases = $this->getCasesDataList();

        if ($this->cases) {
            $this->applyFilters();
        }

        $this->extend('onAfterInit', $this);

        return $this->cases;
    }

    public function PaginatedCases(): ?PaginatedList
    {
        $pageLength = $this->data() instanceof PortfolioPage ? $this->data()->PageLength : $this->data()->Parent()->PageLength;

        $pagination = PaginatedList::create($this->cases, $this->getRequest());
        $pagination->setPageLength($pageLength);
        $pagination->setPaginationGetVar('p');

        $this->extend('updatePaginatedCases', $pagination);

        return $pagination;
    }

    public function getCases(): ?DataList
    {
        return $this->cases;
    }

    private function applyFilters(): void
    {
        $URLFilters = $this->getFiltersFromURL();
        $filterService = new CaseFilterService($this->cases);

        var_dump($URLFilters);
        if ($URLFilters['category']) {
            $filterService->applyCategoryFilter(explode(',', $URLFilters['category']));
        }

        $this->cases = $filterService->getCases();
    }

    public function hasActiveFilters(): bool
    {
        $URLFilters = $this->getFiltersFromURL();
        return (bool)$URLFilters['category'];
    }

    public function getCategories(): ?DataList
    {
        return $this->data()->hasMethod('getCategories') ? $this->data()->getCategories() : null;
    }

    public function getFiltersFromURL(): array
    {
        return [
            'category' => $this->getRequest()->getVar('category'),
        ];
    }
}
