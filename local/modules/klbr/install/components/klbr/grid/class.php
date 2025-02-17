<?php
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\ActionFilter\HttpMethod;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use KLBR\QuickOrderTable;
use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\UI\PageNavigation;


class QuickOrderGrid  extends \CBitrixComponent implements Controllerable {

    private $curUserId;
    public function configureActions()
    {
        return [ 
            'addQuickOrder' => [  
                'prefilters' => [
                    new HttpMethod(
                        array(HttpMethod::METHOD_POST)
                    ),
                    new Csrf(),
                ],
                'postfilters' => []
            ],
        ];
    }
    public function addQuickOrderAction($action){
        $this->checkModules();
        
        $addResult = QuickOrderTable::add($_POST);
        if($addResult->isSuccess())
            return json_encode(["status"=>"success"]);
        else
        {
            $errors = $result->getErrors();
            $message ="";
            foreach ($errors as $error) {
              $message .= $error->getMessage();
            }
            return json_encode(["status"=>"error","message"=>$message]);
        }
    }
    private function checkModules(){
        if(!Loader::includeModule("klbr"))
        {
            throw new \Exception("Не установлен модуль klbr");
        }
    }
    public function executeComponent(){
        $this->checkModules();
        $this->curUserId = CurrentUser::get()->getId();
        if(!$this->curUserId)
        {
            echo "Вы не авторизованы";
            return;
        }
        $mainFilter = array();
       
        $filterOption = new Bitrix\Main\UI\Filter\Options($this->arParams["FILTER_ID"]);

        $filterFields = $filterOption->getFilter([]);
        
        foreach ($filterFields as $k => $v) {
            if($k=="FILTER_ID"||$k=="FILTER_APPLIED")
                    continue;
            if($k == 'FIND' && $v)
                $filterData['UF_NAME'] = "%".$v."%";
            else
                $filterData[$k] = $v;
        }
        
        if($filterData['UF_NAME'])
            $mainFilter['UF_NAME'] = $filterData['UF_NAME'];
        
        
        if($filterData['UF_EMAIL'])
            $mainFilter['UF_EMAIL'] = $filterData['UF_EMAIL'];
        
        if($filterData['UF_PHONE'])
            $mainFilter['UF_PHONE'] = $filterData['UF_PHONE'];
        if($filterData['UF_COMMENT'])
            $mainFilter['UF_COMMENT'] = $filterData['UF_COMMENT'];

        $grid_options = new GridOptions($this->arParams["GRID_ID"]);
        $sort = $grid_options->GetSorting(['sort' => ['ID' => 'DESC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
        $nav_params = $grid_options->GetNavParams();
        
        $nav = new PageNavigation($this->arParams["GRID_ID"]);
        $nav->allowAllRecords(true)
            ->setPageSize($nav_params['nPageSize'])
            ->initFromUri();
            
        if ($nav->allRecordsShown())
            $nav_params = false;
        else
            $nav_params['iNumPage'] = $nav->getCurrentPage();
    
        $getListOptions = array(
            "select" => ['ID', "UF_EMAIL", 'UF_NAME', 'UF_PHONE',"UF_COMMENT"],
            "order" => ["ID" => "DESC"],
            "filter" => $mainFilter,
            'limit' => $nav_params['nPageSize'],
            'offset' => $nav_params['iNumPage'] - 1,
            'runtime' => $runtimes ? $runtimes : '',
            'count_total' => true
        );
        $rowsObj = QuickOrderTable::getList($getListOptions);
    
        $countOrders = $rowsObj->getCount();
        $rows = $rowsObj->fetchAll();
        $nav->setRecordCount($countOrders);
        $this->arResult["NAV"] = $nav;
        
        foreach($rows as $k => $row) {
            $list[] = [
                'data' => [
                    "ID" => $row['ID'],
                    "EMAIL" => $row['UF_EMAIL'],
                    "NAME" => $row['UF_NAME'],
                    "PHONE" => $row['UF_PHONE'],
                    "COMMENT" => $row['UF_COMMENT'],
                ],
            ];
        }
    $this->arResult["ROWS"] = $list;
    $this->includeComponentTemplate();
    }
}