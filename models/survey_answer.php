<?php
class SurveyAnswer extends SurveyAppModel {
	var $name = 'SurveyAnswer';
	var $displayField = 'question';
	var $validate = array(
		'question' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Question key must not be empty.',
				'required' => true,
			),
		),
		'answer' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Answer is required.',
				'required' => true
			),
		),
	);
	
	var $belongsTo = array(
		'SurveyContact' => array(
			'className' => 'Survey.SurveyContact',
			'foreignKey' => 'survey_contact_id',
		)
	);
	
	/**
		* Save the data, set validation to false
		* @param array data
		* @param array of options
		* @return boolean of success
		*/
	function saveData($data, $options = array()){
	  //Because we need the ids inserted we have to itterate throught them one at a time
		return $this->saveAll($data, $options);
	}
	
	/**
		* Clear the blank answers from the data array
		*/
	function clearEmpty($data){
		foreach($data as $key => $answer){
	    if(!$answer['answer']){
	      unset($data[$key]);
	    }
	  }
	  return $data;
	}
	
	/**
	  * Get the contact and answers of the final contact data organized for csv
	  */
	function exportFinal(){
	  $headers = array(
	    'Survey' => array(
	      'first_name' => 'first_name',
	      'last_name' => 'last_name',
	      'zip' => 'zip',
	      'email' => 'email',   
	      'created' => 'created',
	      '1_age' => '1_age',
	      '2_likely_to_schedule' => '2_likely_to_schedule',
	    )
	  );
	  
	  $answers = $this->find('all', array(
	    'conditions' => array(
	    	'OR' => array(
	    		$this->getIgnoreConditions(),
	    		array('SurveyAnswer.survey_contact_id' => '0')
	    	),
	    	'AND' => array(
	    		'OR' => array(
	    			array('SurveyAnswer.question' => '1_age'),
	    			array('SurveyAnswer.question' => '2_likely_to_schedule')
	    		)
	    	)
	    ),
	    'contain' => array('SurveyContact')
	  ));
	  	  
	  $data = array($headers);
	  
	  for($i = 0; $i<count($answers); $i += 2){
	  	//if the answer is one of the first two, otherwise skip it entirely.
	  	if($answers[$i]['SurveyAnswer']['question'] == '1_age' || $answers[$i]['SurveyAnswer']['question'] == '2_likely_to_shcedule'){
				$y = $i + 1;
				$row = array(
					'Survey' => array(
						'first_name' => '',
						'last_name' => '',
						'zip' => '',
						'email' => '',
						'created' => '',
						'1_age' => '',
						'2_likely_to_schedule' => '',
					)
				);
				if(!empty($answers[$i]['SurveyContact']['first_name'])){
					$row['Survey']['first_name'] = $answers[$i]['SurveyContact']['first_name'];
					$row['Survey']['last_name'] = $answers[$i]['SurveyContact']['last_name'];
					$row['Survey']['zip'] = $answers[$i]['SurveyContact']['zip'];
					$row['Survey']['email'] = $answers[$i]['SurveyContact']['email'];
				}
				$row['Survey']['created'] = $answers[$i]['SurveyAnswer']['created'];
				$row['Survey'][$answers[$i]['SurveyAnswer']['question']] = $answers[$i]['SurveyAnswer']['answer'];
				@$row['Survey'][$answers[$y]['SurveyAnswer']['question']] = $answers[$y]['SurveyAnswer']['answer'];
				$data[] = $row;
	  	}
	  }
	  
	  return $data;
	}
	
	/**
	  * Find the report based on data based in
	  *
	  * @param array of data to base report on
	  * - start_month (month to base report on)
	  * - end_month (month to base report on)
	  * - page_views (total page views that month)
	  * @return array of results formatted for easy viewing.
	  */
	function findReport($data = array()){
	  $start_date = $this->str2datetime($data[$this->alias]['start_month']);
	  $end_date = $this->str2datetime($data[$this->alias]['end_month'], true);
	  $page_views = str_replace(",","",$data[$this->alias]['page_views']);
	  
	  $email_conditions = $this->getIgnoreConditions();
	  
	  $conditions = array(
	    "created >=" => $start_date,
	    "created <=" => $end_date,
	  );
	  
	  //Not all answers have a contact, so we must pull them in separately.
	  $answers = $this->find('all', array(
	    'conditions' => array(
	      'OR' => array(
	        array( //answers that don't have an email
	          'SurveyAnswer.created >=' => $start_date,
	          'SurveyAnswer.created <=' => $end_date,
	        )
	      )
	    ),
	    'contain' => array()
	  ));
	  
	  $opt_ins = ClassRegistry::init('Survey.SurveyOptIn')->find('count', array(
	    'conditions' => $conditions,
	    'recursive' => -1
	  ));
	  
	  $continue_clicks = ClassRegistry::init('Survey.SurveyParticipant')->find('count', array(
	    'conditions' => $conditions,
	    'recursive' => -1
	  ));
	  
	  $retval = array(
	    'total' => array(
        'opt_in' => 0,
        'continue' => 0,
	    ),
	    'percent' => array(
        'opt_in' => 0,
        'continue' => 0,
	    ),
	    'age_range' => array(
	      'under-18' => 0,
	      '18-39' => 0,
	      '40-49' => 0,
	      '50-59' => 0,
	      '60-69' => 0,
	      '70-79' => 0,
	      '80plus' => 0,
	    ),
	    'likely' => array(
	      '0' => 0,
	      '1' => 0,
	      '2' => 0,
	      '3' => 0,
	      '4' => 0,
	      '5' => 0,
	      '6' => 0,
	    )
	  );
	  
	  //Totals
	  $retval['total']['opt_in'] = $opt_ins;
	  $retval['total']['continue'] = $continue_clicks;

	  //Percents
	  $retval['percent']['opt_in'] = $this->__calculatePercent($retval['total']['opt_in'], $page_views);
	  $retval['percent']['continue'] = $this->__calculatePercent($retval['total']['continue'], $retval['total']['opt_in']);

	  //Age Range
	  $retval['age_range']['under-18'] = count(Set::extract('/SurveyAnswer[answer=under-18]', $answers));
	  $retval['age_range']['18-39'] = count(Set::extract('/SurveyAnswer[answer=18-39]', $answers));
	  $retval['age_range']['40-49'] = count(Set::extract('/SurveyAnswer[answer=40-49]', $answers));
	  $retval['age_range']['50-59'] = count(Set::extract('/SurveyAnswer[answer=50-59]', $answers));
	  $retval['age_range']['60-69'] = count(Set::extract('/SurveyAnswer[answer=60-69]', $answers));
	  $retval['age_range']['70-79'] = count(Set::extract('/SurveyAnswer[answer=70-79]', $answers));
	  $retval['age_range']['80plus'] = count(Set::extract('/SurveyAnswer[answer=80plus]', $answers));
	  $retval['age_range']['total'] = 0;
	  foreach($retval['age_range'] as $value){
	    $retval['age_range']['total'] += $value;
	  }
	  
	  //Likely
	  $retval['likely']['0'] = count(Set::extract('/SurveyAnswer[question=2_likely_to_schedule][answer=0]', $answers));
	  $retval['likely']['1'] = count(Set::extract('/SurveyAnswer[question=2_likely_to_schedule][answer=1]', $answers));
	  $retval['likely']['2'] = count(Set::extract('/SurveyAnswer[question=2_likely_to_schedule][answer=2]', $answers));
	  $retval['likely']['3'] = count(Set::extract('/SurveyAnswer[question=2_likely_to_schedule][answer=3]', $answers));
	  $retval['likely']['4'] = count(Set::extract('/SurveyAnswer[question=2_likely_to_schedule][answer=4]', $answers));
	  $retval['likely']['5'] = count(Set::extract('/SurveyAnswer[question=2_likely_to_schedule][answer=5]', $answers));
	  $retval['likely']['6'] = count(Set::extract('/SurveyAnswer[question=2_likely_to_schedule][answer=6]', $answers));
	  $retval['likely']['total'] = 0;
	  foreach($retval['likely'] as $value){
	    $retval['likely']['total'] += $value;
	  }
	  	  
	  return $retval;
	}
	
	/**
	  * Calculate the percentage of two numbers to the 2nd degree
	  * @param int numerator
	  * @param int denominator
	  * @return string percentage
	  */
	function __calculatePercent($num, $denom = 100){
	  return (!$denom) ? "0%" : round($num / $denom, 4) * 100 . '%';
	}
	
	/**
	  * Fix the import
	  */
	function fixImport(){
	  $answers = $this->find('all', array(
	    'contain' => array(
	      'SurveyContact' => array('fields' => array('id','email'))
	    )
	  ));
	  foreach($answers as $answer){
	    $this->id = $answer['SurveyAnswer']['id'];
	    
	    if(empty($answer['SurveyContact']['email']) && $answer['SurveyAnswer']['survey_contact_id']){
        if(!empty($answer['SurveyContact']['id'])){
          $this->SurveyContact->delete($answer['SurveyContact']['id'], false);
        }
        $this->saveField('survey_contact_id', false);
	    }
	    if($answer['SurveyAnswer']['question'] == '1_age'){
	      switch(strtolower(substr(strip_tags($answer['SurveyAnswer']['answer']), 0, 1))){
	        case 'u': $this->saveField('answer', 'under-18'); break;
	        case '1': 
	        case '2': 
	        case '3': $this->saveField('answer', '18-39'); break;
	        case '4': $this->saveField('answer', '40-49'); break;
	        case '5': $this->saveField('answer', '50-59'); break;
	        case '6': $this->saveField('answer', '60-69'); break;
	        case '7': $this->saveField('answer', '70-79'); break;
	        default : $this->saveField('answer', '80plus'); break;
	      }
	    }
	    if($answer['SurveyAnswer']['question'] == '2_likely_to_schedule'){
	      $this->saveField('answer', strip_tags($answer['SurveyAnswer']['answer']));
	    }
	  }
	}
	
	/**
	  * Fix the import data from before September.
	  * This is due to the fact the likely scale was from 1-7
	  * and now it's between 0-6.
	  * @see https://audiologyonline.basecamphq.com/projects/4564701/todo_items/68522763/comments
	  */
	function fixLikelyVisit(){
	  $answers = $this->find('all', array(
	    'conditions' => array(
	      'created <' => '2010-09-01 00:00:00',
	      'question' => '2_likely_to_schedule'
	    ),
	    'recursive' => -1
	  ));
	  foreach($answers as $answer){
	    $this->id = $answer['SurveyAnswer']['id'];
	    $this->saveField('answer', $answer['SurveyAnswer']['answer'] - 1);
	  }
	}
}
?>