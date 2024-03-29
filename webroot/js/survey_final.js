/**
  * SurveyFinal is a Prototype JS object that will handle the final survey
  * page, navigating a user through the questions and submitting the form
  * upon completion.
  */
var SurveyFinal = Class.create({
  /**
    * Constructor, setup globals and observe all the radio inputs
    * @param email of the contact needed for any ajax calls
    */
  initialize: function(email){
    //globals
    this.email = email;
    this.form = 'final_form';
    this.pageIds = ['one','two','three','resend','thanks'];    
    this.decisionTree = new Hash({
      question_1: new Hash({
        Yes: 'two',
        Appointment: 'resend',
        No: 'thanks'
      }),
      question_2: new Hash({
        Yes: 'three',
        No: 'thanks'
      }),
      question_3: new Hash({
        Oticon: 'thanks',
        Beltone: 'thanks',
        Phonak: 'thanks',
        MiracleEar: 'thanks',
        Other: 'thanks'
      })
    });
    
    $$('input[type=radio]').each(function(elem){
      elem.observe('click', this.decideNext.bindAsEventListener(this));
    }.bind(this));
  },
  
  /**
    * Decide the next step based on the question and answer
    * this is decided by the decisiontree defined in the init
    *
    * @param event
    * @return void
    */
  decideNext: function(event){
    this.elem = Event.element(event);
    var question = this.elem.readAttribute('class');
    var page_id = this.decisionTree.get(question).get(this.elem.value);
    
    if(question == 'question_3'){
      if(this.elem.value == 'Other'){
        this.appendOtherForm()
      }
      else {
        this.convertOtherFormToLabel()
      }
    }
    
    this.pageCall(page_id);
    this.showPageFromDecision(question, page_id);
  },
  
  /**
    * Convert the Other label into an input field
    */ 
  appendOtherForm: function(){
    var input = $('SurveyAnswer2AnswerOther');
    var label = input.next();
    label.replace("<input id='CustomOther' type='text' class='question_3' name='data[SurveyAnswer][2][answer]'/><label id='customlabel'>Please type in the brand of hearing aid.</label><span id='customerror'></span>");
    $('CustomOther').select();
  },
  
  /**
    * Convert the Other Form back into a label.
    */
  convertOtherFormToLabel: function(){
    var input = $('SurveyAnswer2AnswerOther');
    var label = input.next();
    label.replace("<label for='SurveyAnswer2AnswerOther'>Other</label>");
    if($('customerror')){
      $('customerror').update();
    }
    if($('customlabel')){
      $('customlabel').remove();
    }
  },
  
  /**
    * Make any ajax calls, or form submits depending 
    * on page_id we're viewing
    *
    * @param page_id to show next
    * @return void
    */
  pageCall: function(page_id){
    if(page_id == 'resend'){
      var url = '/resend_survey/' + this.email
      new Ajax.Request(url, {
          method: 'get'
      });
    }
    else if(page_id == 'thanks'){
      //$('final_form').submit(); //Disable autosubmit by BC ticket.
    }
  },
  
  /**
    * Show the next page depending on the quesiton and decision made
    * previously in decideNext function
    *
    * @param question 
    * @param page_id to show
    * @return void
    */
  showPageFromDecision: function(question, page_id){
    var tohide = this.decisionTree.get(question).values();
    tohide.each(function(id){
        if(id != page_id){
          $(id).hide();
        }
    });
    $(page_id).show();
  },
  
  /**
    * Goto a specific page
    * this is useful for an "inline" feel each answer is removed
    * from sight when and the next question prompted.
    *
    * @param string id of page to show
    * @return void
    */
  page: function(page_id){
    this.pageIds.each(function(id){
        if(id != page_id){
          $(id).hide();
        }
    });
    $(page_id).show();
  },
  
  /**
    * Validate the customer other is entered if other is selected.
    */
  submitForm: function(){
    var purchase = $('SurveyAnswer1AnswerYes');
    //Only proceed if we have a purchase.
    if(purchase.value == 'Yes'){
      var input = $('CustomOther');
      if(input && input.value.empty()){
        var error = "<br />Please fill in this question.";
        $('customerror').update(error);
        return false;
      }
    }
    
    return true;
  }
});