<?php


namespace Lui\Kocmo\Request;

use Bitrix\Main\Loader;

class UserDiscount extends Base
{

    protected $card;

    /**
     * UserDiscount constructor.
     * @param string $card
     */
    public function __construct(string $card)
    {
        $this->card = $card;
        $url = 'http://kocmo1c.sellwin.by/Kosmo_Sergey/hs/Kocmo/GetCardDiscount';
        $arParams = ['id' => ''];
        parent::__construct($url, $arParams);
    }

    /**
     * @return array|mixed|\Psr\Http\Message\StreamInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function Run()
    {
        $card = $this->card;
        Loader::includeSharewareModule('kocmo.exchange');
        try {
            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', $this->url, [
                'query' => ['id' => $card]
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
        return [];
    }
}
