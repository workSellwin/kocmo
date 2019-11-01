<?php


namespace Kocmo\Exchange\Tree;


class Rest extends Builder
{
    function __construct()
    {
        parent::__construct();
        $this->entry = $this->arParams['REST_ENTRY'];
        $this->fillInOutputArr();

        $arTemp = [];

        if( count($this->outputArr) ){
            foreach($this->outputArr as $rest){
                $uid = $rest['UID'];
                unset($rest['UID']);

                if( isset($arTemp[$uid]) ){
                    $arTemp[$uid][$rest['ТипСклада']] = $rest['Остаток'];
                }
                else{
                    $arTemp[$uid] = [$rest['ТипСклада'] => $rest['Остаток']];
                }
            }
            $this->outputArr = $arTemp;
        }
    }
}