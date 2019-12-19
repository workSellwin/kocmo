<?php


namespace Kocmo\Exchange\Interfaces;

use Kocmo\Exchange\Support;


interface ErrorCollection
{
    public function getNextError();
    public function getErrorsArray();
    public function addError(Support\Error $error);
}