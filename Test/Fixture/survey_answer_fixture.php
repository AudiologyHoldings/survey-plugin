<?php
/* SurveyAnswer Fixture generated on: 2010-07-27 22:07:40 : 1280292760 */
class SurveyAnswerFixture extends CakeTestFixture {
	var $name = 'SurveyAnswer';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),		
		'question' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'answer' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'survey_contact_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'question' => '1_age',
			'answer' => '80plus',
			'survey_contact_id' => '0',
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 2,
			'question' => '2_likely_to_schedule',
			'answer' => '6',
			'survey_contact_id' => '0',
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 6,
			'question' => '1_age',
			'answer' => '80plus',
			'survey_contact_id' => '0',
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 7,
			'question' => '2_likely_to_schedule',
			'answer' => '6',
			'survey_contact_id' => '0',
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 8,
			'question' => '1_age',
			'answer' => '80plus',
			'survey_contact_id' => '0',
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 9,
			'question' => '2_likely_to_schedule',
			'answer' => '3',
			'survey_contact_id' => '0',
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 10,
			'question' => '3_visited_clinic', //This answer should be ignored on report
			'answer' => 'No',
			'survey_contact_id' => '0',
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 11,
			'question' => '1_age',
			'answer' => '80plus',
			'survey_contact_id' => '4', //igmored
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 12,
			'question' => '2_likely_to_schedule',
			'answer' => '6',
			'survey_contact_id' => '4', //igmored
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 13,
			'question' => '1_age',
			'answer' => '50-59',
			'survey_contact_id' => '2',
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 14,
			'question' => '2_likely_to_schedule',
			'answer' => '6',
			'survey_contact_id' => '2',
			'created' => '2010-07-27 22:52:40'
		),
	);
}
?>