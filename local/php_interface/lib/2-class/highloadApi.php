<?php
use Bitrix\Highloadblock\HighloadBlockTable as HLBT;

class highloadApi
{
    public $MY_HL_BLOCK_ID;

    public function __construct($HlBlockId)
    {
        if(!$HlBlockId)return 'Error!';
        CModule::IncludeModule('highloadblock');
        $this->MY_HL_BLOCK_ID = $HlBlockId;
    }

    /**
     * @param $HlBlockId
     * @return bool
     * Получения экземпляра класса
     */
    protected function GetEntityDataClass($HlBlockId) {
        if (empty($HlBlockId) || $HlBlockId < 1)
        {
            return false;
        }
        $hlblock = HLBT::getById($HlBlockId)->fetch();
        $entity = HLBT::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        return $entity_data_class;
    }

    /**
     * @return mixed
     * Получение названий полей
     */
    public function getFieldName(){
        $hlblock = HLBT::getById($this->MY_HL_BLOCK_ID)->fetch();
        $entity = HLBT::compileEntity($hlblock);
        return $entity->getFields();
    }

    /**
     * @return array
     * Получить элементы highload-инфоблока
     */
    public function getElementHighload($arFilter=array(), $arOrder = array('UF_NAME'=>'ASC'), $arSelect = array('*')){
        $elem = array();
        $entity_data_class = $this->GetEntityDataClass($this->MY_HL_BLOCK_ID);
        $rsData = $entity_data_class::getList(array(
            'order' => $arOrder,
            'select' => $arSelect,
            'filter' => $arFilter
        ));
        while($el = $rsData->fetch()){
            $elem[]=$el;
        }
        return $elem;
    }

    /**
     * @return mixed
     * Количество элементов highload-инфоблока
     */
    public function getCountElementHighload(){
        $entity_data_class = $this->GetEntityDataClass($this->MY_HL_BLOCK_ID);
        return $entity_data_class::getCount();
    }

    /**
     * @return mixed
     * Добавить новый элемент в highload-инфоблок
     */
    public function setNewElementHighload ($arData){
        $entity_data_class = $this->GetEntityDataClass($this->MY_HL_BLOCK_ID);
        $result = $entity_data_class::add($arData);
        return $result;
    }

    /**
     * @param $id
     * @return mixed
     * Удалить элемент в highload-инфоблок
     */
    public function deleteElementHighload ($id){
        $idForDelete = $id;
        $entity_data_class = $this->GetEntityDataClass($this->MY_HL_BLOCK_ID);
        $result = $entity_data_class::delete($idForDelete);
        return $result;
    }

    /**
     * @param $id
     * @param $arField
     * @return mixed
     * Обновить новый элемент в highload-инфоблок
     */
    public function  updateElementHighload($id, $arField){
        $idForUpdate = $id;
        $entity_data_class = $this->GetEntityDataClass($this->MY_HL_BLOCK_ID);
        $result = $entity_data_class::update($idForUpdate, $arField);
        return $result;
    }
}