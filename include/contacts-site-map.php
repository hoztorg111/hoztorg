<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$APPLICATION->IncludeComponent(
	"bitrix:map.yandex.view",
	"map",
	Array(
		"API_KEY" => "",
		"CONTROLS" => array("ZOOM","TYPECONTROL","SCALELINE"),
		"INIT_MAP_TYPE" => "MAP",
		"MAP_DATA" => "a:4:{s:10:\"yandex_lat\";d:54.9973652385957;s:10:\"yandex_lon\";d:73.449751366276;s:12:\"yandex_scale\";i:17;s:10:\"PLACEMARKS\";a:2:{i:0;a:3:{s:3:\"LON\";d:73.447562348444;s:3:\"LAT\";d:54.996841738169;s:4:\"TEXT\";s:257:\"Склад №10, г. Омск, ул. Универсальная 19/10###RN###РЕЖИМ РАБОТЫ###RN###Пн - Пт: 9.00 - 17.00###RN###Сб - Вс: выходные###RN###ТЕЛЕФОН###RN###+7 3812 77-77-72###RN###E-MAIL###RN###hoztorg111@mail.ru\";}i:1;a:3:{s:3:\"LON\";d:73.451006640095;s:3:\"LAT\";d:54.997707553301;s:4:\"TEXT\";s:255:\"Склад №5, г. Омск, ул. Универсальная 19/5###RN###РЕЖИМ РАБОТЫ###RN###Пн - Пт: 9.00 - 17.00###RN###Сб - Вс: выходные###RN###ТЕЛЕФОН###RN###+7 3812 77-77-72###RN###E-MAIL###RN###hoztorg111@mail.ru\";}}}",
		"MAP_HEIGHT" => "100%",
		"MAP_ID" => "",
		"MAP_WIDTH" => "100%",
		"OPTIONS" => array("ENABLE_DBLCLICK_ZOOM","ENABLE_DRAGGING"),
		"USE_REGION_DATA" => "Y"
	)
);?>