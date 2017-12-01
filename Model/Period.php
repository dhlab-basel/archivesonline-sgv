<?php

declare(strict_types = 1);

namespace ArchivesOnlineSGV;

/**
 * Class Model_Period This class represents a period time.
 * @package ArchivesOnlineSGV
 */
class Model_Period {

    /**
     * @var int
     */
    private $fromYear;

    /**
     * @var int
     */
    private $toYear;

    /**
     * @return int
     */
    public function getFromYear(): int {
        return $this->fromYear;
    }

    /**
     * @return int
     */
    public function getToYear(): int {
        return $this->toYear;
    }

    /**
     * Model_Period constructor.
     * @param int $fromYear
     * @param int $toYear
     */
    public function __construct(int $fromYear, int $toYear) {
        $this->fromYear = $fromYear;
        $this->toYear = $toYear;
    }

    /**
     * @return string
     */
    public function __toString() {
        return "Period: ". $this->fromYear. "- ". $this->toYear;
    }
}
