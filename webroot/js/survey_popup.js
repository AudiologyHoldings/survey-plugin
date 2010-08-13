var SurveyPopup = Class.create({
  initialize: function(start_page){
    //globals
    this.pageIds = ['one','two'];
    this.popupId = 'popup';
    
    $('btn_continue').observe('click', this.page.bind(this, 'two'));
    $$('.btn_close').each(function(elem){
      elem.observe('click', this.close.bind(this));
    }.bind(this));
    
    if(start_page != undefined){
      this.page(start_page);
    }
  },
  
  /**
    * Close the popup
    */
  close: function(){
    $(this.popupId).hide();
  },
  
  /**
    * Goto a specific page
    * make sure not to display any other of the listed pages
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