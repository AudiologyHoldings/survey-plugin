var SurveyClose = Class.create({
  initialize: function(){
    this.popupId = 'popup';
    
    $$('.btn_close').each(function(elem){
      elem.observe('click', this.close.bind(this));
    }.bind(this));
  },
  
  /**
    * Close the popup
    */
  close: function(){
    $(this.popupId).hide();
  }
});