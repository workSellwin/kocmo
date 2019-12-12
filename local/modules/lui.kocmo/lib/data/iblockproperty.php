<?php


namespace Lui\Kocmo\Data;


class IBlockProperty extends Base implements IblockInterface
{
    use MinData;

    /**
     * @param array $arFilter
     * @return array
     */
    public function GetData(array $arFilter): array
    {
        $arData = [];
        $rsProperty = \CIBlockProperty::GetList(['ID' => 'ASC'], $arFilter);
        while ($arProperty = $rsProperty->Fetch()) {
            $key = $arProperty[$this->key];
            $arProperty = $this->RemoveKeyT($arProperty);
            $arData[$key] = $arProperty;
        }
        return $arData;
    }
}
