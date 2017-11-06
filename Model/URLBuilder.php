<?php

declare(strict_types = 1);

namespace ArchivesOnlineSGV;

/**
 * Class Model_URLBuilder This class build the URL for the extended search request to the salsah API.
 * @package ArchivesOnlineSGV
 */
class Model_URLBuilder {

    /**
     * @var string First part of the extended search request.
     */
    private const URL_PART_01 = "http://www.salsah.org/api/search/?searchtype=extended&property_id[]=1&property_id[]=46&property_id[]=1&compop[]=MATCH&compop[]=EQ&compop[]=EXISTS&searchval[]=";
    /**
     * @var string Second part of the extended search request.
     */
    private const URL_PART_02 = "&searchval[]=GREGORIAN:";
    /**
     * @var string Third part of the extended search request.
     */
    private const URL_PART_03 = "&searchval[]=&show_nrows=";
    /**
     * @var string Fourth part of the extended search request.
     */
    private const URL_PART_04 = "&start_at=0&filter_by_restype=65";

    /**
     * Builds the URL for the request to the salsah API. The search is a extended search which will send the search word, period and the numbers of results.
     * @param string $word The search word
     * @param int $number The number of results which will be returned by salsah API.
     * @param Model_Period $period The time period where the results fit in.
     * @return string Contains the URL with all the information needed to start the request.
     */
        public function extendedURL(string $word, int $number, Model_Period $period):string {
            return static::URL_PART_01 . \urlencode($word) . static::URL_PART_02 . $period->getFromYear() . ":" . $period->getToYear() . static::URL_PART_03 . $number . static::URL_PART_04;
        }
}