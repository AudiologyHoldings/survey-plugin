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
    this.pageCall(page_id);
    this.showPageFromDecision(question, page_id);
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
      $('final_form').submit();
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
  }
});