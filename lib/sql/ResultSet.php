<?php
namespace lib\sql;

/**
 * A table of data representing a database result set, which
 * is usually generated by executing a statement that queries the database.
 * @author PhpGenerator (https://github.com/leplutonien/php_generator)
 */
class ResultSet{
    private $statement;
    private $resultRows = null;
    private $currentPosition;
    private $status = false;
    private $fetchStyle;

    public function __construct(\PDOStatement $statement, $fetchStyle = null)
    {
        if(is_null($fetchStyle))
            $this->fetchStyle = FetchStyle::$FETCH_ASSOC;
        else
            $this->fetchStyle = $fetchStyle;

        $this->statement = $statement;
        $this->status = $this->statement->execute();
    }

    /**
     * Get status of QUERY after execute
     * @return boolean
     */
    public function getStatus(){
        return $this->status;
    }

    /**
     * Moves the cursor froward one row from its current position.
     * @return bool
     */
    public function nextRow()
    {
        if (is_null($this->resultRows))
            $this->setAllRow();

        if ($this->currentPosition == $this->CountRowReturn()) {
            return false;
        }
        $row = $this->resultRows[$this->currentPosition];
        $this->currentPosition++;
        return $row;
    }

    /**
     * Set a records
     */
    private function setAllRow()
    {
        if (is_null($this->resultRows)) {
            if ($this->fetchStyle == FetchStyle::$FETCH_OBJ)
                $this->resultRows = $this->statement->fetchAll(\PDO::FETCH_OBJ);
            else
                $this->resultRows = $this->statement->fetchAll(\PDO::FETCH_ASSOC);

            $this->currentPosition = 0;
        }
    }

    /**
     * Get count of row of query result
     * @return int
     */
    public function CountRowReturn()
    {
        if (is_null($this->resultRows))
            $this->setAllRow();
        return count($this->resultRows);
    }

    /**
     * @return get all records
     */
    public function getAllRows(){
        if (is_null($this->resultRows))
            $this->setAllRow();
        return $this->resultRows;
    }

    /**
     * Return PDOStatement relative to this result
     * @return \PDOStatement
     */
    public function getStatement(){

        return $this->statement;
    }
}
?>