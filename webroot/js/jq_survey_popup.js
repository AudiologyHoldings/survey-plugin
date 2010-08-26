var SurveyPopup = Class.create({
  init: function(start_page){
    //globals
    this.pageIds = ['one','two'];
    this.popupId = '#survey_popup_wrapper';
    
    jQuery('.btn_close').click(jQuery.proxy(this.close, this));
    jQuery('#btn_continue').click(jQuery.proxy(this.nextPage, this));
    
    if(start_page != undefined){
      this.page(start_page);
    }
  },
  
  /**
    * Close the popup
    */
  close: function(){
    jQuery(this.popupId).hide();
  },
  
  /**
    * Goto the next page
    */
  nextPage: function(){
    this.page('two');
  },
  
  /**
    * Goto a specific page
    * make sure not to display any other of the listed pages
    * @param string id of page to show
    * @return void
    */
  page: function(page_id){
    jQuery.each(this.pageIds, function(index, id){
        if(id != page_id){
          jQuery('#' + id).hide();
        }
    });
    jQuery('#' + page_id).show();
  }
});