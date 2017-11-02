<?php

declare(strict_types = 1);

namespace ArchivesOnlineSGV;

class Model_URLBuilder {
    private const SEARCH_PREFIX = "http://www.salsah.org/api/search/";
    private const RESOURCES_PREFIX = "http://www.salsah.org/api/resources/";

    private const FULLTEXT_01 = "?searchtype=fulltext&filter_by_project=SGV&show_nrows=";
    private const FULLTEXT_02 = "&start_at=0&filter_by_restype=65";

    private const EXTENDED_01 = "?searchtype=extended&property_id[]=46&property_id[]=1&compop[]=EQ&compop[]=MATCH&searchval[]=GREGORIAN:";
    private const EXTENDED_02 = "&searchval[]=";
    private const EXTENDED_03 = "&show_nrows=";
    private const EXTENDED_04 = "&start_at=0&filter_by_restype=65";

    public function fulltextURL(string $word, int $number):string {
        return self::SEARCH_PREFIX . \urlencode($word) . self::FULLTEXT_01 . $number .self::FULLTEXT_02;
    }

    public function extendedURL(string $word, int $number, Model_Period $period):string {
        return self::SEARCH_PREFIX . self::EXTENDED_01 . $period->getFromYear() .":". $period->getToYear() . self::EXTENDED_02 . \urlencode($word) . self::EXTENDED_03 . $number . self::EXTENDED_04;
    }

    public function resourceURL($id):string {
        return self::RESOURCES_PREFIX . $id;
    }
}