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
  //CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/dv_module/install/components",
  //           $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
  return true;
  }
  function UnInstallFiles()
  {
  //eleteDirFilesEx("/local/components/dv");
  return true;
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
  }
  function unInstallEvents()
  {
    $eventManager = \Bitrix\Main\EventManager::getInstance(); 
    $eventManager->unRegisterEventHandler("main","onProlog","klbr","\\KLBR\\PrologEventHandler","onPrologEventHandler");
    $eventManager->unRegisterEventHandler("main","onBuildGlobalMenu","klbr","\\KLBR\\BuildGlobalMenuHandler","oBuildGlobalMenuHandler");
  }
}
