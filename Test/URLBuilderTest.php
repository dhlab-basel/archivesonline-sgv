<?php

use PHPUnit\Framework\TestCase;
include("Model/URLBuilder.php");
include("Model/Period.php");

class URLBuilderTest extends TestCase{
    protected $noWords      = [""];
    protected $oneWord      = ["Haus"];
    protected $twoWords     = ["Haus", "Kinder"];
    protected $threeWords   = ["Haus", "Kinder", "spielen"];
    protected $numbers = 50;
    protected $fromYear  = 1;
    protected $toYear = 2017;
    protected $urlBuilder;
    protected $period;

    protected function setUp() {
        $this->urlBuilder = new \ArchivesOnlineSGV\Model_URLBuilder();
        $this->period = $period = new \ArchivesOnlineSGV\Model_Period($this->fromYear, $this->toYear);
    }

    /**
     * @test Case when there is NO search word and conjunction AND
     */
    public function and_NoWords() {
        $actualURL = $this->urlBuilder->getSearchURL($this->noWords, $this->numbers, $this->period, true);
        $expectedURL = "http://www.salsah.org/api/search/?searchtype=extended&property_id[]=46&property_id[]=1&property_id[]=1&compop[]=EQ&compop[]=EXISTS&compop[]=MATCH&searchval[]=GREGORIAN:1:2017&searchval[]=&searchval[]=&show_nrows=50&filter_by_restype=65";
        $this->assertEquals($expectedURL, $actualURL);
    }

    /**
     * @test Case when there is ONE search word and conjunction AND
     */
    public function and_OneWord() {
        $actualURL = $this->urlBuilder->getSearchURL($this->oneWord, $this->numbers, $this->period, true);
        $expectedURL = "http://www.salsah.org/api/search/?searchtype=extended&property_id[]=46&property_id[]=1&property_id[]=1&compop[]=EQ&compop[]=EXISTS&compop[]=MATCH&searchval[]=GREGORIAN:1:2017&searchval[]=&searchval[]=Haus&show_nrows=50&filter_by_restype=65";
        $this->assertEquals($expectedURL, $actualURL);
    }

    /**
     * @test Case when there is TWO search words and conjunction AND
     */
    public function and_TwoWords() {
        $actualURL = $this->urlBuilder->getSearchURL($this->twoWords, $this->numbers, $this->period, true);
        $expectedURL = "http://www.salsah.org/api/search/?searchtype=extended&property_id[]=46&property_id[]=1&property_id[]=1&property_id[]=1&compop[]=EQ&compop[]=EXISTS&compop[]=MATCH&compop[]=MATCH&searchval[]=GREGORIAN:1:2017&searchval[]=&searchval[]=Haus&searchval[]=Kinder&show_nrows=50&filter_by_restype=65";
        $this->assertEquals($expectedURL, $actualURL);
    }

    /**
     * @test Case when there is THREE search words and conjunction AND
     */
    public function and_ThreeWords() {
        $actualURL = $this->urlBuilder->getSearchURL($this->threeWords, $this->numbers, $this->period, true);
        $expectedURL = "http://www.salsah.org/api/search/?searchtype=extended&property_id[]=46&property_id[]=1&property_id[]=1&property_id[]=1&property_id[]=1&compop[]=EQ&compop[]=EXISTS&compop[]=MATCH&compop[]=MATCH&compop[]=MATCH&searchval[]=GREGORIAN:1:2017&searchval[]=&searchval[]=Haus&searchval[]=Kinder&searchval[]=spielen&show_nrows=50&filter_by_restype=65";
        $this->assertEquals($expectedURL, $actualURL);
    }

    /**
     * @test Case when there is NO search word and conjunction OR
     */
    public function or_NoWords() {
        $actualURL = $this->urlBuilder->getSearchURL($this->noWords, $this->numbers, $this->period, false);
        $expectedURL = "http://www.salsah.org/api/search/?searchtype=extended&property_id[]=46&property_id[]=1&property_id[]=1&compop[]=EQ&compop[]=EXISTS&compop[]=MATCH&searchval[]=GREGORIAN:1:2017&searchval[]=&searchval[]=&show_nrows=50&filter_by_restype=65";
        $this->assertEquals($expectedURL, $actualURL);
    }

    /**
     * @test Case when there is ONE search word and conjunction OR
     */
    public function or_OneWord() {
        $actualURL = $this->urlBuilder->getSearchURL($this->oneWord, $this->numbers, $this->period, false);
        $expectedURL = "http://www.salsah.org/api/search/?searchtype=extended&property_id[]=46&property_id[]=1&property_id[]=1&compop[]=EQ&compop[]=EXISTS&compop[]=MATCH&searchval[]=GREGORIAN:1:2017&searchval[]=&searchval[]=Haus&show_nrows=50&filter_by_restype=65";
        $this->assertEquals($expectedURL, $actualURL);
    }

    /**
     * @test Case when there is TWO search words and conjunction OR
     */
    public function or_TwoWords() {
        $actualURL = $this->urlBuilder->getSearchURL($this->twoWords, $this->numbers, $this->period, false);
        $expectedURL = "http://www.salsah.org/api/search/?searchtype=extended&property_id[]=46&property_id[]=1&property_id[]=1&compop[]=EQ&compop[]=EXISTS&compop[]=MATCH&searchval[]=GREGORIAN:1:2017&searchval[]=&searchval[]=Haus%2BKinder&show_nrows=50&filter_by_restype=65";
        $this->assertEquals($expectedURL, $actualURL);
    }

    /**
     * @test Case when there is THREE search words and conjunction OR
     */
    public function or_ThreeWords() {
        $actualURL = $this->urlBuilder->getSearchURL($this->threeWords, $this->numbers, $this->period, false);
        $expectedURL = "http://www.salsah.org/api/search/?searchtype=extended&property_id[]=46&property_id[]=1&property_id[]=1&compop[]=EQ&compop[]=EXISTS&compop[]=MATCH&searchval[]=GREGORIAN:1:2017&searchval[]=&searchval[]=Haus%2BKinder%2Bspielen&show_nrows=50&filter_by_restype=65";
        $this->assertEquals($expectedURL, $actualURL, "hi");
    }

}
