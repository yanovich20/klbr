<?php 
namespace KLBR;
use Bitrix\Main\Context;

class PrologEventHandler{
    public static function onPrologEventHandler(){
        $request = Context::getCurrent()->getRequest();
        if(strpos($request->getRequestedPageDirectory(),"catalog/")!==false)
        {
            \Bitrix\Main\Page\Asset::getInstance()->addJs("/local/modules/klbr/lib/js/klbr.js");
            \Bitrix\Main\Page\Asset::getInstance()->addCss("/local/modules/klbr/lib/css/styles.css");
        }
    }
}