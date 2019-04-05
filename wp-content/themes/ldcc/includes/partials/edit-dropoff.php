<div id="editDropoffModal" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog" data-options="reveal.multiple_opened:true;">

     <a class="close-reveal-modal" aria-label="Close">&#215;</a>

     <section id="edit-dropoff-details" class="edit-dropoff-details">
     <h2>Dropoff Date/Time</h2>
     
     <?php
     //Require Instance of customWoocheckout Class
     require_once TEMPLATEPATH .'/classes/customWooCheckout.php';
     $customWooCheckout = new customWooCheckout;
     
     $collectDate = $customWooCheckout->getSessionValue('collection-picker');
     $dropoffDate = $customWooCheckout->getSessionValue('dropoff-picker');
     
     require_once TEMPLATEPATH .'/vendor/donatj/simplecalendar/lib/donatj/SimpleCalendar.php';
     $calendarTwo = new donatj\SimpleCalendar();
     $calendarTwo->setStartOfWeek('Sunday');
     $calendarTwo->setTableId('dropoff-picker');
     ?>

         <div class="row"  id="dropoff">
             <div class="small-12 medium-6 large-6 columns">
                 
                <div class="datepicker-collection">
                    <div class="tab-heading">
                    </div>
                    <?php 
                    if(!empty($dropoffDate))
                    {
                        $formatCurrDropoffDate = new DateTime($dropoffDate);
                        $calendarTwo->assignAlreadyChosenDates($formatCurrDropoffDate->format('Y-m-d'));
                    }
                    $calendarTwo->show(true);
                    ?>
                    <div class="tab-footer">
                    </div>
                </div>
                 
                 
             </div>
             <div class="small-12 medium-6 large-6 columns right-data-contents">
                 <h4>Collection Time</h4>
                 <?php echo generateCollectionAndDropoffs('dropoff'); ?>
                 
                 <a class="button revealnext chevron-right" href="#" data-reveal-id="editCollectionModal">Edit collection date/time</a>
             </div>
         </div>
     </section>

</div>