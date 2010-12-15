<?php
/* SurveyAnswer Fixture generated on: 2010-07-27 22:07:40 : 1280292760 */
class SurveyAnswerFixture extends CakeTestFixture {
	var $name = 'SurveyAnswer';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'question' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'answer' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'question' => '1_age',
			'answer' => '80plus',
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 2,
			'question' => '2_likely_to_schedule',
			'answer' => '6',
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 3,
			'question' => '3_visit_clinic',
			'answer' => 'Yes',
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 4,
			'question' => '4_purchase_hearing_aid',
			'answer' => 'Yes',
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 5,
			'question' => '5_what_brand',
			'answer' => 'Oticon',
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 6,
			'question' => '1_age',
			'answer' => '80plus',
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 7,
			'question' => '2_likely_to_schedule',
			'answer' => '6',
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 8,
			'question' => '1_age',
			'answer' => '80plus',
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 9,
			'question' => '2_likely_to_schedule',
			'answer' => '3',
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 10,
			'question' => '3_visit_clinic',
			'answer' => 'No',
			'created' => '2010-09-27 22:52:40'
		),
		array(
			'id' => 11,
			'question' => '1_age',
			'answer' => '80plus',
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 12,
			'question' => '2_likely_to_schedule',
			'answer' => '6',
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 13,
			'question' => '1_age',
			'answer' => '50-59',
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 14,
			'question' => '2_likely_to_schedule',
			'answer' => '6',
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 15,
			'question' => '3_visit_clinic',
			'answer' => 'Yes',
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 16,
			'question' => '4_purchase_hearing_aid',
			'answer' => 'Yes',
			'created' => '2010-07-27 22:52:40'
		),
		array(
			'id' => 17,
			'question' => '5_what_brand',
			'answer' => 'Other',
			'created' => '2010-07-27 22:52:40'
		),
	);
}
?>