<?php

namespace interview;

class Frame{
    private $number;
    private $previousFrame;
    private $nextFrame;
    private $pins = [];
    private $score;

    public function __construct($number, Frame $previousFrame = null){
        $this->number = $number;
        $this->previousFrame = $previousFrame;
    }

    /**
     * @param Frame $nextFrame
     */
    public function setNextFrame(Frame $previousFrame): void
    {
        $this->previousFrame = $previousFrame;
    }

    /**
     * @param int $box
     */
    public function writePins(int $pins): Frame
    {
        array_push($this->pins, $pins);

        // Strike:

        // Spare:

        $this->score = ($this->previousFrame ? $this->previousFrame->getScore() : 0) + array_sum($this->pins);

        if(count($this->pins) >= 2){
            $this->nextFrame = new Frame($this->number, $this);
            return $this->nextFrame;
        }


        return $this;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @return Frame
     */
    public function getPreviousFrame(): Frame
    {
        return $this->previousFrame;
    }

    /**
     * @return Frame
     */
    public function getNextFrame(): Frame
    {
        return $this->nextFrame;
    }

    /**
     * @return int|null
     */
    public function getScore()
    {
        return $this->score;
    }


    public function isStrike(){
        return $this->pins[0] == 10;
    }

    public function isSpare(){
        return array_sum($this->pins) == 10;
    }
}