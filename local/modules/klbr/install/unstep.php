<?php
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;

use Bitrix\Main\Application;

$connection = Application::getConnection();
$isExist = $connection->isTableExists("quick_order");
if($isExist)
{
	$result = $connection->dropTable("quick_order");
	echo CAdminMessage::ShowNote("Модуль klbr успешно удален");
}