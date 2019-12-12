<?php

trait IsOrder
{
    public function isOrder()
    {
        $result = false;
        $entity_data_class = GetEntityDataClass(5);
        $rsData = $entity_data_class::getList(array(
            'select' => array('*'),
            'order' => array(),
            'limit' => '1',
            'filter' => array('UF_ID_ORDER' => $this->ORDER_ID)
        ));
        if ($el = $rsData->fetch()) {
            $result = true;
        }
        return $result;
    }

   function setOrder($id){
       $entity_data_class = GetEntityDataClass(5);
       $entity_data_class::add(['UF_ID_ORDER' => $this->ORDER_ID]);
   }
}

