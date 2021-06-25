<?php

namespace interview;

require_once ('Frame.php');
require_once ('FinalFrame.php');

class Game{

    private $currentFrame;

    public function __construct()
    {
        $this->currentFrame = new Frame(1);
    }

    public function roll($pins){
        $frame = $this->currentFrame;
        $frame->writePins($pins);
        // Strike, Spare or completed:
        if($frame->isStrike() || $frame->isSpare() || $frame->isCompleted()){
            $newFrame = new Frame($frame->getNumber() + 1, $frame);
            $frame->setNextFrame($newFrame);
            $this->currentFrame = $newFrame;
        }
        $this->updateScores();
    }

    public function score(){
        $frame = $this->currentFrame;
        if(! $frame)
            return 0;

        while( $frame && ! $frame->getScore())
            $frame = $frame->getPreviousFrame();

        return $frame ? $frame->getScore() : 0;
    }

    private function updateScores(){
        $frame = $this->currentFrame;

        // get the first frame with missing score
        while( $frame && $frame->getPreviousFrame() && ! $frame->getPreviousFrame()->getScore())
            $frame = $frame->getPreviousFrame();

        do{
            // Strike:
            if($frame->isStrike()){
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
        if ($frame->getNextFrame() && $frame->getNextFrame()->isCompleted()) {
            $pins = $frame->getNextFrame()->getPins();
            $score = ($frame->getPreviousFrame() ? $frame->getPreviousFrame()->getScore() : 0) + array_sum($frame->getPins());
            $score += array_sum($pins);
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
}
