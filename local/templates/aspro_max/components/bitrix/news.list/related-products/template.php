<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<? if ($arResult['ITEMS']): ?>
    <div class="bigdata_recommended_products_items related_products_items">
        <div class="font_md darken subtitle option-font-bold">Сопутствующие товары</div>
        <div class="block-items flexbox flexbox--row flex-wra owl-carousel owl-theme owl-bg-nav short-nav hidden-dots swipeignore"
             data-plugin-options='{"nav": true, "autoplay" : false, "autoplayTimeout" : "3000", "margin": -1, "smartSpeed":1000, <?= (count($arResult["ITEMS"]) > 4 ? "\"loop\": true," : "") ?> "responsiveClass": true, "responsive":{"0":{"items": 1},"600":{"items": 2},"768":{"items": 3},"992":{"items": 4}}}'>
            <? foreach ($arResult['ITEMS'] as $atItem): ?>
                <div class="block-item bordered rounded3">
                    <div class="block-item__wrapper w-btn colored_theme_hover_bg-block">
                        <div class="block-item__inner flexbox flexbox--row">

                            <div class="block-item__image block-item__image--wh80">
                                <a href="<?= $atItem["DETAIL_PAGE_URL"]; ?>"
                                   class="thumb shine">
                                    <? $srcImg = ($atItem["PREVIEW_PICTURE"]["SRC"]) ? $atItem["PREVIEW_PICTURE"]["SRC"] : "/local/templates/aspro_max/images/svg/noimage_product.svg"; ?>
                                    <img class="img-responsive ls-is-cached lazyloaded"
                                         src="<?= $srcImg; ?>"
                                         data-src="<?= $srcImg; ?>"
                                         alt="<?= $atItem["NAME"]; ?>"
                                         title="<?= $atItem["NAME"]; ?>">
                                </a>
                            </div>
                            <div class="block-item__info item_info">
                                <div class="block-item__title">
                                    <a href="<?= $atItem["DETAIL_PAGE_URL"]; ?>"
                                       class="dark-color font_xs"><span><?= $atItem["NAME"]; ?></span></a>
                                </div>
                                <div class="block-item__cost cost prices clearfix">
                                </div>
                                <div class="more-btn"><a
                                            class="btn btn-transparent-border-color btn-xs colored_theme_hover_bg-el has-ripple"
                                            rel="nofollow"
                                            href="<?= $atItem["DETAIL_PAGE_URL"]; ?>"
                                            data-item="64361">Подробнее</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            <? endforeach; ?>
        </div>
    </div>
<? endif; ?>
