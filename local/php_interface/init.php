<?php

require_once( $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/autoload.php');

//AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("aspro_import", "OnBeforeIBlockElementUpdateHandler"));
define('BRANDS_IBLOCK_ID', 30);
define('CATALOG_IBLOCK_ID', 26);

Bitrix\Main\Localization\Loc::loadMessages(__FILE__);

AddEventHandler("iblock", "OnAfterIBlockElementAdd", array( "aspro_import", "FillTheBrands" ));
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", array( "aspro_import", "FillTheBrands" ));
AddEventHandler("iblock", "OnBeforeIBlockElementAdd", array( "aspro_import", "ResizeImages" ));
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", array( "aspro_import", "ResizeImages" ));
AddEventHandler("iblock", "OnBeforeIBlockSectionUpdate", array( "aspro_import", "NameSection" ));

AddEventHandler("catalog", "OnSuccessCatalogImport1C", array("aspro_import", "OnSuccessCatalogImport1C"));
AddEventHandler("socialservices", "OnAfterSocServUserAdd", array("aspro_import", "OnAfterSocServUserAddHandler"));





class aspro_import
{
    public function OnBeforeUserLoginHandler(&$arFields)
    {
        $rsUser=CUser::GetList(($by="personal_country"), ($order="desc"), array("LOGIN" => $arFields["LOGIN"]), array("FIELDS" => array("ID", "EMAIL")));
        if (!$rsUser->SelectedRowsCount()) {
            $arUser=CUser::GetList(($by="personal_country"), ($order="desc"), array("=EMAIL" => $arFields["LOGIN"]), array("FIELDS" => array("ID", "LOGIN")))->Fetch();
            if ($arUser["LOGIN"]) {
                $arFields["LOGIN"]=$arUser["LOGIN"];
            }
        }
        /*AddMessage2Log($arFields, "arFields");
        AddMessage2Log($_REQUEST, "USERS");*/
    }

    public function FillTheBrands($arFields)
    {
        if ($arFields['ID']) {
            if (!($bImport = ($_GET['mode'] == 'import') && $_SESSION['BX_CML2_IMPORT']['NS']['STEP'])) {
                if ($arFields['IBLOCK_ID'] == CATALOG_IBLOCK_ID) {
                    // get changed element
                    $arItem = CIBlockElement::GetList(false, array('IBLOCK_ID' => $arFields['IBLOCK_ID'], 'ID' => $arFields['ID']), false, false, array('ID', 'PROPERTY_BREND'))->fetch();
                    if ($arItem && $arItem['PROPERTY_BREND_VALUE']) {
                        // get BRAND by NAME == element poperty BReND value
                        $BRAND_ID = false;
                        if ($arBrand = CIBlockElement::GetList(false, array('IBLOCK_ID' => BRANDS_IBLOCK_ID, 'NAME' => $arItem['PROPERTY_BREND_VALUE']), false, false, array('ID'))->fetch()) {
                            // BRAND exists
                            $BRAND_ID = $arBrand['ID'];
                        } else {
                            $BRAND_CODE = Cutil::translit($arItem['PROPERTY_BREND_VALUE'], "ru", array("replace_space" => "-", "replace_other" => "-"));
                            if ($arBrand = CIBlockElement::GetList(false, array('IBLOCK_ID' => BRANDS_IBLOCK_ID, 'CODE' => $BRAND_CODE), false, false, array('ID'))->fetch()) {
                                // BRAND exists
                                $BRAND_ID = $arBrand['ID'];
                            } else {
                                // new BRAND
                                $el = new CIBlockElement;
                                $BRAND_ID = $el->Add($arBrandFields = array(
                                    'ACTIVE' => 'Y',
                                    'NAME' => $arItem['PROPERTY_BREND_VALUE'],
                                    'IBLOCK_ID' => BRANDS_IBLOCK_ID,
                                    'CODE' => Cutil::translit($arItem['PROPERTY_BREND_VALUE'], "ru", array("replace_space" => "-", "replace_other" => "-"))
                                ));
                            }
                        }

                        if ($BRAND_ID) {
                            // update element property BRaND value
                            CIBlockElement::SetPropertyValuesEx($arFields['ID'], false, array('BRAND' => $BRAND_ID));
                        } else {
                            echo $el->LAST_ERROR;
                        }
                    }
                }
            }
        }
    }

    public function resizeImages(&$arFields){
        // global $APPLICATION;
        // $APPLICATION->RestartBuffer();
        // echo '<pre>';
        // print_r($arFields);
        // die();
        $maxWidth = 1000;
        $maxHeight = 1000;
        if(is_array($arFields['PROPERTY_VALUES'][377]) && !empty($arFields['PROPERTY_VALUES'][377])){
            foreach($arFields['PROPERTY_VALUES'][377] as &$fileImage){
                if($fileImage['VALUE']['tmp_name']){
                    $file = getImageSizesAndSave($fileImage['VALUE'], $maxWidth, $maxHeight);
                    if ($file) {
                        $fileImage = $file;
                    }
                }
                
                
            }
        }
        if(is_array($arFields['PROPERTY_VALUES'][384]) && !empty($arFields['PROPERTY_VALUES'][384])){
            foreach($arFields['PROPERTY_VALUES'][384] as &$fileImage){
                if($fileImage['VALUE']['tmp_name']){
                    $file = getImageSizesAndSave($fileImage['VALUE'], $maxWidth, $maxHeight);
                    if ($file) {
                        $fileImage = $file;
                    }
                }
            }
        }
        if($arFields['PREVIEW_PICTURE']['tmp_name']){
            $file = getImageSizesAndSave($arFields['PREVIEW_PICTURE'], $maxWidth, $maxHeight, $filename = $arFields['ID'].' Element');
            if ($file) {
                $arFields['PREVIEW_PICTURE'] = $file;
            }
        }
        if($arFields['DETAIL_PICTURE']['tmp_name']){
            $file = getImageSizesAndSave($arFields['DETAIL_PICTURE'], $maxWidth, $maxHeight);
            if ($file) {
                $arFields['DETAIL_PICTURE'] = $file;
            }
        }
        return $arFields;
    }

    public function NameSection(&$arFields) {
        $nameSection = $arFields["NAME"];
        preg_match("/^.*?[А-Я]/", $nameSection, $arr);
        $str_position = (strlen($arr[0]) - 1);
        $chr = mb_substr($nameSection, $str_position);
        if (mb_strtolower($chr) !== $chr) {
            $chr = mb_strtolower($chr, 'UTF-8');
            $chr = ucfirst($chr);

            $fc = mb_strtoupper(mb_substr($chr, 0, 1));
            $arFields["NAME"] = mb_substr($nameSection, 0, $str_position) . $fc . mb_substr($chr, 1);
        }

        return $arFields;
    }

    public function OnSuccessCatalogImport1C($t)
    {
        \Bitrix\Main\Loader::includeModule('iblock');
        \Bitrix\Main\Loader::includeModule('catalog');
        $arElementsID = $arElements = $arBrandIDByName = $arBrandIDByCode = $arOffersID = $arOffers = $arElementIDByOfferID = $arQuantity = array();

        // get elements whitch has been changed in 2 last hours
        $date = time() - 7200; // 2 hours
        $full_date = date("d.m.Y H:i:s", $date);
        $rsItems = CIBlockElement::GetList(array(), array(">=TIMESTAMP_X" => $full_date, "IBLOCK_ID" => CATALOG_IBLOCK_ID), false, false, array("ID", "PROPERTY_V_SOSTAVE", "PROPERTY_VES_REB", 'PROPERTY_BREND'));
        while ($arItem = $rsItems->Fetch()) {
            $arElementsID[] = $arItem['ID'];
            $arElements[$arItem['ID']] = $arItem;
        }

        // get all brands
        if (defined('BRANDS_IBLOCK_ID') && BRANDS_IBLOCK_ID) {
            $dbRes = CIBlockElement::GetList(array(), array('IBLOCK_ID' => BRANDS_IBLOCK_ID), false, false, array('ID', 'NAME', 'CODE'));
            while ($arItem = $dbRes->Fetch()) {
                $arBrandIDByName[$arItem['NAME']] = $arBrandIDByCode[$arItem['CODE']] = $arItem['ID'];
            }
        }

        if ($arElementsID) {
            // get offers of changed elements
            $dbRes = CIBlockElement::GetList(array(), array("PROPERTY_CML2_LINK" => $arElementsID), false, false, array('ID', 'PROPERTY_CML2_LINK'));
            while ($arItem = $dbRes->Fetch()) {
                $arOffersID[] = $arItem['ID'];
                $arElementIDByOfferID[$arItem['ID']] = $arItem['PROPERTY_CML2_LINK_VALUE'];
                $arOffers[$arItem['PROPERTY_CML2_LINK_VALUE']][] = $arItem;
            }

            // get quantity of offers of changed elements
            if ($arOffersID) {
                $dbRes = CCatalogProduct::GetList(array(), array('ID' => $arOffersID), false, false, array('ID', 'QUANTITY'));
                while ($arItem = $dbRes->Fetch()) {
                    if ($arItem['QUANTITY'] > 0) {
                        // and count quantity of product
                        $arQuantity[$arElementIDByOfferID[$arItem['ID']]] += $arItem['QUANTITY'];
                    }
                }
            }

            foreach ($arElements as $ID => $arItem) {
                $arFields = array();
                if ($arItem['PROPERTY_V_SOSTAVE_VALUE']) {
                    $arFields['V_SOSTAVE_FILTER'] = explode(", ", $arItem['PROPERTY_V_SOSTAVE_VALUE']);
                }
                if ($arItem['PROPERTY_VES_REB_VALUE']) {
                    $arFields['VES_REB_FILTER'] = explode(", ", $arItem['PROPERTY_VES_REB_VALUE']);
                }
                if ($arItem['PROPERTY_BREND_VALUE']) {
                    $BRAND_ID = false;
                    $BRAND_CODE = Cutil::translit($arItem['PROPERTY_BREND_VALUE'], "ru", array("replace_space" => "-", "replace_other" => "-"));
                    if (!isset($arBrandIDByName[$arItem['PROPERTY_BREND_VALUE']]) && !isset($arBrandIDByCode[$BRAND_CODE])) {
                        // new BRAND
                        $el = new CIBlockElement;
                        $BRAND_ID = $el->Add($arBrandFields = array(
                            'ACTIVE' => 'Y',
                            'NAME' => $arItem['PROPERTY_BREND_VALUE'],
                            'IBLOCK_ID' => BRANDS_IBLOCK_ID,
                            'CODE' => $BRAND_CODE
                        ));
                    } else {
                        // BRAND exists
                        $BRAND_ID = isset($arBrandIDByName[$arItem['PROPERTY_BREND_VALUE']]) ? $arBrandIDByName[$arItem['PROPERTY_BREND_VALUE']] : $arBrandIDByCode[$BRAND_CODE];
                    }

                    if ($BRAND_ID) {
                        $arFields['BRAND'] = $BRAND_ID;
                    } else {
                        echo $el->LAST_ERROR;
                    }
                }

                // update properties
                if ($arFields) {
                    CIBlockElement::SetPropertyValuesEx($ID, CATALOG_IBLOCK_ID, $arFields);
                }

                // update sku quantity
                if ($arQuantity[$ID] > 0) { // why?
                    CCatalogProduct::Update($ID, array('QUANTITY' => $arQuantity[$ID]));
                }
            }
        }
    }
}

function getImageSizesAndSave(&$fileImage, $maxWidth, $maxHeight, $filename = ''){
    if($fileImage['tmp_name'] && $fileImage['type'] != "image/webp"){
        if($fileImage['type'] == 'image/jpeg'){
            $image = imagecreatefromjpeg($fileImage['tmp_name']);
        }else{
            $image = imagecreatefrompng($fileImage['tmp_name']);
        }
        imagewebp($image, $fileImage['tmp_name'].'.webp', 100);
        imagedestroy($image);
        $file = CFile::MakeFileArray($fileImage['tmp_name'].'.webp');
        $sizes = CFile::GetImageSize($file['tmp_name']);
        // if($sizes[0] > $maxWidth || $sizes[1] > $maxHeight){
            
        //     CFile::ResizeImage(
        //         $file, // путь к изображению, сюда же будет записан уменьшенный файл
        //         array(
        //          "width" => $maxWidth,  // новая ширина
        //          "height" => $maxHeight // новая высота
        //         ),
        //         BX_RESIZE_IMAGE_PROPORTIONAL // метод масштабирования. обрезать прямоугольник без учета пропорций
        //     );
        // }
        if($filename)
            $file['name'] = $filename.'.webp';
        return $file;
    }
}

AddEventHandler("search", "BeforeIndex", "BeforeIndexHandler");
function BeforeIndexHandler($arFields)
{
   if(!CModule::IncludeModule("iblock")) // подключаем модуль
      return $arFields;
   if($arFields["MODULE_ID"] == "iblock")
   {
        $db_props = CIBlockElement::GetProperty(                        // Запросим свойства индексируемого элемента
                                        $arFields["PARAM2"],         // BLOCK_ID индексируемого свойства
                                        $arFields["ITEM_ID"],          // ID индексируемого свойства
                                        array("sort" => "asc"),       // Сортировка (можно упустить)
                                        Array("CODE"=>"CML2_ARTICLE")); // CODE свойства (в данном случае артикул)
        if($ar_props = $db_props->Fetch()){
            $arFields['TITLE'] .= " ".preg_replace('/([0-9]+) ([0-9]+) ([a-zA-Zа-яА-ЯЁё]+)$/', '$1$2', $ar_props['VALUE']);
        }
   }
   return $arFields; // вернём изменения
}