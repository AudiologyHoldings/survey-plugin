var SurveyFinal = Class.create({
  initialize: function(){
    //globals
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
    * if the page_id is resend, run an ajax save to mark the contact
    * as a resend in 30 days.
    *
    * @param event
    * @return void
    */
  decideNext: function(event){
    this.elem = Event.element(event);
    var question = this.elem.readAttribute('class');
    var page_id = this.decisionTree.get(question).get(this.elem.value);
    this.showPageFromDecision(question, page_id);
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