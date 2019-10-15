<?php


namespace Kocmo\Exchange\Tree;


class Property extends Product
{
    const POINT_OF_ENTRY = 'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetScheme/22e8d9ce-ed52-47ca-a524-e32b586aab0a';

    protected $languageConstants = [
        'UID' => 'UID',
        'NAME' => 'Наименование',
        'IS_PROP' => "ЭтоСвойство",
        'MULTI' => "Мультизначение",
        'TYPE' => "Тип",
        'YES' => 'Да',
        'NO' => 'Нет',
        "LIST" => "Список",
        "HANDBOOK" => "Справочник"
    ];

    function __construct()
    {
        parent::__construct();
        $this->fillInOutputArr();
    }

    public function fillInOutputArr(){

        $this->send($this->arParams['PROP_POINT_OF_ENTRY']);

        $properties = [];

        foreach( $this->outputArr as $item ){

            if( $item[$this->languageConstants['IS_PROP']] == $this->languageConstants['YES'] ){

                $prop = [
                    //'UID' => $item[$this->languageConstants['UID']],
                    'NAME' => $item[$this->languageConstants['NAME']]
                ];

                if( $item[ $this->languageConstants['TYPE'] ] == $this->languageConstants['LIST'] ){

                    $prop['PROPERTY_TYPE'] = 'L';
                    if( $item[ $this->languageConstants['MULTI'] ] == $this->languageConstants['YES']){
                        $prop['MULTIPLE'] = 'Y';
                    }
                    else{
                        $prop['MULTIPLE'] = 'N';
                    }
                }
                elseif( $item[ $this->languageConstants['TYPE'] ] == $this->languageConstants['HANDBOOK'] ){
                    $prop['PROPERTY_TYPE'] = 'L';
                    $prop['MULTIPLE'] = 'N';
                }
                else{
                    $prop['PROPERTY_TYPE'] = 'S';
                }

                $properties[] = $prop;

            }
        }
        $this->outputArr = $properties;
    }
}