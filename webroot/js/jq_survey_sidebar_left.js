var SurveySidebar = jQuery.Class.create({
  initialize: function(){
    //globals
    this.popupId = '#survey_popup_wrapper';
    this.sidebarId = '#survey_sidebar';
    this.sidebarBodyId = '#survey_sidebar_body';
    
    jQuery('#close_popup').click(jQuery.proxy(this.closePopup, this));
    jQuery('#survey_sidebar_button').click(jQuery.proxy(this.toggleSidebar, this));
  },
  
  closePopup: function(){
    jQuery(this.popupId).hide();
    jQuery(this.sidebarId).show();
  },
  
  toggleSidebar: function(){
    jQuery(this.sidebarBodyId).toggle();
  }
});