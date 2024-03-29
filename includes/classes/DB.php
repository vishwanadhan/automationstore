<?php

/**
 * DB.php
 * An interface which includes functions used to communicate with the Database.
 * 
 */
interface DB {
    
    public function error();

    public function errno();

    public static function escape_string($string);

    public function query($query);

    public function selectQry($table, $condition, $limitS, $limitE);

    public function executeQry($query);

    public function getResultRow($resourceid);

    public function getResultObject($resourceid);

    public function getTotalRow($resourceid);

    public function fetchValue($tbl, $field, $con);

    public function updateValue($tbl, $con, $val);

    public function singleValue($tbl, $con);

    public function deleteRec($table, $condition);

    public function insertQry();

    public function updateQry();

    public function fetch_array($result, $array_type = MYSQL_BOTH);

    public function fetch_row($result);

    public function fetch_assoc($result);

    public function fetch_object($result);

    public function num_rows($result);

    public function insert_id();
}
?>