<?php
namespace Kocmo\Exchange;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

/**
 * Class DataTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> UID string(36) mandatory
 * <li> JSON string mandatory
 * </ul>
 *
 * @package Bitrix\Exhange
 **/

class DataTable extends Main\Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'kocmo_exchange_data';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return array(
            'ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('DATA_ENTITY_ID_FIELD'),
            ),
            'UID' => array(
                'data_type' => 'string',
                'required' => true,
                'validation' => array(__CLASS__, 'validateUid'),
                'title' => Loc::getMessage('DATA_ENTITY_UID_FIELD'),
            ),
            'ENTRY' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateUid'),
                'title' => Loc::getMessage('DATA_ENTITY_UID_FIELD'),
            ),
            'JSON' => array(
                'data_type' => 'text',
                'required' => true,
                'title' => Loc::getMessage('DATA_ENTITY_JSON_FIELD'),
            ),
        );
    }
    /**
     * Returns validators for UID field.
     *
     * @return array
     */
    public static function validateUid()
    {
        return array(
            new Main\Entity\Validator\Length(null, 38),
        );
    }
}