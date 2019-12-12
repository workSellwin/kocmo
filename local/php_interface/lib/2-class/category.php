<?php

namespace Kocmo\Category;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

class CategoryTable extends Main\Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'catalog_category';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return array(
            'id' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('CATEGORY_ENTITY_ID_FIELD'),
            ),
            'parent_id' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('CATEGORY_ENTITY_PARENT_ID_FIELD'),
            ),
            'cid' => array(
                'data_type' => 'integer',
                'primary' => true,
                'title' => Loc::getMessage('CATEGORY_ENTITY_CID_FIELD'),
            ),
            'title' => array(
                'data_type' => 'string',
                'required' => true,
                'validation' => array(__CLASS__, 'validateTitle'),
                'title' => Loc::getMessage('CATEGORY_ENTITY_TITLE_FIELD'),
            ),
            'updated' => array(
                'data_type' => 'datetime',
                'title' => Loc::getMessage('CATEGORY_ENTITY_UPDATED_FIELD'),
            ),
            'created' => array(
                'data_type' => 'datetime',
                'title' => Loc::getMessage('CATEGORY_ENTITY_CREATED_FIELD'),
            ),
            'menu_order' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('CATEGORY_ENTITY_MENU_ORDER_FIELD'),
            ),
            'alias' => array(
                'data_type' => 'string',
                'required' => true,
                'validation' => array(__CLASS__, 'validateAlias'),
                'title' => Loc::getMessage('CATEGORY_ENTITY_ALIAS_FIELD'),
            ),
            'seo_h1' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateSeoH1'),
                'title' => Loc::getMessage('CATEGORY_ENTITY_SEO_H1_FIELD'),
            ),
            'seo_title' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateSeoTitle'),
                'title' => Loc::getMessage('CATEGORY_ENTITY_SEO_TITLE_FIELD'),
            ),
            'seo_description' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateSeoDescription'),
                'title' => Loc::getMessage('CATEGORY_ENTITY_SEO_DESCRIPTION_FIELD'),
            ),
            'seo_keywords' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateSeoKeywords'),
                'title' => Loc::getMessage('CATEGORY_ENTITY_SEO_KEYWORDS_FIELD'),
            ),
            'image' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateImage'),
                'title' => Loc::getMessage('CATEGORY_ENTITY_IMAGE_FIELD'),
            ),
            'active' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('CATEGORY_ENTITY_ACTIVE_FIELD'),
            ),
            'description' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('CATEGORY_ENTITY_DESCRIPTION_FIELD'),
            ),
        );
    }
    /**
     * Returns validators for title field.
     *
     * @return array
     */
    public static function validateTitle()
    {
        return array(
            new Main\Entity\Validator\Length(null, 500),
        );
    }
    /**
     * Returns validators for alias field.
     *
     * @return array
     */
    public static function validateAlias()
    {
        return array(
            new Main\Entity\Validator\Length(null, 255),
        );
    }
    /**
     * Returns validators for seo_h1 field.
     *
     * @return array
     */
    public static function validateSeoH1()
    {
        return array(
            new Main\Entity\Validator\Length(null, 255),
        );
    }
    /**
     * Returns validators for seo_title field.
     *
     * @return array
     */
    public static function validateSeoTitle()
    {
        return array(
            new Main\Entity\Validator\Length(null, 255),
        );
    }
    /**
     * Returns validators for seo_description field.
     *
     * @return array
     */
    public static function validateSeoDescription()
    {
        return array(
            new Main\Entity\Validator\Length(null, 500),
        );
    }
    /**
     * Returns validators for seo_keywords field.
     *
     * @return array
     */
    public static function validateSeoKeywords()
    {
        return array(
            new Main\Entity\Validator\Length(null, 255),
        );
    }
    /**
     * Returns validators for image field.
     *
     * @return array
     */
    public static function validateImage()
    {
        return array(
            new Main\Entity\Validator\Length(null, 255),
        );
    }
}