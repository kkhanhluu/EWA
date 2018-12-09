<?php	// UTF-8 marker äöüÄÖÜß€
/**
 * Class PageTemplate for the exercises of the EWA lecture
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

// to do: change name 'PageTemplate' throughout this file
require_once './Page.php';
require_once './blocks/Fahrer/FahrerForm.php';

/**
 * This is a template for top level classes, which represent 
 * a complete web page and which are called directly by the user.
 * Usually there will only be a single instance of such a class. 
 * The name of the template is supposed
 * to be replaced by the name of the specific HTML page e.g. baker.
 * The order of methods might correspond to the order of thinking 
 * during implementation.
 
 * @author   Bernhard Kreling, <b.kreling@fbi.h-da.de> 
 * @author   Ralf Hahn, <ralf.hahn@h-da.de> 
 */
class Fahrer extends Page
{
    // to do: declare reference variables for members 
    // representing substructures/blocks
    
    /**
     * Instantiates members (to be defined above).   
     * Calls the constructor of the parent i.e. page class.
     * So the database connection is established.
     *
     * @return none
     */

    private $_forms = array(); 
    private $_selectedStatus;
    private $selectedFormId; 
    protected function __construct() 
    {
        parent::__construct();
        // to do: instantiate members representing substructures/blocks
        $_selectedStatus = "xxx";
        $selectedFormId = "xxx"; 
    }
    
    /**
     * Cleans up what ever is needed.   
     * Calls the destructor of the parent i.e. page class.
     * So the database connection is closed.
     *
     * @return none
     */
    protected function __destruct() 
    {
        parent::__destruct();
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
        $sql = "SELECT PizzaID FROM bestelltePizza WHERE fBestellungID = 1"; 
        $recordSet = $this->_database->query($sql); 
        if (!$recordSet) {
            throw new Exception("Abfrage fehlgeschlagen ".$this->_database->error); 
        }

        $id = $recordSet->fetch_assoc();
        while ($id) {
            $this->_forms[] = new FahrerForm($this->_database, $id["PizzaID"]); 
            $id = $recordSet->fetch_assoc(); 
        }

    }
    
    protected function generatePageHeader($headline = "Bestellung") 
    {
        $headline = htmlspecialchars($headline);
        header("Content-type: text/html; charset=UTF-8");
        // to do: call generateView() for all members
        echo <<<EOF
        <head>
            <meta charset="UTF-8">
            <link rel="stylesheet" href="styles/Fahrer.css" />
            <link rel="stylesheet" href="styles/index.css" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Fahrer</title>
        </head>
EOF;
    }

    /**
     * First the necessary data is fetched and then the HTML is 
     * assembled for output. i.e. the header is generated, the content
     * of the page ("view") is inserted and -if avaialable- the content of 
     * all views contained is generated.
     * Finally the footer is added.
     *
     * @return none
     */
    protected function generateView() 
    {
        $this->getViewData();
        $this->generatePageHeader('Fahrer');

        echo<<<EOF
        <div class="container">
        <ul class="topnav">
            <li>
                <a href="Uebersicht.php">Übersicht</a>
            </li>
            <li>
                <a href="Bestellung.php">Bestellung</a>
            </li>
            <li>
                <a href="Kunde.php">Kunde</a>
            </li>
            <li>
                <a href="Baecker.php">Bäcker</a>
            </li>
            <li>
                <a href="Fahrer.php">Fahrer</a>
            </li>
        </ul>
        <section>
            <h2>Fahrer</h2>
            <hr />
EOF;
        foreach($this->_forms as $form) {
            $form->generateView();
        }  

        echo <<<EOF
        </section>
        </div>
EOF;
    $this->generatePageFooter();
    }
    
    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If this page is supposed to do something with submitted
     * data do it here. 
     * If the page contains blocks, delegate processing of the 
	 * respective subsets of data to them.
     *
     * @return none 
     */
    protected function processReceivedData() 
    {
        parent::processReceivedData();
        // to do: call processReceivedData() for all members
        foreach($this->_forms as $form) {
            $form->processReceivedData($this->_selectedStatus, $this->selectedFormId);
            $sqlStatus = $this->_database->real_escape_string($this->_selectedStatus);
    
            // query ordered pizza
            $sqlQuery = "SELECT * FROM bestelltepizza WHERE PizzaID = ".$this->selectedFormId; 
            $recordSet = $this->_database->query($sqlQuery); 
            if ($recordSet->num_rows <= 0) {
                throw new Exception("Bestellte pizza nicht vorhanden"); 
                $recordSet->free();
            }
            else {
                $sqlUpdate = "UPDATE bestelltepizza SET Status = ".$this->_selectedStatus." WHERE PizzaID = ".$this->selectedFormId; 
                var_dump($sqlUpdate);
                $this->_database->query($sqlUpdate);
            }
        }
    }

    /**
     * This main-function has the only purpose to create an instance 
     * of the class and to get all the things going.
     * I.e. the operations of the class are called to produce
     * the output of the HTML-file.
     * The name "main" is no keyword for php. It is just used to
     * indicate that function as the central starting point.
     * To make it simpler this is a static function. That is you can simply
     * call it without first creating an instance of the class.
     *
     * @return none 
     */    
    public static function main() 
    {
        try {
            $page = new Fahrer();
            $page->processReceivedData();
            $page->generateView();
        }
        catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

// This call is starting the creation of the page. 
// That is input is processed and output is created.
Fahrer::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >