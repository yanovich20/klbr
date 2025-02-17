<?php 
namespace KLBR;

class BuildGlobalMenuHandler{
    public static function OnBuildGlobalMenuHandler(&$aGlobalMenu, &$aModuleMenu)
    {
	global $USER;
	if(!$USER->IsAdmin())
		return;
	$aMenu = array(
		"parent_menu" => "global_menu_content",
		"section" => "settings",
		"sort" => 150,
		"text" => "Быстрые заказы",
		"title" => "Быстрые заказы",
		"url" => "index.php?lang=".LANGUAGE_ID,
		"icon" => "clouds_menu_icon",
		"page_icon" => "clouds_page_icon",
		"items_id" => "my_menu",
		"items" => array()
	);
	
	$aMenu["items"][] = array(
		"text" => "Быстрые заказы",
		"url" => "_klbr_quick_order.php?lang=".LANGUAGE_ID,
		"title" => "",
		"page_icon" => "clouds_page_icon",
		"items_id" => "my_menu_id",
		"module_id" => "klbr",
		"items" => array()
	);
	if(!empty($aMenu["items"]))
		$aModuleMenu[]= $aMenu;
    }
}