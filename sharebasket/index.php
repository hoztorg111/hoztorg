<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Поделиться корзиной");
?>
<?$APPLICATION->IncludeComponent(
	"aspro:basket.share.max", 
	".default", 
	array(
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"DETAIL_ACTUAL" => "Y",
		"DETAIL_PRODUCT_PROPERTIES" => array(
			0 => "COLOR_REF",
			1 => "SIZES",
			2 => "SIZES2",
			3 => "SIZES3",
			4 => "SIZES4",
			5 => "SIZES5",
		),
		"DETAIL_SET_PAGE_TITLE" => "Y",
		"DETAIL_SHOW_AMOUNT" => "Y",
		"DETAIL_SHOW_DISCOUNT_PERCENT" => "Y",
		"DETAIL_SHOW_DISCOUNT_PERCENT_NUMBER" => "Y",
		"DETAIL_SHOW_OLD_PRICE" => "Y",
		"DETAIL_SHOW_ONE_CLICK_BUY" => "N",
		"DETAIL_SHOW_STICKERS" => "N",
		"DETAIL_SHOW_VERSION_SWITCHER" => "Y",
		"DETAIL_USE_COMPARE" => "Y",
		"DETAIL_USE_CUSTOM_MESSAGES" => "N",
		"DETAIL_USE_DELAY" => "N",
		"DETAIL_USE_FAST_VIEW" => "N",
		"FILE_404" => "",
		"MESSAGE_404" => "",
		"NEW_SET_PAGE_TITLE" => "Y",
		"NEW_SHARE_SOCIALS" => array(
			0 => "VKONTAKTE",
			1 => "FACEBOOK",
			2 => "ODNOKLASSNIKI",
			3 => "TWITTER",
		),
		"NEW_SHOW_SHARE_SOCIALS" => "Y",
		"NEW_SITE_ID" => "mk",
		"NEW_USER_ID" => "",
		"NEW_USE_CUSTOM_MESSAGES" => "N",
		"SEF_FOLDER" => "/sharebasket/",
		"SEF_MODE" => "Y",
		"SET_STATUS_404" => "Y",
		"SHOW_404" => "N",
		"COMPONENT_TEMPLATE" => ".default",
		"SEF_URL_TEMPLATES" => array(
			"new" => "new/",
			"detail" => "#CODE#/",
		)
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>