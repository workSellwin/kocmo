<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<form action="<?= $APPLICATION->GetCurPage(); ?>" method="POST" class="cabinet-profile-form">
    <div class="cabinet-profile container">
        <div class="cabinet-profile__title">Личные данные</div>

        <div class="cabinet-profile__fields">
            <div class="cabinet-profile__fields-row">
                <div class="cabinet-profile__fields-half">
                    <div class="form-field">
                        <input name="user-name" value="<?= $arResult['AR_USER']['NAME'] ?>" class="form-field__input"
                               type="text"
                               placeholder="Введите ваше имя">
                    </div>
                </div>

                <div class="cabinet-profile__fields-half">
                    <div class="form-field">
                        <input name="user-lastname" value="<?= $arResult['AR_USER']['LAST_NAME'] ?>"
                               class="form-field__input" type="text"
                               placeholder="Введите вашу фамилию">
                    </div>
                </div>
            </div>

            <div class="cabinet-profile__fields-row">
                <div class="cabinet-profile__fields-half">
                    <div class="form-field">
                        <input name="user-email" value="<?= $arResult['AR_USER']['EMAIL'] ?>" class="form-field__input"
                               type="email"
                               placeholder="Введите ваш email">
                    </div>
                </div>
                <?$arResult['AR_USER']['PERSONAL_PHONE'] = '+' . $arResult['AR_USER']['PERSONAL_PHONE'] ?>
                <div class="cabinet-profile__fields-half">
                    <div class="form-field">
                        <input name="user-phone" value="<?= $arResult['AR_USER']['PERSONAL_PHONE'] ?>"
                               class="form-field__input phone-mask js_phone-mask"
                               type="text"
                               placeholder="+375__-___-__-__">
                    </div>
                </div>
            </div>

            <div class="cabinet-profile__fields-row">
                <div class="cabinet-profile__fields-outer-half">
                    <div class="cabinet-profile__fields-half">
                        <div class="form-field">
                            <select name="user-city" class="js_custom-select">
                                <option value="Минск" selected>Минск</option>
                            </select>
                        </div>
                    </div>
                    <?
                    $street = empty($arResult['USER_STREET']) ? "" : $arResult['USER_STREET'];
                    $house = empty($arResult['USER_HOUSE']) ? "" : $arResult['USER_HOUSE'];
                    $building = empty($arResult['USER_BUILDING']) ? "" : $arResult['USER_BUILDING'];
                    $apartment = empty($arResult['USER_APARTMENT']) ? "" : $arResult['USER_APARTMENT'];
                    ?>
                    <div class="cabinet-profile__fields-half">
                        <div class="form-field">
                            <input name="user-street" value="<?= $street ?>" class="form-field__input" type="text"
                                   placeholder="Введите вашу улицу">
                        </div>
                    </div>
                </div>

                <div class="cabinet-profile__fields-outer-half">
                    <div class="cabinet-profile__fields-third">
                        <div class="form-field">
                            <input name="user-house" value="<?= $house ?>" class="form-field__input" type="text"
                                   placeholder="Дом">
                        </div>
                    </div>
                    <div class="cabinet-profile__fields-third">
                        <div class="form-field">
                            <input name="user-building" value="<?= $building ?>" class="form-field__input" type="text"
                                   placeholder="Корпус">
                        </div>
                    </div>
                    <div class="cabinet-profile__fields-third">
                        <div class="form-field">
                            <input name="user-apartment" value="<?= $apartment ?>" class="form-field__input" type="text"
                                   placeholder="Квартира">
                        </div>
                    </div>
                </div>
            </div>

            <div class="cabinet-profile__fields-row">
                <label class="checkbox js_checkbox cabinet-profile__fields-checkbox">
                    <input type="checkbox"
                           <? if (!empty($arResult['UF_NEWS_SUBSCRIBE'])): ?>checked="checked"<? endif; ?>
                           name="user-news-subscribe">
                    Подписка на новости косметики и парфюмерии
                </label>
            </div>
        </div>
    </div>

    <hr>

    <div class="cabinet-profile container">
        <div class="cabinet-profile__title">Параметры</div>

        <div class="cabinet-profile__fields">
            <div class="cabinet-profile__fields-row">
                <div class="cabinet-profile__fields-half">
                    <div class="cabinet-profile__fields-row">
                        <div class="form-field form-field--full-width">
                            <select name="user-age" class="js_custom-select">
                                <option value="default"<? if (empty($arResult['AR_USER']['UF_AGE'])): ?> selected<? endif; ?>>
                                    Выберите ваш возраст
                                </option>
                                <? foreach ($arResult['HL_AGES'] as $age): ?>
                                    <option value="<?= $age['ID'] ?>"<? if ($arResult['AR_USER']['UF_AGE'] == $age['ID']): ?> selected<? endif; ?>>
                                        <?= $age['UF_NAME'] ?>
                                    </option>
                                <? endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="cabinet-profile__fields-row">
                        <div class="form-field form-field--full-width">
                            <select name="user-eye" class="js_custom-select">
                                <option value="default"<? if (empty($arResult['AR_USER']['UF_EYE_COLOR'])): ?> selected<? endif; ?>>
                                    Выберите цвет глаз
                                </option>
                                <? foreach ($arResult['HL_EYES'] as $eye): ?>
                                    <option value="<?= $eye['ID'] ?>"<? if ($arResult['AR_USER']['UF_EYE_COLOR'] == $eye['ID']): ?> selected<? endif; ?>>
                                        <?= $eye['UF_NAME'] ?>
                                    </option>
                                <? endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="cabinet-profile__fields-row">
                        <div class="form-field form-field--full-width">
                            <select name="user-skin" class="js_custom-select">
                                <option value="default"<? if (empty($arResult['AR_USER']['UF_SKIN'])): ?> selected<? endif; ?>>
                                    Выберите цвет волос
                                </option>
                                <? foreach ($arResult['HL_SKINS'] as $skin): ?>
                                    <option value="<?= $skin['ID'] ?>"<? if ($arResult['AR_USER']['UF_SKIN'] == $skin['ID']): ?> selected<? endif; ?>>
                                        <?= $skin['UF_NAME'] ?>
                                    </option>
                                <? endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="cabinet-profile__fields-row">
                        <div class="form-field form-field--full-width">
                            <select name="user-hair" class="js_custom-select">
                                <option value="default"<? if (empty($arResult['AR_USER']['UF_HAIR'])): ?> selected<? endif; ?>>
                                    Выберите тип кожи
                                </option>
                                <? foreach ($arResult['HL_HAIRS'] as $hair): ?>
                                    <option value="<?= $hair['ID'] ?>"<? if ($arResult['AR_USER']['UF_HAIR'] == $hair['ID']): ?> selected<? endif; ?>>
                                        <?= $hair['UF_NAME'] ?>
                                    </option>
                                <? endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="cabinet-profile__fields-half cabinet-profile__discount-wrap">
                    <!-- DiscountCard.discountCardAJAX() -->
                    <div class="cabinet-profile__discount discount-card js_discount-card preloader-wrap">

                        <? if (empty($arResult['AR_USER']['UF_CARD_KOCMO'])): ?>

                            <div class="discount-card__header js_discount-card__header">
                                <div class="discount-card__submit-one js_discount-card__submit-one btn">
                                    ПРИВЯЗАТЬ КАРТУ КОСМО
                                </div>
                            </div>

                            <div class="discount-card__content js_discount-card__content">
                                <img src="/local/templates/kocmo/imposition/build/assets/images/discount-empty.png"
                                     alt="">
                                <div class="discount-card__content-info">
                                    <div class="discount-card__content-title">
                                        Еще нет<br>
                                        <b>карты космо</b>?
                                    </div>
                                    <div class="discount-card__content-how">
                                        Узнай как можно легко<br>
                                        <a href="/loyalty/">получить ее</a>!
                                    </div>
                                </div>
                            </div>

                            <div class="preloader" style="display: none;">
                                <svg width="64" height="64">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-preloader"></use>
                                </svg>
                            </div>

                        <? else: ?>

                            <div class="discount-card__header discount-card--four js_discount-card__header">
                                <div class="discount-card__header-label">Ваша дисконтная карта:</div>
                                <div class="discount-card__header-card"><?= $arResult['AR_USER']['UF_CARD_KOCMO'] ?></div>
                                <span class="js_discount-card__remove discount-card__remove">Удалить</span>
                            </div>

                            <div class="discount-card__content discount-card__content--four js_discount-card__content">
                                <img src="/local/templates/kocmo/imposition/build/assets/images/discount-request.png"
                                     alt="">
                            </div>

                            <div class="preloader" style="display: none;">
                                <svg width="64" height="64">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-preloader"></use>
                                </svg>
                            </div>
                        <? endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="cabinet-profile container">
        <div class="cabinet-profile__title">Сменить пароль</div>

        <div class="cabinet-profile__fields">
            <div class="cabinet-profile__fields-row">
                <div class="cabinet-profile__fields-half">
                    <div class="form-field">
                        <input name="user-old-pass" value="" class="form-field__input" type="text"
                               placeholder="Введите старый пароль">
                    </div>
                </div>
            </div>

            <div class="cabinet-profile__fields-row">
                <div class="cabinet-profile__fields-half">
                    <div class="form-field">
                        <input name="user-new-pass" value="" class="form-field__input" type="text"
                               placeholder="Введите новый пароль">
                    </div>
                </div>

                <div class="cabinet-profile__fields-half">
                    <div class="form-field">
                        <input name="user-confirm-pass" value="" class="form-field__input" type="text"
                               placeholder="Повторите новый пароль">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="cabinet-profile cabinet-profile--footer container">
        <div class="cabinet-profile__title"></div>

        <div class="cabinet-profile__fields">
            <input type="reset" class="btn btn--transparent cabinet-profile__reset js_custom-select-reset"
                   value="Отменить">

            <input name="submit" type="submit" class="btn cabinet-profile__submit" value="Сохранить изменения">
        </div>
    </div>
</form>
