<?php

/**
 * Class Db
 * Provides a lightweight abstraction for working with a MySQL database
 * using PHP's MySQLi extension. This class supports prepared statements,
 * query execution, and fetching results in multiple formats.
 */

namespace App;

/**
 * Class Db
 *
 * Provides a simple wrapper for interacting with a MySQL database using the MySQLi extension.
 * Offers methods for executing queries, fetching results, and handling errors.
 */
class Db
{

    protected \mysqli $connection;
    protected object $query;
    protected bool $show_errors = TRUE;
    protected bool $query_closed = TRUE;
    public int $query_count = 0;

    /**
     * Initializes a database connection using MySQLi with the specified parameters.
     * Establishes the connection and sets the desired character set. If the connection fails,
     * an error message is generated.
     *
     * @param string $dbhost The hostname of the database server. Defaults to 'localhost'.
     * @param string $dbuser The username for the database connection. Defaults to 'root'.
     * @param string $dbpass The password for the database connection. Defaults to an empty string.
     * @param string $dbname The name of the database to connect to. Defaults to an empty string.
     * @param string $charset The character set to use for the connection. Defaults to 'utf8'.
     * @return void
     */
    public function __construct($dbhost = 'localhost', $dbuser = 'root', $dbpass = '', $dbname = '', $charset = 'utf8') {
        $this->connection = new \mysqli($dbhost, $dbuser, $dbpass, $dbname);
        if ($this->connection->connect_error) {
            $this->error('Failed to connect to MySQL - ' . $this->connection->connect_error);
        }
        $this->connection->set_charset($charset);
    }

    /**
     * Executes a prepared MySQL query with optional parameters.
     * The method automatically binds the provided arguments to the query
     * and executes it. If the query encounters an error, an error message
     * is generated. Keeps track of the query count and manages the query's
     * closed state.
     *
     * @param string $query The SQL query to execute. The query may include placeholders for parameter binding.
     * @return self Returns the current instance to allow method chaining.
     */
    public function query($query) {
        if (!$this->query_closed) {
            $this->query->close();
        }
        if ($this->query = $this->connection->prepare($query)) {
            if (func_num_args() > 1) {
                $x = func_get_args();
                $args = array_slice($x, 1);
                $types = '';
                $args_ref = array();
                foreach ($args as $k => &$arg) {
                    if (is_array($args[$k])) {
                        foreach ($args[$k] as $j => &$a) {
                            $types .= $this->_gettype($args[$k][$j]);
                            $args_ref[] = &$a;
                        }
                    } else {
                        $types .= $this->_gettype($args[$k]);
                        $args_ref[] = &$arg;
                    }
                }
                array_unshift($args_ref, $types);
                call_user_func_array(array($this->query, 'bind_param'), $args_ref);
            }
            $this->query->execute();
            if ($this->query->errno) {
                $this->error('Unable to process MySQL query (check your params) - ' . $this->query->error);
            }
            $this->query_closed = FALSE;
            $this->query_count++;
        } else {
            $this->error('Unable to prepare MySQL statement (check your syntax) - ' . $this->connection->error);
        }
        return $this;
    }


    /**
     * Fetches all rows from the result set of a prepared and executed MySQL query.
     * Optionally applies a callback function to each row before adding it to the results.
     * If the callback returns 'break', iteration over the rows is stopped early.
     *
     * @param callable|null $callback An optional callback function to process each row. The function should accept one argument, an associative array representing the row, and can return 'break' to terminate early.
     * @return array Returns an array of rows as associative arrays. Each key in the array corresponds to a column name from the result set.
     */
    public function fetchAll($callback = null) {
        $params = array();
        $row = array();
        $meta = $this->query->result_metadata();
        while ($field = $meta->fetch_field()) {
            $params[] = &$row[$field->name];
        }
        call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
        while ($this->query->fetch()) {
            $r = array();
            foreach ($row as $key => $val) {
                $r[$key] = $val;
            }
            if ($callback != null && is_callable($callback)) {
                $value = call_user_func($callback, $r);
                if ($value == 'break') break;
            } else {
                $result[] = $r;
            }
        }
        $this->query->close();
        $this->query_closed = TRUE;
        return $result;
    }

    /**
     * Fetches the result set of a executed MySQL query as an associative array.
     * The method binds the result columns to variables, iterates over the result set,
     * and collects the data into an associative array, where keys are column names
     * and values are their corresponding data. Closes the query after fetching.
     *
     * @return array Returns an associative array representing the query result set.
     *               If no data is found, an empty array is returned.
     */
    public function fetchArray() {
        $params = array();
        $row = array();
        $meta = $this->query->result_metadata();
        while ($field = $meta->fetch_field()) {
            $params[] = &$row[$field->name];
        }
        call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
        while ($this->query->fetch()) {
            foreach ($row as $key => $val) {
                $result[$key] = $val;
            }
        }
        $this->query->close();
        $this->query_closed = TRUE;
        return $result;
    }

    /**
     * Closes the active database connection.
     * This method terminates the current connection to the database and releases associated resources.
     *
     * @return bool Returns true on success or false on failure.
     */
    public function close() {
        return $this->connection->close();
    }

    /**
     * Retrieves the number of rows in the result set for the executed query.
     * The method requires the query results to be stored in memory before
     * accessing the row count.
     *
     * @return int The number of rows in the result set.
     */
    public function numRows() {
        $this->query->store_result();
        return $this->query->num_rows;
    }

    /**
     * Retrieves the number of rows affected by the last executed query.
     * This method provides the count of rows that were changed, deleted, or inserted
     * by the most recently executed query. Only applicable for queries that modify data.
     *
     * @return int The number of rows affected by the last query.
     */
    public function affectedRows() {
        return $this->query->affected_rows;
    }

    /**
     * Retrieves the ID generated by the last INSERT operation.
     * This method returns the auto-increment value generated by
     * the most recent INSERT statement executed on the database
     * connection.
     *
     * @return int|string The ID of the last inserted row. The return type
     * may vary depending on the database configuration.
     */
    public function lastInsertID() {
        return $this->connection->insert_id;
    }

    /**
     * Handles an error based on the current error display configuration.
     * If error display is enabled, the method outputs the error message
     * and terminates the script.
     *
     * @param string $error The error message to be displayed or processed.
     * @return void
     */
    public function error($error) {
        if ($this->show_errors) {
            exit($error);
        }
    }

    /**
     * Determines the data type of a given variable for use in parameter binding.
     * The method maps variable types to their corresponding MySQL data type specifiers.
     *
     * @param mixed $var The variable whose type is to be identified.
     * @return string Returns a single-character string representing the type:
     *                's' for string, 'd' for floating-point, 'i' for integer, and 'b' for binary data.
     */
    private function _gettype($var) {
        if (is_string($var)) return 's';
        if (is_float($var)) return 'd';
        if (is_int($var)) return 'i';
        return 'b';
    }
}