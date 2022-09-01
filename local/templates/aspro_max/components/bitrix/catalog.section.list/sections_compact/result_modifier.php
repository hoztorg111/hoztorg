<?
foreach($arResult['SECTIONS'] as $key => $arSection){
    $arResult['SECTIONS'][$key]['NAME'] = preg_replace('/([0-9. ]{3,6})(.*)/', '$2', $arSection['NAME']);
}