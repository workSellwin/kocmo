<?php


class sortConfig
{
    protected $TEMPLATE;

    protected $SORT_CONFING = [
        'COMMENT_SORT' => [
            'TEMPLATE_ID' => 'COMMENT_SORT',
            'SORT' => [
                [
                    'NAME' => 'Дата добавления(A-Z)',
                    'CODE' => 'ACTIVE_FROM',
                    'VALUE' => 'DESC',
                ],
                [
                    'NAME' => 'Дата добавления(Z-A)',
                    'CODE' => 'ACTIVE_FROM',
                    'VALUE' => 'ASC',
                ]
            ],
            'DEFAULT' => [
                'NAME' => 'Дата добавления(A-Z)',
                'CODE' => 'ACTIVE_FROM',
                'VALUE' => 'DESC',
            ],
        ]
    ];

    /**
     * sortConfig constructor.
     * @param $template
     */
    public function __construct($template)
    {
        $this->TEMPLATE = $template;
    }

    /**
     * @return bool|mixed|string
     */
    public function getSortConfig()
    {
        if($res = $this->SORT_CONFING[$this->TEMPLATE]){
            if($session = $this->getSessionSortConfig($this->TEMPLATE)){
                $res['SESSION'][$this->TEMPLATE]=$session;
            }
            return $res;

        }else{
            return 'Нет настроек для сортировки '.$this->TEMPLATE;
        }
    }

    /**
     * @param $template
     * @return bool|mixed
     */
    public function getSessionSortConfig($template){
        if(isset($_SESSION[$template]) && !empty($_SESSION[$template])){
            return $_SESSION[$template];
        }else{
            return false;
        }
    }

    /**
     * @param $template
     * @param $arData
     */
    public function setSessionSortConfig($template, $code, $value){
        if($template && $code &&  $value){
            $_SESSION[$template]['CODE']=$code;
            $_SESSION[$template]['VALUE']=$value;
        }
    }

}