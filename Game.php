<?php

namespace interview;

require_once ('Frame.php');
require_once ('FinalFrame.php');

class Game{

    private $currentFrame;

    public function __construct()
    {
        $this->currentFrame = new Frame(0);
    }

    public function roll($pins){
        $this->currentFrame = $this->currentFrame->writePins($pins);
    }

    public function score(){
        $frame = $this->currentFrame;
        while( ! $frame->getScore())
            $frame = $frame->getPreviousFrame();

        return $frame->getScore();
    }
}
