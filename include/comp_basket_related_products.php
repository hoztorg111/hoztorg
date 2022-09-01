<? global $arRegion;
if ($arRegion) {
    if ($arRegion['LIST_PRICES']) {
        if (reset($arRegion['LIST_PRICES']) != 'component')
            $arParams['PRICE_CODE'] = array_keys($arRegion['LIST_PRICES']);
    }
    if ($arRegion['LIST_STORES']) {
        if (reset($arRegion['LIST_STORES']) != 'component')
            $arParams['STORES'] = $arRegion['LIST_STORES'];
    }
}

$arBasketItems = [];
$dbBasketItems = CSaleBasket::GetList(
    [],
    [
        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
        "LID" => SITE_ID,
        "ORDER_ID" => "NULL",
    ],
    false,
    false,
    ["ID", "PRODUCT_ID"]
);
while ($arItems = $dbBasketItems->Fetch()) {
    $arBasketItems[] = $arItems["PRODUCT_ID"];
}

$res = CIBlockElement::GetList([], ["IBLOCK_ID" => 26, "ID" => $arBasketItems,], ["ID", "PROPERTY_RELATED_PRODUCTS"]);
while ($ar_fields = $res->GetNext()) {
    $relatedProducts = $ar_fields["PROPERTY_RELATED_PRODUCTS_VALUE"];
    if ($relatedProducts) {
        $arRelatedProducts[$relatedProducts] = $relatedProducts;
    }
}
?>
<? if ($arRelatedProducts): ?>
    <div class="basket-wrapper-bd">
        <? $GLOBALS['arrFilterRelatedProducts'] = ['ID' => $arRelatedProducts]; ?>
        <? $APPLICATION->IncludeComponent("bitrix:news.list", "related-products", [
                "DISPLAY_DATE" => "Y",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "Y",
                "AJAX_MODE" => "Y",
                "IBLOCK_TYPE" => "aspro_max_catalog",
                "IBLOCK_ID" => "26",
                "NEWS_COUNT" => "20",
                "SORT_BY1" => "ACTIVE_FROM",
                "SORT_ORDER1" => "DESC",
                "SORT_BY2" => "SORT",
                "SORT_ORDER2" => "ASC",
                "FILTER_NAME" => "arrFilterRelatedProducts",
                "FIELD_CODE" => ["ID"],
                "PROPERTY_CODE" => ["DESCRIPTION"],
                "CHECK_DATES" => "Y",
                "DETAIL_URL" => "",
                "PREVIEW_TRUNCATE_LEN" => "",
                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                "SET_TITLE" => "N",
                "SET_BROWSER_TITLE" => "N",
                "SET_META_KEYWORDS" => "N",
                "SET_META_DESCRIPTION" => "N",
                "SET_LAST_MODIFIED" => "N",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                "ADD_SECTIONS_CHAIN" => "N",
                "HIDE_LINK_WHEN_NO_DETAIL" => "Y",
                "PARENT_SECTION" => "",
                "PARENT_SECTION_CODE" => "",
                "INCLUDE_SUBSECTIONS" => "Y",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "CACHE_FILTER" => "Y",
                "CACHE_GROUPS" => "Y",
                "DISPLAY_TOP_PAGER" => "Y",
                "DISPLAY_BOTTOM_PAGER" => "Y",
                "PAGER_TITLE" => "Сопутствующие товары",
                "PAGER_SHOW_ALWAYS" => "Y",
                "PAGER_TEMPLATE" => "",
                "PAGER_DESC_NUMBERING" => "Y",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "Y",
                "PAGER_BASE_LINK_ENABLE" => "Y",
                "SET_STATUS_404" => "Y",
                "SHOW_404" => "Y",
                "MESSAGE_404" => "",
                "PAGER_BASE_LINK" => "",
                "PAGER_PARAMS_NAME" => "arrPager",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_ADDITIONAL" => "",
            ]
        ); ?>
    </div>
<? endif; ?>