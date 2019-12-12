<?php

namespace Lui\Kocmo\Request;

abstract class Base
{
    protected $arJson;
    protected $url;
    protected $arData = [];
    protected $arParams;

    public function __construct($url, array $arParams)
    {
        $this->url = $url;
        $this->arParams = $arParams;
    }

    public function SetData(array $arData)
    {
        $this->arData = $arData;
    }

    public function GetJson()
    {
        $this->SetJson();
        return $this->arJson;
    }

    protected function SetJson()
    {
        $this->arData = $this->Run();
        $this->arJson = json_encode($this->arData);
    }

    /**
     * @return array;
     */
    abstract public function Run();

    /**
     * @return array;
     */
    abstract protected function GetStructure();

}
