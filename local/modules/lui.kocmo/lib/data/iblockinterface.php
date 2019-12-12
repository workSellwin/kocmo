<?php


namespace Lui\Kocmo\Data;

/**
 * Interface IblockInterface
 * @package Lui\Kocmo\Data
 */
interface IblockInterface
{
    /**
     * @param int $id
     * @return array
     */
    public function GetID(int $id): array;

    /**
     * @param array $arId
     * @return array
     */
    public function GetIDs(array $arId): array;

    /**
     * @param string $id
     * @return array
     */
    public function GetXmlID(string $id): array;

    /**
     * @param array $arXmlIDs
     * @return array
     */
    public function GetXmlIDs(array $arXmlIDs): array;

    /**
     * @param array $arFilter
     * @return array
     */
    public function GetData(array $arFilter): array;

    /**
     * @param string $key
     * @return IblockInterface
     */
    public function SetKey(string $key);
}
