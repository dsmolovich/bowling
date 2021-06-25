<?php

namespace interview;

require_once ('Frame.php');


class FinalFrame extends Frame{
    protected $boxesNum = 2;

    /**
     * @param int $box
     */
    public function writePins(int $pins): Frame
    {
        if($pins == 10 && empty($this->pins))
            $this->boxesNum = 3;

        return parent::writePins($pins);
    }

    /**
     * @return bool
     */
    public function isCompleted(){
        $isCompleted = (count($this->pins) >= $this->boxesNum);

        return $isCompleted;
    }
}