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

    /**
     * Changes a column in the specified table.
     *
     * @param string $TableName The name of the table.
     * @param string $oldColumnName The current name of the column.
     * @param string $newColumnName The new name of the column.
     * @param string $definition The column definition (e.g., data type).
     */
    function Change_table_column($TableName, $oldColumnName, $newColumnName, $definition)
    {
        $checkTableSql = "SHOW TABLES LIKE '$TableName'";
        $result = $this->conn->query($checkTableSql)->fetch();

        if ($result) {
            $checkColumnSql = "SHOW COLUMNS FROM $TableName LIKE '$oldColumnName'";
            $columnResult = $this->conn->query($checkColumnSql)->fetch();

            if ($columnResult) {
                $sql = "ALTER TABLE $TableName CHANGE COLUMN $oldColumnName $newColumnName $definition;";
                $this->conn->exec($sql);
                echo "Column $oldColumnName in table $TableName renamed to $newColumnName successfully!";
            } else {
                echo "Column $oldColumnName does not exist in table $TableName!";
            }
        } else {
            echo "Table $TableName does not exist!";
        }
    }

    /**
     * Modifies a column in the specified table.
     *
     * @param string $tableName The name of the table.
     * @param string $columnName The name of the column to be modified.
     * @param string $definition The new column definition (e.g., data type).
     */
    function Modify_table_column($tableName, $columnName, $definition)
    {
        $checkTableSql = "SHOW TABLES LIKE '$tableName'";
        $result = $this->conn->query($checkTableSql)->fetch();

        if ($result) {
            $checkColumnSql = "SHOW COLUMNS FROM $tableName LIKE '$columnName'";
            $columnResult = $this->conn->query($checkColumnSql)->fetch();

            if ($columnResult) {
                $sql = "ALTER TABLE $tableName MODIFY COLUMN $columnName $definition";
                $this->conn->exec($sql);
                echo "Column $columnName in table $tableName modified successfully!";
            } else {
                echo "Column $columnName does not exist in table $tableName!";
            }
        } else {
            echo "Table $tableName does not exist!";
        }
    }

    /**
     * Adds a new column to the specified table.
     *
     * @param string $tableName The name of the table.
     * @param string $newColumnName The name of the new column to be added.
     * @param string $definition The column definition (e.g., data type).
     * @param string $position (optional) The position to add the new column (e.g., 'FIRST' or 'AFTER column_name').
     */
    function addTableColumn($tableName, $newColumnName, $definition, $position = '')
    {
        $checkTableSql = "SHOW TABLES LIKE '$tableName'";
        $result = $this->conn->query($checkTableSql)->fetch();

        if ($result) {
            $sql = "ALTER TABLE $tableName ADD COLUMN $newColumnName $definition";
            if (!empty($position)) {
                $sql .= " $position";
            }
            $this->conn->exec($sql);
            echo "Column $newColumnName added to table $tableName successfully!";
        } else {
            echo "Table $tableName does not exist!";
        }
    }

    /**
     * Drops a column from the specified table.
     *
     * @param string $tableName The name of the table.
     * @param string $columnName The name of the column to be dropped.
     */
    function deleteTableColumn($tableName, $columnName)
    {
        $checkTableSql = "SHOW TABLES LIKE '$tableName'";
        $result = $this->conn->query($checkTableSql)->fetch();

        if ($result) {
            $checkColumnSql = "SHOW COLUMNS FROM $tableName LIKE '$columnName'";
            $columnResult = $this->conn->query($checkColumnSql)->fetch();

            if ($columnResult) {
                $sql = "ALTER TABLE $tableName DROP COLUMN $columnName";
                $this->conn->exec($sql);
                echo "Column $columnName from table $tableName dropped successfully!";
            } else {
                echo "Column $columnName does not exist in table $tableName!";
            }
        } else {
            echo "Table $tableName does not exist!";
        }
    }

    /**
     * Adds a foreign key to the specified table.
     *
     * This method constructs and executes a SQL query to add a foreign key
     * to the specified table. The method checks if the tables and columns 
     * involved in the foreign key constraint exist before attempting to 
     * add the foreign key. The method uses the current database connection
     * stored in the $conn property. Additionally, it supports options 
     * such as ON DELETE and ON UPDATE.
     *
     * @param string $tableName The name of the table to which the foreign key is added.
     * @param string $columnName The name of the column in the table that is the foreign key.
     * @param string $referencedTable The name of the referenced table.
     * @param string $referencedColumn The name of the column in the referenced table.
     * @param string $constraintName (optional) The name of the foreign key constraint.
     * @param string $onDelete (optional) The action to be taken on delete (e.g., 'CASCADE', 'SET NULL').
     * @param string $onUpdate (optional) The action to be taken on update (e.g., 'CASCADE', 'SET NULL').
     */
    function addForeignKey($tableName, $columnName, $referencedTable, $referencedColumn, $constraintName = '', $onDelete = '', $onUpdate = '')
    {
        $checkTableSql = "SHOW TABLES LIKE '$tableName'";
        $result = $this->conn->query($checkTableSql)->fetch();

        $checkReferencedTableSql = "SHOW TABLES LIKE '$referencedTable'";
        $referencedTableResult = $this->conn->query($checkReferencedTableSql)->fetch();

        if ($result && $referencedTableResult) {
            $checkColumnSql = "SHOW COLUMNS FROM $tableName LIKE '$columnName'";
            $columnResult = $this->conn->query($checkColumnSql)->fetch();

            $checkReferencedColumnSql = "SHOW COLUMNS FROM $referencedTable LIKE '$referencedColumn'";
            $referencedColumnResult = $this->conn->query($checkReferencedColumnSql)->fetch();

            if ($columnResult && $referencedColumnResult) {
                $constraintNameSql = $constraintName ? "CONSTRAINT $constraintName" : '';
                $sql = "ALTER TABLE $tableName ADD $constraintNameSql FOREIGN KEY ($columnName) REFERENCES $referencedTable($referencedColumn)";

                if (!empty($onDelete)) {
                    $sql .= " ON DELETE $onDelete";
                }
                if (!empty($onUpdate)) {
                    $sql .= " ON UPDATE $onUpdate";
                }

                $this->conn->exec($sql);
                echo "Foreign key added to column $columnName in table $tableName successfully!";
            } else {
                echo "Column $columnName in table $tableName or column $referencedColumn in table $referencedTable does not exist!";
            }
        } else {
            echo "Table $tableName or table $referencedTable does not exist!";
        }
    }

    /**
     * Inserts data into the specified table.
     *
     * This method constructs and executes a SQL query to insert data
     * into the specified table. The method checks if the table and 
     * columns exist before attempting to insert the data. The method 
     * uses the current database connection stored in the $conn property.
     *
     * @param string $tableName The name of the table.
     * @param array $data An associative array of column names and values to be inserted.
     */
    function insert_Into_Table($tableName, $data)
    {
        $checkTableSql = "SHOW TABLES LIKE '$tableName'";
        $result = $this->conn->query($checkTableSql)->fetch();

        if ($result) {
            $columns = implode(", ", array_keys($data));
            $values = implode(", ", array_map(function ($value) {
                return is_numeric($value) ? $value : "'$value'";
            }, array_values($data)));
            $sql = "INSERT INTO $tableName ($columns) VALUES ($values)";
            $this->conn->exec($sql);
            echo "Data inserted into table $tableName successfully!";
        } else {
            echo "Table $tableName does not exist!";
        }
    }

    /**
     * Selects data from the specified table with conditions, ordering, and limit.
     *
     * This method constructs and executes a SQL query to select data
     * from the specified table with the given conditions, ordering, 
     * and limit. The method checks if the table and columns exist before 
     * attempting to select the data. The method uses the current database 
     * connection stored in the $conn property.
     *
     * @param string $tableName The name of the table.
     * @param array $columns An array of column names to be selected.
     * @param string $where (optional) The WHERE condition for the query.
     * @param string $orderBy (optional) The ORDER BY condition for the query.
     * @param int $limit (optional) The limit for the number of rows to be selected.
     * @return array The selected data as an array of associative arrays.
     */
    function select_From_Table($tableName, $columns, $where = '', $orderBy = '', $limit = 0)
    {
        $checkTableSql = "SHOW TABLES LIKE '$tableName'";
        $result = $this->conn->query($checkTableSql)->fetch();
        if ($result) {
            $columnsSql = implode(', ', $columns);
            $sql = "SELECT $columnsSql FROM $tableName";

            if (!empty($where)) {
                $sql .= " WHERE $where";
            }
            if (!empty($orderBy)) {
                $sql .= " ORDER BY $orderBy";
            }
            if ($limit > 0) {
                $sql .= " LIMIT $limit";
            }
            $stmt = $this->conn->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        } else {
            echo "Table $tableName does not exist!";
            return [];
        }
    }

    /**
     * Updates data in the specified table.
     *
     * This method constructs and executes a SQL query to update data
     * in the specified table with the given conditions. The method checks 
     * if the table and columns exist before attempting to update the data.
     * The method uses the current database connection stored in the $conn property.
     *
     * @param string $tableName The name of the table.
     * @param array $data An associative array of column names and new values to be updated.
     * @param string $where The WHERE condition for the query.
     */
    function update_Table($tableName, $data, $where)
    {
        $checkTableSql = "SHOW TABLES LIKE '$tableName'";
        $result = $this->conn->query($checkTableSql)->fetch();

        if ($result) {
            $set = implode(", ", array_map(function ($column, $value) {
                return is_numeric($value) ? "$column = $value" : "$column = '$value'";
            }, array_keys($data), array_values($data)));
            $sql = "UPDATE $tableName SET $set WHERE $where";
            $this->conn->exec($sql);
            echo "Data in table $tableName updated successfully!";
        } else {
            echo "Table $tableName does not exist!";
        }
    }

    /**
     * Deletes data from the specified table.
     *
     * This method constructs and executes a SQL query to delete data
     * from the specified table with the given conditions. The method checks 
     * if the table exists before attempting to delete the data. The method 
     * uses the current database connection stored in the $conn property.
     *
     * @param string $tableName The name of the table.
     * @param string $where The WHERE condition for the query.
     */
    function deleteFromTable($tableName, $where)
    {
        $checkTableSql = "SHOW TABLES LIKE '$tableName'";
        $result = $this->conn->query($checkTableSql)->fetch();

        if ($result) {
            $sql = "DELETE FROM $tableName WHERE $where";
            $this->conn->exec($sql);
            echo "Data from table $tableName deleted successfully!";
        } else {
            echo "Table $tableName does not exist!";
        }
    }
}
