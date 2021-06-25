<?php

namespace interview;

require_once ('Frame.php');
require_once ('FinalFrame.php');

class Game{

    const FRAMES_NUM = 10;

    /**
     * @var Frame
     */
    private $currentFrame;

    /**
     * Game constructor.
     */
    public function __construct()
    {
        $this->currentFrame = new Frame(1);
    }

    /**
     * @param int $pins
     */
    public function roll($pins){
        if($this->isGameOver())
            return;

        $frame = $this->currentFrame;

        $frame->writePins($pins);
        // Strike, Spare or completed:
        if(($frame->isStrike() || $frame->isSpare() || $frame->isCompleted()) && $frame->getNumber() < self::FRAMES_NUM){
            $newFrameNumber = $frame->getNumber() + 1;
            $newFrame = $newFrameNumber >= self::FRAMES_NUM ?
                new FinalFrame($newFrameNumber, $frame):
                new Frame($newFrameNumber, $frame);
            $frame->setNextFrame($newFrame);
            $this->currentFrame = $newFrame;
        }
        $this->updateScores();
    }

    /**
     * @return int|null
     */
    public function score(){
        $frame = $this->currentFrame;
        if(! $frame)
            return 0;

        while( $frame && ! $frame->getScore())
            $frame = $frame->getPreviousFrame();

        return $frame ? $frame->getScore() : 0;
    }

    /**
     * @return bool
     */
    private function isGameOver(){
        if($this->currentFrame->getNumber() >= self::FRAMES_NUM && $this->currentFrame->isCompleted())
            return true;

        return false;
    }

    /**
     * @return void
     */
    private function updateScores(){
        $frame = $this->currentFrame;

        // get the first frame with missing score
        while( $frame && $frame->getPreviousFrame() && ! $frame->getPreviousFrame()->getScore())
            $frame = $frame->getPreviousFrame();

        do{
            // Strike:
            if($frame->getNumber() == self::FRAMES_NUM){
                $this->scoreLastFrame($frame);
            } if($frame->isStrike()){
                $this->scoreStrike($frame);
            }
            // Spare:
            elseif($frame->isSpare()){
                $this->scoreSpare($frame);
            }
            // Simple:
            elseif($frame->isCompleted()){
                $this->scoreCompleted($frame);
            }
            $frame = $frame->getNextFrame();
        } while ($frame);
    }

    /**
     * @param Frame|null $frame
     */
    private function scoreStrike(?Frame $frame): void
    {
        $nextPins = [];
        $nextFrame = $frame->getNextFrame();
        do{
            if( ! $nextFrame)
                break;

            foreach ($nextFrame->getPins() as $pin)
                array_push($nextPins, $pin);

            $nextFrame = $nextFrame->getNextFrame();
        } while ( count($nextPins)<2 );

        if( count($nextPins)>=2 ){
            $score = ($frame->getPreviousFrame() ? $frame->getPreviousFrame()->getScore() : 0) + array_sum($frame->getPins());
            $score += array_sum($nextPins);
            $frame->setScore($score);
        }
    }

    /**
     * @param Frame|null $frame
     */
    private function scoreSpare(?Frame $frame): void
    {
        if ($frame->getNextFrame()) {
            $pins = $frame->getNextFrame()->getPins();
            if (isset($pins[0])) {
                $score = ($frame->getPreviousFrame() ? $frame->getPreviousFrame()->getScore() : 0) + array_sum($frame->getPins());
                $score += $pins[0];
                $frame->setScore($score);
            }
        }
    }

    /**
     * @param Frame|null $frame
     */
    private function scoreCompleted(?Frame $frame): void
    {
        $score = ($frame->getPreviousFrame() ? $frame->getPreviousFrame()->getScore() : 0) + array_sum($frame->getPins());
        $frame->setScore($score);
    }

    /**
     * @param Frame|null $frame
     */
    private function scoreLastFrame(?Frame $frame): void
    {
        if($frame->isCompleted()){
            $score = ($frame->getPreviousFrame() ? $frame->getPreviousFrame()->getScore() : 0) + array_sum($frame->getPins());
            $frame->setScore($score);
        }
    }
}
