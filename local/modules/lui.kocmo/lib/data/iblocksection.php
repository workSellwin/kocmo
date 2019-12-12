<?php


namespace Lui\Kocmo\Data;


class IblockSection extends Base implements IblockInterface
{
    use MinData;

    /**
     * @param array $arFilter
     * @return array
     */
    public function GetData(array $arFilter): array
    {
        $arData = [];
        $rsSections = \CIBlockSection::GetList(array('LEFT_MARGIN' => 'ASC'), $arFilter, false, ['*', 'UF_*']);
        while ($arSection = $rsSections->GetNext()) {
            $key = $arSection[$this->key];
            $arSection = $this->RemoveKeyT($arSection);
            $arData[$key] = $arSection;
        }
        return $arData;
    }
}
