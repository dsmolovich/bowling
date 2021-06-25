<?php

namespace interview;

class Frame{
    protected $boxesNum = 2;

    private $number;
    private $previousFrame;
    private $nextFrame;
    protected $pins = [];
    private $score;

    /**
     * Frame constructor.
     * @param int $number
     * @param Frame|null $previousFrame
     */
    public function __construct($number, Frame $previousFrame = null){
        $this->number = $number;
        $this->previousFrame = $previousFrame;
    }

    /**
     * @param Frame $nextFrame
     */
    public function setNextFrame(Frame $nextFrame): void
    {
        $this->nextFrame = $nextFrame;
    }

    /**
     * @param int $box
     */
    public function writePins(int $pins): Frame
    {
        array_push($this->pins, $pins);

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
     * @return Frame|null
     */
    public function getPreviousFrame()
    {
        return $this->previousFrame;
    }

    /**
     * @return Frame|null
     */
    public function getNextFrame()
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

    /**
     * @param mixed $score
     */
    public function setScore($score): void
    {
        $this->score = $score;
    }

    /**
     * @return array
     */
    public function getPins(): array
    {
        return $this->pins;
    }

    /**
     * @return bool
     */
    public function isStrike(){
        return isset($this->pins[0]) && $this->pins[0] == 10;
    }

    /**
     * @return bool
     */
    public function isSpare(){
        return array_sum($this->pins) == 10;
    }

    /**
     * @return bool
     */
    public function isCompleted(){
        return count($this->pins) >= $this->boxesNum;
    }
}