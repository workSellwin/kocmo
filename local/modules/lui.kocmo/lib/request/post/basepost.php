<?php


namespace Lui\Kocmo\Request\Post;


use Bitrix\Main\Loader;

abstract class BasePost
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
            $res = $client->request('POST', $this->url, [
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
