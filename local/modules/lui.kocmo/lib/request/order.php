<?php


namespace Lui\Kocmo\Request;


use Bitrix\Main\Loader;
use Lui\Kocmo\Helper\DataOrderJson;

class Order extends Base
{
    protected $id;
    protected $obOrder;
    protected $arCache = [];

    public function __construct(int $id)
    {
        $this->id = $id;
        $url = \Lui\Kocmo\Request\Config\Url::SetOrder();
        AddMessage2Log($url);
        $arParams = ['json' => ''];
        parent::__construct($url, $arParams);
    }

    /**
     * @return array|mixed|\Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Run()
    {
        $json = \Lui\Kocmo\Helper\Order::GetJson($this->id);
        Loader::includeSharewareModule('kocmo.exchange');
        try {
            $client = new \GuzzleHttp\Client();
            $res = $client->request('POST', $this->url, [
                'query' => ['json' => $json]
            ]);
            $data = $res->getBody();
            $data = json_decode($data, true);
        } catch (\Exception $e) {
            $data = [
                'ERROR' => [$e->getMessage()],
            ];
        }
        return $data;
    }

    /**
     * @return array;
     */
    protected function GetStructure()
    {
        return DataOrderJson::Order();
    }
}
