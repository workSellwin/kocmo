<?php

class BitrixTwigExtension extends Twig_Extension
{
    const DEFAULT_TEMPLATE_PATH = "/bitrix/templates/.default";

    private $isD7 = null;

    public function getName()
    {
        return 'bitrix';
    }

    public function getGlobals()
    {
        global $APPLICATION, $USER;

        return array(
            'APPLICATION' => $APPLICATION,
            'USER' => $USER,
            'LANG' => LANG,
            'POST_FORM_ACTION_URI' => POST_FORM_ACTION_URI,
            'DEFAULT_TEMPLATE_PATH' => self::DEFAULT_TEMPLATE_PATH,
            '_REQUEST' => $_REQUEST,
            'SITE_SERVER_NAME' => SITE_SERVER_NAME,
        );
    }

    private function isD7()
    {
        if ($this->isD7 === null) {
            $this->isD7 = class_exists('\Bitrix\Main\Application');
        }
        return $this->isD7;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getMessage', $this->isD7() ? '\\Bitrix\\Main\\Localization\\Loc::getMessage' : 'GetMessage'),
            new \Twig_SimpleFunction('showComponent', array(__CLASS__, 'showComponent')),
            new Twig_SimpleFunction('ShowMessage', array($this, 'showMessage'), array('message')),
            new Twig_SimpleFunction('bitrix_sessid_post', array($this, 'bitrix_sessid_post')),
            new Twig_SimpleFunction('bitrix_sessid_get', array($this, 'bitrix_sessid_get')),
            new Twig_SimpleFunction('ShowError', array($this, 'showError'), array('message', 'css_class')),
            new Twig_SimpleFunction('ShowNote', array($this, 'showNote'), array('message', 'css_class')),
            new Twig_SimpleFunction('IsUserAdmin', array($this, 'isUserAdmin')),
            new Twig_SimpleFunction('implode', array($this, 'implode'), array('glue', 'pieces')),
            new Twig_SimpleFunction('IsUserAuthorized', array($this, 'isUserAuthorized')),
            new \Twig_SimpleFunction('AddBitrixActions', function ($arItem) {
                $component = new \CBitrixComponent();
                $component->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], \CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $component->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], \CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"), array("CONFIRM" => 'Удалить элемент?'));
            }),
            new \Twig_SimpleFunction('GetEditAreaId', function ($arItem) {
                $component = new \CBitrixComponent();
                return $component->GetEditAreaId($arItem['ID']);
            }),
            new \Twig_SimpleFunction('str_repeat', function ($input, $multiplier) {
                return str_repeat($input, $multiplier);
            }),
            new \Twig_SimpleFunction('include', function ($file) {
                return include($file);
            }),
        );
    }

    /**
     * @param string $componentName
     * @param string $componentTemplate
     * @param array $arParams
     * @param \CBitrixComponent $parentComponent
     * @param array $arFunctionParams
     */
    public static function showComponent($componentName, $componentTemplate, $arParams = array(), $parentComponent = null, $arFunctionParams = array())
    {
        global $APPLICATION;
        $APPLICATION->IncludeComponent($componentName, $componentTemplate, $arParams, $parentComponent, $arFunctionParams);
    }

    /**
     * @param string $glue
     * @param array $pieces
     * @return string
     */
    public function implode($glue = ' ', $pieces = [])
    {
        return implode($glue, $pieces);
    }

    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('formatDate', array($this, 'formatDate'), array('rawDate', 'format')),
            new Twig_SimpleFilter('russianPluralForm', array($this, 'russianPluralForm'), array('string', 'count', 'delimiter')),
        );
    }

    public function showMessage($message)
    {
        ShowMessage($message);
    }

    public function showError($message, $css_class = "errortext")
    {
        ShowError($message, $css_class);
    }

    public function showNote($message, $css_class = "notetext")
    {
        ShowNote($message, $css_class);
    }

    public function bitrix_sessid_post()
    {
        return bitrix_sessid_post();
    }

    public function bitrix_sessid_get()
    {
        return bitrix_sessid_get();
    }

    public function isUserAdmin()
    {
        global $USER;
        return $USER->IsAdmin();
    }

    public function isUserAuthorized()
    {
        global $USER;
        return $USER->IsAuthorized();
    }

    public function formatDate($rawDate, $format = 'FULL')
    {
        return FormatDateFromDB($rawDate, $format);
    }


    public function russianPluralForm($string, $count, $delimiter = "|")
    {
        list($endWith1, $endWith2to4, $endWith5to9and0) = explode($delimiter, $string);

        if (strlen($count) > 1 && substr($count, strlen($count) - 2, 1) == "1") {
            return $endWith5to9and0;
        } else {
            $lastDigit = intval(substr($count, strlen($count) - 1, 1));
            if ($lastDigit == 0 || ($lastDigit >= 5 && $lastDigit <= 9)) {
                return $endWith5to9and0;
            } elseif ($lastDigit == 1) {
                return $endWith1;
            } else {
                return $endWith2to4;
            }
        }
    }
}
