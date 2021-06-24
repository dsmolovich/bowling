<?php

namespace interview;

require_once ('Frame.php');


class FinalFrame extends Frame{
    private $box3;

    /**
     * @return int
     */
    public function getBox3(): int
    {
        return $this->box3;
    }

    public function isSpare(){
        throw \Exception('Final frame can\'t be a spare');
    }
}