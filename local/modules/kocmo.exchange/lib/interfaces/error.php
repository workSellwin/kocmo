<?php


namespace Kocmo\Exchange\Interfaces;


interface Error
{
    public function setError(string $message, int $code);
    public function getError();
    public function getMessage();
    public function getCode();
}