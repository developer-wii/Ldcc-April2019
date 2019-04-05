<div id="editCollectionModal" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog" data-options="reveal.multiple_opened:true;">

     <a class="close-reveal-modal" aria-label="Close">&#215;</a>

     <section id="edit-collection-details" class="edit-collection-details">
     <h2>Collection Date/Time</h2>
     
     <?php
     //Require Instance of customWoocheckout Class
     require_once TEMPLATEPATH .'/classes/customWooCheckout.php';
     $customWooCheckout = new customWooCheckout;
     
     $collectDate = $customWooCheckout->getSessionValue('collection-picker');
     $dropoffDate = $customWooCheckout->getSessionValue('dropoff-picker');
     
     require_once TEMPLATEPATH .'/vendor/donatj/simplecalendar/lib/donatj/SimpleCalendar.php';
     $calendarOne = new donatj\SimpleCalendar();
     $calendarOne->setStartOfWeek('Sunday');
     $calendarOne->setTableId('collection-picker');
     ?>

         <div class="row" id="collection">
             <div class="small-12 medium-6 large-6 columns">
                 
                <div class="datepicker-collection">
                    <div class="tab-heading">
                    </div>
                    <?php 
                    if(!empty($collectDate))
                    {
                        $formatCurrCollectDate = new DateTime($collectDate);
                        $calendarOne->assignAlreadyChosenDates($formatCurrCollectDate->format('Y-m-d'));
                    }
                    $calendarOne->show(true);
                    ?>
                    <div class="tab-footer">
                    </div>
                </div>
                 
                 
             </div>
             <div class="small-12 medium-6 large-6 columns right-data-contents">
                 <h4>Collection Time</h4>
                 <?php echo generateCollectionAndDropoffs('collection'); ?>
                 
                 <a class="button revealnext chevron-right" href="#" data-reveal-id="editDropoffModal">Edit drop off date/time</a>
             </div>
         </div>
     </section>
</div>