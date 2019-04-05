jQuery(document).ready(function($) {

	  if(typeof google !== typeof undefined){
		  
		    var autocomplete;
            function initialize() {
              autocomplete = new google.maps.places.Autocomplete(
                  (document.getElementById('wdap_store_address')),
                  { types: ['geocode'] });
              google.maps.event.addListener(autocomplete, 'place_changed', function() {
				  
				  var place = autocomplete.getPlace();
				  if (!place.geometry) {
					  window.alert("No details available for input: '" + place.name + "'");
					  return;
				  }else{
					  placename = place.name+', '+place.formatted_address;  
					
					//Get postal code / zipcode
					for (var i = 0; i < place.address_components.length; i++) {
					 	
					  for (var j = 0; j < place.address_components[i].types.length; j++) {
						
						if (place.address_components[i].types[j] == "postal_code") {
							place_zipcode = place.address_components[i].long_name;
						}
						if (place.address_components[i].types[j] == "country") {
							place_country_name = place.address_components[i].short_name;
						}
						
					  }
					}
					
					var address = {lat : place.geometry.location.lat(),
								   lng : place.geometry.location.lng(),
								   placename : $('#wdap_store_address').val()};
					
					if(typeof place_zipcode != typeof undefined){
					   autosuggest_zip_obj = {placezipcode : place_zipcode};
					   $.extend(address, autosuggest_zip_obj);
				    }
				    if(typeof place_country_name != typeof undefined){
					   place_country_name_obj = {place_country_name : place_country_name};
					   $.extend(address, place_country_name_obj);
				    }			   
					$('#store_address_json').val(JSON.stringify(address));
					
				  }
              });
            }
            initialize();
                        
		  
	  }
	  			
	  if(jQuery('.form_product_list').length>0)
       $(".form_product_list").select2();

	if(jQuery('.wdap_select_collections').length>0)

    $(".wdap_select_collections").select2({
		  placeholder: "Select Collections",
		  allowClear: true
		});

    $(".check_availability").click(function(event){
      event.preventDefault();
    });

	var myOptions = {
      defaultColor: false,
      change: function(event, ui){

      	var theColor = ui.color.toString();
      	if(this.id == "form_button_color"){
			$(".wdap_product_availity_form button").css("color",theColor);
      	}
      	if(this.id == "form_button_bgcolor"){
      		$(".wdap_product_availity_form button").css("background",theColor);
      	}

      },
      clear: function() {},
      hide: true,
      palettes: true
  };
  if($(".scolor").length>0)
   $('.scolor').wpColorPicker(myOptions);
   $('#pac-input, #wdap_store_address').keypress(function (e) {
		 var key = e.which;
		 if(key == 13)  // the enter key code
			return false;

	});
	$('.delete').click(function(event){
		 if (confirm(errormessage.deleltemessage))
             return true;

        return false;
	});

	$('.my-color-field').hide();

	/*terget form*/

	$('.switch_onoffs').change(function() {
        var target = $(this).data('target');
        if ($(this).attr('type') == 'radio') {
          if ( ($(this).is(":checked")) && ($(this).val()=="Selected Products") || ($(this).val()=="redirect_url") || ($(this).val()=="selected_categories")     ) {
          	if($(this).val()=='selected_categories'){
          	$(target).closest('.fc-form-group ').hide();
          	$('.wdappage_listing_selected_categories').parent().parent('.fc-form-group ').show();
          	}else{
          	$('.wdappage_listing_selected_categories').parent().parent('.fc-form-group ').hide();
              $(target).closest('.fc-form-group ').show();
            }

          } else {
              $(target).closest('.fc-form-group ').hide();
              if ($(target).hasClass('switch_onoffs')) {
                  $(target).attr('checked', false);
                  $(target).trigger("change");
              }
          }
        }
	});
    $.each($('.switch_onoffs'), function(index, element) {
        if (true == $(this).is(":checked")) {
            $(this).trigger("change");
        }
    });
    
    $(".cancel_import").click(function() {
        var wdap_bid = confirm("Do you want to cancel import process?.");
        if (wdap_bid == true) {
            $(this).closest("form").find("input[name='operation']").val("cancel_import");
            $(this).closest("form").submit();
            return true;
        } else {
            return false;
        }
    });


    $(".wdap_check_backup").click(function() {
        var wdap_bid = confirm("Import woocommerce delivery area pro collection database from import file and delete all existing collections ?");
        if (wdap_bid ) {
            var bkid = $(this).data("backup");
            $(this).closest("form").find("input[name='row_id']").val(bkid);
            $(this).closest("form").find("input[name='operation']").val("import_backup");
            $(this).closest("form").submit();
            return true;
        } else {
            return false;
        }
    });
    

});

