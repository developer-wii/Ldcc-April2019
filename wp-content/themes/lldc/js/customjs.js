var getUrl = window.location;
//var baseUrl = window.location.host;
var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1]; 


	/* $(".navbar-toggle").toggle(
            function() {
                $('.navbar-collapse').slideDown();
            },
            function() {
                $('.navbar-collapse').slideUp();

            });
        
        $("body").click(function() {
            $('.navbar-collapse').slideUp();
        });
 */
    /* Add/remove active class to show current active tab in product category nav menu code start*/
        $(".nav-tabs li a").click(function() {
            //alert('test');
            $(".nav-tabs li").removeClass('active');
            $(this).parent().addClass('active');
            var href = $(this).attr('href');
            $(".tab-pane, .fade").removeClass('in active');
            $(href).addClass('in active');
        });

     /* Add/remove active class to show current active tab in product category nav menu code end*/
	 
        /* $(document).ready(function() {
            $("button").click(function() {
                $("form").submit();
            });
        }); */
        //$('.user_bsket').load(document.URL +  ' .user_bsket');
    
/* Order button */
$(document).ready(function() {
    
    jQuery(window).load(function() {
		// Animate loader off screen
		jQuery(".se-pre-con").fadeOut("slow");
	});	
	
	//jQuery.scrollSpeed(100, 800);
    
    
	$('#order_button').click(function(){
var url = '/wp-admin/admin-ajax.php'; 

		var postcode_order=$('#postcode_order').val();		
		var hidden_locations=$('#hidden_locations').val();
		var hidden_locations1=$('#hidden_locations').val().toLowerCase();		

		var total = hidden_locations+','+hidden_locations1;

		var numbersArray = hidden_locations.split(',');
		var length=postcode_order.length;
			var variable2 = postcode_order.substring(0, 2);
				var num=total.indexOf(variable2) ;

		//if($.inArray(variable2, numbersArray) !== -1){
		if(postcode_order==''){
			$('#text_orderlink').text("Sorry, we don't cover your postcode yet!");
			return false;
		}	
		if(num !== -1){
			$('#text_orderlink').text('');
			 $.ajax({
                type: 'POST',		
                url: url,
                data: {
                    action: 'store_zipcode',
					postcode:postcode_order,
                },  
                success: function(data) { 
					window.location.href='/order';
				}
				});
                } 
				else {
					$('#text_orderlink').text("Sorry, we don't cover your postcode yet!");
				} 
			}); 
         }); 

/* onclick of checkbox Add/ Remove product code start */  

/* onclick of checkbox Add/ Remove product code end */  

/* Increase /decrease quantity on click of plus/minus icon in order page code start*/
jQuery(document).ready(function($){
    
    // scroll overflow if list elemnt more then 4
    var $list = $('.servicecover_in ul li');
    if($list.length > 4){
       $('.overflow_scroll').css({"height":"307px","overflow-y":"scroll"});
    }
    
    $(document).on('click', '.plus', function(e) { // replace '.quantity' with document (without single quote)
        $input = $(this).prev('input.qty');
        var val = parseInt($input.val());
        var step = $input.attr('step');
        step = 'undefined' !== typeof(step) ? parseInt(step) : 1;
        $input.val( val + step ).change();
    });
    $(document).on('click', '.minus',  // replace '.quantity' with document (without single quote)
        function(e) {
        $input = $(this).next('input.qty');
        var val = parseInt($input.val());
        var step = $input.attr('step');
        step = 'undefined' !== typeof(step) ? parseInt(step) : 1;
        if (val > 0) {
            $input.val( val - step ).change();
        } 
    });
    
 });
/* Increase /decrease quantity on click of plus/minus icon in order page code end*/

/* Rate change on increasing / decreasing quantity code start */
jQuery( function( $ ) {

    $('.add_to_cart_custom_buton_ajax').on('click',function(e){
        e.preventDefault();
        var product_id =  $(this).val();        
        var protocol = location.protocol;
        var slashes = protocol.concat("//");
        var host = slashes.concat(window.location.hostname);
        var ajaxurl = host + "/wp-admin/admin-ajax.php";
        $.ajax({
            method: "POST",
            url: ajaxurl,
            data: {
                'action': 'cusotm_add_to_cart',
                'product_id' : product_id
            },
            success: function (res) {
              //window.location.href = $redirect;
            //   alert(res);
            //  console.log(res);
            //   location.reload(true);               
            $('html,body').animate({
                scrollTop: $(".wcn").offset().top - 100
            }, 'slow');
            setTimeout(() => {
                $('.wcn').html('<div class="woocommerce-message" role="alert"><a href="https://londondrycleaningcompany.com/cart/" tabindex="1" class="button wc-forward">View cart</a> “' + res + '” has been added to your cart.	</div>');
            }, 1000);
            
            

            }
        });

    });

    $( document ).on( 'change', '.customcart input.qty', function() {  
		var url = '/wp-admin/admin-ajax.php';
        $('.loader_order').show();
        var item_hash = $( this ).attr( 'name' ).replace(/cart\[([\w]+)\]\[qty\]/g, "$1"); 
		var datepicker=$('#datepicker').val();
        var item_quantity = $( this ).val();
		if(item_quantity==0){}	
        var currentVal = parseFloat(item_quantity);
		var nextSectionWithId = $(this).parent().nextAll('#prhidden').first().val();
		
		if(currentVal==0){
			document.getElementById(nextSectionWithId).checked = false;
			$("label[for='" + nextSectionWithId +"']").text('Add to Cart');		
		}
            $.ajax({
                type: 'POST',		
                url: url,
                data: {
                    action: 'qty_cart',
                    hash: item_hash,
                    quantity: currentVal,
					datepicker_ajax: datepicker
                },
                 success: function(data) { 
				 $('.loader_order').hide();
					$("#your_basket_id").load(document.URL + ' .woocommerce-cart-form');
					$("body").on("click", "#datepicker", function(){
						$(this).datepicker({ minDate: 0});
						$(this).datepicker("show");
					});
					$('.countdynamic').load(document.URL + ' .header-cart-count', function(responseTxt, statusTxt, xhr) {
								if (statusTxt == "success") {
									$('.loader_order').hide();
								}
					});
					/* $('.pro_checkbox').load(document.URL + ' .checkboxorder', function(responseTxt, statusTxt, xhr) {
								if (statusTxt == "success") {
									$('.loader_order').hide();
								}
					}); */
                } 
            });  

        $('.error_msg').on('click', function() {
			$(".Tool_tiip").css({
				'display': 'block'
			});
		});

    });

});


