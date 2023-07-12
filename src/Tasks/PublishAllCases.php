<?php

namespace WeDevelop\ElementalGrid\Tasks;

use SilverStripe\Dev\BuildTask;
use WeDevelop\Portfolio\Pages\CasePage;

class PublishAllCases extends BuildTask
{
    protected $title = 'Publish all cases';

    protected $description = 'Republish all cases';

    private static string $segment = 'publish-cases';

    public function run($request)
    {
        $cases = CasePage::get();

        $counter = 0;
        $totalElements = $cases->count();

        print_r(sprintf("Starting publication of %s pages\n\n", $totalElements));

        foreach ($cases as $case) {
            $isPublished = $case->isPublished();

            if ($isPublished) {
                $case->publishSingle();
            }

            $counter++;

            print_r(sprintf("Published %s of %s pages\n", $counter, $totalElements));
        }

        print_r(sprintf("\n\nPublications done for %s pages", $counter));
    }
}
