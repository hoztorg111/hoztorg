<?$arResult = CMax::getChilds($arResult);
global $arRegion, $arTheme;
if($arResult){
	$MENU_TYPE = $arTheme['MEGA_MENU_TYPE']['VALUE'];

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
	}
	
	if($MENU_TYPE == 3) {
		CMax::replaceMenuChilds($arResult, $arParams);
	}
}
foreach ($arResult as $key => &$value) {
    $value['TEXT'] = preg_replace('/([0-9. ]{3,6})(.*)/', '$2', $value['TEXT']);
	foreach($value['CHAIN'] as $keychain => $chain){
		$value['CHAIN'][$keychain] = preg_replace('/([0-9. ]{3,6})(.*)/', '$2', $chain);
	}
	if($value['CHILD']){
		foreach($value['CHILD'] as $key2 => $arChild){
			foreach($arChild['CHAIN'] as $keychain => $chain){
				$arChild['CHAIN'][$keychain] = preg_replace('/([0-9. ]{3,6})(.*)/', '$2', $chain);
			}
			$arResult[$key]['CHILD'][$key2]['TEXT'] = preg_replace('/([0-9. ]{3,6})(.*)/', '$2', $arChild['TEXT']);
			if($arChild['CHILD'])
				foreach($arChild['CHILD'] as $key3 => $child){
					foreach($child['CHAIN'] as $keychain => $chain){
						$child['CHAIN'][$keychain] = preg_replace('/([0-9. ]{3,6})(.*)/', '$2', $chain);
						
					}
					$arResult[$key]['CHILD'][$key2]['CHILD'][$key3]['TEXT'] = preg_replace('/([0-9. ]{3,6})(.*)/', '$2', $child['TEXT']);
				}
		}
	}
}
?>