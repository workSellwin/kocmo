<?php


namespace Kocmo\Exchange\Import;


class Base
{
    protected $errors = [];

    function __construct()
    {
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param $error
     */
    public function setError($error)
    {
        $this->errors[] = $error;
    }
}