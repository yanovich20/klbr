<?php
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\ActionFilter\HttpMethod;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use KLBR\QuickOrderTable;
use \Bitrix\Main\Type\DateTime as DT;
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
            'changeOrderStatus'=> [  
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
        $data = $this->xssSave( $_POST);
        $data["UF_DATE"] = new DT();
        $data["UF_STATUS"]="Новый";
        $addResult = QuickOrderTable::add($data);
        if($addResult->getId())
        {
            $data["ID"] = $addResult->getId();
            \CEvent::Send("NEW_QUICK_ORDER","s1", $data);
        }
        return $this-> returnResult($addResult);
    }
    public function changeOrderStatusAction($action){
        $id = $_POST["ID"];
        $updateResult = QuickOrderTable::update($id,["UF_STATUS"=>"Обработанный"]);
        return $this->returnResult($updateResult);
    }
    private function returnResult($result){
        if($result->isSuccess())
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
    private function xssSave($data){
        $newData = [];
        foreach($data as $key=>$value)
        {
           $newData[$key] = htmlspecialchars($value);
        }
        return $newData;
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

        if($filterData['UF_DATE'][0]){
            $format = "Y-m-d H:i:s";
            $today = date($format);
            $interval = 0;
        
            switch ($filterData['UF_DATE'][0]){
                case '1':
                    $interval = 7;
                    break;
                case '2':
                    $interval = 14;
                    break;
                case '3':
                    $interval = 30;
                    break;
                case '4':
                    $interval = 60;
                    break;
                case '5':
                    $interval = 180;
                    break;
            }
        
            $data = (new \DateTime($today))->modify("-". $interval ." day");
            $mainFilter['>UF_DATE'] = DT::createFromTimestamp(strtotime($data->format('Y-m-d')));
            }

        if($filterData['UF_STATUS'])
            $mainFilter['UF_STATUS'] = $filterData['UF_STATUS'];

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
            "select" => ['ID', "UF_EMAIL", 'UF_NAME', 'UF_PHONE',"UF_COMMENT","UF_DATE","UF_STATUS"],
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
                    "DATE"=>$row["UF_DATE"],
                    "STATUS"=>$row["UF_STATUS"]
                ],
                'actions' => [
                    [
                'text' => 'Сменить статус',
                'default' => true,
                'onclick' =>'setOrderStatus('.$row["ID"].',"'.$this->arParams["GRID_ID"].'")'
                    ]
                ]
            ];
        }
    $this->arResult["ROWS"] = $list;
    $this->includeComponentTemplate();
    }
}