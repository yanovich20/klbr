<?
Class KLBR extends CModule
{
var $MODULE_ID = "klbr";
var $MODULE_VERSION;
var $MODULE_VERSION_DATE;
var $MODULE_NAME;
var $MODULE_DESCRIPTION;
var $MODULE_CSS;
  function __construct()
  {
    $arModuleVersion = array();
    $path = str_replace("\\", "/", __FILE__);
    $path = substr($path, 0, strlen($path) - strlen("/index.php"));
    include($path."/version.php");
    if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
    {
    $this->MODULE_VERSION = $arModuleVersion["VERSION"];
    $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
    }
    $this->MODULE_NAME = "klbr модуль для работы с быстрыми заказами";
    $this->MODULE_DESCRIPTION = "После установки достпна кнопка быстрого заказа";
  }
  function InstallFiles()
  {
    CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/klbr/install/components",
              $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
    copy($_SERVER["DOCUMENT_ROOT"]."/local/modules/klbr/install/_klbr_quick_order.php",$_SERVER["DOCUMENT_ROOT"]."/bitrix/admin/_klbr_quick_order.php");
  }
  function UnInstallFiles()
  {
    DeleteDirFilesEx("/bitrix/components/klbr");
    unlink($_SERVER["DOCUMENT_ROOT"]."/bitrix/admin/_klbr_quick_order.php");
  }
  function DoInstall()
  {
    global $DOCUMENT_ROOT, $APPLICATION;
    $this->InstallFiles();
    RegisterModule("klbr");
    $this->installEvents();
    $APPLICATION->IncludeAdminFile("Установка модуля klbr", $DOCUMENT_ROOT."/local/modules/klbr/install/step.php");
  }
  function DoUninstall()
  {
    global $DOCUMENT_ROOT, $APPLICATION;
    $this->UnInstallFiles();
    $this->unInstallEvents();
    UnRegisterModule("klbr");
    $APPLICATION->IncludeAdminFile("Деинсталляция модуля klbr", $DOCUMENT_ROOT."/local/modules/klbr/install/unstep.php");
  }
  function installEvents(){
    $eventManager = \Bitrix\Main\EventManager::getInstance(); 
    $eventManager->registerEventHandler("main","onProlog","klbr","\\KLBR\\PrologEventHandler","onPrologEventHandler");
    $eventManager->registerEventHandler("main","onBuildGlobalMenu","klbr","\\KLBR\\BuildGlobalMenuHandler","onBuildGlobalMenuHandler");
    $obEventType = new CEventType;
    $obEventType->Add(array(
      "EVENT_NAME"    => "NEW_QUICK_ORDER",
      "NAME"          => "Новый быстрый заказ",
      "LID"           => "ru",
      "DESCRIPTION"   => "
        #ID# - ID баннера
        #UF_DATE# - Дата заказа
        #UF_STATUS# - Статус заказа
        #UF_NAME# - Название организации
        #UF_EMAIL#- email
        #UF_PHONE#- Телефон
        #UF_COMMENT# -Комментарий
        "
      ));
    $admin = \Bitrix\Main\UserTable::getList(["filter"=>array("LOGIN"=>"admin"),"select"=>array("ID","EMAIL")])->fetch();
    $arr["ACTIVE"]      = "Y";
    $arr["EVENT_NAME"]  = "NEW_QUICK_ORDER";
    $arr["LID"]         = array("s1");
    $arr["EMAIL_FROM"]  = "admin@site.ru";
    $arr["EMAIL_TO"]    = $admin["EMAIL"];
    $arr["BCC"]         = "";
    $arr["SUBJECT"]     = "Добавлен быстрый заказ #ID#";
    $arr["BODY_TYPE"]   = "text";
    $arr["MESSAGE"]     = "
    Внимание! Создан новый быстрый заказ с # #ID#.
    Имя - #UF_NAME#
    Дата - #UF_DATE#
    Email - #UF_EMAIL#
    Телефон   #UF_PHONE#
    Комментарий - #UF_COMMENT#
    ";
    $obTemplate = new CEventMessage;
    $obTemplate->Add($arr);
      }
  function unInstallEvents()
  {
    $eventManager = \Bitrix\Main\EventManager::getInstance(); 
    $eventManager->unRegisterEventHandler("main","onProlog","klbr","\\KLBR\\PrologEventHandler","onPrologEventHandler");
    $eventManager->unRegisterEventHandler("main","onBuildGlobalMenu","klbr","\\KLBR\\BuildGlobalMenuHandler","onBuildGlobalMenuHandler");
    $arFilter = Array(
      "TYPE_ID"       => array("NEW_QUICK_ORDER"),
    );
    $rsMess = CEventMessage::GetList($by="site_id", $order="desc", $arFilter);
    $message =$rsMess->GetNext();
    CEventMessage::Delete($message["ID"]);
    CEventType::Delete("NEW_QUICK_ORDER");
  }
}
