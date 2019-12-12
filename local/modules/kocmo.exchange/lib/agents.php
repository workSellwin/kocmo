<?php


namespace Kocmo\Exchange;


class Agents
{
    static public function start($step = 0, $status = 'end'){

        $bx = StaticFactory::factory($step);

        if($bx) {
            $flag = $bx->update();

            if ($bx->getStatus() == 'run') {

                $status = 'run';
            }
            elseif ($bx->getStatus() == 'end') {

                $status = 'end';
                $step = StaticFactory::nextStep($step);
            }
        }

        if($status == 'end'){
            return '';
        }
        return 'Kocmo\\Exchange\\Agents::start(' . "$step, '$status'" . ');';
    }
}