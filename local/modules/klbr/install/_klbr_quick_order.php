<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
// подключим все необходимые файлы:
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php"); // первый общий пролог
Loader::includeModule('klbr'); // инициализация модуля
?>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php"); // второй общий пролог
// здесь будет вывод страницы
?><?php
$APPLICATION->IncludeComponent(
	"klbr:grid",
	"",
	Array(
		"GRID_ID" => "klbr_grid",
		"FILTER_ID" => "klbr_filter",
	)
);?>
<?php
 require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");