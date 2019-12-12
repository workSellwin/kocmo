<?php


namespace Lui\Kocmo\Request\Get;


use Bitrix\Main\Loader;

abstract class BaseGet
{
    protected $url;
    protected $arQuery = [];

    public function __construct(string $url)
    {
        Loader::includeModule('kocmo.exchange');
        $this->url = $url;
    }

    public function Send(): array
    {
        try {
            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', $this->url, [
                'query' => $this->arQuery
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

    public abstract function SetQuery(array $arQuery);

}
