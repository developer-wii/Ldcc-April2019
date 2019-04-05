<?php
    /*
    Plugin Name: LDCC Postcodes
    Plugin URI: http://www.futuraservices.co.uk
    Description: Plugin to manage collection & delivery postcodes
    Author: AB
    Version: 1.0
    Author URI: http://www.futuraservices.co.uk
    */

function ldcc_postcodes_control_admin() {
    add_menu_page("LDCC Postcodes", "LDCC Postcodes", 1, "LDCC_Postcodes", "ldcc_postcodes_control_admin_page");
    add_submenu_page("LDCC_Postcodes", "Import Postcodes", "Import Postcodes", 1, "Import_Postcodes", "ldcc_postcodes_control_import_postcodes");
}
add_action('admin_menu', 'ldcc_postcodes_control_admin');

function your_css_and_js() {
    wp_register_style('your_css_and_js', plugins_url('style.css',__FILE__ ));
    wp_enqueue_style('your_css_and_js');
}
add_action( 'admin_init','your_css_and_js');

function loadControlMenu()
{
    $out = '';
    
    $out .= '<ul class="ttt-control-admin-bar">';
        $out .= '<li><a href="admin.php?page=LDCC_Postcodes">Postcodes</a></li>';
        $out .= '<li><a href="admin.php?page=Import_Postcodes">Import Postcodes</a></li>';
    $out .= '</ul>';
    
    return $out;
}

function ldcc_postcodes_control_admin_page()
{
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    echo loadControlMenu();
    
    if(isset($_GET['doAct']) && null !== $_GET['doAct'])
    {
        $allowedActions = array('EditPostcode', 'DeletePostcode');
        $doAction = esc_sql(trim($_GET['doAct']));
        if(!in_array($doAction, $allowedActions))
        {
            ?>
            <div class="tabler-container">
                <p>There was an error processing this page, please go back and try again.</p>
            </div>
            <?php
        }
        
        $thePostcode = esc_sql(trim($_GET['Postcode']));
        if(null !== $thePostcode)
        {
        
            switch($doAction)
            {
                case 'EditPostcode':

                    if(!empty($thePostcode) && checkPostcodeExists($thePostcode) == true)
                    {

                    }

                break;
                case 'DeletePostcode':

                    if(!empty($thePostcode) && checkPostcodeExists($thePostcode) == true)
                    {

                    }

                break;
            }
        
        }
        else
        {
            ?>
            <div class="tabler-container">
                <p>Postcode appears to be invalid, please check and try again.</p>
            </div>
            <?php
        }
  
    }
    else
    {
    
        $getAllPostcodes = getFullListOfPostcodes();
        //var_dump($getAllPostcodes);
        if($getAllPostcodes != null)
        {
        ?>
        <div class="tabler-container">
        <h1>Postcodes</h1>    

           <table width="100%" cellspacing="0" cellpaddin="0" class="tabler-data table">
           <tr>
           <th width="15%">ID</th>
           <th width="25%">Postcode</th>
           <th width="60%"></th>
           </tr>
           <?php
            $countTabler = 0;       
            foreach($getAllPostcodes as $pcodes)
            {
                ($countTabler %2 == 0) ? $classColour = 'odd' : $classColour = 'even';

                echo '<tr class="'.$classColour.'">';
                    echo '<td>'.$pcodes->wp_pc_id.'</td>';
                    echo '<td>'.$pcodes->wp_postcode.'</td>';
                    echo '<td>';

                        echo '<a class="button" href="admin.php?page=LDCC_Postcodes&doAct=EditPostcode&Postcode='.$pcodes->wp_pc_id.'">Edit</a> &nbsp;&nbsp;&nbsp;';
                        echo '<a class="button" href="admin.php?page=LDCC_Postcodes&doAct=DeletePostcode&Postcode='.$pcodes->wp_pc_id.'"">Delete</a>';

                    echo '</td>';
                echo '</tr>';

                $countTabler++;
             }
             ?>
           </table>
        </div>
         <?php
        }
        else
        {
            echo '<div class="tabler-container"><p>No postcodes have been imported.</p></div>';
        }
    }
    
}

function ldcc_postcodes_control_import_postcodes()
{
    echo loadControlMenu();
    ?>
<div class="tabler-container">
<h1>Import Postcodes</h1>    
<p>Importer only accepts spreadsheets for the following file types (csv, xls, xlsx, ods)</p>
<a href="<?php bloginfo('url'); ?>/wp-content/plugins/ldcc-postcodes/includes/postcodes-template.ods" class="button">Download Template</a>   
    
    <?php
    if(isset($_POST['doact-import']))
    {
        echo '<br style="clear:both;" /><br style="clear:both;" /><br style="clear:both;" />';
        
    //First upload and move file to folder
    if(isset($_FILES['fileToUpload']))
    {
        //$fileStatic = $_SERVER['DOCUMENT_ROOT']. '/wp-content/plugins/ldcc-postcodes/includes/postcodes-template.ods';
        //var_dump($fileStatic);die;
        $uploadDirectory = plugin_dir_path( __FILE__ ) . 'includes/';
        //var_dump($uploadDirectory);
        
        echo '<p>Start to upload file.</p>';
        
        //Get submitted file
        $uploadName = $_FILES['fileToUpload']['name'];
        $uploadError = $_FILES['fileToUpload']['error'];
        $uploadSize = $_FILES['fileToUpload']['size'];
        $uploadTMP = $_FILES['fileToUpload']['tmp_name'];
        if(isset($uploadName))
        {
            
            //Remove existing
            if(file_exists($uploadDirectory.$uploadName)) 
            {
               chmod($uploadDirectory.$uploadName, 0755); 
               unlink($uploadDirectory.$uploadName);
            }
            
            $moveToFolder = move_uploaded_file($uploadTMP, $uploadDirectory.$uploadName);
            if($moveToFolder)
            {
               $pathToFile = $uploadDirectory.$uploadName;
               if(!empty($pathToFile))    
               {

                    echo '<p>Starting import parser...</p>';
                    //$pathToFile = $_FILES['file']['tmp_name'];

                    echo '<p>Start to read file: '.$pathToFile.'</p>';

                    require_once TEMPLATEPATH .'/vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
                    require_once TEMPLATEPATH .'/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';

                    $objPHPExcel = PHPExcel_IOFactory::createReaderForFile($pathToFile);
                    $objPHPExcel->setReadDataOnly(true);
                    $objPHPExcel = PHPExcel_IOFactory::load($pathToFile);

                    //Truncate the postcodes table
                    if(donePostcodeTruncate() == true)
                    {
                        echo '<p>Old postcodes removed and truncated</p>';
                            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
                            {
                                $worksheetTitle     = $worksheet->getTitle();
                                $highestRow         = $worksheet->getHighestRow();
                                $highestColumn      = $worksheet->getHighestColumn();
                                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                                $nrColumns = ord($highestColumn) - 64;

                            echo '<br>The worksheet '.$worksheetTitle.' has ';
                            echo $nrColumns . ' columns (A-' . $highestColumn . ') ';
                            echo ' and ' . $highestRow . ' row.';

                            echo '<p>Itterating and inserting new postcodes.</p>';

                                echo '<br>Data: <table border="1">';
                                for ($row = 1; $row <= $highestRow; ++ $row)
                                {
                                    echo '<tr>';
                                    for ($col = 0; $col < $highestColumnIndex; ++ $col)
                                    {
                                        $cell = $worksheet->getCellByColumnAndRow($col, $row);
                                        $val = $cell->getCalculatedValue();
                                        echo '<td>' . $val . '</td>';
                                        $insertPostcode = insertPostcodeToDatabase($val);
                                    }
                                    echo '</tr>';
                                }
                                echo '</table>';
                            }
                    }
                    else
                    {
                        echo '<p>Postcodes could not be truncated.</p>';
                    }

                    echo '<p><strong>Postcode import completed successfully.</strong></p>';
                    
                    
               }
            }   
        }
        
    }
        
    }
    else
    {
    ?> 
    <form action="admin.php?page=Import_Postcodes" method="post" enctype="multipart/form-data" class="importer">
        <label>Select spreadsheet to upload:</label>
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input class="button" type="submit" value="Upload" name="doact-import">
    </form>
    <?php
    }
    ?>
    
</div>
    <?php
}



/* GETTERS & SETTERS */

function insertPostcodeToDatabase($val)
{
    global $wpdb;
    $postcode = esc_sql(trim($val));
    if(!empty($postcode))
    {
        $postcodeArr = array(
            'wp_postcode' => $postcode
        );
        $doPostcodeInsert = $wpdb->insert('wp_postcodes', $postcodeArr );
    }
    return;
}

function getFullListOfPostcodes()
{
    global $wpdb;
    $getPostcodes = "SELECT * FROM wp_postcodes ORDER BY wp_postcode ";
    $doGetPostcodes = $wpdb->get_results($getPostcodes);
    if(count($doGetPostcodes))
    {
        return $doGetPostcodes;
    }
    return null;
}

function donePostcodeTruncate()
{
    global $wpdb;
    $doTrunc = $wpdb->query('TRUNCATE TABLE wp_postcodes;');
    if($doTrunc)
    {
        return true;
    }
    return false;
}

function checkPostcodeExists($thePostcode)
{
    global $wpdb;
    $checkPCExists = "SELECT wp_postcode FROM wp_postcodes WHERE wp_pc_id = '".$thePostcode."' LIMIT 1";
    if($checkPCExists)
    {
        return true;
    }
    return false;
}