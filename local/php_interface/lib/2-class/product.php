<?php
namespace Kocmo\Product;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class ProductTable extends Main\Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'catalog_product';
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
                'title' => Loc::getMessage('PRODUCT_ENTITY_ID_FIELD'),
            ),
            'brand_id' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('PRODUCT_ENTITY_BRAND_ID_FIELD'),
            ),
            'category_id' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('PRODUCT_ENTITY_CATEGORY_ID_FIELD'),
            ),
            'parent_id' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('PRODUCT_ENTITY_PARENT_ID_FIELD'),
            ),
            'title' => array(
                'data_type' => 'string',
                'required' => true,
                'validation' => array(__CLASS__, 'validateTitle'),
                'title' => Loc::getMessage('PRODUCT_ENTITY_TITLE_FIELD'),
            ),
            'price' => array(
                'data_type' => 'float',
                'required' => true,
                'title' => Loc::getMessage('PRODUCT_ENTITY_PRICE_FIELD'),
            ),
            'description' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('PRODUCT_ENTITY_DESCRIPTION_FIELD'),
            ),
            'short_description' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateShortDescription'),
                'title' => Loc::getMessage('PRODUCT_ENTITY_SHORT_DESCRIPTION_FIELD'),
            ),
            'composition' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('PRODUCT_ENTITY_COMPOSITION_FIELD'),
            ),
            'application' => array(
                'data_type' => 'text',
                'title' => Loc::getMessage('PRODUCT_ENTITY_APPLICATION_FIELD'),
            ),
            'date_create' => array(
                'data_type' => 'datetime',
                'title' => Loc::getMessage('PRODUCT_ENTITY_DATE_CREATE_FIELD'),
            ),
            'date_update' => array(
                'data_type' => 'datetime',
                'title' => Loc::getMessage('PRODUCT_ENTITY_DATE_UPDATE_FIELD'),
            ),
            'color_title' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateColorTitle'),
                'title' => Loc::getMessage('PRODUCT_ENTITY_COLOR_TITLE_FIELD'),
            ),
            'color_image' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateColorImage'),
                'title' => Loc::getMessage('PRODUCT_ENTITY_COLOR_IMAGE_FIELD'),
            ),
            'barcode' => array(
                'data_type' => 'string',
                'primary' => true,
                'validation' => array(__CLASS__, 'validateBarcode'),
                'title' => Loc::getMessage('PRODUCT_ENTITY_BARCODE_FIELD'),
            ),
            'bonus' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('PRODUCT_ENTITY_BONUS_FIELD'),
            ),
            'novelty' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('PRODUCT_ENTITY_NOVELTY_FIELD'),
            ),
            'hit' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('PRODUCT_ENTITY_HIT_FIELD'),
            ),
            'exclusive' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('PRODUCT_ENTITY_EXCLUSIVE_FIELD'),
            ),
            'sale_percent' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('PRODUCT_ENTITY_SALE_PERCENT_FIELD'),
            ),
            'rating' => array(
                'data_type' => 'float',
                'required' => true,
                'title' => Loc::getMessage('PRODUCT_ENTITY_RATING_FIELD'),
            ),
            'review_count' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('PRODUCT_ENTITY_REVIEW_COUNT_FIELD'),
            ),
            'active' => array(
                'data_type' => 'integer',
                'required' => true,
                'title' => Loc::getMessage('PRODUCT_ENTITY_ACTIVE_FIELD'),
            ),
            'alias' => array(
                'data_type' => 'string',
                'required' => true,
                'validation' => array(__CLASS__, 'validateAlias'),
                'title' => Loc::getMessage('PRODUCT_ENTITY_ALIAS_FIELD'),
            ),
            'seo_h1' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateSeoH1'),
                'title' => Loc::getMessage('PRODUCT_ENTITY_SEO_H1_FIELD'),
            ),
            'seo_title' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateSeoTitle'),
                'title' => Loc::getMessage('PRODUCT_ENTITY_SEO_TITLE_FIELD'),
            ),
            'seo_description' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateSeoDescription'),
                'title' => Loc::getMessage('PRODUCT_ENTITY_SEO_DESCRIPTION_FIELD'),
            ),
            'seo_keywords' => array(
                'data_type' => 'string',
                'validation' => array(__CLASS__, 'validateSeoKeywords'),
                'title' => Loc::getMessage('PRODUCT_ENTITY_SEO_KEYWORDS_FIELD'),
            ),
            'sort_novelty' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('PRODUCT_ENTITY_SORT_NOVELTY_FIELD'),
            ),
            'sort_hit' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('PRODUCT_ENTITY_SORT_HIT_FIELD'),
            ),
            'sort_exclusive' => array(
                'data_type' => 'integer',
                'title' => Loc::getMessage('PRODUCT_ENTITY_SORT_EXCLUSIVE_FIELD'),
            ),
            'discount_price' => array(
                'data_type' => 'float',
                'required' => true,
                'title' => Loc::getMessage('PRODUCT_ENTITY_DISCOUNT_PRICE_FIELD'),
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
     * Returns validators for short_description field.
     *
     * @return array
     */
    public static function validateShortDescription()
    {
        return array(
            new Main\Entity\Validator\Length(null, 500),
        );
    }
    /**
     * Returns validators for color_title field.
     *
     * @return array
     */
    public static function validateColorTitle()
    {
        return array(
            new Main\Entity\Validator\Length(null, 255),
        );
    }
    /**
     * Returns validators for color_image field.
     *
     * @return array
     */
    public static function validateColorImage()
    {
        return array(
            new Main\Entity\Validator\Length(null, 255),
        );
    }
    /**
     * Returns validators for barcode field.
     *
     * @return array
     */
    public static function validateBarcode()
    {
        return array(
            new Main\Entity\Validator\Length(null, 128),
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
}