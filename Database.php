<?php

class Database
{
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $conn;

    /**
     * Constructor for the Database class.
     *
     * This constructor initializes the database connection by setting the
     * host, database name, username, and password properties, and then
     * calls the connect method to establish the connection. If the database
     * does not exist, it creates the database.
     *
     * @param string $host The hostname of the database server.
     * @param string $dbname The name of the database.
     * @param string $username The username for the database connection.
     * @param string $password The password for the database connection.
     */
    function __construct($host, $dbname, $username, $password)
    {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;

        // Try connecting to the database
        $this->connect($host, $dbname, $username, $password);

        // If connection fails, create the database and connect again
        if ($this->conn === null) {
            $this->createDatabase($host, $username, $password, $dbname);
            $this->connect($host, $dbname, $username, $password);
        }
    }

    /**
     * Establishes a connection to the database.
     */
    private function connect($host, $dbname, $username, $password)
    {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Comment out the echo statement to prevent revealing database information
            // echo "Connection error: " . $e->getMessage();
        }
    }

    /**
     * Creates a new database.
     *
     * This method creates a new database using the provided
     * host, username, password, and database name.
     * 
     */
    private function createDatabase($host, $username, $password, $dbname)
    {
        try {
            $conn = new PDO("mysql:host=$host", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "CREATE DATABASE $dbname";
            $conn->exec($sql);
            echo "Database $dbname created successfully!";
        } catch (PDOException $e) {
            echo "Database creation error: " . $e->getMessage();
        }
    }

    /**
     * Deletes the current database.
     */
    function deleteDatabase()
    {
        $sql = "DROP DATABASE IF EXISTS $this->dbname";
        $this->conn->exec($sql);
    }


    /**
     * Creates a new table in the specified database.
     *
     * @param string $tableName The name of the table to be created.
     * @param array $columns An associative array of column definitions where the key is the column name and the value is the column type.
     */
    function createTable($tableName, $columns)
    {
        $checkTableSql = "SHOW TABLES LIKE '$tableName'";
        $result = $this->conn->query($checkTableSql)->fetch();

        if (!$result) {
            $sql = "CREATE TABLE $tableName (";
            foreach ($columns as $column => $type) {
                $sql .= "$column $type, ";
            }
            $sql = rtrim($sql, ', ');
            $sql .= ")";
            $this->conn->exec($sql);
            echo "Table $tableName created successfully!";
        } else {
            echo "Table $tableName already exists!";
        }
    }

    /**
     * Deletes a table from the database if it exists.
     *
     * @param string $tableName The name of the table to be deleted.
     */
    function deleteTable($tableName)
    {
        $sql = "DROP TABLE IF EXISTS $tableName";
        $this->conn->exec($sql);
    }


    /**
     * Renames a table in the database.
     *
     * @param string $oldTableName The current name of the table.
     * @param string $newTableName The new name of the table.
     */
    function renameTable($oldTableName, $newTableName)
    {
        $checkTableSql = "SHOW TABLES LIKE '$oldTableName'";
        $result = $this->conn->query($checkTableSql)->fetch();

        if ($result) {
            $sql = "RENAME TABLE $oldTableName TO $newTableName";
            $this->conn->exec($sql);
            echo "Table $oldTableName renamed to $newTableName successfully!";
        } else {
            echo "Table $oldTableName does not exist!";
        }
    }

    
}
