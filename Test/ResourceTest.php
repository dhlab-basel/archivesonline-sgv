<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class ResourceTest extends TestCase{
    /**
     * @var int This juilan day equals 4/30/1949 in gregorian calendar
     */
    private const JULIAN_DAY_VAL_1 = 2433037;
    /**
     * @var int This juilan day equals 5/20/1958 in gregorian calendar
     */
    private const JULIAN_DAY_VAL_2 = 2436344;
    /**
     * @var int This juilan day equals 12/31/1972 in gregorian calendar
     */
    private const JULIAN_DAY_VAL_3 = 2441683;
    /**
     * @var int This juilan day equals 4/30/1984 in gregorian calendar
     */
    private const JULIAN_DAY_VAL_4 = 2445821;
    protected $dateValue;

    private function generateDateValue(int $dateval1, int $dateval2, string $dateprecision1, string $dateprecision2): stdClass {
        $dateValue = new StdClass();
        $dateValue->dateval1 = strval($dateval1);
        $dateValue->dateval2 = strval($dateval2);
        $dateValue->dateprecision1 = $dateprecision1;
        $dateValue->dateprecision2 = $dateprecision2;
        return $dateValue;
    }

    /**
     * @test
     */
    public function convertDate_DateValue1():void{
        $result = \ArchivesOnlineSGV\Model_Resource::convertDate(static::JULIAN_DAY_VAL_1,"DAY", true);
        $this->assertEquals("30.4.1949", $result);
    }

    /**
     * @test
     */
    public function convertDate_DateValue2():void{
        $result = \ArchivesOnlineSGV\Model_Resource::convertDate(static::JULIAN_DAY_VAL_2,"DAY", true);
        $this->assertEquals("20.5.1958", $result);
    }

    /**
     * @test
     */
    public function convertDate_DateValue3():void{
        $result = \ArchivesOnlineSGV\Model_Resource::convertDate(static::JULIAN_DAY_VAL_3,"DAY", true);
        $this->assertEquals("31.12.1972", $result);
    }

    /**
     * @test
     */
    public function convertDate_DateValue4():void{
        $result = \ArchivesOnlineSGV\Model_Resource::convertDate(static::JULIAN_DAY_VAL_4,"DAY", true);
        $this->assertEquals("30.4.1984", $result);
    }

    /**
     * @test
     */
    public function convertDate_NoValidPrecision():void{
        $result = \ArchivesOnlineSGV\Model_Resource::convertDate(static::JULIAN_DAY_VAL_1,"Pippi Langstrumpf", true);
        $this->assertEquals("NONE", $result);
    }

    /**
     * @test
     */
    public function convertDate_DayPrecision():void{
        $result = \ArchivesOnlineSGV\Model_Resource::convertDate(static::JULIAN_DAY_VAL_3,"DAY", true);
        $this->assertEquals("31.12.1972", $result);
    }

    /**
     * @test
     */
    public function convertDate_MonthPrecision():void{
        $result = \ArchivesOnlineSGV\Model_Resource::convertDate(static::JULIAN_DAY_VAL_3,"MONTH", true);
        $this->assertEquals("12.1972", $result);
    }

    /**
     * @test
     */
    public function convertDate_YearPrecision():void{
        $result = \ArchivesOnlineSGV\Model_Resource::convertDate(static::JULIAN_DAY_VAL_3,"YEAR", true);
        $this->assertEquals("1972", $result);
    }

    /**
     * @test
     */
    public function convertDate_NoReduction():void{
        $result = \ArchivesOnlineSGV\Model_Resource::convertDate(static::JULIAN_DAY_VAL_4,"MONTH", false);
        $this->assertEquals("1984-4-30", $result);
    }

    /**
     * @test
     */
    public function id(): void {
        $dateValues = $this->generateDateValue(static::JULIAN_DAY_VAL_1, static::JULIAN_DAY_VAL_2, "DAY", "DAY");
        $dateObject = new \ArchivesOnlineSGV\Model_Resource("799", "Lorem Ipsum", $dateValues);
        $this->assertEquals("Lorem Ipsum",$dateObject->getTitle());
        $this->assertEquals("799",$dateObject->getID());
    }

    /**
     * @test
     */
    public function title(): void {
        $dateValues = $this->generateDateValue(static::JULIAN_DAY_VAL_1, static::JULIAN_DAY_VAL_2, "DAY", "DAY");
        $dateObject = new \ArchivesOnlineSGV\Model_Resource("1", "Der Hofnarr von Pfeffingen bringt den Basler Bischoff zum Lachen", $dateValues);
        $this->assertEquals("Der Hofnarr von Pfeffingen bringt den Basler Bischoff zum Lachen",$dateObject->getTitle());
        $this->assertEquals("1",$dateObject->getID());
    }

    /**
     * @test
     */
    public function reducedDate_PrecisionDay_SameYear(): void {
        $dateValues = $this->generateDateValue(static::JULIAN_DAY_VAL_1, static::JULIAN_DAY_VAL_1, "DAY", "DAY");
        $dateObject = new \ArchivesOnlineSGV\Model_Resource("1", "Lorem Ipsum", $dateValues);
        $this->assertEquals("30.4.1949",$dateObject->getDate());
    }

    /**
     * @test
     */
    public function reducedDate_PrecisionDay_DifferentYear(): void {
        $dateValues = $this->generateDateValue(static::JULIAN_DAY_VAL_1, static::JULIAN_DAY_VAL_2, "DAY", "DAY");
        $dateObject = new \ArchivesOnlineSGV\Model_Resource("1", "Lorem Ipsum", $dateValues);
        $this->assertEquals("30.4.1949- 20.5.1958",$dateObject->getDate());
    }

    /**
     * @test
     */
    public function reducedDate_PrecisionMonth_SameYear(): void {
        $dateValues = $this->generateDateValue(static::JULIAN_DAY_VAL_1, static::JULIAN_DAY_VAL_1, "MONTH", "MONTH");
        $dateObject = new \ArchivesOnlineSGV\Model_Resource("1", "Lorem Ipsum", $dateValues);
        $this->assertEquals("4.1949",$dateObject->getDate());
    }

    /**
     * @test
     */
    public function reducedDate_PrecisionMonth_DifferentYear(): void {
        $dateValues = $this->generateDateValue(static::JULIAN_DAY_VAL_1, static::JULIAN_DAY_VAL_2, "MONTH", "MONTH");
        $dateObject = new \ArchivesOnlineSGV\Model_Resource("1", "Lorem Ipsum", $dateValues);
        $this->assertEquals("4.1949- 5.1958",$dateObject->getDate());
    }

    /**
     * @test
     */
    public function reducedDate_PrecisionYear_SameYear(): void {
        $dateValues = $this->generateDateValue(static::JULIAN_DAY_VAL_1, static::JULIAN_DAY_VAL_1, "YEAR", "YEAR");
        $dateObject = new \ArchivesOnlineSGV\Model_Resource("1", "Lorem Ipsum", $dateValues);
        $this->assertEquals("1949",$dateObject->getDate());
    }

    /**
     * @test
     */
    public function reducedDate_PrecisionYear_DifferentYear(): void {
        $dateValues = $this->generateDateValue(static::JULIAN_DAY_VAL_1, static::JULIAN_DAY_VAL_2, "YEAR", "YEAR");
        $dateObject = new \ArchivesOnlineSGV\Model_Resource("1", "Lorem Ipsum", $dateValues);
        $this->assertEquals("1949- 1958",$dateObject->getDate());
    }

    /**
     * @test
     */
    public function fullDate1(): void {
        $dateValues = $this->generateDateValue(static::JULIAN_DAY_VAL_3, static::JULIAN_DAY_VAL_4, "YEAR", "YEAR");
        $dateObject = new \ArchivesOnlineSGV\Model_Resource("1", "Lorem Ipsum", $dateValues);
        $this->assertEquals("1972-12-31",$dateObject->getDate1Full());
    }

    /**
     * @test
     */
    public function fullDate2(): void {
        $dateValues = $this->generateDateValue(static::JULIAN_DAY_VAL_3, static::JULIAN_DAY_VAL_4, "YEAR", "YEAR");
        $dateObject = new \ArchivesOnlineSGV\Model_Resource("1", "Lorem Ipsum", $dateValues);
        $this->assertEquals("1984-4-30",$dateObject->getDate2Full());
    }

    /**
     * @test
     */
    public function nullDateValues(): void {
        $dateObject = new \ArchivesOnlineSGV\Model_Resource("1", "Lorem Ipsum", null);
        $this->assertEquals("Undatiert",$dateObject->getDate());
        $this->assertEquals("",$dateObject->getDate1Full());
        $this->assertEquals("",$dateObject->getDate2Full());
    }

}