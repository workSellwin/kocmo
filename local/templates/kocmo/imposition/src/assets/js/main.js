;(function ($) {
    $(function () {
        MainJs.init({
            fancyboxLink: 'a.fancybox',
            fancyboxLinkOverflow: 'a.fancybox-overflow',
            fancyboxVacancy: '.js_fancybox-vacancy',
            fullWidthSlider: '.js_full-width-slider',
            suggestionMoreBtn: '.js_suggestions__btn',
            reviewsMoreBtn: '.js_reviews__btn',
            productsMoreBtn: '.js_products__btn',
            vacanciesMoreBtn: '.js_vacancies__item-more',
            mobileScrollSlider: '.js_mobile-scroll-slider',
            scrollSlider: '.js_scroll-slider',
            salesSlider: '.js_sales-slider__swiper-container',
            tabWrapper: '.js_tabs-wrap',
            tabBtn: '.js_tab',
            tabInner: '.js_panel',
            customSelect: '.js_custom-filter-select',
            customSelectReset: '.js_custom-select-reset',
            customSelectField: '.js_custom-select',
            categoryBanner: '.js_category-banner',
            breadcrumbs: '.js_breadcrumbs',
            asideNavWrap: '.js_aside-nav-wrap',
            asideNavTitle: '.js_aside-nav__title',
            asideNav: '.js_aside-nav',
            asideNavInner: '.js_aside-nav__inner',
            newsSlider: '.news-inner__slider-container',
            giftSlider: '.js_e-gift__slider-container',
            productSlider: '.js_product__slider',
            productSliderThumbs: '.js_product__slider-thumbs',
            counterButton: '.js_counter__button',
            counterInput: '.js_counter__input',
            productForm: '.js_product__form',
            tabColumnsBtnMore: '.js_panel__two-columns-more',
            brandsInnerBtnMore: '.js_brands-inner-info__more',
            loyaltyBtnMore: '.js_loyalty__more-btn',
            scrollToBtn: '.js_scroll-to',
            writeReviewBtn: '.js_reviews__send-review',
            reviewMoreBtn: '.js_reviews__item-main-more',
            preorderBtn: '.js_product__preorder',
            fieldRequiredInput: '.js_field-required-input',
            basketItemClose: '.js_basket__item-close',
            headerBasketItemClose: '.js_header-basket__item-close',
            basketRadio: '.js_basket-radio',
            headerBasketScroll: '.js_header-basket__content',
            phoneMask: '.js_phone-mask',
            cabinetOrdersBtn: '.js_cabinet-orders__item-header',
            productsItemRemove: '.js_products-item__remove',
            sliderPointerSetPrice: '.js_slider-pointer-set-price',
            filterDropDown: '.js_filter-scroll'
        });

        Filter.init({
            filterAccordion: '.js_filter-accordion',
            filterWatchingFields: '.js_category-filter input[type="checkbox"]',
            filterActiveField: '.js_category-filter-active-fields',
            filterActiveItem: '.category-filter-active__item',
            filterActiveWrapper: '.category-filter-active-fields-wrap',
            closeBtn: '.js_category-filter-active__item-close'
        });

        Header.init({
            menu: '.nav',
            menuContainer: '.header__bottom-inner',
            menuItem: '.nav__lnk',
            dropdownBrands: '.nav-dropdown__brands-inner',
            personalityStateCounter: '.personality-state__count',
            searchInput: '.header-search__text',
            searchForm: '.header-search',
            header: '.header',
            headerInner: '.header-inner',
            topBanner: '.top-banner',
            mobileBurger: '.mobile-burger',
            mobileNav: '.mobile-nav',
            mobileNavOverlay: '.mobile-nav-overlay'
        });

        ProductColor.init({
            productColorTitle: '.js_product__colors-title',
            productColorItem: '.js_product__colors-item',
            productColorToggleItemsBtn: '.js_product__show-hide-colors',
            productColorContent: '.js_product__colors-content'
        });

        DiscountCard.init({
            discountCardWrap: '.js_discount-card',
            discountCardHeader: '.js_discount-card__header',
            discountCardContent: '.js_discount-card__content',
            btnStepOne: '.js_discount-card__submit-one',
            btnStepTwo: '.js_discount-card__submit-two',
            btnStepThree: '.js_discount-card__submit-three',
            btnStepFour: '.js_discount-card__remove'
        });

        LoyaltySale.init({
            checkSaleBtn: '.js_check-sale',
            btnStepOne: '.js_check-sale__submit-one',
            btnStepTwo: '.js_check-sale__submit-two',
            wrapper: '.popup',
            formWrap: '.popup-check-sale__form-wrap'
        });
    });
})(jQuery);

var Header = {
    init: function (config) {
        this.menu = $(config.menu);
        this.menuContainer = $(config.menuContainer);
        this.menuItem = $(config.menuItem);
        this.dropdownBrands = $(config.dropdownBrands);
        this.personalityStateCounter = $(config.personalityStateCounter);
        this.searchForm = $(config.searchForm);
        this.searchInput = $(config.searchInput);
        this.header = $(config.header);
        this.headerInner = config.headerInner;
        this.topBanner = $(config.topBanner);
        this.mobileBurger = $(config.mobileBurger);
        this.mobileNav = $(config.mobileNav);
        this.mobileNavOverlay = $(config.mobileNavOverlay);

        if (this.dropdownBrands) {
            this.dropdownBrandsScroll();
        }

        if (this.personalityStateCounter.length) {
            this.setSvgNavColor();
        }

        if (this.searchForm.length) {
            this.searchFormState();
        }

        this.headerScroll();
        this.mobileNavigation();

        this.resize();
        //this.autoSizeFontMenu();
    },

    headerScroll: function () {
        var topPosition = this.topBanner ? this.topBanner.height() : 0,
            headerInnerHeight = $(this.headerInner).height(),
            scrollShift = window.innerWidth > 1023 ? 45 : 0, //header__top height
            isMobile = window.innerWidth <= 1023,
            _this = this;

        if (!isMobile) {
            this.header.css('min-height', headerInnerHeight + 'px');
        }


        $(window).off('scroll.header');
        $(window).on('scroll.header', function () {
            if (($(this).scrollTop() >= topPosition + scrollShift) && window.innerWidth > 1023) {
                $(_this.headerInner).addClass('header-inner--fixed');
            } else {
                $(_this.headerInner).removeClass('header-inner--fixed');
            }
        });
    },

    resetHeaderHeight: function () {
        this.header.css('min-height', '');
    },

    dropdownBrandsScroll: function () {
        this.dropdownBrands.jScrollPane({
            autoReinitialise: true
        });

        this.menuItem.hover(
            function () {
                var $dropdown = $(this).next('.nav-dropdown'),
                    subHeight = $dropdown.find('.nav-dropdown__sub').height(),
                    imgHeight = $dropdown.find('.nav-dropdown__img').height(),
                    maxHeight = parseInt(Math.max(subHeight, imgHeight)) - 26; //26 title height + margin bottom

                $dropdown
                    .find('.nav-dropdown__brands-inner')
                    .css({'max-height': maxHeight + 'px'});
            }
        );
    },


    setSvgNavColor: function () {
        $('.personality-state__count').each(function () {
            if ($(this).is(':visible')) {
                $(this).next('svg').css({'fill': '#8C249F'})
            }
        });
    },

    searchFormState: function () {
        this.searchInput.on('keyup', function (e) {
            if ($(e.target).val().trim().length) {
                this.searchForm.addClass('header-search--hasText');
            } else {
                this.searchForm.removeClass('header-search--hasText');
            }
        }.bind(this));
    },

    mobileNavigation: function () {
        this.mobileBurger.on('click', function (e) {
            this.mobileNav.toggleClass('mobile-nav--active');
            $('body').toggleClass('mobile-nav--active');
            this.mobileNavOverlay.toggleClass('mobile-nav-overlay--active');
            $(e.currentTarget).toggleClass('mobile-burger--active');
        }.bind(this));

        $('.mobile-nav-overlay').on('click', function () {
            this.mobileBurger.trigger('click');
        }.bind(this));

    },

    // autoSizeFontMenu: function () {
    //     if (window.innerWidth > 768 && this.isMenuBiggerContainer()) {
    //         this.menuItem.css('font-size', parseInt(this.menuItem.css('font-size')) - 1 + 'px');
    //         this.autoSizeFontMenu();
    //     }
    //
    //     return false;
    // },
    //
    // isMenuBiggerContainer: function () {
    //     return this.getMenuWidth() > this.menuContainer.width();
    // },
    //
    // getMenuWidth: function () {
    //     var menuWidth = 0;
    //
    //     this.menuItem.each(function () {
    //         menuWidth += $(this).outerWidth();
    //     });
    //
    //     return parseInt(menuWidth);
    // },

    resize: function () {
        $(window).resize(function () {
            //this.autoSizeFontMenu();
            this.resetHeaderHeight();
            this.headerScroll();
        }.bind(this));
    }
};

var MainJs = {
    init: function (config) {
        this.fancyboxLink = $(config.fancyboxLink);
        this.fancyboxLinkOverflow = $(config.fancyboxLinkOverflow);
        this.fancyboxVacancy = $(config.fancyboxVacancy);
        this.fullWidthSlider = config.fullWidthSlider;
        this.suggestionMoreBtn = $(config.suggestionMoreBtn);
        this.productsMoreBtn = $(config.productsMoreBtn);
        this.vacanciesMoreBtn = config.vacanciesMoreBtn;
        this.reviewsMoreBtn = $(config.reviewsMoreBtn);
        this.mobileScrollSlider = config.mobileScrollSlider;
        this.salesSlider = config.salesSlider;
        this.newsSlider = config.newsSlider;
        this.giftSlider = config.giftSlider;
        this.mobileScrollSliderObj = null;
        this.scrollSlider = config.scrollSlider;
        this.tabWrapper = config.tabWrapper;
        this.tabBtn = config.tabBtn;
        this.tabInner = config.tabInner;
        this.customSelect = $(config.customSelect);
        this.customSelectReset = $(config.customSelectReset);
        this.customSelectField = $(config.customSelectField);
        this.categoryBanner = $(config.categoryBanner);
        this.breadcrumbs = $(config.breadcrumbs);
        this.asideNavWrap = $(config.asideNavWrap);
        this.asideNavTitle = config.asideNavTitle;
        this.asideNav = config.asideNav;
        this.asideNavInner = $(config.asideNavInner);
        this.productSlider = $(config.productSlider);
        this.productSliderThumbs = $(config.productSliderThumbs);
        this.counterButton = $(config.counterButton);
        this.counterInput = config.counterInput;
        this.productForm = config.productForm;
        this.tabColumnsBtnMore = $(config.tabColumnsBtnMore);
        this.brandsInnerBtnMore = $(config.brandsInnerBtnMore);
        this.loyaltyBtnMore = $(config.loyaltyBtnMore);
        this.fieldRequiredInput = $(config.fieldRequiredInput);
        this.scrollToBtn = $(config.scrollToBtn);
        this.writeReviewBtn = $(config.writeReviewBtn);
        this.preorderBtn = $(config.preorderBtn);
        this.basketItemClose = $(config.basketItemClose);
        this.basketRadio = $(config.basketRadio);
        this.headerBasketScroll = $(config.headerBasketScroll);
        this.reviewMoreBtn = config.reviewMoreBtn;
        this.headerBasketItemClose = config.headerBasketItemClose;
        this.phoneMask = $(config.phoneMask);
        this.cabinetOrdersBtn = $(config.cabinetOrdersBtn);
        this.productsItemRemove = $(config.productsItemRemove);
        this.sliderPointerSetPrice = $(config.sliderPointerSetPrice);
        this.filterDropDown = $(config.filterDropDown);

        if (this.fancyboxLink.length) {
            this.fancyboxPopup();
        }

        if (this.fancyboxLinkOverflow.length) {
            this.fancyboxPopupOverflow();
        }

        if (this.fancyboxVacancy.length) {
            this.fancyboxVacancyPopup();
        }

        if (this.fullWidthSlider.length) {
            this.mainSlider();
        }

        if (this.suggestionMoreBtn.length) {
            this.suggestionMore();
        }

        if (this.productsMoreBtn.length) {
            this.productsMore();
        }

        if ($(this.vacanciesMoreBtn).length) {
            this.vacanciesAccordion();
        }

        if (this.reviewsMoreBtn.length) {
            this.reviewsMore();
        }

        if ($(this.mobileScrollSlider).length) {
            this.mobileScrollSliderInit();
        }

        if ($(this.scrollSlider).length) {
            this.scrollSliderInit();
        }

        if ($(this.tabWrapper).length) {
            this.tabsInit();
        }

        if ($(this.salesSlider).length) {
            this.salesSliderInit();
        }

        if ($(this.newsSlider).length) {
            this.newsSliderInit();
        }

        if ($(this.giftSlider).length) {
            this.giftSliderInit();
        }

        if (this.customSelect.length) {
            this.customSelectInit();
        }

        if (this.customSelectReset.length) {
            this.customSelectResetInit();
        }

        if (this.customSelectField.length) {
            this.customSelectFieldInit();
        }

        if (this.categoryBanner.length) {
            this.categoryBannerInit();
        }

        if (this.breadcrumbs.length) {
            this.breadcrumbsInit();
        }

        if (this.asideNavWrap.length) {
            this.asideNavAccordion();
            this.asideNavActiveInit();
        }

        if (this.productSlider.length) {
            this.productSliderInit();
        }

        if ($(this.counterInput).length) {
            this.counterInit();
        }

        if ($(this.productForm).length) {
            this.productFormSubmit();
            this.productElementsMove();
        }

        if (this.tabColumnsBtnMore.length) {
            this.tabColumnsBtnMoreInit();
        }

        if (this.brandsInnerBtnMore.length) {
            this.brandsInnerBtnMoreInit();
        }

        if (this.loyaltyBtnMore.length) {
            this.loyaltyBtnMoreInit();
        }

        if (this.scrollToBtn.length) {
            this.scrollToID();
        }

        if (this.writeReviewBtn.length) {
            this.writeReview();
        }

        if ($(this.reviewMoreBtn).length) {
            this.reviewMore();
        }

        if (this.preorderBtn.length) {
            this.preorderSetColor();
        }

        if ($(this.fieldRequiredInput).length) {
            this.fieldRequiredInit();
        }

        if (this.basketItemClose.length) {
            this.basketItemCloseInit();
        }

        if (this.headerBasketScroll.length) {
            this.headerBasketScrollInit();
        }

        if (this.phoneMask.length) {
            this.phoneMaskInit();
        }

        if (this.cabinetOrdersBtn.length) {
            this.cabinetOrdersAccordion();
        }

        if (this.productsItemRemove.length) {
            this.productsItemRemoveInit();
        }

        if (this.filterDropDown.length) {
            this.filterDropDownInit();
        }

        this.cleanReadOnlyAttributes();
        this.checkedInput();
        this.fileInput();
        this.setDescriptionsMaxHeight();
        this.footerAccordion();
        this.headerBasketItemCloseInit();
        this.resize();

        if (this.basketRadio.length) {
            this.basketRadioInit();
        }

        if (this.sliderPointerSetPrice.length) {
            this.sliderPointerSetPriceInit();
        }
    },

    fancyboxPopup: function () {
        this.fancyboxLink.fancybox({
            closeBtn: true,
            padding: [0, 0, 0, 0],
            prevEffect: 'none',
            nextEffect: 'none',
            maxWidth: 720,
            helpers: {
                overlay: {
                    css: {
                        'background': 'rgba(33,0,39,0.25)'
                    },
                },
                title: {type: 'inside'},
                thumbs: {
                    width: 50,
                    height: 50
                }
            },
            backFocus: false,
            mouseWheel: false
        });
    },

    fancyboxPopupOverflow: function () {
        this.fancyboxLinkOverflow.fancybox({
            closeBtn: true,
            padding: [0, 0, 0, 0],
            prevEffect: 'none',
            nextEffect: 'none',
            maxWidth: 750,
            wrapCSS: 'popup-overflow',
            helpers: {
                overlay: {
                    css: {
                        'background': 'rgba(33,0,39,0.25)'
                    },
                },
                title: {type: 'inside'},
                thumbs: {
                    width: 50,
                    height: 50
                }
            },
            backFocus: false,
            mouseWheel: false
        });
    },

    fancyboxVacancyPopup: function () {
        this.fancyboxVacancy.fancybox({
            closeBtn: true,
            padding: [0, 0, 0, 0],
            prevEffect: 'none',
            nextEffect: 'none',
            maxWidth: 720,
            helpers: {
                overlay: {
                    css: {
                        'background': 'rgba(33,0,39,0.25)'
                    },
                },
                title: {type: 'inside'},
                thumbs: {
                    width: 50,
                    height: 50
                }
            },
            backFocus: false,
            mouseWheel: false,
            afterLoad: function () {
                var $content = this.content,
                    position = this.element.data('vacancy');

                $content.find('.popup__subtitle').text(position);
                $content.find('.popup-vacancies__position').val(position);
            }
        });
    },

    footerAccordion: function () {
        var $btn = $('.footer-nav__title');

        $btn.on('click', function () {
            if ($(window).width() < 641) {
                $btn.not($(this)).removeClass('active').next().slideUp();
                $(this).toggleClass('active').next().stop().slideToggle();
            }
        })
    },

    footerAccordionClean: function () {
        if (window.innerWidth > 640) {
            $('.footer-nav__title').removeClass('active').next().css('display', '');
        }
    },

    setImageSrc: function ($imagesCollection) {
        $imagesCollection.each(function () {
            var $el = $(this);

            if (window.innerWidth > 640) {
                $el.attr('src', $el.data('desktop-src'));
            } else {
                $el.attr('src', $el.data('mobile-src'));
            }
        });
    },

    mainSlider: function () {
        var _this = this,
            $imagesCollection = $(this.fullWidthSlider).find('img');

        this.setImageSrc($imagesCollection);

        new Swiper(this.fullWidthSlider, {
            wrapperClass: 'full-width-slider__wrapper',
            slidesPerView: 1,
            loop: true,
            navigation: {
                nextEl: '.full-width-slider__next',
                prevEl: '.full-width-slider__prev',
            },
            pagination: {
                el: '.full-width-slider__pagination',
                type: 'bullets',
                modifierClass: 'full-width-slider__',
                bulletElement: 'div',
                clickable: true
            },
            on: {
                resize: function () {
                    _this.setImageSrc($imagesCollection);
                }
            }
        });
    },

    mobileScrollSliderInit: function () {
        var _this = this,

            swiperOptions = {
                wrapperClass: 'mobile-scroll-slider-wrapper',
                slidesPerView: 2,
                mousewheel: false,
                mousewheelControl: false,
                scrollbar: {
                    el: '.mobile-scroll-slider__scrollbar',
                    draggable: true
                }
            };


        this.mobileScrollSliderMedia(swiperOptions);

        $(window).on('resize', function () {
            _this.mobileScrollSliderMedia(swiperOptions);
        });
    },

    mobileScrollSliderMedia: function (swiperOptions) {
        var $mobileScrollSlider = $(this.mobileScrollSlider),
            $mobileScrollSliderWrapper = $('.mobile-scroll-slider-wrapper');

        if (window.innerWidth <= 640) {
            $mobileScrollSlider.addClass('swiper-container');
            $mobileScrollSlider.find('.mobile-scroll-slider-item').addClass('swiper-slide');
            $mobileScrollSliderWrapper.addClass('swiper-wrapper');
            this.mobileScrollSliderObj = new Swiper(this.mobileScrollSlider, swiperOptions);
        } else {
            if (this.mobileScrollSliderObj && this.mobileScrollSliderObj.destroy) this.mobileScrollSliderObj.destroy(false, true);
            this.mobileScrollSliderObj = undefined;
            $mobileScrollSlider.removeClass('swiper-container');
            $mobileScrollSlider.find('.mobile-scroll-slider-item').removeClass('swiper-slide');
            $mobileScrollSliderWrapper.removeClass('swiper-wrapper').attr('style', '');
        }
    },

    suggestionMore: function () {
        var _this = this;

        this.suggestionMoreBtn.on('click', function () {
            var $container = $(this).closest('.suggestions'),
                $inner = $container.find('.suggestions__inner'),
                $preloader = $container.find('.preloader');

            $preloader.addClass('preloader--active');

            $.ajax({
                url: 'test-ajax.html',
                dataType: 'html',

                success: function (response) {
                    $inner.append(response);
                    _this.setMaxHeights($('.suggestions__inner .products-item__description'));
                    $preloader.removeClass('preloader--active');

                    if (window.innerWidth <= 640) {
                        $inner.find('.mobile-scroll-slider-item').addClass('swiper-slide');
                        $inner.parent().get(0).swiper.update();
                    }
                },

                error: function (jqXHR, exception) {
                    var msg = '';

                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }

                    $preloader.removeClass('preloader--active');
                    alert(msg);
                }
            });
        });
    },

    productsMore: function () {
        var _this = this;

        this.productsMoreBtn.on('click', function () {
            var $container = $(this).closest('.products'),
                $inner = $container.find('.products__container'),
                $preloader = $container.find('.preloader');

            $preloader.addClass('preloader--active');

            $.ajax({
                url: 'test-ajax.html',
                dataType: 'html',

                success: function (response) {
                    $inner.append(response);
                    _this.setMaxHeights($('.products__container .products-item__description'));
                    $preloader.removeClass('preloader--active');
                },

                error: function (jqXHR, exception) {
                    var msg = '';

                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }

                    $preloader.removeClass('preloader--active');
                    alert(msg);
                }
            });
        });
    },

    reviewsMore: function () {
        var _this = this;

        this.reviewsMoreBtn.on('click', function () {
            var $container = $(this).closest('.reviews'),
                $inner = $container.find('.reviews__content'),
                $preloader = $container.find('.preloader');

            $preloader.addClass('preloader--active');

            $.ajax({
                url: 'test-ajax-reviews.html',
                dataType: 'html',

                success: function (response) {
                    $inner.append(response);
                    $preloader.removeClass('preloader--active');
                },

                error: function (jqXHR, exception) {
                    var msg = '';

                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }

                    $preloader.removeClass('preloader--active');
                    alert(msg);
                }
            });
        });
    },

    scrollSliderInit: function () {
        var _this = this;

        new Swiper(this.scrollSlider, {
            slidesPerView: 5,
            slidesOffsetBefore: -180,
            scrollbar: {
                el: '.scroll-slider__scrollbar',
                draggable: true
            },
            breakpoints: {
                1440: {
                    slidesPerView: 3,
                    slidesOffsetBefore: 0
                },
                640: {
                    slidesPerView: 2,
                    slidesOffsetBefore: 0
                }
            }
        });

        $(window).on('load resize', function () {
            $(_this.scrollSlider).each(function () {
                var $slideCollecitons = $(this).find('.swiper-slide'),
                    $sliderWripper = $(this).find('.swiper-wrapper'),
                    $duplicates = $sliderWripper.find('.swiper-slide__duplicated');

                if (window.innerWidth <= 1440) {
                    $duplicates.remove();
                } else if (window.innerWidth > 1440 && !$duplicates.length) {
                    $sliderWripper
                        .append($slideCollecitons.first().clone().addClass('swiper-slide__duplicated'))
                        .prepend($slideCollecitons.last().clone().addClass('swiper-slide__duplicated'));
                }

                this.swiper.update();
            });
        });
    },

    salesSliderInit: function () {
        var _this = this;

        new Swiper(this.salesSlider, {
            slidesPerView: 1,
            effect: 'fade',
            loop: true,
            on: {
                slideChangeTransitionEnd: function () {
                    var html = $(this.$el[0]).find('.swiper-slide-active .sales-slider__hide-info').html();

                    $(this.$el[0]).closest('.sales-slider').find('.sales-slider__info-content').html(html);
                }
            }
        });

        $('.sales-slider__prev').on('click', function () {
            $(this).closest('.sales-slider').find(_this.salesSlider).get(0).swiper.slidePrev();
        });

        $('.sales-slider__next').on('click', function () {
            $(this).closest('.sales-slider').find(_this.salesSlider).get(0).swiper.slideNext();
        });
    },

    newsSliderInit: function () {
        var _this = this,
            wrapper = '.news-inner__slider-wrapper',
            slider = new Swiper(this.newsSlider, {
                slidesPerView: 3,
                spaceBetween: 31,
                slidesPerGroup: 3,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    type: 'fraction'
                },
                breakpoints: {
                    1200: {
                        spaceBetween: 17
                    },
                    480: {
                        slidesPerGroup: 1,
                        slidesPerView: 1
                    }
                },
                on: {
                    slideChange: function () {
                        _this.sliderCounter(this, wrapper);
                    }
                }
            });

        $('.js_news-inner__slider-prev').on('click', function () {
            var swiperSlider = $(this).closest(wrapper).find(_this.newsSlider).get(0).swiper;

            swiperSlider.slidePrev();
        });

        $('.js_news-inner__slider-next').on('click', function () {
            var swiperSlider = $(this).closest(wrapper).find(_this.newsSlider).get(0).swiper;

            swiperSlider.slideNext();
        });


        //set counter after slider init
        setTimeout(function () {
            $(slider).each(function () {
                _this.sliderCounter(this, wrapper);
            });

            $('.js_news-inner__slider-counter').css('opacity', '1');
        }, 1000);

    },

    sliderCounter: function (swiperSlider, wrapper) {
        var $wrapperSlider = $(swiperSlider.$el[0]).closest(wrapper),
            $currentSlider = $wrapperSlider.find('.js_slider-counter-current'),
            $allSliders = $wrapperSlider.find('.js_slider-counter-all');

        $currentSlider.text(swiperSlider.activeIndex + swiperSlider.params.slidesPerGroup);
        $allSliders.text(swiperSlider.imagesLoaded);
    },

    giftSliderInit: function () {
        var _this = this,
            wrapper = '.e-gift__slider-wrapper',
            slider = new Swiper(this.giftSlider, {
                slidesPerView: 4,
                spaceBetween: 18,
                slidesPerGroup: 4,
                breakpoints: {
                    1200: {
                        spaceBetween: 12
                    },
                    480: {
                        spaceBetween: 7
                    }
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    type: 'fraction'
                },
                on: {
                    slideChange: function () {
                        _this.sliderCounter(this, wrapper);
                    }
                }
            });

        $('.js_e-gift__slider-prev').on('click', function () {
            var swiperSlider = $(this).closest(wrapper).find(_this.giftSlider).get(0).swiper;

            swiperSlider.slidePrev();
        });

        $('.js_e-gift__slider-next').on('click', function () {
            var swiperSlider = $(this).closest(wrapper).find(_this.giftSlider).get(0).swiper;

            swiperSlider.slideNext();
        });


        //set counter after slider init
        setTimeout(function () {
            $(slider).each(function () {
                _this.sliderCounter(this, wrapper);
            });

            $('.js_e-gift__slider-counter').css('opacity', '1');
        }, 1000);

    },

    tabsInit: function () {
        var _this = this, $slider;

        $(document).on('click', this.tabBtn, function () {
            $(this)
                .closest(_this.tabWrapper)
                .find(_this.tabBtn + ', ' + _this.tabInner)
                .removeClass('active');

            $(this)
                .addClass('active')
                .closest(_this.tabWrapper)
                .find('div[data-id="' + $(this).attr('data-id') + '"]')
                .addClass('active');

            $slider = $(this).closest(_this.tabWrapper).find('.panel.active ' + _this.scrollSlider);

            if ($slider.length) {
                $slider.get(0)
                    .swiper.update();

                _this.setMaxHeights($('.panel.active .products-item__description'));
            }
        });
    },

    checkedInput: function () {
        var reset = document.querySelectorAll('input[type="reset"]'),
            _this = this;

        this.inspectionInputs(document.querySelectorAll('input[type="checkbox"], input[type="radio"]'));

        document.addEventListener('change', function (e) {
            if (e.target.closest('.js_checkbox') && !e.target.hasAttribute('disabled')) {
                e.target.closest('.js_checkbox').classList.toggle('active');
            }

            if (e.target.closest('.js_radio')) {
                _this.inspectionInputs(document.querySelectorAll('input[type="radio"]'));
            }
        });

        document.addEventListener('click', function (e) {
            for (var i = 0; i < reset.length; i++) {
                if (e.target === reset[i]) {
                    setTimeout(function () {
                        _this.inspectionInputs(document.querySelectorAll('input[type="checkbox"], input[type="radio"]'));
                    }, 0);
                }
            }
        })
    },

    inspectionInputs: function (arr) {
        for (var i = 0; i < arr.length; i++) {
            if (arr[i].checked) {
                arr[i].parentElement.classList.add('active');
            } else {
                arr[i].parentElement.classList.remove('active');
            }

            if (arr[i].hasAttribute('disabled')) {
                arr[i].parentElement.classList.add('disabled');
            }
        }
    },

    customSelectInit: function () {
        this.customSelect.each(function () {
            $(this).select2({
                width: "100%",
                theme: 'classic',
                minimumResultsForSearch: Infinity,
                dropdownParent: $(this).closest('.custom-select-wrap')
            });
        });
    },

    customSelectResetInit: function () {
        this.customSelectReset.on('click', function () {
            setTimeout(function () {
                $(this).closest('form').find('.js_custom-select').trigger('change');
            }.bind(this), 50);

        });
    },

    customSelectFieldInit: function () {
        this.customSelectField.each(function () {
            var _this = this;

            $(this).select2({
                width: "100%",
                theme: 'classic',
                // placeholder: $(_this).attr('placeholder'),
                minimumResultsForSearch: Infinity,
                dropdownParent: $(this).closest('.form-field')
            });
        });
    },

    fileInput: function () {
        $(document).on('change', ".js_filename", function () {
            var filename = $(this).val().replace(/.*\\/, ""),
                $wrap = $(this).closest('.label-wrap-file');
            $wrap.find(".file-title").text(filename);
            $wrap.find(".file-btn").hide();
        });
    },

    categoryBannerInit: function () {
        var src = '';
        this.categoryBanner.each(function () {
            if (window.innerWidth > 480) {
                src = $(this).data('desktop-background');
            } else {
                src = $(this).data('mobile-background');
            }

            $(this).css('background-image', 'url(' + src + ')')
        });


    },

    breadcrumbsWidth: function () {
        var width = 0;

        $('.breadcrumbs__lnk').each(function () {
            width += $(this).outerWidth(true);
        });

        return width;
    },

    breadcrumbsInit: function () {
        setTimeout(function () {
            if ($(window).width() < this.breadcrumbsWidth() + 25) {
                this.breadcrumbs.addClass('breadcrumbs--overflow');
            } else {
                this.breadcrumbs.removeClass('breadcrumbs--overflow');
            }
        }.bind(this), 500);

    },

    asideNavActiveInit: function () {
        $('.aside-nav__lnk--active')
            .closest(this.asideNav)
            .find(this.asideNavTitle)
            .trigger('click');
    },

    productSliderInit: function () {
        var _this = this,
            $btnPrev = $('.js_product__slider-thumbs-prev'),
            $btnNext = $('.js_product__slider-thumbs-next'),
            productSliderThumbs, productSlider;

        productSliderThumbs = new Swiper(this.productSliderThumbs, {
            spaceBetween: 16,
            slidesPerView: 5,
            freeMode: true,
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
            breakpoints: {
                480: {
                    spaceBetween: 8
                }
            },
            on: {
                'slideChange': function () {
                    $btnPrev.removeClass('disabled');
                    $btnNext.removeClass('disabled');

                    if (this.isBeginning) {
                        $btnPrev.addClass('disabled');
                    } else if (this.isEnd) {
                        $btnNext.addClass('disabled');
                    }
                }
            }
        });

        new Swiper(this.productSlider, {
            spaceBetween: 10,
            thumbs: {
                swiper: productSliderThumbs
            }
        });

        $btnPrev.on('click', function () {
            var swiperSlider = $(this).closest('.product__slider-thumbs-wrap').find(_this.productSliderThumbs).get(0).swiper;

            swiperSlider.slidePrev();
        });

        $btnNext.on('click', function () {
            var swiperSlider = $(this).closest('.product__slider-thumbs-wrap').find(_this.productSliderThumbs).get(0).swiper;

            swiperSlider.slideNext();
            swiperSlider.update();
        });
    },

    asideNavAccordion: function () {
        var $btn = $(this.asideNavTitle);

        $btn.on('click.asideAccordion', function () {
            if (window.innerWidth < 1024) {
                $btn.not($(this)).removeClass('active').next().slideUp();
                $(this).toggleClass('active').next().stop().slideToggle();
            }
        });

        //reset
        $(window).resize(function () {
            if (window.innerWidth > 1023) {
                this.asideNavInner.attr('style', '');
                $(this.asideNavTitle).removeClass('active');
            }
        }.bind(this));
    },

    vacanciesAccordion: function () {
        var _this = this;

        $(document).on('click.vacanciesAccordion', this.vacanciesMoreBtn, function () {
            var $btn = $(_this.vacanciesMoreBtn),
                $item = $(this).closest('.vacancies__item');

            $btn.not($(this)).each(function () {
                var $item = $(this).closest('.vacancies__item');

                $item.removeClass('active');
                $item.find('.vacancies__item-info').slideUp();
                $item.find('.vacancies__item-send').slideUp();
            });

            $item.addClass('active');
            $item.find('.vacancies__item-info').slideToggle();
            $item.find('.vacancies__item-send').slideToggle({
                complete: function () {
                    var scrollTop = $item.offset().top - $('.header').height();

                    $('body, html').animate({scrollTop: scrollTop}, 500);
                }
            });
        });
    },

    counterInit: function () {
        var _this = this;

        this.counterButton.on('click', function () {
            var $counter = $(this).closest('.counter').find(_this.counterInput),
                maxValue = $counter.data('max-count');

            if ($(this).hasClass('counter__button--up')) {
                $counter.val(parseInt($counter.val()) + 1);

                if ($counter.val() > maxValue) {
                    $counter.val(maxValue);
                }
            } else {
                $counter.val(parseInt($counter.val()) - 1);

                if (parseInt($counter.val()) < 1) {
                    $counter.val(1);
                }
            }
        });

        $(this.counterInput).on('keyup', function (event) {
            var value = parseInt($(this).val()),
                maxValue = $(this).data('max-count');

            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }

            $(this).val($(this).val().replace(/\D/g, ''));

            if (value <= 0) {
                $(this).val('1');
            } else if (value > maxValue) {
                $(this).val(maxValue);
            }
        });
    },

    productFormSubmit: function () {
        $(this.productForm).submit(function (e) {
            e.preventDefault();

            //AJAX for product form
        })
    },

    productElementsMove: function () {
        var $title = $('.product__title'),
            $productContainer = $('.product__container'),
            $colors = $('.product__colors'),
            $banner = $('.product__banner'),
            $productFooter = $('.product__footer'),
            $productSliders = $('.product__sliders'),
            $productViewInner = $('.product__view-inner'),
            $productViewInnerWrap = $('.product__view-inner-wrap'),
            $productBuy = $('.product__buy');

        if (window.innerWidth < 1024) {
            $productContainer.prepend($title);
        } else {
            $(this.productForm).prepend($title);
        }

        if (window.innerWidth <= 640) {
            $productBuy.before($productViewInner);
        } else {
            $productViewInnerWrap.append($productViewInner);
        }

        if (window.innerWidth <= 480) {
            $productFooter.prepend($banner);
            $productSliders.append($colors);

        } else {
            $productSliders.append($banner);
            $productFooter.prepend($colors);
        }
    },

    tabColumnsBtnMoreInit: function () {
        this.tabColumnsBtnMore.on('click', function () {
            $(this).toggleClass('active');
            $(this).prev('.panel__two-columns').toggleClass('active');
        });
    },

    brandsInnerBtnMoreInit: function () {
        this.brandsInnerBtnMore.on('click', function () {
            $(this).toggleClass('active');
            $(this).prev('.brands-inner-info__description').toggleClass('active');
        });
    },

    loyaltyBtnMoreInit: function () {
        this.loyaltyBtnMore.on('click', function () {
            $(this).toggleClass('active');
            $(this).prev('.two-columns-grid--sales-bottom').toggleClass('active');
        });
    },

    scrollToID: function () {
        this.scrollToBtn.on('click', function () {
            var ID = $(this).data('scroll-to-id'),
                scrollTop = $('#' + ID).offset().top - $('.header').height();

            $('body, html').animate({scrollTop: scrollTop}, 500);
        });
    },

    writeReview: function () {
        this.writeReviewBtn.on('click', function () {
            $(this).toggleClass('active');
            $('.reviews__form').stop().slideToggle();
        });
    },

    reviewMore: function () {
        $(document).on('click', this.reviewMoreBtn, function () {
            $(this).toggleClass('active');
            $(this).prev('.reviews__item-main-content').toggleClass('active');
        });
    },

    preorderSetColor: function () {
        this.preorderBtn.on('click', function () {
            var $target = $('.popup-preorder__product-color'),
                $color = $('.product__colors-item.js_product__colors-item.active').clone();

            $color.removeClass('js_product__colors-item active')

            $target.html($color).append($color.data('product-color-name'));
        });
    },

    fieldRequiredInit: function () {
        this.fieldRequiredInput.each(function () {
            if ($(this).val() !== '') {
                $(this).next().hide();
            }
        });

        this.fieldRequiredInput.focus(function () {
            $(this).next().hide();
        });

        this.fieldRequiredInput.blur(function () {
            if ($(this).val().trim() === '') {
                $(this).next().show();
            }
        });
    },

    cleanReadOnlyAttributes: function () {
        //input autocomplete hook
        setTimeout(function () {
            $('.form-field__input, .form-field__required-input').removeAttr("readOnly");
        }, 1000);
    },

    basketItemCloseInit: function () {
        this.basketItemClose.on('click', function () {
            //AJAX for basket page

            $(this).closest('.basket__item').remove();
        });
    },

    basketRadioInit: function () {
        this.basketRadioCheck();

        this.basketRadio.on('change', this.basketRadioCheck.bind(this));
    },

    basketRadioCheck: function () {
        this.basketRadio.each(function () {
            var $wrapper = $(this).closest('.basket-radio-wrap'),
                $additional = $wrapper.find('.basket-shipment__item-additional');

            if ($(this).prop('checked')) {
                $wrapper.addClass('active');
                $additional.stop().slideDown(200);
            } else {
                $wrapper.removeClass('active');
                $additional.stop().slideUp(200);
            }
        });
    },

    headerBasketItemCloseInit: function () {
        $(document).on('click', this.headerBasketItemClose, function () {
            $(this).closest('.header-basket__item').remove();
        });
    },

    headerBasketScrollInit: function () {
        this.headerBasketScroll.jScrollPane({
            autoReinitialise: true
        });
    },

    phoneMaskInit: function () {
        this.phoneMask.inputmask("+37599-999-99-99");
    },

    cabinetOrdersAccordion: function () {
        var _this = this;

        this.cabinetOrdersBtn.on('click', function () {
            _this.cabinetOrdersBtn.not($(this)).removeClass('active').next().slideUp();
            $(this).toggleClass('active').next().stop().slideToggle();
        });

        this.cabinetOrdersBtn.first().trigger('click');
    },

    productsItemRemoveInit: function () {
        this.productsItemRemove.on('click', function (e) {
            e.preventDefault();

            $(this).closest('.products-item').remove();
        });
    },

    filterDropDownInit: function () {
      this.filterDropDown.jScrollPane({
           autoReinitialise: true
      });
    },

    sliderPointerSetPriceInit: function () {
        this.sliderPointerSetPriceAnimation();

        this.sliderPointerSetPrice.on('change', this.sliderPointerSetPriceAnimation.bind(this));
    },

    sliderPointerSetPriceAnimation: function (e) {
        var $labelActive, price;

        if (e) {
            $labelActive = $(e.target).closest('label');
        } else {
            $labelActive = this.sliderPointerSetPrice.closest('label.active');
        }

        price = parseInt($labelActive.find('span').text());

        this.animateNumber($('.js_slider-pointer-price'), price);
    },

    animateNumber: function (element, value) {
        element.each(function () {
            $(this).prop("number", $(this).text()).animate({
                number: value
            }, {
                duration: 600,
                step: function (value) {
                    $(this).text(parseInt(value));
                }
            })
        });
    },

    setDescriptionsMaxHeight: function () {
        var _this = this;

        $('.suggestions__inner').each(function () {
            _this.setMaxHeights($(this).find('.products-item__description'));
        });

        $('.scroll-slider').each(function () {
            _this.setMaxHeights($(this).find('.products-item__description'));
        });

        $('.products').each(function () {
            _this.setMaxHeights($(this).find('.products-item__description'));
        });
    },

    setMaxHeights: function (els) {
        var maxHeight = els.map(function (i, e) {
            return $(e).css('height', 'auto').height();
        }).get();

        return els.height(Math.max.apply(els, maxHeight));
    },

    resize: function () {
        $(window).resize(function () {
            this.setDescriptionsMaxHeight();
            this.footerAccordionClean();

            if (this.categoryBanner.length) {
                this.categoryBannerInit();
            }

            if (this.breadcrumbs.length) {
                this.breadcrumbsInit();
            }

            if ($(this.productForm).length) {
                this.productElementsMove();
            }
        }.bind(this));
    }
};

var Filter = {
    init: function (config) {
        this.filterAccordion = config.filterAccordion;
        this.filterWatchingFields = config.filterWatchingFields;
        this.filterActiveField = $(config.filterActiveField);
        this.filterActiveItem = config.filterActiveItem;
        this.filterActiveWrapper = $(config.filterActiveWrapper);
        this.closeBtn = config.closeBtn;

        if ($(this.filterAccordion).length) {
            this.filterAccordionInit();
        }

        if ($(this.filterWatchingFields).length) {
            this.filterWatchingFieldsInit();
            this.filterEvents();
        }
    },

    filterAccordionInit: function () {
        var _this = this;

        $(document).on('click', this.filterAccordion, function () {
            $(_this.filterAccordion).not($(this))
                .closest('.category-filter__item')
                .removeClass('category-filter__item--active');
            $(this)
                .closest('.category-filter__item')
                .toggleClass('category-filter__item--active');
        });

        //reset accordion
        $(document).on('click', function (e) {
            if (
                !$(e.target).closest('.category-filter__item').length
                && $('.category-filter__item').hasClass('category-filter__item--active')
            ) {
                e.preventDefault();
                $('.category-filter__item').removeClass('category-filter__item--active')
            }
        });

        this.closeBtnInit();
    },

    filterWatchingFieldsInit: function () {
        var _this = this;

        $(document).on('change', this.filterWatchingFields, function () {
            $(_this.filterWatchingFields).each(function () {
                if (this.checked) {
                    _this.filterAddActiveFields(this);
                } else {
                    _this.filterRemoveActiveFields(this);
                }
            });

            _this.filterCheckActiveFieldsLength();
        });
    },

    filterRemoveActiveFields: function (el) {
        this.filterActiveField.find(this.filterActiveItem + "[data-name=" + el.name + "]").remove();
    },

    filterAddActiveFields: function (el) {
        if (this.filterItemIsActive(el)) return;

        var $activeItem = $('<div />', {
                class: 'category-filter-active__item',
                text: el.parentElement.innerText,
                'data-name': el.name
            }),
            $closeBtn = $('<div />', {
                class: 'category-filter-active__item-close js_category-filter-active__item-close'
            });

        this.filterActiveField.append($activeItem.append($closeBtn));
    },

    filterCheckActiveFieldsLength: function () {
        if (this.filterActiveField.find(this.filterActiveItem).length) {
            this.filterActiveWrapper.show();
        } else {
            this.filterActiveWrapper.hide();
        }
    },

    filterItemIsActive: function (el) {
        return !!this.filterActiveField.find(this.filterActiveItem + "[data-name=" + el.name + "]").length;
    },

    closeBtnInit: function () {
        var _this = this;

        $(document).on('click', this.closeBtn, function () {
            var $el = $(this).closest(_this.filterActiveItem),
                name = $el.data('name');

            $(_this.filterWatchingFields).each(function () {
                if ($(this).attr('name') === name) {
                    $(this).closest('label').trigger('click')
                }
            });
        });
    },

    filterEvents: function () {
        //добавь инпуты для отслеживания фильтра
        $(document).on('change', '.js_checkbox, .js_custom-filter-select', this.sendAJAX);
    },

    //аякс для фильтра
    //добавь логику обновления пагинации
    sendAJAX: function () {
        var $container = $('.products'),
            $inner = $container.find('.products__container'),
            $preloader = $container.find('.preloader');

        $preloader.addClass('preloader--active');


        $.ajax({
            url: 'test-ajax.html',
            dataType: 'html',

            success: function (response) {
                $inner.html(response);
                MainJs.setMaxHeights($('.products__container .products-item__description'));
                $preloader.removeClass('preloader--active');
            },

            error: function (jqXHR, exception) {
                var msg = '';

                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }

                $preloader.removeClass('preloader--active');
                alert(msg);
            }
        });
    }
};

var ProductColor = {
    init: function (config) {
        this.productColorTitle = config.productColorTitle;
        this.productColorItem = config.productColorItem;
        this.productColorToggleItemsBtn = config.productColorToggleItemsBtn;
        this.productColorContent = config.productColorContent;

        if ($(this.productColorItem).length) {
            this.productColorInit();
        }
    },

    productColorInit: function () {
        var $btn = $(this.productColorToggleItemsBtn),
            $content = $(this.productColorContent);

        $content.on('click', this.productColorItem, this.productColorSetActive.bind(this));
        $(this.productColorItem).first().trigger('click');

        if ($content.height() > 60) {
            $btn.css('visibility', 'visible');
            $content.addClass('product__colors-content--spoiler');
        }

        $content.css('visibility', 'visible');

        $btn.on('click', function () {
            $(this).toggleClass('active');
            $content.toggleClass('product__colors-content--spoiler');
        });
    },

    productColorSetActive: function (e) {
        var _this = this;

        $(this.productColorItem).each(function () {
            $(_this.productColorItem).removeClass('active');
            $(e.currentTarget).addClass('active');
        });

        $(this.productColorTitle).text($(e.currentTarget).data('product-color-name'));
    }
};

var DiscountCard = {
    init: function (config) {
        this.discountCardWrap = config.discountCardWrap;
        this.discountCardHeader = config.discountCardHeader;
        this.discountCardContent = config.discountCardContent;
        this.btnStepOne = config.btnStepOne;
        this.btnStepTwo = config.btnStepTwo;
        this.btnStepThree = config.btnStepThree;
        this.btnStepFour = config.btnStepFour;

        if ($(this.discountCardWrap).length) {
            this.discountCardAJAX();
        }
    },

    discountCardAJAX: function () {
        var _this = this;

        $(document).on('click', this.btnStepOne, function () {
            var $preloader = $(_this.discountCardWrap).find('.preloader');

            $preloader.addClass('preloader--active');

            $.ajax({
                url: 'discount-card-ajax-step-two.html',
                dataType: 'html',

                success: function (response) {
                    $(_this.discountCardWrap).html(response);
                    $('.form-field__input').inputmask("***-***");
                    $preloader.removeClass('preloader--active');
                },

                error: _this.ajaxError
            });
        });

        $(document).on('click', this.btnStepTwo, function () {
            var $preloader = $(_this.discountCardWrap).find('.preloader');

            $preloader.addClass('preloader--active');

            $.ajax({
                url: 'discount-card-ajax-step-three.html',
                dataType: 'html',

                success: function (response) {
                    $(_this.discountCardWrap).html(response);
                    $('.form-field__input').inputmask("******");
                    $preloader.removeClass('preloader--active');
                },

                error: _this.ajaxError
            });
        });

        $(document).on('click', this.btnStepThree, function () {
            var $preloader = $(_this.discountCardWrap).find('.preloader');

            $preloader.addClass('preloader--active');

            $.ajax({
                url: 'discount-card-ajax-step-four.html',
                dataType: 'html',

                success: function (response) {
                    $(_this.discountCardWrap).html(response);
                    $preloader.removeClass('preloader--active');
                },

                error: _this.ajaxError
            });
        });

        $(document).on('click', this.btnStepFour, function () {
            var $preloader = $(_this.discountCardWrap).find('.preloader');

            $preloader.addClass('preloader--active');

            $.ajax({
                url: 'discount-card-ajax-step-one.html',
                dataType: 'html',

                success: function (response) {
                    $(_this.discountCardWrap).html(response);
                    $preloader.removeClass('preloader--active');
                },

                error: _this.ajaxError
            });
        });
    },

    ajaxError: function (jqXHR, exception) {
        var msg = '';

        if (jqXHR.status === 0) {
            msg = 'Not connect.\n Verify Network.';
        } else if (jqXHR.status == 404) {
            msg = 'Requested page not found. [404]';
        } else if (jqXHR.status == 500) {
            msg = 'Internal Server Error [500].';
        } else if (exception === 'parsererror') {
            msg = 'Requested JSON parse failed.';
        } else if (exception === 'timeout') {
            msg = 'Time out error.';
        } else if (exception === 'abort') {
            msg = 'Ajax request aborted.';
        } else {
            msg = 'Uncaught Error.\n' + jqXHR.responseText;
        }

        $preloader.removeClass('preloader--active');
        alert(msg);
    }
};

var LoyaltySale = {
    init: function (config) {
        this.checkSaleBtn = $(config.checkSaleBtn);
        this.btnStepOne = config.btnStepOne;
        this.btnStepTwo = config.btnStepTwo;
        this.wrapper = config.wrapper;
        this.formWrap = config.formWrap;

        if (this.checkSaleBtn.length) {
            this.loyaltySaleAJAX();
        }
    },

    loyaltySaleAJAX: function () {
        var _this = this;

        $(document).on('click', this.btnStepOne, function (e) {
            var $preloader = $(_this.wrapper).find('.preloader'),
                $form = $(e.target).closest('form'),
                $formWrap = $form.closest(_this.formWrap);

            e.preventDefault();

            $preloader.addClass('preloader--active');

            $.ajax({
                url: 'loyalty-sale-ajax-step-one.html',
                dataType: 'html',
                data: $form.serialize(),

                success: function (response) {
                    $formWrap.html(response);
                    $preloader.removeClass('preloader--active');
                },

                error: _this.ajaxError
            });
        });

        $(document).on('click', this.btnStepTwo, function (e) {
            var $preloader = $(_this.wrapper).find('.preloader'),
                $form = $(e.target).closest('form'),
                $formWrap = $form.closest(_this.formWrap);

            e.preventDefault();

            $preloader.addClass('preloader--active');

            $.ajax({
                url: 'loyalty-sale-ajax-step-two.html',
                dataType: 'html',
                data: $form.serialize(),

                success: function (response) {
                    $formWrap.closest('.popup').find('.popup__title').text('ВАША СКИДКА');
                    $formWrap.html(response);
                    $preloader.removeClass('preloader--active');
                },

                error: _this.ajaxError
            });
        });
    },

    ajaxError: function (jqXHR, exception) {
        var msg = '';

        if (jqXHR.status === 0) {
            msg = 'Not connect.\n Verify Network.';
        } else if (jqXHR.status == 404) {
            msg = 'Requested page not found. [404]';
        } else if (jqXHR.status == 500) {
            msg = 'Internal Server Error [500].';
        } else if (exception === 'parsererror') {
            msg = 'Requested JSON parse failed.';
        } else if (exception === 'timeout') {
            msg = 'Time out error.';
        } else if (exception === 'abort') {
            msg = 'Ajax request aborted.';
        } else {
            msg = 'Uncaught Error.\n' + jqXHR.responseText;
        }

        $preloader.removeClass('preloader--active');
        alert(msg);
    }
};