var SurveyPopup = jQuery.Class.create({
  initialize: function(start_page){
    //globals
    this.pageIds = ['one','two'];
    this.popupId = '#survey_popup_wrapper';
    
    jQuery('.btn_close').click(jQuery.proxy(this.close, this));
    jQuery('#btn_continue').click(jQuery.proxy(this.nextPage, this));
    jQuery('#btn_submit').click(jQuery.proxy(this.submitForm, this));
    jQuery('#SurveyContactEmail').keypress(jQuery.proxy(this.checkForEnter, this));
    
    if(start_page != undefined){
      this.page(start_page);
    }
  },
  
  /**
    * Submit the form via Ajax
    */
  submitForm: function(){
    jQuery.ajax({
        data:jQuery("#btn_submit").closest("form").serialize(), 
        dataType:"text", 
        success: jQuery.proxy(this.handleRequest, this), 
        type:"post", 
        url:"\/survey"
      });
    return false;
  },
  
  /**
    * Handle the form submit return
    *
    * @param mixed data returned by ajax call
    * @param textStatus the success/failure of request
    * @return void
    */
  handleRequest: function(data, textStatus){
    if(data == 1){
      this.thanksPage();
    }
    else {
      this.showError(data);
    }
  },
  
  /**
    * Handle the keypress, check for enter, if enter is found submit the form
    * @param event
    */
  checkForEnter: function(event){
    if(event.keyCode == '13'){
      this.submitForm();
    }
  },
  
  /**
    * show the error
    * @param string error string to show
    * @return void
    */
  showError: function(error){
    jQuery('#EmailError')
      .empty()
      .append(error)
      .show();
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
    * Show thanks page.
    */
  thanksPage: function(){
    jQuery('#survey').hide();
    jQuery('#thanks').show();
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