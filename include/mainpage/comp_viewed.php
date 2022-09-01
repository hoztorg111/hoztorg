<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme, $arRegion;
$IsViewedTypeLocal = $arTheme['VIEWED_TYPE']['VALUE'] === 'LOCAL';
if($arRegion)
{
    if($arRegion['LIST_PRICES'])
    {
        if(reset($arRegion['LIST_PRICES']) != 'component')
            $arParams['PRICE_CODE'] = array_keys($arRegion['LIST_PRICES']);
    }
    if($arRegion['LIST_STORES'])
    {
        if(reset($arRegion['LIST_STORES']) != 'component')
            $arParams['STORES'] = $arRegion['LIST_STORES'];
    }
}

$arViewedIDs=CMax::getViewedProducts((int)CSaleBasket::GetBasketUserID(false), SITE_ID);?>
<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("viewed-block");?>
<?if($arViewedIDs){?>
    <div class="content_wrapper_block map_type_2 front_map2">
        <div class="maxwidth-theme">
            <div class="wrapper_block with_title title_left">
                <div class="top_block">
                    <h3>Вы смотрели</h3>
                </div>
            </div>
            <div class="ajax_load cur block">
                <?$GLOBALS['arrFilterViewed'] = array('ID' => $arViewedIDs);?>
                <?$APPLICATION->IncludeComponent(
                    "bitrix:catalog.section",
                    "catalog_block",
                    array(
                        "IBLOCK_TYPE" => "aspro_max_catalog",
                        "IBLOCK_ID" => "26",
                        "HIDE_NOT_AVAILABLE" => "N",
                        "BASKET_URL" => "/basket/",
                        "ACTION_VARIABLE" => "action",
                        "PRODUCT_ID_VARIABLE" => "id",
                        "SECTION_ID_VARIABLE" => "SECTION_ID",
                        "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                        "PRODUCT_PROPS_VARIABLE" => "prop",
                        "SEF_MODE" => "Y",
                        "AJAX_MODE" => "N",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_STYLE" => "Y",
                        "AJAX_OPTION_HISTORY" => "Y",
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "3600000",
                        "CACHE_FILTER" => "Y",
                        "CACHE_GROUPS" => "Y",
                        "SET_TITLE" => "Y",
                        "SET_STATUS_404" => "Y",
                        "FILTER_NAME" => "arrFilterViewed",
                        "PRICE_CODE" => array(
                            0 => "BASE",
                            1 => "Оптовая сайт",
                            2 => "Приходная сайт",
                            3 => "Розничная сайт",
                        ),
                        "USE_PRICE_COUNT" => "N",
                        "SHOW_PRICE_COUNT" => "1",
                        "PRICE_VAT_INCLUDE" => "Y",
                        "PRODUCT_PROPERTIES" => "",
                        "USE_PRODUCT_QUANTITY" => "Y",
                        "CONVERT_CURRENCY" => "Y",
                        "CURRENCY_ID" => "RUB",
                        "OFFERS_CART_PROPERTIES" => "",
                        "PAGE_ELEMENT_COUNT" => "20",
                        "LINE_ELEMENT_COUNT" => "4",
                        "ELEMENT_SORT_FIELD" => "SHOWS",
                        "ELEMENT_SORT_ORDER" => "asc",
                        "ELEMENT_SORT_FIELD2" => "sort",
                        "ELEMENT_SORT_ORDER2" => "asc",
                        "INCLUDE_SUBSECTIONS" => "Y",
                        "OFFERS_SORT_FIELD" => "sort",
                        "OFFERS_SORT_ORDER" => "asc",
                        "OFFERS_SORT_FIELD2" => "sort",
                        "OFFERS_SORT_ORDER2" => "asc",
                        "PAGER_TEMPLATE" => "main",
                        "DISPLAY_TOP_PAGER" => "N",
                        "DISPLAY_BOTTOM_PAGER" => "Y",
                        "PAGER_TITLE" => "Товары",
                        "PAGER_SHOW_ALWAYS" => "N",
                        "PAGER_DESC_NUMBERING" => "N",
                        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                        "PAGER_SHOW_ALL" => "N",
                        "AJAX_OPTION_ADDITIONAL" => "",
                        "ADD_SECTIONS_CHAIN" => "Y",
                        "ADD_PROPERTIES_TO_BASKET" => "N",
                        "PARTIAL_PRODUCT_PROPERTIES" => "N",
                        "OFFER_TREE_PROPS" => array(
                            0 => "COLOR_REF",
                            1 => "SIZES",
                            2 => "VOLUME",
                            3 => "FRTYPE",
                            4 => "WEIGHT",
                            5 => "SIZES2",
                            6 => "SIZES3",
                            7 => "SIZES4",
                            8 => "SIZES5",
                        ),
                        "SHOW_DISCOUNT_PERCENT" => "Y",
                        "SHOW_OLD_PRICE" => "Y",
                        "ADD_PICT_PROP" => "MORE_PHOTO",
                        "OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
                        "USE_MAIN_ELEMENT_SECTION" => "Y",
                        "SET_LAST_MODIFIED" => "Y",
                        "PAGER_BASE_LINK_ENABLE" => "N",
                        "SHOW_404" => "Y",
                        "MESSAGE_404" => "",
                        "DISABLE_INIT_JS_IN_COMPONENT" => "N",
                        "HIDE_NOT_AVAILABLE_OFFERS" => "N",
                        "COMPATIBLE_MODE" => "Y",
                        "TEMPLATE_THEME" => "blue",
                        "LABEL_PROP" => "",
                        "PRODUCT_DISPLAY_MODE" => "Y",
                        "PRODUCT_SUBSCRIPTION" => "Y",
                        "SHOW_MAX_QUANTITY" => "N",
                        "MESS_BTN_BUY" => "Купить",
                        "MESS_BTN_ADD_TO_BASKET" => "В корзину",
                        "MESS_BTN_COMPARE" => "Сравнение",
                        "MESS_BTN_DETAIL" => "Подробнее",
                        "MESS_NOT_AVAILABLE" => "Нет в наличии",
                        "MESS_BTN_SUBSCRIBE" => "Подписаться",
                        "LAZY_LOAD" => "N",
                        "LOAD_ON_SCROLL" => "N",
                        "USE_ENHANCED_ECOMMERCE" => "N",
                        "TYPE_VIEW_BASKET_BTN" => "TYPE_1",
                    ),
                    false
                );?>
            </div>
        </div>
    </div>
<?}?>
<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("viewed-block", "");?>