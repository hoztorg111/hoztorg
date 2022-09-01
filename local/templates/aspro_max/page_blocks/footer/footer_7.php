<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme;
?>

<div class="footer-v7">
	<div class="footer-inner short">
		<div class="maxwidth-theme">
			<div class="row">
				<div class="subscribe_wrap col-md-3">
					<div class="info">
						<?if(\Bitrix\Main\Loader::includeModule('subscribe') && $arTheme['HIDE_SUBSCRIBE']['VALUE'] != 'Y'):?>
							<div class="subscribe_button">
								<span class="btn" data-event="jqm" data-param-id="subscribe" data-param-type="subscribe" data-name="subscribe"><?=GetMessage('SUBSCRIBE_TITLE')?>
								<?=CMax::showIconSvg('subscribe', SITE_TEMPLATE_PATH.'/images/svg/subscribe_small_footer.svg')?></span>
							</div>
						<?endif?>
						<div class="copy-block">
							<div class="copy font_xs">
								<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/copy/copyright.php", Array(), Array(
										"MODE" => "php",
										"NAME" => "Copyright",
										"TEMPLATE" => "include_area.php",
									)
								);?>
							</div>
						</div>
					</div>
				</div>
				<div class="contact-block col-md-6">
					<div class="row">
						<div class="contact_wrap col-md-6">
							<div class="info">
								<div class="phone blocks">
									<div class="inline-block">
										<?CMax::ShowHeaderPhones('white sm', true);?>
									</div>
									<?$callbackExploded = explode(',', $arTheme['SHOW_CALLBACK']['VALUE']);
									if( in_array('FOOTER', $callbackExploded) ):?>
										<div class="inline-block callback_wrap">
											<span class="callback-block animate-load colored" data-event="jqm" data-param-form_id="CALLBACK" data-name="callback"><?=GetMessage("CALLBACK")?></span>
										</div>
									<?endif;?>
								</div>
                                <div class="schedule blocks">
                                    <svg class="svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 512C114.6 512 0 397.4 0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256C512 397.4 397.4 512 256 512zM232 256C232 264 236 271.5 242.7 275.1L338.7 339.1C349.7 347.3 364.6 344.3 371.1 333.3C379.3 322.3 376.3 307.4 365.3 300L280 243.2V120C280 106.7 269.3 96 255.1 96C242.7 96 231.1 106.7 231.1 120L232 256z"/></svg>
                                    <?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-schedule.php", array(), array(
                                            "MODE" => "html",
                                            "NAME" => "Text in title",
                                            "TEMPLATE" => "include_area.php",
                                        )
                                    );?>
                                </div>
								<?=CMax::showEmail('email blocks')?>
								<?=CMax::showAddress('address blocks')?>
							</div>
						</div>
						<?/*<div class="social-block col-md-6">
							<?$APPLICATION->IncludeComponent(
								"aspro:social.info.max",
								".default",
								array(
									"CACHE_TYPE" => "A",
									"CACHE_TIME" => "3600000",
									"CACHE_GROUPS" => "N",
									"COMPONENT_TEMPLATE" => ".default"
								),
								false
							);?>
							<div class="pays">
								<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/copy/pay_system_icons.php", Array(), Array(
										"MODE" => "php",
										"NAME" => "onfidentiality",
										"TEMPLATE" => "include_area.php",
									)
								);?>
							</div>
						</div>*/?>
					</div>
				</div>
				<?/*<div class="col-md-3 right_block_wrap">
					<div class="right_block">
						<div class="link_block">
							<div class="confidentiality">
								<?=CMax::showIconSvg('privacy_policy', SITE_TEMPLATE_PATH.'/images/svg/privacy_policy.svg')?>
								<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/confidentiality.php", Array(), Array(
										"MODE" => "php",
										"NAME" => "onfidentiality",
										"TEMPLATE" => "include_area.php",
									)
								);?>
							</div>
							<?=CMax::ShowPrintLink();?>
						</div>
						<div class="copy-block media">
							<div class="copy font_xs">
								<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/copy.php", Array(), Array(
										"MODE" => "php",
										"NAME" => "Copyright",
										"TEMPLATE" => "include_area.php",
									)
								);?>
							</div>
						</div>
						<div id="bx-composite-banner"></div>
					</div>
				</div>*/?>
			</div>
		</div>
	</div>
</div>