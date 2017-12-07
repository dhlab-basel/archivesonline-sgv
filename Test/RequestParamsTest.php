<?php

use PHPUnit\Framework\TestCase;
include("Model/RequestParams.php");
include("Config.php");

class RequestParamsTest extends TestCase {

    /**
     * @test
     */
    public function invalidQuery_NoAnd() {
        $request = "Invalid";

        $this->expectExceptionMessage("Invalid query string (preg_match -> fail)");
        \ArchivesOnlineSGV\Model_RequestParams::fromArchivesOnlineRequest($request,50);
    }

    /**
     * @test
     */
    public function invalidQuery_TwoAnd() {
        $request = "Serverchoice all \"Haus\" AND isad.date WITHIN \"2000 2000\" AND invalid";

        $this->expectExceptionMessage("Invalid query string");
        \ArchivesOnlineSGV\Model_RequestParams::fromArchivesOnlineRequest($request,50);
    }

    /**
     * @test
     */
    public function all_NoWords_WithoutDate() {
        $request = "Serverchoice all \"\"";
        $requestParam = \ArchivesOnlineSGV\Model_RequestParams::fromArchivesOnlineRequest($request,50);

        $this->assertEquals(50, $requestParam->getMaxRecords());
        $this->assertEquals(null, $requestParam->getPeriod());
        $this->assertEquals(1, count($requestParam->getSearch()));
        $this->assertEquals("", $requestParam->getSearch()[0]);
        $this->assertEquals(true, $requestParam->isAND());
    }

    /**
     * @test
     */
    public function all_NoWords_SameYear() {
        $request = "Serverchoice all \"\" AND isad.date WITHIN \"2000 2000\"";
        $requestParam = \ArchivesOnlineSGV\Model_RequestParams::fromArchivesOnlineRequest($request,50);

        $this->assertEquals(50, $requestParam->getMaxRecords());
        $this->assertEquals(2000, $requestParam->getPeriod()->getFromYear());
        $this->assertEquals(2000, $requestParam->getPeriod()->getToYear());
        $this->assertEquals(1, count($requestParam->getSearch()));
        $this->assertEquals("", $requestParam->getSearch()[0]);
        $this->assertEquals(true, $requestParam->isAND());
    }

    /**
     * @test
     */
    public function all_NoWords_DifferentYear() {
        $request = "Serverchoice all \"\" AND isad.date WITHIN \"1 2017\"";
        $requestParam = \ArchivesOnlineSGV\Model_RequestParams::fromArchivesOnlineRequest($request,50);

        $this->assertEquals(50, $requestParam->getMaxRecords());
        $this->assertEquals(1, $requestParam->getPeriod()->getFromYear());
        $this->assertEquals(2017, $requestParam->getPeriod()->getToYear());
        $this->assertEquals(1, count($requestParam->getSearch()));
        $this->assertEquals("", $requestParam->getSearch()[0]);
        $this->assertEquals(true, $requestParam->isAND());
    }

    /**
     * @test
     */
    public function any_NoWords_WithoutDate() {
        $request = "Serverchoice any \"\"";
        $requestParam = \ArchivesOnlineSGV\Model_RequestParams::fromArchivesOnlineRequest($request,50);

        $this->assertEquals(50, $requestParam->getMaxRecords());
        $this->assertEquals(null, $requestParam->getPeriod());
        $this->assertEquals(1, count($requestParam->getSearch()));
        $this->assertEquals("", $requestParam->getSearch()[0]);
        $this->assertEquals(false, $requestParam->isAND());
    }

    /**
     * @test
     */
    public function any_NoWords_SameYear() {
        $request = "Serverchoice any \"\" AND isad.date WITHIN \"2000 2000\"";
        $requestParam = \ArchivesOnlineSGV\Model_RequestParams::fromArchivesOnlineRequest($request,50);

        $this->assertEquals(50, $requestParam->getMaxRecords());
        $this->assertEquals(2000, $requestParam->getPeriod()->getFromYear());
        $this->assertEquals(2000, $requestParam->getPeriod()->getToYear());
        $this->assertEquals(1, count($requestParam->getSearch()));
        $this->assertEquals("", $requestParam->getSearch()[0]);
        $this->assertEquals(false, $requestParam->isAND());
    }

    /**
     * @test
     */
    public function any_NoWords_DifferentYear() {
        $request = "Serverchoice any \"\" AND isad.date WITHIN \"1 2017\"";
        $requestParam = \ArchivesOnlineSGV\Model_RequestParams::fromArchivesOnlineRequest($request,50);

        $this->assertEquals(50, $requestParam->getMaxRecords());
        $this->assertEquals(1, $requestParam->getPeriod()->getFromYear());
        $this->assertEquals(2017, $requestParam->getPeriod()->getToYear());
        $this->assertEquals(1, count($requestParam->getSearch()));
        $this->assertEquals("", $requestParam->getSearch()[0]);
        $this->assertEquals(false, $requestParam->isAND());
    }

    /**
     * @test
     */
    public function all_OneWord_WithoutDate() {
        $request = "Serverchoice all \"Haus\"";
        $requestParam = \ArchivesOnlineSGV\Model_RequestParams::fromArchivesOnlineRequest($request,50);

        $this->assertEquals(50, $requestParam->getMaxRecords());
        $this->assertEquals(null, $requestParam->getPeriod());
        $this->assertEquals(1, count($requestParam->getSearch()));
        $this->assertEquals("Haus", $requestParam->getSearch()[0]);
        $this->assertEquals(true, $requestParam->isAND());
    }

    /**
     * @test
     */
    public function all_OneWord_SameYear() {
        $request = "Serverchoice all \"Haus\" AND isad.date WITHIN \"2000 2000\"";
        $requestParam = \ArchivesOnlineSGV\Model_RequestParams::fromArchivesOnlineRequest($request,50);

        $this->assertEquals(50, $requestParam->getMaxRecords());
        $this->assertEquals(2000, $requestParam->getPeriod()->getFromYear());
        $this->assertEquals(2000, $requestParam->getPeriod()->getToYear());
        $this->assertEquals(1, count($requestParam->getSearch()));
        $this->assertEquals("Haus", $requestParam->getSearch()[0]);
        $this->assertEquals(true, $requestParam->isAND());
    }

    /**
     * @test
     */
    public function all_OneWord_DifferentYear() {
        $request = "Serverchoice all \"Haus\" AND isad.date WITHIN \"1 2017\"";
        $requestParam = \ArchivesOnlineSGV\Model_RequestParams::fromArchivesOnlineRequest($request,50);

        $this->assertEquals(50, $requestParam->getMaxRecords());
        $this->assertEquals(1, $requestParam->getPeriod()->getFromYear());
        $this->assertEquals(2017, $requestParam->getPeriod()->getToYear());
        $this->assertEquals(1, count($requestParam->getSearch()));
        $this->assertEquals("Haus", $requestParam->getSearch()[0]);
        $this->assertEquals(true, $requestParam->isAND());
    }

    /**
     * @test
     */
    public function all_TwoWords_WithoutDate() {
        $request = "Serverchoice all \"Haus Kinder\"";
        $requestParam = \ArchivesOnlineSGV\Model_RequestParams::fromArchivesOnlineRequest($request,50);

        $this->assertEquals(50, $requestParam->getMaxRecords());
        $this->assertEquals(null, $requestParam->getPeriod());
        $this->assertEquals(2, count($requestParam->getSearch()));
        $this->assertEquals("Haus", $requestParam->getSearch()[0]);
        $this->assertEquals("Kinder", $requestParam->getSearch()[1]);
        $this->assertEquals(true, $requestParam->isAND());
    }

    /**
     * @test
     */
    public function all_TwoWords_SameYear() {
        $request = "Serverchoice all \"Haus Kinder\" AND isad.date WITHIN \"2000 2000\"";
        $requestParam = \ArchivesOnlineSGV\Model_RequestParams::fromArchivesOnlineRequest($request,50);

        $this->assertEquals(50, $requestParam->getMaxRecords());
        $this->assertEquals(2000, $requestParam->getPeriod()->getFromYear());
        $this->assertEquals(2000, $requestParam->getPeriod()->getToYear());
        $this->assertEquals(2, count($requestParam->getSearch()));
        $this->assertEquals("Haus", $requestParam->getSearch()[0]);
        $this->assertEquals("Kinder", $requestParam->getSearch()[1]);
        $this->assertEquals(true, $requestParam->isAND());
    }

    /**
     * @test
     */
    public function all_TwoWords_DifferentYear() {
        $request = "Serverchoice all \"Haus Kinder\" AND isad.date WITHIN \"1 2017\"";
        $requestParam = \ArchivesOnlineSGV\Model_RequestParams::fromArchivesOnlineRequest($request,50);

        $this->assertEquals(50, $requestParam->getMaxRecords());
        $this->assertEquals(1, $requestParam->getPeriod()->getFromYear());
        $this->assertEquals(2017, $requestParam->getPeriod()->getToYear());
        $this->assertEquals(2, count($requestParam->getSearch()));
        $this->assertEquals("Haus", $requestParam->getSearch()[0]);
        $this->assertEquals("Kinder", $requestParam->getSearch()[1]);
        $this->assertEquals(true, $requestParam->isAND());
    }

    /**
     * @test
     */
    public function any_OneWord_WithoutDate() {
        $request = "Serverchoice any \"Haus\"";
        $requestParam = \ArchivesOnlineSGV\Model_RequestParams::fromArchivesOnlineRequest($request,50);

        $this->assertEquals(50, $requestParam->getMaxRecords());
        $this->assertEquals(null, $requestParam->getPeriod());
        $this->assertEquals(1, count($requestParam->getSearch()));
        $this->assertEquals("Haus", $requestParam->getSearch()[0]);
        $this->assertEquals(false, $requestParam->isAND());
    }


    /**
     * @test
     */
    public function any_OneWord_SameYear() {
        $request = "Serverchoice any \"Haus\" AND isad.date WITHIN \"2000 2000\"";
        $requestParam = \ArchivesOnlineSGV\Model_RequestParams::fromArchivesOnlineRequest($request,50);

        $this->assertEquals(50, $requestParam->getMaxRecords());
        $this->assertEquals(2000, $requestParam->getPeriod()->getFromYear());
        $this->assertEquals(2000, $requestParam->getPeriod()->getToYear());
        $this->assertEquals(1, count($requestParam->getSearch()));
        $this->assertEquals("Haus", $requestParam->getSearch()[0]);
        $this->assertEquals(false, $requestParam->isAND());
    }



    /**
     * @test
     */
    public function any_OneWord_DifferentYear() {
        $request = "Serverchoice any \"Haus\" AND isad.date WITHIN \"1 2017\"";
        $requestParam = \ArchivesOnlineSGV\Model_RequestParams::fromArchivesOnlineRequest($request,50);

        $this->assertEquals(50, $requestParam->getMaxRecords());
        $this->assertEquals(1, $requestParam->getPeriod()->getFromYear());
        $this->assertEquals(2017, $requestParam->getPeriod()->getToYear());
        $this->assertEquals(1, count($requestParam->getSearch()));
        $this->assertEquals("Haus", $requestParam->getSearch()[0]);
        $this->assertEquals(false, $requestParam->isAND());
    }



    /**
     * @test
     */
    public function any_TwoWords_WithoutDate() {
        $request = "Serverchoice any \"Haus Kinder\"";
        $requestParam = \ArchivesOnlineSGV\Model_RequestParams::fromArchivesOnlineRequest($request,50);

        $this->assertEquals(50, $requestParam->getMaxRecords());
        $this->assertEquals(null, $requestParam->getPeriod());
        $this->assertEquals(2, count($requestParam->getSearch()));
        $this->assertEquals("Haus", $requestParam->getSearch()[0]);
        $this->assertEquals("Kinder", $requestParam->getSearch()[1]);
        $this->assertEquals(false, $requestParam->isAND());
    }

    /**
     * @test
     */
    public function any_TwoWords_SameYear() {
        $request = "Serverchoice any \"Haus Kinder\" AND isad.date WITHIN \"2000 2000\"";
        $requestParam = \ArchivesOnlineSGV\Model_RequestParams::fromArchivesOnlineRequest($request,50);

        $this->assertEquals(50, $requestParam->getMaxRecords());
        $this->assertEquals(2000, $requestParam->getPeriod()->getFromYear());
        $this->assertEquals(2000, $requestParam->getPeriod()->getToYear());
        $this->assertEquals(2, count($requestParam->getSearch()));
        $this->assertEquals("Haus", $requestParam->getSearch()[0]);
        $this->assertEquals("Kinder", $requestParam->getSearch()[1]);
        $this->assertEquals(false, $requestParam->isAND());
    }

    /**
     * @test
     */
    public function any_TwoWords_DifferentYear() {
        $request = "Serverchoice any \"Haus Kinder\" AND isad.date WITHIN \"1 2017\"";
        $requestParam = \ArchivesOnlineSGV\Model_RequestParams::fromArchivesOnlineRequest($request,50);

        $this->assertEquals(50, $requestParam->getMaxRecords());
        $this->assertEquals(1, $requestParam->getPeriod()->getFromYear());
        $this->assertEquals(2017, $requestParam->getPeriod()->getToYear());
        $this->assertEquals(2, count($requestParam->getSearch()));
        $this->assertEquals("Haus", $requestParam->getSearch()[0]);
        $this->assertEquals("Kinder", $requestParam->getSearch()[1]);
        $this->assertEquals(false, $requestParam->isAND());
    }

}
