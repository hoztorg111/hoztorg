<?
foreach($arResult as $key => $arSection){
    $arResult[$key] = preg_replace('/([0-9. ]{3,6})(.*)/', '$2', $arSection);
}