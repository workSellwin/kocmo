<?php


namespace Lui\Kocmo\Request\Post;


class SetPayment extends BasePost
{
    /**
     * SetPayment constructor.
     * @param string $uid_order
     * @param string $uid_payment
     * @param string $value
     */
    public function __construct(string $uid_order, string $uid_payment, float $value)
    {
        parent::__construct(\Lui\Kocmo\Request\Config\Url::SetPayment());
        $json = [
            'uid_order' => $uid_order,
            'uid_payment' => $uid_payment,
            'value' => $value,
        ];
        $this->SetQuery(['json' => json_encode($json)]);
    }

    public function SetQuery(array $arQuery)
    {
        $this->arQuery = $arQuery;
    }
}
