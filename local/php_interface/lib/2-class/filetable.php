<?php
namespace Asdrubael\Bx;

use Bitrix\Main\Entity;
use Bitrix\Main\Type;


class FileTable extends Entity\DataManager
{

    public static function getTableName()
    {
        return 'b_file';
    }

    public static function getMap()
    {
        return array(
            'ID' => new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true,
            )),
            'TIMESTAMP_X' => new Entity\DatetimeField('TIMESTAMP_X', array(
                'default_value' => new Type\DateTime
            )),
            'MODULE_ID' => new Entity\StringField('MODULE_ID', array(
                'validation' => array(__CLASS__, 'validateModuleId'),
            )),
            'HEIGHT' => new Entity\IntegerField('HEIGHT'),
            'WIDTH' => new Entity\IntegerField('WIDTH'),
            'FILE_SIZE' => new Entity\IntegerField('FILE_SIZE'),
            'CONTENT_TYPE' => new Entity\StringField('CONTENT_TYPE', array(
                'validation' => array(__CLASS__, 'validateContentType'),
            )),
            'SUBDIR' => new Entity\StringField('SUBDIR', array(
                'validation' => array(__CLASS__, 'validateSubdir'),
            )),
            'FILE_NAME' => new Entity\StringField('FILE_NAME', array(
                'validation' => array(__CLASS__, 'validateFileName'),
                'required' => true,
            )),
            'ORIGINAL_NAME' => new Entity\StringField('ORIGINAL_NAME', array(
                'validation' => array(__CLASS__, 'validateOriginalName'),
            )),
            'DESCRIPTION' => new Entity\StringField('DESCRIPTION', array(
                'validation' => array(__CLASS__, 'validateDescription'),
            )),
            'HANDLER_ID' => new Entity\StringField('HANDLER_ID', array(
                'validation' => array(__CLASS__, 'validateHandlerId'),
            )),
            'EXTERNAL_ID' => new Entity\StringField('EXTERNAL_ID', array(
                'validation' => array(__CLASS__, 'validateExternalId'),
            )),
        );
    }

    public static function validateModuleId()
    {
        return array(
            new Entity\Validator\Length(null, 50),
        );
    }

    public static function validateContentType()
    {
        return array(
            new Entity\Validator\Length(null, 255),
        );
    }

    public static function validateSubdir()
    {
        return array(
            new Entity\Validator\Length(null, 255),
        );
    }

    public static function validateFileName()
    {
        return array(
            new Entity\Validator\Length(null, 255),
        );
    }

    public static function validateOriginalName()
    {
        return array(
            new Entity\Validator\Length(null, 255),
        );
    }

    public static function validateDescription()
    {
        return array(
            new Entity\Validator\Length(null, 255),
        );
    }

    public static function validateHandlerId()
    {
        return array(
            new Entity\Validator\Length(null, 50),
        );
    }

    public static function validateExternalId()
    {
        return array(
            new Entity\Validator\Length(null, 50),
        );
    }

 }