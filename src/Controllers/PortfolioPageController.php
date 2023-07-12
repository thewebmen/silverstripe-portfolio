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
    protected ?DataList $cases;

    public function getCategories(): ?DataList
    {
        return $this->data()->getCategories();
    }

    public function CasesFilterForm(): CasesFilterForm
    {
        return new CasesFilterForm($this);
    }

    public function index(): static
    {
        return $this;
    }

    protected function getCasesDataList(): ?DataList
    {
        $cases = CasePage::get()->filter([
            'ParentID' => $this->data()->ID,
        ])->sort('CaseSort');

        $this->extend('updateCasesDataList', $cases);

        return $cases;
    }

    public function init(): ?DataList
    {
        parent::init();

        $this->cases = $this->getCasesDataList();

        if (!is_null($this->cases)) {
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

        if ($URLFilters['categories']) {
            $filterService->applyCategoryFilter(explode(',', $URLFilters['categories']));
        }

        $this->cases = $filterService->getCases();
    }

    public function hasActiveFilters(): bool
    {
        $URLFilters = $this->getFiltersFromURL();
        return (bool)$URLFilters['categories'];
    }

    public function getFiltersFromURL(): array
    {
        return [
            'categories' => $this->getRequest()->getVar('category'),
        ];
    }
}
