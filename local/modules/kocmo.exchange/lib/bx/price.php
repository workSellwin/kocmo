<?php


namespace Kocmo\Exchange\Bx;

use \Bitrix\Catalog\Model,
    \Bitrix\Catalog\GroupTable;


class Price extends Helper
{
    protected $currency = 'BYN';

    function __construct()
    {
        $treeBuilder = new \Kocmo\Exchange\Tree\Price();
        parent::__construct($treeBuilder);
    }

    public function update($full = true, array $exchangeData = []) : bool {

        if(!$full){
            $this->treeBuilder->setOutputArr($exchangeData);
        }

        $this->treeBuilder->setPrice();
        $arReq = $this->treeBuilder->getRequestArr();

        $typePrice = GroupTable::getlist([
            'select' => ['ID', 'XML_ID']
        ])->fetchAll();

        $typePrice = array_column($typePrice, NULL, "XML_ID");

        $res = \CIBlockElement::GetList(
            [],
            ["XML_ID" => array_keys($arReq)],
            false,
            false,
            ['ID', 'XML_ID']
        );

        $elementsId = [];

        while($fields = $res->fetch()){
            $elementsId[$fields['ID']] = $fields['XML_ID'];
        }

        $dbPrices = Model\Price::getlist([
            "filter" => ["PRODUCT_ID" => array_keys($elementsId), "CURRENCY" => $this->currency],
            "select" => ["ID", "PRODUCT_ID", "CATALOG_GROUP_ID"],
            "order" => ["PRODUCT_ID" => "asc"]
        ]);

        $prices = [];

        while($row = $dbPrices->fetch()){
            $prices[$row["PRODUCT_ID"] . '_' . $row["CATALOG_GROUP_ID"]] = $row["ID"];
        }

        unset($row);

        foreach($elementsId as $id => $xmlId) {

            foreach ($arReq[$xmlId] as $typePriceXmlId => $priceValue) {

                $price = str_replace(',', '.', $priceValue);
                $price = floatval($price);
                $catalogGroup = $typePrice[$typePriceXmlId]['ID'];

                try {
                    if (isset($prices[$id . '_' . $catalogGroup])) {

                        $result = Model\Price::update($prices[$id . '_' . $catalogGroup], [
                            "PRODUCT_ID" => $id,
                            "CATALOG_GROUP_ID" => $catalogGroup,
                            "PRICE" => $price,
                            "CURRENCY" => $this->currency,
                        ]);
                    } else {
                        $result = \Bitrix\Catalog\Model\Price::add([
                            "PRODUCT_ID" => $id,
                            "CATALOG_GROUP_ID" => $catalogGroup,
                            "PRICE" => $price,
                            "CURRENCY" => $this->currency,
                        ]);
                    }

                    if (!$result->isSuccess()) {
                        //pr($result);
                    }
                } catch (\Exception $e) {
                    $this->errors[] = $e->getMessage();
                }
            }
        }
        if($full) {
            //$this->clearOldPrice();
        }
        $this->status = 'end';

        return true;
    }

    function clearOldPrice(){

        if($this->timestamp !== null ) {

            $dbPrices = Model\Price::getList(['filter' => ["<TIMESTAMP_X" => $this->timestamp]]);
            $oldPrices = [];

            while ($row = $dbPrices->fetch()) {
                $oldPrices[] = $row['ID'];
            }

            foreach ($oldPrices as $id) {
                Model\Price::delete($id);
            }
        }
    }
}