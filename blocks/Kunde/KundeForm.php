<?php	// UTF-8 marker äöüÄÖÜß€
/**
 * Class BlockTemplate for the exercises of the EWA lecture
 * Demonstrates use of PHP including class and OO.
 * Implements Zend coding standards.
 * Generate documentation with Doxygen or phpdoc
 * 
 * PHP Version 5
 *
 * @category File
 * @package  Pizzaservice
 * @author   Bernhard Kreling, <b.kreling@fbi.h-da.de> 
 * @author   Ralf Hahn, <ralf.hahn@h-da.de> 
 * @license  http://www.h-da.de  none 
 * @Release  1.2
 * @link     http://www.fbi.h-da.de 
 */

/**
 * This is a template for classes, which represent div-blocks 
 * within a web page. Instances of these classes are used as members 
 * of top level classes.
 * The order of methods might correspond to the order of thinking 
 * during implementation.
 
 * @author   Bernhard Kreling, <b.kreling@fbi.h-da.de> 
 * @author   Ralf Hahn, <ralf.hahn@h-da.de> 
*/
class KundeForm          // to do: change name of class
{
    // --- ATTRIBUTES ---

    /**
     * Reference to the MySQLi-Database that is
     * accessed by all operations of the class.
     */
    protected $_database = null;
    protected $_id = -1;
    // to do: declare reference variables for members 
    // representing substructures/blocks
    
    // --- OPERATIONS ---
    
    /**
     * Gets the reference to the DB from the calling page template.
     * Stores the connection in member $_database.
     *
     * @param $database $database is the reference to the DB to be used     
     *
     * @return none
     */
    public function __construct($database, $id) 
    {
        $this->_database = $database;
        $this->_id = $id; 
        // to do: instantiate members representing substructures/blocks
    }

    /**
     * Fetch all data that is necessary for later output.
     * Data is stored in an easily accessible way e.g. as associative array.
     *
     * @return none
     */
    protected function getViewData()
    {
        // to do: fetch data for this view from the database
        $sql = "SELECT * FROM bestelltepizza WHERE PizzaID = ".$this->_id;
        $recordSet = $this->_database->query($sql); 

        if (!$recordSet) {
            throw new Exception("Abfrage fehlgeschlagen ".$this->_database->error); 
        }

        $bestelltePizza = $recordSet->fetch_assoc();
        return $bestelltePizza; 
    }
    
    private function insertInput($status, $value) {
        $intValue= -1; 
        switch($value) {
            case "Bestellt": 
                $intValue = 0;
                break;
            case "ImOfen": 
                $intValue = 1;
                break;
            case "Fertig": 
                $intValue = 2;
                break;
            case "Unterwegs": 
                $intValue = 3;
                break;
            case "Geliefert": 
                $intValue = 4;
                break;  
        }
        if ($intValue == $status) {
            echo("<label><input checked=\"checked\" onclick=\"changeStatus('hide-$this->_id');document.forms['form-$this->_id']. submit();\" type=\"radio\" name=\"status\" value=\"$intValue\">$value</label>");
        }
        else {
            echo("<label><input onclick=\"changeStatus('hide-$this->_id');document.forms['form-$this->_id']. submit();\" type=\"radio\" name=\"status\" value=\"$intValue\">$value</label>");            
        }
    }
    
    /**
     * Generates an HTML block embraced by a div-tag with the submitted id.
     * If the block contains other blocks, delegate the generation of their 
	 * parts of the view to them.
     *
     * @param $id $id is the unique (!!) id to be used as id in the div-tag     
     *
     * @return none
     */
    public function generateView($id = "kunde-form") 
    {
        // to do: call generateView() for all members
        $bestelltePizza = $this->getViewData();
        if ($id) {
            $id = "id=\"$id\"";
        }
        echo "<div class=\"div-pizza\">\n";
        echo "<form class=\"customer-input\" id=\"form-$this->_id\" action=\"Kunde.php\" accept-charset=\"UTF-8\" method=\"POST\">\n";
        echo <<<EOT
<fieldset>
<legend>Kunde</legend>
<div class="div-input">
EOT;
            // var_dump($pizza);
            $this->insertInput($bestelltePizza["Status"], "Bestellt");
            $this->insertInput($bestelltePizza["Status"], "ImOfen");
            $this->insertInput($bestelltePizza["Status"], "Fertig");
            $this->insertInput($bestelltePizza["Status"], "Unterwegs");
            $this->insertInput($bestelltePizza["Status"], "Geliefert");
            echo("<input id=\"hide-$this->_id\" type=\"text\" name=\"isSubmitted-$this->_id\" value=\"false\" hidden />");
            echo <<<EOT
</div>
</fieldset>
</form>
EOT;
            echo "</div>";
    }
    
    
    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If this block is supposed to do something with submitted
     * data do it here. 
     * If the block contains other blocks, delegate processing of the 
	 * respective subsets of data to them.
     *
     * @return none 
     */
    public function processReceivedData(&$status, &$formId)
    {
        // to do: call processData() for all members
        if(isset($_POST["status"]) && isset($_POST["isSubmitted-$this->_id"])) {
            if ($_POST["isSubmitted-$this->_id"] == "true") {
                $status = $_POST["status"];
                $formId = $this->_id;
            }
        }
    }
}
// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >