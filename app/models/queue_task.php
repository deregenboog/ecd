<?php

class QueueTask extends AppModel
{
	public $name = 'QueueTask';
	public $displayField = 'action';

	public $hasMany = array(
		'Document' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
					'Document.model' => 'QueueTask',
					'is_active' => 1,
			),
			'dependent' => true,
			'order' => 'created desc',
		),
	);

	public function beforeSave($options = array())
	{
		if (isset($this->data['QueueTask']['data'])) {
			$this->data['QueueTask']['data'] =
				json_encode($this->data['QueueTask']['data']);
		}

		if (isset($this->data['QueueTask']['output'])) {
			$this->data['QueueTask']['output'] =
				json_encode($this->data['QueueTask']['output']);
		}

		return parent::beforeSave($options);
	}

	public function afterFind($results, $primary)
	{

		foreach ($results as $key => $data) {
			if (is_array($data) && isset($data['QueueTask']['id'])) {
				if (!empty($data['QueueTask']['data'])) {
					$results[$key]['QueueTask']['data'] =
					json_decode($data['QueueTask']['data'], true);
				} else {
					$results[$key]['QueueTask']['data'] = array();
				}

				if (!empty($data['QueueTask']['output']) && !empty($results[$key]['QueueTask']['data']['task'])) {
					$results[$key]['QueueTask']['output'] =
					json_decode($data['QueueTask']['output'], true);
				} else {
					$results[$key]['QueueTask']['output'] = array();
				}
			}
		}
		
		return $results;
	}
}
