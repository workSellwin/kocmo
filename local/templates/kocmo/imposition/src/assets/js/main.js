;(function ($) {
    $(function () {
        MainJs.init({
            fancyboxLink: 'a.fancybox',
            fullWidthSlider: '.js_full-width-slider',
            suggestionMoreBtn: '.js_suggestions__btn',
            productsMoreBtn: '.js_products__btn',
            mobileScrollSlider: '.js_mobile-scroll-slider',
            scrollSlider: '.js_scroll-slider',
            salesSlider: '.js_sales-slider__swiper-container',
            tabWrapper: '.js_tabs-wrap',
            tabBtn: '.js_tab',
            tabInner: '.js_panel',
            customSelect: '.js_custom-select',
            categoryBanner: '.js_category-banner',
            breadcrumbs: '.js_breadcrumbs'
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
        this.headerInner = $(config.headerInner);
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
            headerInnerHeight = this.headerInner.height(),
            scrollShift = window.innerWidth > 1023 ? 45 : 0, //header__top height
            isMobile = window.innerWidth <= 1023,
            _this = this;

        if (!isMobile) {
            this.header.css('height', headerInnerHeight + 'px');
        }


        $(window).off('scroll.header');
        $(window).on('scroll.header', function () {
            if (($(this).scrollTop() >= topPosition + scrollShift) && window.innerWidth > 1023) {
                _this.headerInner.addClass('header-inner--fixed');
            } else {
                _this.headerInner.removeClass('header-inner--fixed');
            }
        });
    },

    resetHeaderHeight: function () {
        this.header.css('height', '');
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
        this.fullWidthSlider = config.fullWidthSlider;
        this.suggestionMoreBtn = $(config.suggestionMoreBtn);
        this.productsMoreBtn = $(config.productsMoreBtn);
        this.mobileScrollSlider = config.mobileScrollSlider;
        this.salesSlider = config.salesSlider;
        this.mobileScrollSliderObj = null;
        this.scrollSlider = config.scrollSlider;
        this.scrollSliderObj = null;
        this.tabWrapper = config.tabWrapper;
        this.tabBtn = config.tabBtn;
        this.tabInner = config.tabInner;
        this.customSelect = $(config.customSelect);
        this.categoryBanner = $(config.categoryBanner);
        this.breadcrumbs = $(config.breadcrumbs);

        if (this.fancyboxLink.length) {
            this.fancyboxPopup();
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

        if (this.customSelect.length) {
            this.customSelectInit();
        }

        if (this.categoryBanner.length) {
            this.categoryBannerInit();
        }

        if (this.breadcrumbs.length) {
            this.breadcrumbsInit();
        }

        this.checkedInput();
        this.setDescriptionsMaxHeight();
        this.footerAccordion();
        this.resize();
    },

    fancyboxPopup: function () {
        this.fancyboxLink.fancybox({
            closeBtn: true,
            padding: [20, 20, 18, 20],
            helpers: {
                overlay: {
                    css: {
                        'background': 'rgba(51,51,51,0.8)'
                    },

                },
                title: {type: 'inside'}
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

    scrollSliderInit: function () {
        var _this = this;

        this.scrollSliderObj = new Swiper(this.scrollSlider, {
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

    tabsInit: function () {
        var _this = this;

        $(this.tabBtn).on('click', function () {
            $(this)
                .closest(_this.tabWrapper)
                .find(_this.tabBtn + ', ' + _this.tabInner)
                .removeClass('active');

            $(this)
                .addClass('active')
                .closest(_this.tabWrapper)
                .find('div[data-id="' + $(this).attr('data-id') + '"]')
                .addClass('active');

            if (_this.scrollSliderObj) {
                $(this)
                    .closest(_this.tabWrapper)
                    .find('.panel.active ' + _this.scrollSlider).get(0)
                    .swiper.update();

                _this.setMaxHeights($('.panel.active .products-item__description'));
            }
        });
    },

    checkedInput: function () {
        var reset = document.querySelectorAll('input[type="reset"]');

        this.inspectionInputs(document.querySelectorAll('input[type="checkbox"], input[type="radio"]'));

        document.addEventListener('change', function (e) {
            if (e.target.closest('.js_checkbox') && !e.target.hasAttribute('disabled')) {
                e.target.closest('.js_checkbox').classList.toggle('active');
            }

            if (e.target.closest('.js_radio')) {
                this.inspectionInputs(document.querySelectorAll('input[type="radio"]'));
            }
        });

        document.addEventListener('click', function (e) {
            var _this = this;

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
        this.customSelect.select2({
            width: "100%",
            theme: 'classic',
            allowClear: true,
            minimumResultsForSearch: Infinity
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

    breadcrumbsInit: function () {
        if ($(window).width() <= this.breadcrumbs.width() + 20) {
            this.breadcrumbs.addClass('breadcrumbs--overflow');
        } else {
            this.breadcrumbs.removeClass('breadcrumbs--overflow');
        }
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
        }.bind(this));
    }
};