<?php


namespace Kocmo\Exchange\Tree;


class Rest extends Builder
{
    function __construct(/*$storeXmlId*/)
    {
        parent::__construct();
    }

    public function setStoreRest( $storeXmlId ){

        if(!$this->utils->checkRef($storeXmlId)){
            throw new \Error("store id is empty or incorrect");
        }

        $this->entry = $this->arParams['REST_ENTRY'] . '?id=' . $storeXmlId;
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