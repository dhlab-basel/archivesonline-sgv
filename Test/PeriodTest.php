<?php

use PHPUnit\Framework\TestCase;

class PeriodTest extends TestCase{

    /**
     * @test Instantiating a period object with specific year
     */
    public function sameYear() {
        $period = new \ArchivesOnlineSGV\Model_Period(2017,2017);
        $this->assertEquals(2017,$period->getFromYear());
        $this->assertEquals(2017,$period->getToYear());
    }

    /**
     * @test Instantiating a period object with from year 1 to year 2030
     */
    public function differentYear() {
        $period = new \ArchivesOnlineSGV\Model_Period(1,2030);
        $this->assertEquals(1,$period->getFromYear());
        $this->assertEquals(2030,$period->getToYear());
    }
}
