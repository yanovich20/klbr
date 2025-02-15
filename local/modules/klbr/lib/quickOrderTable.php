<?php 
namespace KLBR;

class QuickOrderTable extends \Bitrix\Main\Entity\DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'quick_order';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return array(
			'ID' => array(
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true,
				'title' => "ID",
			),
			'UF_NAME' => array(
				'data_type' => 'text',
				'title' => 'Название'
			),
			'UF_PHONE' => array(
				'data_type' => 'text',
				'title' => 'Телефон',
			),
            'UF_EMAIL'=>array(
                'data_type' => 'text',
				'title' => 'email',
            ),
            'UF_COMMENT'=>array(
                'data_type' => 'text',
				'title' => 'Комментарий',
                )
		);
	}
}