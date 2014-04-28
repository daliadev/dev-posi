/*
 * Use jQuery v1.10.2 | (c) 2013
 */
$(function() {


  $( "#main-form #ref_organ_cbox" ).change(function() {
  
    if ( $( this ).val() === "new" ) {
      $( "#second-form" ).show();
    }
    else {
      $( "#second-form" ).hide();
    }

  });


});
