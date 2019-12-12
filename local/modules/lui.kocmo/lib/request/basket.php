<?php


namespace Lui\Kocmo\Request;


use Bitrix\Main\Loader;
use Bitrix\Sale;

class Basket extends Base
{

    protected $obBasket;
    protected $arPrice = [];
    protected $arCache = [];
    protected $hash = '';


    public function __construct()
    {
        $url = \Lui\Kocmo\Request\Config\Url::GetBasket();
        $arParams = ['json' => ''];
        parent::__construct($url, $arParams);
        $this->SetBasket();
        $this->SetAllPrice();
        $this->GetCache();
    }

    protected function GetCacheKey()
    {
        return 'Basket-Request-arCache';
    }

    protected function RemoveCache()
    {
        unset($_SESSION[$this->GetCacheKey()]);
    }

    protected function KeyHashFinal()
    {
        return 'Basket-Request-1c-final';
    }

    public static function Get1CRequest()
    {
        $ob = new self();
        return $ob->arCache[$ob->KeyHashFinal()];
    }


    protected function SetCacheData($key, $data)
    {
        $this->arCache[$key] = $data;
        $this->SetCache();
    }

    protected function GetCacheData($key)
    {
        return $this->arCache[$key];
    }

    protected function GetCache()
    {
        $this->arCache = $_SESSION[$this->GetCacheKey()];
    }

    protected function SetCache()
    {
        $_SESSION[$this->GetCacheKey()] = $this->arCache;
    }

    /**
     * @return array;
     */
    public function Run()
    {
        return $this->GetDiscount();
    }


    public function SetBasket()
    {
        $this->obBasket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());
    }

    protected function GetStructure()
    {
        return [
            // 'card' => '000223',
            'card' => '',
            'promo' => '',
            'shop' => '554e5045-aa97-11e8-a216-00505601048d',
            'goods' => [
                [
                    'UID' => '8e1e5ee4-aaac-11e8-a216-00505601048d',
                    'COUNT' => '10',
                    'SUMM' => '125'
                ]
            ],
        ];
    }


    public function GetDiscount()
    {
        $arReqest = $this->GetStructure();

        $obU = \Lui\Kocmo\Helper\UserData::getInstance();

        if ($card = $obU->GetCard()) {
            $arReqest['card'] = $card;
        }

        if ($promo = $_REQUEST['ORDER_PROP_27']) {
            $arReqest['promo'] = $promo;
        }

        $arReqest['goods'] = $this->SetGoods();
        if($arReqest['goods']){
            return $this->Send($arReqest);
        }else{
            return [];
        }

    }

    protected function Send(array $arRequest)
    {
        Loader::includeSharewareModule('kocmo.exchange');
        $json = json_encode($arRequest);
        $hash = $this->GetHash($json);
        if (!$data = $this->GetCacheData($hash)) {
            try {
                $client = new \GuzzleHttp\Client();
                $res = $client->request('GET', $this->url, [
                    'query' => ['json' => $json]
                ]);
                $data = $res->getBody();

                $data = json_decode($data, true);
            } catch (\Exception $e) {
                $data = [
                    'ERROR' => [$e->getMessage()],
                ];
            }
            $this->SetCacheData($hash, $data);
            $this->SetCacheData($this->KeyHashFinal(), $data);
        }
        return $data;
    }


    public function GetHash($str)
    {
        return md5($str);
    }

    protected function SetGoods()
    {
        $ob = $this->obBasket;
        $arResult = [];
        foreach ($ob as $basketItem) {
            $arResult[] = $this->SetGood($basketItem);
        }
        return $arResult;
    }

    /**
     *
     */
    protected function SetAllPrice()
    {
        $this->arPrice = $this->GetAllPriceItems();
    }


    protected function SetGood(Sale\BasketItem $basketItem)
    {

        $arRequest = [
            'UID' => $basketItem->getField('PRODUCT_XML_ID'),
            'COUNT' => $basketItem->getQuantity(),
            'SUMM' => $this->GetPriceRequest($basketItem),
        ];
        return $arRequest;
    }

    protected function GetPriceRequest(Sale\BasketItem $basketItem): float
    {
        $result = 1000000;
        $id = $basketItem->getField('PRODUCT_ID');
        $count = $basketItem->getQuantity();
        $arPrice = $this->arPrice[$id];
        if ($arPrice[2]) {
            $result = round($arPrice[2]['PRICE'] * $count, 2);
        } elseif ($arPrice[3]) {
            $result = round($arPrice[3]['PRICE'] * $count, 2);
        }
        return $result;
    }


    protected function GetAllPriceItems()
    {
        $arResult = [];
        $ob = $this->obBasket;
        $arId = [];
        foreach ($ob as $basketItem) {
            if ($basketItem instanceof Sale\BasketItem) {
                $arId[] = $basketItem->getField('PRODUCT_ID');
            }
        }

        $allProductPrices = \Bitrix\Catalog\PriceTable::getList([
            "select" => ['ID', 'PRODUCT_ID', 'CATALOG_GROUP_ID', 'PRICE', 'CURRENCY'],
            "filter" => [
                "=PRODUCT_ID" => $arId,
            ],
            "order" => ["ID" => "ASC"]
        ])->fetchAll();
        if ($allProductPrices) {
            foreach ($allProductPrices as $arPrice) {
                $arResult[$arPrice['PRODUCT_ID']][$arPrice['CATALOG_GROUP_ID']] = $arPrice;
            }
        }
        return $arResult;
    }


}
