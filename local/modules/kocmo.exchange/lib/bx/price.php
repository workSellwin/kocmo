<?php


namespace Kocmo\Exchange\Bx;
use Bitrix\Catalog;


class Price extends Helper
{
    protected $currency = 'BYN';

    function __construct($catalogId)
    {
        $treeBuilder = new \Kocmo\Exchange\Tree\Price();
        parent::__construct($treeBuilder, $catalogId);
    }

    public function update(){

        $arReq = $this->treeBuilder->getRequestArr();
        $typePrice = \Bitrix\Catalog\GroupTable::getlist(['select' => ['ID', 'XML_ID']])->fetchAll();
        $typePrice = array_column($typePrice, NULL, "XML_ID");

        $res = \CIBlockElement::GetList([], ["XML_ID" => array_keys($arReq)], false, false, ['ID', 'XML_ID']);
        $elementsId = [];

        while($fields = $res->fetch()){
            //$elementsId[] = $fields['ID'];
            $elementsId[$fields['ID']] = $fields['XML_ID'];
        }

        $dbPrices = \Bitrix\Catalog\Model\Price::getlist([
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

                        $result = \Bitrix\Catalog\Model\Price::update($prices[$id . '_' . $catalogGroup], [
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
                        pr($result);
                    }
                } catch (\Exception $e) {
                    pr($e->getMessage());
                }
            }
        }

//        $iterator = Catalog\Model\Product::getlist([
//            "filter" => ['ID' => array_keys($elementsId)]
//        ]);

//        $products = [];
//
//        while($row = $iterator->fetch()){
//            //$products[] = $row;
//            $price = str_replace(',', '.', $arReq[$elementsId[$row['ID']]][$this->arParams['PRICE']]);
//            $price = floatval($price);
//
//            try {
//                $result = \Bitrix\Catalog\Model\Price::add([
//                    "PRODUCT_ID" => $row['ID'],
//                    "CATALOG_GROUP_ID" => $typePrice[ $arReq[$elementsId[$row['ID']]][$this->arParams['TYPE_PRICE']] ]['ID'],
//                    "PRICE" => $price,
//                    "CURRENCY" => $this->currency,
//                ]);
//                //pr($result);
//                if(!$result->isSuccess()){
//                    pr($result);
//                }
//            } catch (\Exception $e){
//                pr($e->getMessage());
//            }
//        }

        //pr($products);
    }
}