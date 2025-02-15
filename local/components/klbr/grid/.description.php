<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => "Грид отображения таблицы быстрых заказов",
    "DESCRIPTION" => "Компонент отображения быстрых заказов",
	"PATH" => array(
        "NAME"=>"Компонент отображения быстрых заказов",
		"ID" => "quick.orders",
		"CHILD" => array(
			"ID" => "quick.orders",
			"NAME" => "Быстрые заказы"
		)
	),
);
?>