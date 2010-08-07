var SurveyFinal = Class.create({
  initialize: function(){
    //globals
    this.endIds = ['resend','thanks'];
    this.pageIds = ['one','two','three'];
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
    
    //this.btn_continue = $('btn_continue');
    //this.btn_continue.observe('click', this.page.bind(this, 'two'));
    $$('input[type=radio]').each(function(elem){
      elem.observe('click', this.decideNext.bindAsEventListener(this));
    }.bind(this));
  },
  
  /**
    *
    */
  decideNext: function(event){
    this.elem = Event.element(event);
    var question = this.elem.readAttribute('class');
    var page_id = this.decisionTree.get(question).get(this.elem.value);
    this.page(page_id);
  },
  
  /**
    * Goto a specific page
    * make sure not to display any other of the listed pages
    * @param string id of page to show
    * @return void
    */
  page: function(page_id){
    this.endIds.each(function(id){
        if(id != page_id){
          $(id).hide();
        }
    });
    $(page_id).show();
  }
});