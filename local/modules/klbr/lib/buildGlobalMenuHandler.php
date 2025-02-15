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
		"text" => "Быстрые заказы",//GetMessage("CLO_STORAGE_MENU"),
		"title" => "Быстрые заказы",//GetMessage("CLO_STORAGE_TITLE"),
		"url" => "index.php?lang=".LANGUAGE_ID,
		"icon" => "clouds_menu_icon",
		"page_icon" => "clouds_page_icon",
		"items_id" => "my_menu",
		//"more_url" => array(
		//	"clouds_index.php",
		//),
		"items" => array()
	);
	
	$aMenu["items"][] = array(
		"text" => "Быстрые заказы",
		"url" => "_klbr.php?lang=".LANGUAGE_ID,
		//"more_url" => array(
		//"clouds_file_list.php?bucket=".$arBucket["ID"],
		//),
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