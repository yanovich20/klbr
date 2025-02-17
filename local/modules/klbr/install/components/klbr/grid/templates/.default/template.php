<?php $APPLICATION->IncludeComponent('bitrix:main.ui.filter', '.default', [
    'FILTER_ID' => $arParams["FILTER_ID"],
    'GRID_ID' => $arParams["GRID_ID"],
    'FILTER' => [
        ['id' => 'UF_NAME', 'name' => 'Название', 'type'=>'text', 'default' => true],
        ['id' => 'UF_EMAIL', 'name' => 'Email', 'type'=>'text', 'default' => true],        
        ['id' => 'UF_PHONE', 'name' => 'Телефон', 'type'=>'text', 'default' => true], 
        ['id' => 'UF_COMMENT', 'name' => 'Комментарий', 'type' => 'text','default' => true],
        ['id' => 'UF_DATE', 'name' => 'Дата начала сделки', 'type' => 'list', 'items' => [
            '1' => '0-7 дней',
            '2' => '0-14 дней',
            '3' => '0-30 дней',
            '4' => '0-2 мес',
            '5' => '0-6 мес',
        ], 'params' => ['multiple' => 'N'], 'default' => true],
        ['id' => 'UF_STATUS','name'=>'Статус','type'=>'text','default'=>true],
    ],
    'ENABLE_LIVE_SEARCH' => true,
    'ENABLE_LABEL' => true,
    'VALUE_REQUIRED_MODE' => true,
    'FILTER_PRESETS' => []
]);?><?
$columns = [];
$columns[] = ['id' => 'ID','type'=>'int','name' => 'ID', 'sort' => 'ID', 'title' => 'ID', 'column_sort' => 150, 'default' => true];
$columns[] = ['id' => 'EMAIL','type'=>'text','name' => 'Email', 'sort' => 'EMAIL', 'title' => 'Email', 'column_sort' => 100, 'default' => true];
$columns[] = ['id' => 'NAME','type'=>'text', 'name' => 'Название', 'sort' => 'NAME', 'title' => 'Название', 'column_sort' => 200, 'default' => true];
$columns[] = ['id' => 'PHONE', 'type'=>'text', 'name' => 'Телефон', 'sort' => 'AGE', 'title' => 'Телефон', 'column_sort' => 400, 'default' => true];
$columns[] = ['id' => 'COMMENT','type'=>'text', 'name' => 'Комментарий', 'sort' => 'CoMMENT', 'title' => 'Комментарий','column_sort' => 500, 'default' => true];
$columns[] = ['id' => 'DATE','type'=>'date', 'name' => 'Дата', 'sort' => 'DATE', 'title' => 'Дата','column_sort' => 500, 'default' => true];
$columns[] = ['id' => 'STATUS','type'=>'text', 'name' => 'Статус', 'sort' => 'STATUS', 'title' => 'Статус','column_sort' => 500, 'default' => true];
$gridParams = [
    'GRID_ID' => $arParams["GRID_ID"],
    'COLUMNS' => $columns,
    'ROWS' => $arResult["ROWS"],
    'SHOW_ROW_CHECKBOXES' => false,
    'NAV_OBJECT' => $arResult["NAV"],
    'AJAX_MODE' => 'Y',
    'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
    'PAGE_SIZES' => [
        ['NAME' => "5", 'VALUE' => '5'],
        ['NAME' => '10', 'VALUE' => '10'],
        ['NAME' => '20', 'VALUE' => '20'],
        ['NAME' => '50', 'VALUE' => '50'],
        ['NAME' => '100', 'VALUE' => '100']
    ],
    'AJAX_OPTION_JUMP' => 'N',
    'SHOW_CHECK_ALL_CHECKBOXES' => false,
    'SHOW_ROW_ACTIONS_MENU' => true,
    'SHOW_GRID_SETTINGS_MENU' => true,
    'SHOW_NAVIGATION_PANEL' => true,
    'SHOW_PAGINATION' => true,
    'SHOW_SELECTED_COUNTER' => false,
    'SHOW_TOTAL_COUNTER' => true,
    'SHOW_PAGESIZE' => true,
    'SHOW_ACTION_PANEL' => false,
    'ALLOW_COLUMNS_SORT' => true,
    'ALLOW_COLUMNS_RESIZE' => true,
    'ALLOW_HORIZONTAL_SCROLL' => true,
    'ALLOW_SORT' => false,
    'ALLOW_PIN_HEADER' => false,
    'AJAX_OPTION_HISTORY' =>'N',
    'ALLOW_INLINE_EDIT' =>false,
    'SHOW_GROUP_EDIT_BUTTON'=>true,
];?>
<?
$APPLICATION->IncludeComponent('bitrix:main.ui.grid', '.default', $gridParams);
\Bitrix\Main\UI\Extension::load("ui.dialogs.messagebox");
?>
<script>
    async  function setOrderStatus(id,quickOrderGridId){
        try{
            let data={"ID":id};
            response = await BX.ajax.runComponentAction("klbr:grid","changeOrderStatus",{mode:'class',data:data});
            console.log(quickOrderGridId);
            let gridObject = BX.Main.gridManager.getById(quickOrderGridId); // Идентификатор грида
            let reloadParams = { apply_filter: 'Y'};
            if (gridObject.hasOwnProperty('instance')){
                gridObject.instance.reloadTable('POST',reloadParams);
                }
            }
            catch(errorResult)
            {
                if(errorResult.status=="error")
                {
                    messageToDisplay = "";
                    errorResult.errors.forEach(function(errorObject){
                        messageToDisplay += " " + errorObject.message;
                    })
                    showMessage(messageToDisplay);
                    return;
                }
            }
                        
            if(response.status==="error" && response.data.status=="error")
            {
                showMessage(response.data.message);
                return;
            }
            showMessage("Данные сохранены");
    }
    function showMessage(message){
        const messageBox = new BX.UI.Dialogs.MessageBox(
            {
                message: message,
                title: "Информационное сообщение",
                buttons: BX.UI.Dialogs.MessageBoxButtons.OK,
                okCaption: "OK",
            });
            messageBox.show();
    }
    </script>