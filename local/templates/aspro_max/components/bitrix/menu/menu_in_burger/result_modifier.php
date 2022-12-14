<?
$arResult = CMax::getChilds($arResult);
global $arRegion, $arTheme;
$catalogLink = $arTheme['CATALOG_PAGE_URL']['VALUE'];

$MENU_TYPE = $arTheme['MEGA_MENU_TYPE']['VALUE'];
if($MENU_TYPE == 3) {
	CMax::replaceMenuChilds($arResult, $arParams);
}

if($arResult){
	foreach($arResult as $key=>$arItem)
	{
		if(isset($arItem['CHILD']))
		{
			foreach($arItem['CHILD'] as $key2=>$arItemChild)
			{
				if(isset($arItemChild['PARAMS']) && $arRegion && $arTheme['USE_REGIONALITY']['VALUE'] === 'Y' && $arTheme['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_FILTER_ITEM']['VALUE'] === 'Y')
				{
					// filter items by region
					if(isset($arItemChild['PARAMS']['LINK_REGION']))
					{
						if($arItemChild['PARAMS']['LINK_REGION'])
						{
							if(!in_array($arRegion['ID'], $arItemChild['PARAMS']['LINK_REGION']))
								unset($arResult[$key]['CHILD'][$key2]);
						}
						else
							unset($arResult[$key]['CHILD'][$key2]);
					}
				}
			}
		}

		if($arItem['LINK'] == $catalogLink) {
			$arResult['EXPANDED'] = $arItem;
			unset($arResult[$key]);
		}
	}
}
foreach ($arResult as $key => &$value) {
    $value['TEXT'] = preg_replace('/([0-9. ]{3,6})(.*)/', '$2', $value['TEXT']);
	if($value['CHILD']){
		foreach($value['CHILD'] as $key2 => $arChild){
			$arResult[$key]['CHILD'][$key2]['TEXT'] = preg_replace('/([0-9. ]{3,6})(.*)/', '$2', $arChild['TEXT']);
			if($arChild['CHILD'])
				foreach($arChild['CHILD'] as $key3 => $child){
					$arResult[$key]['CHILD'][$key2]['CHILD'][$key3]['TEXT'] = preg_replace('/([0-9. ]{3,6})(.*)/', '$2', $child['TEXT']);
				}
		}
	}
}
?>