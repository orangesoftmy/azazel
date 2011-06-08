// $Id: tao.js,v 1.1.2.1 2010/09/28 19:39:45 yhahn Exp $

Drupal.behaviors.tao = function (context) {
  $('fieldset.collapsible:not(.tao-processed) > legend > .fieldset-title').each(function() {
    var fieldset = $(this).parents('fieldset').eq(0);
    fieldset.addClass('tao-processed');

    // Expand any fieldsets containing errors.
    if ($('input.error, textarea.error, select.error', fieldset).size() > 0) {
      $(fieldset).removeClass('collapsed');
    }

    // Add a click handler for toggling fieldset state.
    $(this).click(function() {
      if (fieldset.is('.collapsed')) {
        $(fieldset).removeClass('collapsed').children('.fieldset-content').show();
      }
      else {
        $(fieldset).addClass('collapsed').children('.fieldset-content').hide();
      }
      return false;
    });
  });
  
  if (Drupal.settings.dropdown == 1){
	  Drupal.azazel_dropdown = new Drupal.azazel_dropdown(Drupal.settings.options);
  }
  

};

Drupal.azazel_dropdown = function(options){
  $('#navigation .block-content > ul').addClass('sf-menu');
  $('ul.sf-menu').sooperfish({
    dualColumn  : options['2_col'], 
    tripleColumn  : options['3_col'], 
    hoverClass  : 'sfHover',
    delay    : options['delay'], 
    animationShow  : {height:'show',opacity:'show'},
    speedShow    : options['speed_show'],
    //easingShow      : 'easeOutBounce',
    animationHide  : {height:'hide',opacity:'hide'},
    speedHide    : options['speed_hide'],
    //easingHide      : 'easeInOvershoot',
    autoArrows  : false
  });
}