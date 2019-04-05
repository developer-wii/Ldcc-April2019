jQuery( function( $ ) {

    //removeDisabledFlag('collection');
    //removeDisabledFlag('dropoff');


    /*
     * Remove products from basket
     */
    $(document.body).on('click', '.remove_sidebar_basket', function()
    {
        var productToRemove = $(this).attr('data-product-id');
        var prodItemKey = $(this).attr('data-item-key');
        var basketDiv = $(document.body).find('#sidebar-cart');
        basketDiv.empty();
        basketDiv.append('<div id="basket-loading"><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></div>');
        
        if(productToRemove != '')
        {
            $.ajax({
                type : "post",
                dataType : "json",
                url : AjaxRequests.ajaxurl,
                data : 
                {
                    action: "remove_product_from_basket_sidebar",
                    product: productToRemove,
                    itemKey: prodItemKey
                },
                success: function(response) {
                    if(response.output == "success") 
                    {
                        basketDiv.empty();
                        //loadBasket();
                        basketDiv.append(response.basket).text();
                    }
                    else 
                    {
                        alert(response.msg);
                    }
                }
            }); 
        }
    });

   /*
    * Add products to basket sidebar
    */
   $(document.body).on('click', '.add_to_basket', function()
   {
        var basketDiv = $(document.body).find('#sidebar-cart');
        basketDiv.empty();
        basketDiv.append('<div id="basket-loading"><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></div>');
        var $thisbutton = $( this );
        if($thisbutton.is( '.product_type_simple' ))
        {
            var dataProduct = $thisbutton.attr('data-product-id');
            var dataQuantity = $thisbutton.parents('.post-' + dataProduct).find('.quantity-cell').find('input[name="quantity"]').val();
	    if (dataProduct == null && dataQuantity == null)
            return true;
           
                var thisHTML = $('html');
                var showResponive = $thisbutton.parents('.products-add-basket').parents('.products-category').find('.sidebar');
                if(thisHTML.hasClass('lower-then-seven-six-eight'))
                {
                    var basketButton = $(document.body).find('.products-category').find('.aside-button');
                    var basketAfterAppend = $(document.body).find('.products-category').find('.top-view-basket');
                    var basketFlashMessenger = '<div data-alert class="alert-box secondary">Item has been added to your basket. <a href="#" class="close remove-alert">&times;</a></div>';
                    
                    var totalItemsAlready = basketButton.attr('data-items');
                    basketAfterAppend.after(basketFlashMessenger);
                    
                    totalItemsAlready = parseInt(totalItemsAlready) + 1;
                    basketButton.attr('data-items', totalItemsAlready);
                    
                    if(basketButton.text() == 'Hide Basket - Items('+totalItemsAlready+')')
                    {
                        basketButton.text('View Basket - Items('+totalItemsAlready+')');
                    }
                    else
                    {
                        basketButton.text('Hide Basket - Items('+totalItemsAlready+')');
                    }
                    showResponive.css({'display':'block'});
                }
                
                $.ajax({
                    type : "post",
                    dataType : "json",
                    url : AjaxRequests.ajaxurl,
                    data : 
                    {
                        action: "add_basket",
                        product: dataProduct,
                        quantity: dataQuantity
                    },
                    success: function(response) {
                        if(response.output == "success") 
                        {
                            basketDiv.empty();
                            //loadBasket();
                            basketDiv.append(response.basket).text();
                        }
                        else 
                        {
                            alert(response.msg);
                        }
                    }
                });  
        }
        return false;
   });

    /*
     * Remove product from checkout review basket
     */
    $('.remove_from_basket').on('click', function()
    {
        var removeDataTrId = $(this).attr('data-tr-id');
        var removeProduct = $(this).attr('data-product-id');
        var removeProductKey = $(this).attr('data-cart-key');
        var appendSelector = $(this).parents('#content').find('.checkout-review-basket');
        var removeProductRow = $(this).parents('.tr_'+removeDataTrId);
        var updatedTotalsSelector = $(this).parents('tbody').find('.totals').find('.basket-view-totals');
        var successMessage = '<div class="woocommerce-message">Your basket was updated.</div>';
        var errorMessage = '<div class="woocommerce-error">Your basket failed to be updated, please try again.</div>';
        var emptycartMessage = '<div class="woocommerce-info">Your basket is now empty.</div>';
        
        appendSelector.find('.woocommerce-message').remove();
        appendSelector.find('.woocommerce-error').remove();
        appendSelector.find('.woocommerce-info').remove();

        if(removeProduct != '')
        {
            $.ajax({
                type : "post",
                dataType : "json",
                url : AjaxRequests.ajaxurl,
                data : 
                {
                    action: "remove_product_from_basket",
                    product: removeProduct,
                    itemKey: removeProductKey
                },
                success: function(response) {
                    if(response.output == "success") 
                    {
                        if(response.empty != '')
                        {
                            updatedTotalsSelector.empty();
                            removeProductRow.remove();
                            appendSelector.prepend(emptycartMessage);
                        }
                        else
                        {
                            updatedTotalsSelector.empty();
                            removeProductRow.remove();
                            appendSelector.prepend(successMessage);
                            updatedTotalsSelector.append('Total: <span class="mini-total">'+response.totals+'</span>');
                        }
                    }
                    else 
                    {
                        appendSelector.prepend(errorMessage);
                    }
                }
            }); 
        }
    });
    
});

/*
 * #sidebar-cart
 */
function loadBasket()
{
    var basketDiv = $(document.body).find('#sidebar-cart');
    
    $.ajax({
        type : "get",
        dataType : "json",
        url : AjaxRequests.ajaxurl,
        data : 
        {
            action: "load_basket"
        },
        success: function(response) {
            //basketDiv.empty().html(returnData);
            basketDiv.append(response.basket).text();
        }
    });  
}

function dateSelector(chosen)
{
    var tablerId = $(chosen).parents('.SimpleCalendar').attr('id');
    var datesAndTimes = $(chosen).attr('datetime');
    var tabFooter = $(chosen).parents('.SimpleCalendar').parent().find('.tab-footer');

    tabFooter.append('<div class="picker-loading"><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></div>');

    $('#'+tablerId+'').find('td').each(function(index)
    {
        $(this).find('.date-cell').removeClass('saved-date');
    });

    if(datesAndTimes != '' && tablerId != '')
    {
        $.ajax({
          url : AjaxRequests.ajaxurl,
          type: 'post',
          dataType: 'json',
          data: {
              action: "choose_dates",
              dates: datesAndTimes,
              tab: tablerId
          },
          success: function(response){
              if(response.output == "success") 
              {
                  tabFooter.find('.picker-loading').remove();
                  tabFooter.find('.para-date').remove();
                  tabFooter.find('input[name="'+tablerId+'-section"]').remove();
                  $(chosen).addClass('saved-date');
                  tabFooter.append(response.dates);
                  tabFooter.append(response.fields);
                  
                  
                     //Checkout slectors
                    if(tablerId == 'collection-picker')
                    {
                        var checkoutChosenDateCollection = $(chosen).parents('#editCollectionModal').parent().find('#body');
                        var newDateDataCollection = '<p class="co-collect-chosen-date">'+ response.codate +'</p>';    
                        var collectAddDate = checkoutChosenDateCollection.find('.collection-data-container');
                        collectAddDate.find('.co-collect-chosen-date').remove();
                        collectAddDate.prepend(newDateDataCollection);
                        $(chosen).parents('.SimpleCalendar').addClass('flagged');
                        
                        var isTimeSelected = $(chosen).parents('#collection').find('#collection-tod');
                        if(isTimeSelected.hasClass('flagged'))
                        {
                            $('#collection-button').removeAttr('disabled');
                        }
                        
                    }
                    else
                    {
                        var checkoutChosenDateDropoff = $(chosen).parents('#editDropoffModal').parent().find('#body');
                        var newDateDataDropoff = '<p class="dropoff-data-container">'+ response.codate +'</p>';  
                        var dropoffAddDate = checkoutChosenDateDropoff.find('.dropoff-data-container');
                        dropoffAddDate.find('.co-deliver-chosen-date').remove();
                        dropoffAddDate.prepend(newDateDataDropoff);
                        $(chosen).parents('.SimpleCalendar').addClass('flagged');
                        
                        var isTimeSelected = $(chosen).parents('#dropoff').find('#dropoff-tod');
                        if(isTimeSelected.hasClass('flagged'))
                        {
                            $('#dropoff-button').removeAttr('disabled');
                        }
                    }
                  
              }
              else if (response.output == "failed")
              {
                  tabFooter.find('.picker-loading').remove();
                  $(chosen).removeClass('saved-date');
                  alert(response.msg);
              }
              else
              {
                  tabFooter.find('.picker-loading').remove();
                  $(chosen).removeClass('saved-date');
                  alert('There was an error saving your date, please refresh the page.');
              }
          }
       });
   }

    return false;
}

function setChosenTime(time)
{
    var typeChosen = $(time).attr('data-type');
    var timeChosen = $(time).attr('data-time');
    var tabTimeFooter = $(time).parents('#'+typeChosen+'').find('.tab-footer');

    $(time).parents('.timeofday').each(function(index)
    {
        $(this).find('li').removeClass('active-time');
    });
    
    $(time).parent('.tod-choose').addClass('active-time');
    tabTimeFooter.append('<div class="picker-loading"><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></div>');

    if(typeChosen != '' && timeChosen != '')
    {
        $.ajax({
          url : AjaxRequests.ajaxurl,
          type: 'post',
          dataType: 'json',
          data: {
              action: "choose_times",
              type: typeChosen,
              time: timeChosen
          },
          success: function(response){
              if(response.output == "success") 
              {
                  tabTimeFooter.find('.picker-loading').remove();
                  tabTimeFooter.find('.para-time').remove();
                  tabTimeFooter.find('input[name="'+typeChosen+'-times"]').remove();
                  tabTimeFooter.append(response.times);
                  tabTimeFooter.append(response.fields);
                  
                     //Checkout slectors
                    if(typeChosen == 'collection')
                    {
                        var checkoutChosenTimeCollection = $(time).parents('#editCollectionModal').parent().find('#body');
                        var newTimeDataCollection = '<p class="co-collect-chosen-time">Between '+ timeChosen +'</p>';    
                        var collectAddTime = checkoutChosenTimeCollection.find('.collection-data-container');
                        collectAddTime.find('.co-collect-chosen-time').remove();
                        collectAddTime.append(newTimeDataCollection);
                        
                        $(time).parents('#collection-tod').addClass('flagged');
                        
                        var isDateSelected = $(time).parents('#'+typeChosen+'').find('.SimpleCalendar');
                        if(isDateSelected.hasClass('flagged'))
                        {
                            $('#collection-button').removeAttr('disabled');
                        }
                    }
                    else
                    {
                        var checkoutChosenTimeDropoff = $(time).parents('#editDropoffModal').parent().find('#body');
                        var newTimeDataDropoff = '<p class="co-deliver-chosen-time">Between '+ timeChosen +'</p>';  
                        var dropoffAddTime = checkoutChosenTimeDropoff.find('.dropoff-data-container');
                        dropoffAddTime.find('.co-deliver-chosen-time').remove();
                        dropoffAddTime.append(newTimeDataDropoff);
                        
                        $(time).parents('#dropoff-tod').addClass('flagged');
                        
                        var isDateSelected = $(time).parents('#'+typeChosen+'').find('.SimpleCalendar');
                        if(isDateSelected.hasClass('flagged'))
                        {
                            $('#dropoff-button').removeAttr('disabled');
                        }
                    }
                    
              }
              else if (response.output == "failed")
              {
                  $(time).parent('.tod-choose').removeClass('active-time');
                  tabTimeFooter.find('.picker-loading').remove();
                  alert(response.msg);
              }
              else
              {
                  $(time).parent('.tod-choose').removeClass('active-time');
                  tabTimeFooter.find('.picker-loading').remove();
                  alert('There was an error saving your '+typeChosen+' time, please refresh the page.');
              }
          }
       });
   }
    
    return false;
}

/*
function removeDisabledFlag(flag)
{
    if(flag.hasClass('flagged'))
    {
        flag.prop("disabled", false);
    }
}
*/

function addDisabledFlag(flag)
{
    console.log(flag);
    
    if(flag == 'collection')
    {
        $(document.body).find('#collection-button').prop("disabled", false);
    }
    else
    {
        $(document.body).find('#dropoff-button').prop("disabled", false);
    }
}
