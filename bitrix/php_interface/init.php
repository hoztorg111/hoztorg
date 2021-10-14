<?php
//AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("aspro_import", "OnBeforeIBlockElementUpdateHandler"));
define('BRANDS_IBLOCK_ID', 30);
define('CATALOG_IBLOCK_ID', 26);

Bitrix\Main\Localization\Loc::loadMessages(__FILE__);

AddEventHandler("iblock", "OnAfterIBlockElementAdd", array( "aspro_import", "FillTheBrands" ));
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", array( "aspro_import", "FillTheBrands" ));

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

    public function OnSuccessCatalogImport1C($t)
    {
        \Bitrix\Main\Loader::includeModule('iblock');
        \Bitrix\Main\Loader::includeModule('catalog');
        /* обновление названий разделов */
        $arFilter = array('IBLOCK_ID' => 26);
        $rsSections = CIBlockSection::GetList(array('NAME' => 'ASC'), $arFilter, false, ['ID', 'NAME']);
        while ($arSection = $rsSections->Fetch())
        {
            
            $bs = new CIBlockSection;
            $arFields['NAME'] = preg_replace('/[0-9]{2}.[0-9]{2} /', '', $arSection['NAME']);
            $arFields['NAME'] = preg_replace('/[0-9]{2}./', '', $arSection['NAME']);
            AddMessage2Log($arSection['NAME'] . $arFields['NAME']);
            $arFields['CODE'] = CUtil::translit($arFields['NAME'], 'ru');
            $res = $bs->Update($arSection['ID'], $arFields);
        }
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
AddEventHandler("iblock", "OnBeforeIBlockSectionUpdate", Array("haClassEvents", "OnBeforeIBlockSectionUpdateHandler"));
AddEventHandler("iblock", "OnBeforeIBlockSectionAdd", Array("haClassEvents", "OnBeforeIBlockSectionUpdateHandler"));

class haClassEvents
{
    // создаем обработчик события "OnBeforeIBlockSectionUpdate"
    function OnBeforeIBlockSectionUpdateHandler(&$arFields)
    {
        AddMessage2Log($arFields['NAME']);
        $arFields['NAME'] = preg_replace('/[0-9]{2}.[0-9]{2} /', '', $arFields['NAME']);
        $arFields['CODE'] = CUtil::translit($arFields['NAME'], 'ru');
    }
}
?>