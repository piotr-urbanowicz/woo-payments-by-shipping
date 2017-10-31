jQuery(document).ready(function( $ )
{ 

  if ($('.agano').length ) {     
    $('.agano').select2();
  }

  //on modal
  $( document.body ).on( 'wc_backbone_modal_loaded', function( evt, target ) {
    if ($('.agano').length ) {
      $('.agano').select2();
    }
	});

});