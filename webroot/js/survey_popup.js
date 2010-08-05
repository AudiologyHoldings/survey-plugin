var SurveyPopup = Class.create({
  initialize: function(){
    //globals
    this.pageIds = ['one','two'];
    this.btn_continue = $('btn_continue');
    
    this.btn_continue.observe('click', this.page.bind(this, 'two'));
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