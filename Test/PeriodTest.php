<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class PeriodTest extends TestCase{

    /**
     * @test
     */
    public function sameYear(): void {
        $period = new \ArchivesOnlineSGV\Model_Period(2017,2017);
        $this->assertEquals(2017,$period->getFromYear());
        $this->assertEquals(2017,$period->getToYear());
    }

    /**
     * @test
     */
    public function differentYear(): void {
        $period = new \ArchivesOnlineSGV\Model_Period(1,2030);
        $this->assertEquals(1,$period->getFromYear());
        $this->assertEquals(2030,$period->getToYear());
    }
}
