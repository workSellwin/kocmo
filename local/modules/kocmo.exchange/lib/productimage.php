<?php
namespace Kocmo\Exchange;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

/**
 * Class ProductImageTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> IMG_GUI string(36) mandatory
 * <li> PRODUCT_ID int mandatory
 * </ul>
 *
 * @package Bitrix\Exchange
 **/

class ProductImageTable extends Main\Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'kocmo_exchange_product_image';
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
                'title' => Loc::getMessage('PRODUCT_IMAGE_ENTITY_ID_FIELD'),
            ),
            'IMG_GUI' => array(
                'data_type' => 'string',
                'required' => true,
                'validation' => array(__CLASS__, 'validateImgGui'),
                'title' => Loc::getMessage('PRODUCT_IMAGE_ENTITY_IMG_GUI_FIELD'),
            ),
            'PRODUCT_ID' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('PRODUCT_IMAGE_ENTITY_PRODUCT_ID_FIELD'),
            ),
        );
    }
    /**
     * Returns validators for IMG_GUI field.
     *
     * @return array
     */
    public static function validateImgGui()
    {
        return array(
            new Main\Entity\Validator\Length(null, 36),
        );
    }
}