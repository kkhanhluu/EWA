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
require_once './blocks/Bestellung/Menu.php';
require_once './blocks/Bestellung/RightDiv.php'; 

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
class Bestellung extends Page
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

    private $_menu; 
    private $_rightDiv; 
    protected function __construct() 
    {
        parent::__construct();
        // to do: instantiate members representing substructures/blocks
        $this->_menu = new Menu($this->_database);
        $this->_rightDiv = new RightDiv($this->_database); 
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
    }
    
    protected function generatePageHeader($headline = "Bestellung") 
    {
        $headline = htmlspecialchars($headline);
        header("Content-type: text/html; charset=UTF-8");
        // to do: call generateView() for all members
        echo <<<EOF
        <head>
            <meta charset="UTF-8">
            <title>Bestellung</title>
            <link rel="stylesheet" href="styles/BestellungStyle.css" />
            <link rel="stylesheet" href="styles/index.css" />
            <!-- Google fonts -->
            <link href="https://fonts.googleapis.com/css?family=Gloria+Hallelujah" rel="stylesheet">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <!-- scripts -->
            <script type="text/javascript" src="scripts/bestellung.js"></script>
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
        $this->generatePageHeader('Bestellung');

        echo <<<EOF
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
            <h2>Bestellung</h2>
            <hr />
EOF;
        $this->_menu->generateView('left-side-div');
        $this->_rightDiv->generateView('right-side-div');
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
        // check parameters 
        if (isset($_POST["name"]) && isset($_POST["strasse"]) && isset($_POST["Hausnummer"]) && isset($_POST["PLZ"]) && isset($_POST["Stadt"]) && isset($_POST["pizza"])) {
            $name = $_POST["name"];
            $street = $_POST["strasse"];
            $hausnummer = $_POST["Hausnummer"];
            $plz = $_POST["PLZ"];
            $city = $_POST["Stadt"];
            $pizzas = $_POST["pizza"];
            
            if (strlen($name) <= 0 || strlen($street) <= 0 || strlen($hausnummer) <= 0 || strlen($plz) <= 0 || strlen($city) <= 0) {
                throw new Exception("Bitte geben Sie etwas an"); 
            }
            else {
                $sqlName = $this->_database->real_escape_string($name);
                $sqlStreet = $this->_database->real_escape_string($street);
                $sqlHausnummer = $this->_database->real_escape_string($hausnummer); 
                $sqlPlz = $this->_database->real_escape_string($plz); 
                $sqlCity = $this->_database->real_escape_string($city); 
                
                // update Bestellung table
                $sqlQuery = "SELECT * FROM bestellung WHERE ". "Name = \"$sqlName\"";
                $recordSet = $this->_database->query($sqlQuery); 

                if ($recordSet->num_rows > 0) {
                    throw new Exception("Diese Bestellung ist bereits eingetragen"); 
                    $recordSet->free(); 
                }
                else {
                    $adress = $sqlStreet." ".$sqlHausnummer.", ".$sqlPlz.", ".$sqlCity;
                    $sqlInsert = "INSERT INTO bestellung(Adresse, Name) VALUES('$adress', '$sqlName')"; 
                    $this->_database->query($sqlInsert); 
                    $newId = $this->_database->insert_id;
                }

                // update BestelltePizza table
                foreach($pizzas as $pizza) {
                    $sqlPizza = $this->_database->real_escape_string($pizza); 
                    $sqlInsertPizza = "INSERT INTO bestelltepizza(fBestellungID, fPizzaName, Status) VALUES('$newId', '$sqlPizza', 0)"; 
                    $this->_database->query($sqlInsertPizza); 
                }
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
            $page = new Bestellung();
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
Bestellung::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >