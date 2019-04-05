<?php
/**
 * Description of postcodeLookUp
 *
 * @author Andrew Burns
 */
class postcodeLookUp 
{

    public function postcodeLookUp($postcode = '')
    {
      if ($postcode != null)
      {
        if($this->checkIfPostcodeDeliver($postcode) == true)
        {
          $this->canDeliver(true);  
          $this->chosenPostcode = $postcode;
          $this->setSessionPostcode($postcode);
        }
      } 
      else 
      {
        $this->canDeliver(false);
      }
    }
    
    public function getPostcode()
    {
        if(!empty($this->chosenPostcode))
        {
            return $this->chosenPostcode;
        }
        else if(!empty($_SESSION['postcode']))
        {
            return $_SESSION['postcode'];
        }
    
        return null;
    }
    
    public function setSessionPostcode($postcode)
    {
        $_SESSION['postcode'] = $postcode;
    }
    
    public function getSessionPostcode($postcode)
    {
        return $_SESSION['postcode'];
    }
    
    public function removeSessionPostcode()
    {
        $_SESSION['postcode'] = '';
        unset($_SESSION['postcode']);
    }
    
    public function checkIfPostcodeDeliver($postcode)
    {
        if(!empty($postcode) && null !== $postcode)
        {
            if($this->queryPostcode($postcode) == true)
            {
                return true;
            }
            return false;
        }
            return false;
    }
    
    public function canDeliver($bool)
    {
        switch($bool)
        {
            case true:
                $toOutput = 1;
            break;
            case false:
                $toOutput = 0;
            break;
        }
        
        return $toOutput;
    }
    
    public function isPostcodeActive($postcode)
    {
        $checkPostcode = strip_tags($postcode);
        if($this->queryActive($checkPostcode) == true)
        {
            return true;
        }
        return false;
    }
    
    private function queryActive($postcode)
    {
        global $wpdb;
        $postcode = esc_sql(trim($postcode));
        $postcodeOneWord = str_replace(" ", "", $postcode);
        $postCanDeliver = "SELECT * FROM wp_postcodes WHERE wp_postcode = '".$postcodeOneWord."' LIMIT 1";
        $runCanDelvier = $wpdb->get_results($postCanDeliver);
        if(count($runCanDelvier))
        {
           if($runCanDelvier[0]->wp_pc_active == 1)
           {
               return true;
           }
           return false;
        }
        return false;
    }

    private function queryPostcode($postcode)
    {
        global $wpdb;
        $postcode = esc_sql(trim($postcode));
        $postcodeOneWord = str_replace(" ", "", $postcode);
        $postcodeWhatever = $postcode;
        $postcodeSubStrThree = substr($postcode, 0, 3);
        $postcodeSubStrTwo = substr($postcode, 0, 2);
        $postCanDeliver = "SELECT wp_pc_id FROM wp_postcodes WHERE wp_postcode LIKE '%".$postcodeOneWord."%' OR wp_postcode LIKE '%".$postcodeWhatever."%' OR wp_postcode LIKE '%".$postcodeSubStrThree."%' OR wp_postcode LIKE '%".$postcodeSubStrTwo."%' ";
        $runCanDelvier = $wpdb->get_results($postCanDeliver);
        //var_dump($runCanDelvier);
        //echo $runCanDelvier[0]->wp_pc_id;die;
        if(!empty($runCanDelvier[0]->wp_pc_id))
        {
            return true;
        }
        return false;
    }
    

}
