<?if(!check_bitrix_sessid()) return;?>

<?
use KLBR\QuickOrderTable;
use Bitrix\Main\Application;

$connection = Application::getConnection();
$isExist = $connection->isTableExists("quick_order");
if(!$isExist)
{
    \Bitrix\Main\Loader::includeModule("klbr");
    $entity = new QuickOrderTable();
    QuickOrderTable::getEntity()->createDbTable();
}
echo CAdminMessage::ShowNote("Модуль klbr установлен");

?>