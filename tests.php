<?php
/*
 * @doc Bowling rules:
 * https://www.rookieroad.com/bowling/scoring-rules/
 * https://www.wikihow.com/Score-Bowling
 */

require_once ('Game.php');

use PHPUnit\Framework\TestCase;
use interview\Game;

class GameTest extends TestCase{

    public function testSimpleScenario(){
        $game = new Game();

        $game->roll(4);
        $game->roll(3);
        $this->assertEquals(7, $game->score());

        $game->roll(2);
        $game->roll(1);
        $this->assertEquals(10, $game->score());
    }


    public function testSpareScenario(){
        $game = new Game();

        $game->roll(5);
        $game->roll(5);
        // wait

        // frame 2
        $game->roll(3);
        $this->assertEquals($game->score(), 13);
        $game->roll(2);
        $this->assertEquals($game->score(), 18);
    }

    public function testStrikeScenario(){
        $game = new Game();

        // frame 1
        $game->roll(10);
        // wait

        // frame 2
        $game->roll(3);
        $game->roll(2);
        $this->assertEquals($game->score(), 20);
    }

    public function testLastStrike(){
        $game = new Game();

        // frame 1
        $game->roll(3);
        $game->roll(3);
        $this->assertEquals($game->score(), 6);

        // frame 2
        $game->roll(3);
        $game->roll(3);
        $this->assertEquals($game->score(), 12);

        // frame 3
        $game->roll(3);
        $game->roll(3);
        $this->assertEquals($game->score(), 18);

        // frame 4
        $game->roll(3);
        $game->roll(3);
        $this->assertEquals($game->score(), 24);

        // frame 5
        $game->roll(3);
        $game->roll(3);
        $this->assertEquals($game->score(), 30);

        // frame 6
        $game->roll(3);
        $game->roll(3);
        $this->assertEquals($game->score(), 36);

        // frame 7
        $game->roll(3);
        $game->roll(3);
        $this->assertEquals($game->score(), 42);

        // frame 8
        $game->roll(3);
        $game->roll(3);
        $this->assertEquals($game->score(), 48);

        // frame 9
        $game->roll(3);
        $game->roll(3);
        $this->assertEquals($game->score(), 54);

        // frame 10
        $game->roll(10);
        // 2 bonus rolls
        $game->roll(10);
        $game->roll(10);

        $this->assertEquals($game->score(), 6*9 + 10 + 20); // 84
    }

    public function testExampleScore(){
        $game = new Game();

        /***
         * Example from https://www.rookieroad.com/bowling/scoring-rules/
         */
        // frame 1
        $game->roll(5);
        $game->roll(4);
        $this->assertEquals($game->score(), 9); // 5 + 4

        // frame 2
        $game->roll(4);
        $game->roll(6); // spare
        // wait to calculate the running score until the first roll of frame 3 is done

        // frame 3
        $game->roll(7);
        $this->assertEquals($game->score(), 26); // 5 + 4 + 4 + 6 + 7
        $game->roll(0);
        $this->assertEquals($game->score(), 33);

        // frame 4
        $game->roll(10); // strike
        // wait to calculate the running total because the next two rolls are added to the score after a strike

        // frame 5
        $game->roll(10); // strike
        // still wait to calculate the running score for frame 4 and 5

        // frame 6
        $game->roll(10); // strike
        $this->assertEquals($game->score(), 63); // frame 4 calculation: 33 + 10 + 10 + 10, bacause on a strike we always add the next roll

        // frame 7
        $game->roll(5);
        $this->assertEquals($game->score(), 88); // frame 5 calculation: 63 + 10 + 10 + 5 (!!!)

        $game->roll(3);
        // frame 6 score = 106 // frame 6 calculation: 88 + 10 + 5 + 3 (!!!)
        $this->assertEquals($game->score(), 114); // 106 + 5 + 3

        // frame 8
        $game->roll(6);
        $game->roll(4); // spare
        // wait to calculate the running scrore until the next roll is dene

        // frame 9
        $game->roll(4);
        $this->assertEquals($game->score(), 128); // frame 8 score = 114 + 10 + 4
        // wait to calculate the running total for frame 9

        $game->roll(6); // spare
        // wait

        // frame 10
        $game->roll(10); // strike, 2 bonus rolls
        $this->assertEquals($game->score(), 148); // 128 + 10 + 10
        $game->roll(10); // strike, no bonuses
        $game->roll(10); // strike, no bonuses
        $this->assertEquals($game->score(), 178);
    }

    public function testMaxPossibleScore(){
        $game = new Game();

        // frame 1
        $game->roll(10);
        $game->roll(10);
        // frame 2
        $game->roll(10);
        $game->roll(10);
        // frame 3
        $game->roll(10);
        $game->roll(10);
        // frame 4
        $game->roll(10);
        $game->roll(10);
        // frame 5
        $game->roll(10);
        $game->roll(10);
        // frame 6
        $game->roll(10);
        $game->roll(10);
        // frame 7
        $game->roll(10);
        $game->roll(10);
        // frame 8
        $game->roll(10);
        $game->roll(10);
        // frame 9
        $game->roll(10);
        $game->roll(10);
        // frame 10
        $game->roll(10);
        // extra 2 bonus rolls
        $game->roll(10);
        $game->roll(10);


        $this->assertEquals($game->score(), 300);
    }

}