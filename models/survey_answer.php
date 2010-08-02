<?php
class SurveyAnswer extends SurveyAppModel {
	var $name = 'SurveyAnswer';
	var $displayField = 'question';
	var $validate = array(
		'question' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Question key must not be empty.',
			),
		),
		'answer' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Answer is required.',
			),
		),
	);
	
	var $belongsTo = array(
		'SurveyContact' => array(
			'className' => 'Survey.SurveyContact',
			'foreignKey' => 'survey_contact_id',
		)
	);
}
?>