<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
include("Model/URLBuilder.php");
include("Model/Period.php");

class URLBuilderTest extends TestCase{
    private const NO_WORDS      = [""];
    private const ONE_WORD      = ["Haus"];
    private const TWO_WORDS     = ["Haus", "Kinder"];
    private const THREE_WORDS   = ["Haus", "Kinder", "spielen"];
    private const NUMBERS = 50;
    private const FROM_YEAR  = 1;
    private const TO_YEAR = 2017;
    protected $urlBuilder;
    protected $period;

    protected function setUp(): void {
        $this->urlBuilder = new \ArchivesOnlineSGV\Model_URLBuilder();
        $this->period = $period = new \ArchivesOnlineSGV\Model_Period(static::FROM_YEAR, static::TO_YEAR);
    }

    /**
     * @test Case when there is NO search word and conjunction AND
     */
    public function and_NoWords(): void {
        $actualURL = $this->urlBuilder->getSearchURL(static::NO_WORDS, static::NUMBERS, $this->period, true);
        $expectedURL = "http://www.salsah.org/api/search/?searchtype=extended&property_id[]=46&property_id[]=1&property_id[]=1&compop[]=EQ&compop[]=EXISTS&compop[]=MATCH&searchval[]=GREGORIAN:1:2017&searchval[]=&searchval[]=&show_nrows=50&filter_by_restype=65";
        $this->assertEquals($expectedURL, $actualURL);
    }

    /**
     * @test Case when there is ONE search word and conjunction AND
     */
    public function and_OneWord(): void {
        $actualURL = $this->urlBuilder->getSearchURL(static::ONE_WORD, static::NUMBERS, $this->period, true);
        $expectedURL = "http://www.salsah.org/api/search/?searchtype=extended&property_id[]=46&property_id[]=1&property_id[]=1&compop[]=EQ&compop[]=EXISTS&compop[]=MATCH&searchval[]=GREGORIAN:1:2017&searchval[]=&searchval[]=Haus&show_nrows=50&filter_by_restype=65";
        $this->assertEquals($expectedURL, $actualURL);
    }

    /**
     * @test Case when there is TWO search words and conjunction AND
     */
    public function and_TwoWords(): void {
        $actualURL = $this->urlBuilder->getSearchURL(static::TWO_WORDS, static::NUMBERS, $this->period, true);
        $expectedURL = "http://www.salsah.org/api/search/?searchtype=extended&property_id[]=46&property_id[]=1&property_id[]=1&property_id[]=1&compop[]=EQ&compop[]=EXISTS&compop[]=MATCH&compop[]=MATCH&searchval[]=GREGORIAN:1:2017&searchval[]=&searchval[]=Haus&searchval[]=Kinder&show_nrows=50&filter_by_restype=65";
        $this->assertEquals($expectedURL, $actualURL);
    }

    /**
     * @test Case when there is THREE search words and conjunction AND
     */
    public function and_ThreeWords(): void {
        $actualURL = $this->urlBuilder->getSearchURL(static::THREE_WORDS, static::NUMBERS, $this->period, true);
        $expectedURL = "http://www.salsah.org/api/search/?searchtype=extended&property_id[]=46&property_id[]=1&property_id[]=1&property_id[]=1&property_id[]=1&compop[]=EQ&compop[]=EXISTS&compop[]=MATCH&compop[]=MATCH&compop[]=MATCH&searchval[]=GREGORIAN:1:2017&searchval[]=&searchval[]=Haus&searchval[]=Kinder&searchval[]=spielen&show_nrows=50&filter_by_restype=65";
        $this->assertEquals($expectedURL, $actualURL);
    }

    /**
     * @test Case when there is NO search word and conjunction OR
     */
    public function or_NoWords(): void {
        $actualURL = $this->urlBuilder->getSearchURL(static::NO_WORDS, static::NUMBERS, $this->period, false);
        $expectedURL = "http://www.salsah.org/api/search/?searchtype=extended&property_id[]=46&property_id[]=1&property_id[]=1&compop[]=EQ&compop[]=EXISTS&compop[]=MATCH&searchval[]=GREGORIAN:1:2017&searchval[]=&searchval[]=&show_nrows=50&filter_by_restype=65";
        $this->assertEquals($expectedURL, $actualURL);
    }

    /**
     * @test Case when there is ONE search word and conjunction OR
     */
    public function or_OneWord(): void {
        $actualURL = $this->urlBuilder->getSearchURL(static::ONE_WORD, static::NUMBERS, $this->period, false);
        $expectedURL = "http://www.salsah.org/api/search/?searchtype=extended&property_id[]=46&property_id[]=1&property_id[]=1&compop[]=EQ&compop[]=EXISTS&compop[]=MATCH&searchval[]=GREGORIAN:1:2017&searchval[]=&searchval[]=Haus&show_nrows=50&filter_by_restype=65";
        $this->assertEquals($expectedURL, $actualURL);
    }

    /**
     * @test Case when there is TWO search words and conjunction OR
     */
    public function or_TwoWords(): void {
        $actualURL = $this->urlBuilder->getSearchURL(static::TWO_WORDS, static::NUMBERS, $this->period, false);
        $expectedURL = "http://www.salsah.org/api/search/?searchtype=extended&property_id[]=46&property_id[]=1&property_id[]=1&compop[]=EQ&compop[]=EXISTS&compop[]=MATCH&searchval[]=GREGORIAN:1:2017&searchval[]=&searchval[]=Haus%2BKinder&show_nrows=50&filter_by_restype=65";
        $this->assertEquals($expectedURL, $actualURL);
    }

    /**
     * @test Case when there is THREE search words and conjunction OR
     */
    public function or_ThreeWords(): void {
        $actualURL = $this->urlBuilder->getSearchURL(static::THREE_WORDS, static::NUMBERS, $this->period, false);
        $expectedURL = "http://www.salsah.org/api/search/?searchtype=extended&property_id[]=46&property_id[]=1&property_id[]=1&compop[]=EQ&compop[]=EXISTS&compop[]=MATCH&searchval[]=GREGORIAN:1:2017&searchval[]=&searchval[]=Haus%2BKinder%2Bspielen&show_nrows=50&filter_by_restype=65";
        $this->assertEquals($expectedURL, $actualURL, "hi");
    }

}
