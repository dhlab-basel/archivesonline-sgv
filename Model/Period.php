<?php

declare(strict_types = 1);

namespace ArchivesOnlineSGV;

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
     * @param int $fromYear
     */
    private function setFromYear(int $fromYear) {
        $this->fromYear = $fromYear;
    }

    /**
     * @return int
     */
    public function getToYear(): int {
        return $this->toYear;
    }

    /**
     * @param int $toYear
     */
    private function setToYear(int $toYear) {
        $this->toYear = $toYear;
    }

    public function __construct(int $fromYear, int $toYear) {
        $this->fromYear = $fromYear;
        $this->toYear = $toYear;
    }

    public function isSameYear(): bool {
        return $this->fromYear === $this->toYear;
    }

    public function __toString() {
        return "Period: ". $this->fromYear. "- ". $this->toYear;
    }
}