<?php


namespace Lui\Kocmo\Helper;

use Bitrix\Sale;
use Lui\Kocmo\Data\IblockElement;

class EmailBasket
{
    public static function GetHtml(Sale\Basket $basket)
    {
        $html = '';
        $basketItems = $basket->getBasketItems();
        if ($basketItems) {
            $html .= self::Start();
            $i = 1;
            $count = count($basketItems);
            foreach ($basketItems as $bItem) {
                $html .= self::ItemTr($bItem, $i, $count);
                $i++;
            }
            $html .= self::Finish();
        }
        return $html;
    }

    public static function Start()
    {
        return <<<HTML
         <table cellpadding="0" cellspacing="0" border="0" width="522">
                                        <tbody>
HTML;
    }

    public static function Finish()
    {
        return <<<HTML
             </tbody>
           </table>
HTML;
    }

    public static function ItemTr(\Bitrix\Sale\BasketItem $basketItem, int $i, int $count)
    {
        $html = ' <tr><td>';
        $html .= self::HrTop($i, $count);
        $html .= self::Item($basketItem);
        $html .= self::HrBottom($i, $count);
        $html .= ' </td></tr>';
        return $html;
    }

    public static function Item(\Bitrix\Sale\BasketItem $basketItem)
    {

        $ob = new  IblockElement();
        $arElement = $ob->GetID($basketItem->getProductId());
        if ($arElement) {
            $arElement = reset($arElement);
        }
        PR($arElement, true);    $html = <<<HTML
<table cellspacing="0" cellpadding="0" border="0" width="100%" bgcolor="#ffffff">
                                                    <tbody>
                                                    <tr  height="20">
                                                        <td height="20" width="134" >

                                                        </td>
                                                        <td width="191" >

                                                        </td>
                                                        <td width="82" >

                                                        </td>
                                                        <td >

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" rowspan="3">
                                                            <img src="assets/img/p1.jpg" alt="">
                                                        </td>
                                                        <td colspan="2">

                                                        </td>
                                                        <td >
                                                            <font face="Calibri, Tahoma, Segoe, sans-serif"
                                                                  style="font-size:20px;color:#D01C60;line-height: 120%;">
                                                            {$basketItem->getPrice()}</font> <font face="Calibri, Tahoma, Segoe, sans-serif"
                                                                        style="font-size:14px;color:#D01C60;line-height: 20px;">руб
                                                            </font>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <font face="Calibri, Tahoma, Segoe, sans-serif"
                                                                  style="font-size:14px;color:#333131;line-height: 20px;">
                                                         {$arElement['NAME']}
                                                            </font>
                                                        </td>
                                                        <td>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <font face="Calibri, Tahoma, Segoe, sans-serif"
                                                                  style="font-size:14px;color:#181717;line-height: 20px;">
                                                            Артикул: {$arElement['PROPERTY']['ARTIKUL']}
                                                            </font>
                                                        </td>
                                                        <td colspan="2">
                                                            
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td height="20" colspan="4">

                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                               
HTML;

        return $html;
    }

    public static function HrTop(int $i, int $count)
    {
        $s = '';
        if ($i == 1) {
            $s = self::Hr();
        }
        return $s;
    }

    public static function HrBottom(int $i, int $count)
    {
        $s = '';
        if ($i != $count) {
            $s = self::Hr();
        }
        return $s;
    }

    public static function Hr()
    {
        return '<hr style="border: 1px solid #EDEDED">';
    }

    public static function Exsample()
    {
        return <<<HTML

                                        <tr>
                                            <td>
                                                <hr style="border: 1px solid #EDEDED">
                                                <table cellspacing="0" cellpadding="0" border="0" width="100%" bgcolor="#ffffff">
                                                    <tbody>
                                                    <tr  height="20">
                                                        <td height="20" width="134" >

                                                        </td>
                                                        <td width="191" >

                                                        </td>
                                                        <td width="82" >

                                                        </td>
                                                        <td >

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" rowspan="3">
                                                            <img src="assets/img/p1.jpg" alt="">
                                                        </td>
                                                        <td colspan="2">

                                                        </td>
                                                        <td >
                                                            <font face="Calibri, Tahoma, Segoe, sans-serif"
                                                                  style="font-size:20px;color:#D01C60;line-height: 120%;">
                                                            23,90</font> <font face="Calibri, Tahoma, Segoe, sans-serif"
                                                                        style="font-size:14px;color:#D01C60;line-height: 20px;">руб
                                                            </font>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <font face="Calibri, Tahoma, Segoe, sans-serif"
                                                                  style="font-size:14px;color:#333131;line-height: 20px;">
                                                            Маска карбонатная, на основе чистка очистки от черных точек, 500 мл.
                                                            </font>
                                                        </td>
                                                        <td>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <font face="Calibri, Tahoma, Segoe, sans-serif"
                                                                  style="font-size:14px;color:#181717;line-height: 20px;">
                                                            Артикул: 455-90-90
                                                            </font>
                                                        </td>
                                                        <td colspan="2">
                                                            <img src="assets/ico/color.png" alt="">
                                                            <font face="Calibri, Tahoma, Segoe, sans-serif"
                                                                  style="font-size:14px;color:#181717;line-height: 20px;position: relative;top:-8px">
                                                                светло-бежевый 102
                                                            </font>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td height="20" colspan="4">

                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <hr style="border: 1px solid #EDEDED">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table cellspacing="0" cellpadding="0" border="0" width="100%" bgcolor="#ffffff">
                                                    <tbody>
                                                    <tr  height="20">
                                                        <td height="20" width="134" >

                                                        </td>
                                                        <td width="191" >

                                                        </td>
                                                        <td width="82" >

                                                        </td>
                                                        <td >

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" rowspan="3">
                                                            <img src="assets/img/p2.png" alt="">
                                                        </td>
                                                        <td colspan="2">

                                                        </td>
                                                        <td >
                                                            <font face="Calibri, Tahoma, Segoe, sans-serif"
                                                                  style="font-size:20px;color:#D01C60;line-height: 120%;">
                                                                78,50</font>
                                                            <font face="Calibri, Tahoma, Segoe, sans-serif"
                                                                  style="font-size:14px;color:#D01C60;line-height: 20px;">руб
                                                        </font>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <font face="Calibri, Tahoma, Segoe, sans-serif"
                                                                  style="font-size:14px;color:#333131;line-height: 20px;">
                                                                Маска карбонатная, на основе чистка очистки от черных точек, 500 мл.
                                                            </font>
                                                        </td>
                                                        <td>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">
                                                            <font face="Calibri, Tahoma, Segoe, sans-serif"
                                                                  style="font-size:14px;color:#181717;line-height: 20px;">
                                                                Артикул: 455-90-90
                                                            </font>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td height="20" colspan="4">

                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <hr style="border: 1px solid #EDEDED">
                                            </td>
                                        </tr>
                                     
HTML;

    }
}
