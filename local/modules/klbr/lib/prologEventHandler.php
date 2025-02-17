<?php 
namespace KLBR;
use Bitrix\Main\Context;

class PrologEventHandler{
    public static function onPrologEventHandler(){
        $request = Context::getCurrent()->getRequest();
        if(preg_match('/^\/catalog\/.+\/.+\/$/',$request->getRequestedPageDirectory()) === 1)
        {
            //\Bitrix\Main\Page\Asset::getInstance()->addJs("/local/modules/klbr/lib/js/jquery-1.8.3.min.js");
            \Bitrix\Main\Page\Asset::getInstance()->addJs("/local/modules/klbr/lib/js/jquery.maskedinput.js");
            \Bitrix\Main\Page\Asset::getInstance()->addJs("/local/modules/klbr/lib/js/klbr.js");
            \Bitrix\Main\Page\Asset::getInstance()->addCss("/local/modules/klbr/lib/css/styles.css");
            \Bitrix\Main\UI\Extension::load("ui.dialogs.messagebox");
        }
    }
}