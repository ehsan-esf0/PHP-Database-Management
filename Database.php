<?php
class Database
{
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $conn;

    // Connecting to the database
    private function connect( $host, $dbname, $username, $password )
    {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $host . ";dbname=" . $dbname, $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }
    }

    /**
     * Constructor for the Database class.
     *
     * This constructor initializes the database connection by setting the
     * host, database name, username, and password properties, and then
     * calls the connect method to establish the connection.
     *
     * @param string $host The hostname of the database server.
     * @param string $dbname The name of the database.
     * @param string $username The username for the database connection.
     * @param string $password The password for the database connection.
     */
    function __construct( $host, $dbname, $username, $password )
    {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;

        $this->connect( $host, $dbname, $username, $password );
    }

}
