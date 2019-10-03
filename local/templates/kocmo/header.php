<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
use Lui\Kocmo\IncludeComponent;

global $USER;
if (!is_object($USER)) $USER = new \CUser;


Loc::loadMessages(__FILE__);
$obAsset = Asset::getInstance();
define('KOCMO_TEMPLATE_PATH', SITE_TEMPLATE_PATH . '/imposition/build');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <? $APPLICATION->ShowHead(); ?>
    <title><? $APPLICATION->ShowTitle() ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">
    <!-- CSS -->
    <?$obAsset->addCss(KOCMO_TEMPLATE_PATH . "/assets/css/style.css"); ?>
    <?$obAsset->addCss(KOCMO_TEMPLATE_PATH . "/assets/css/custom.css"); ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

    <!-- favicon -->
</head>

<body>
<? $APPLICATION->ShowPanel() ?>
<? IncludeComponent::AdvertisingBanner(['template' => 'top', 'QUANTITY' => '1', 'TYPE' => 'MAIN']) ?>
<header class="header">
    <div class="header-inner">
        <div class="header__top">
            <div class="container header__top-inner">
                <div class="header-place">
                    <a href="#" class="header-place__item">
                        <svg width="16" height="21">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-location"></use>
                        </svg>
                        <span>Ваш город:</span> Могилев
                    </a>
                    <a href="#" class="header-place__item">
                        <svg width="21" height="21">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-find-shop"></use>
                        </svg>
                        Hайти магазин
                    </a>
                </div>
                <? IncludeComponent::Menu(['template' => 'top', 'ROOT_MENU_TYPE' => 'top', 'MAX_LEVEL' => '1']) ?>
                <a href="#" class="header__top-promo">
                    <svg width="24" height="29">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-promo-page"></use>
                    </svg>
                    Акционная листовка
                </a>
            </div>
        </div>




        <div class="header__middle">
            <div class="container header__middle-inner">
                <div class="mobile-burger">
                    <span class="mobile-burger__line"></span>
                </div>

                <form class="header-search field-bordered" method="post" action="" name="">
                    <button type="submit" value="" class="header-search__submit">
                        <svg width="20" height="18">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-magnifier"></use>
                        </svg>
                    </button>
                    <input type="text" name="" id="" class="header-search__text"
                           placeholder="Введите свой поисковый запрос">
                </form>

                <a href="/" class="header__middle-logo">
                    <img src="/assets/images/logo.png" alt="" class="header-logo">
                    <img src="/assets/images/logo-mobile.png" alt="" class="header-logo-mobile">
                </a>

                <a href="#" class="header__middle-promo">
                    <svg width="24" height="29">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-promo-page"></use>
                    </svg>
                    Акционная листовка
                </a>

                <div class="personality-state">
                    <a href="#"
                       class="personality-state__item personality-state__item--fixed-show personality-state__item--mobile-show">
                        <svg width="25" height="25">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-magnifier"></use>
                        </svg>
                        Поиск по сайту
                    </a>
                    <a href="#" class="personality-state__item">
                        <svg width="25" height="25">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-wishlist"></use>
                        </svg>
                        Избранное
                    </a>
                    <a href="#" class="personality-state__item  personality-state__item--mobile-show">
                        <div class="personality-state__count">9</div>
                        <svg width="25" height="25">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-basket"></use>
                        </svg>
                        Корзина
                    </a>
                    <? if ($USER->IsAuthorized()) { ?>
                        <a href="/personal/" class="personality-state__item personality-state__item--registered">
                            <svg width="25" height="25">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-cabinet"></use>
                            </svg>
                            <div>
                                <? $arUserGet = explode(' ', $USER->GetFullName()); ?>
                                <div><?= $arUserGet[0] ?></div>
                                <div><?= $arUserGet[1] ?></div>
                            </div>
                        </a>
                    <? } else { ?>
                        <a href="/auth/" class="personality-state__item">
                            <svg width="25" height="25">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-cabinet"></use>
                            </svg>
                            <span>Личный кабинет</span>
                        </a>
                    <? } ?>
                </div>
            </div>
        </div>


        <? IncludeComponent::Menu([
            'template' => 'catalog',
            'ROOT_MENU_TYPE' => 'catalog',
            'MAX_LEVEL' => '3',
            "CHILD_MENU_TYPE" => "catalog",
            "USE_EXT" => "Y"
        ]); ?>


    </div>
</header>

<div class="mobile-nav">
    <div class="mobile-nav-header">
        <a href="#" class="mobile-nav-header__item">
            <svg width="22" height="22">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-wishlist"></use>
            </svg>
            Избранное
        </a>

        <? if ($USER->IsAuthorized()) { ?>
            <a href="/personal/" class="mobile-nav-header__item personality-state__item--registered">
                <svg width="25" height="25">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-cabinet"></use>
                </svg>
                <div>
                    <? $arUserGet = explode(' ', $USER->GetFullName()); ?>
                    <div><?= $arUserGet[0] ?></div>
                    <div><?= $arUserGet[1] ?></div>
                </div>
            </a>
        <? } else { ?>
            <a href="/auth/" class="mobile-nav-header__item">
                <svg width="22" height="22">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-cabinet"></use>
                </svg>
                Личный кабинет
            </a>
        <? } ?>

    </div>


    <? IncludeComponent::Menu([
        'template' => 'header_mobil_menu',
        'ROOT_MENU_TYPE' => 'catalog',
        'MAX_LEVEL' => '1',
        "CHILD_MENU_TYPE" => "catalog",
        "USE_EXT" => "Y"
    ]); ?>


    <div class="mobile-nav-footer">

        <? IncludeComponent::NewsList(['template' => 'header_mob_soc', 'PARENT_SECTION' => '23']) ?>

        <div class="mobile-nav__contacts">
            <a href="tel:+375296665544" class="mobile-nav__phone">666-55-44</a>
            <div>
                <div class="mobile-nav__contacts-title">Горячая линия</div>
                <div class="mobile-nav__contacts-schedule">ежедневно с 8:00 до 22:00</div>
            </div>
        </div>
        <div class="mobile-nav__loc-wrap">
            <a href="#" class="mobile-nav__loc-find">
                <svg width="21" height="21">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-find-shop"></use>
                </svg>
                Hайти магазин
            </a>
            <a href="#" class="mobile-nav__location">
                <svg width="16" height="21">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-location"></use>
                </svg>
                <span>Ваш город:</span> Могилев
            </a>
        </div>
    </div>
</div>

<div class="mobile-nav-overlay"></div>
