/*
db.with.class.php

Allows the use of multiple database types using one class
*/

class Database
{
    var $mInsertId;             // holds last insert id. Only applies to MySQL
    var $mQueries     = array(); // holds all queries
    var $mType         = 'mysql'; // holds database type. Used to execute functions
    var $mQueryCount = 0;         // holds number of queries executed
    var $mConn;                 // holds connection identifier

// sets type and connects

    function Database($type='mysql', $host='localhost', $userName, $passWord, $dataBase, $port=false)
    {
        switch ($type)
        {
            case 'mysql':
            case 'my':
                $this->mType = 'mysql';
                $this->mConn = mysql_connect($host, $userName, $passWord);
                mysql_select_db($dataBase, $this->mConn);
                break;
            case 'post':
            case 'pgsql':
            case 'pg':
                $this->mType = 'pg';
                if (false != $port) $port = ' port='.$port;
                else $port = '';
                $this->mConn = pg_connect("host=$host".$port." user=$userName password=$passWord dbname=$dataBase");
                break;
            case 'msql':
            case 'm':
                $this->mType = 'msql';
                $this->mConn = msql_connect($host, $userName, $passWord);
                msql_select_db($dataBase, $this->mConn);
                break;
            case 'mssql':
            case 'ms':
                $this->mType = 'mssql';
                $this->mConn = mssql_connect($host, $userName, $passWord);
                mssql_select_db($dataBase, $this->mConn);
                break;
            case 'oracle':
            case 'ora':
                $this->mType = 'ora';
                $this->mConn = ora_logon($userName, $passWord);
                break;
            case 'interbase':
            case 'ibase':
            case 'inter':
                $this->mType = 'ibase';
                $this->mConn = ibase_connect($host, $userName, $passWord);
                break;
            case 'sybase':
            case 'sbase':
                $this->mType = 'sybase';
                $this->mConn = sybase_connect($host, $userName, $passWord);
                sybase_select_db($dataBase, $this->mConn);
                break;
            default:
                die('Did not recognize database type.');
        }
    }        

// queries database

    function Query($query)
    {
        switch ($this->mType)
        {
            case 'mysql':
            case 'msql':
            case 'mssql':
            case 'sybase':
                eval('$result = '.$this->mType.'_query($query, $this->mConn);');
                break;
            case 'pg':
            case 'ibase':
                eval('$result = '.$this->mType.'_query($this->mConn, $query);');
                break;
            case 'ora':
                $result = ora_open($this->mConn);
                $parse = ora_parse($result, $query);
                $exec = ora_exec($result);
                break;
        }
        if ('mysql' == $this->mType) $this->mInsertId = mysql_insert_id($this->mConn);
        $this->mQueryCount++;
        $this->mQueries[$this->mQueryCount] = $query;
        return $result;
    }
    
// fetches associative array of result
// tries to fetch in most compatible way

    function FetchAssoc($result, $row=NULL)
    {
        switch ($this->mType)
        {
            case 'mysql':
            case 'msql':
            case 'mssql':
                $type = strtoupper($this->mType);
                eval('$row = '.$this->mType.'_fetch_array($result, '.$type.'_ASSOC);');
                break;
            case 'pg':
                $row = pg_fetch_array($result, $row, PGSQL_ASSOC);
                break;
            case 'ibase':
                $row = ibase_fetch_assoc($result);
                break;
            case 'sybase':
                $row = sybase_fetch_array($result);
                break;
            case 'ora':
                $row = array();
                ora_fetch_into($result, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
                break;
        }
        return $row;
    }
    
// fetches numerically indexed array of result

    function FetchRow($result, $row=NULL)
    {
        switch ($this->mType)
        {
            case 'mysql':
            case 'msql':
            case 'mssql':
            case 'ibase':
            case 'sybase':
                eval('$row = '.$this->mType.'_fetch_row($result);');
                break;
            case 'pg':
                $row = pg_fetch_array($result, $row, PGSQL_NUM);
                break;
            case 'ora':
                $row = array();
                ora_fetch_into($result, $row, ORA_FETCHINTO_NULLS);
                break;
        }
        return $row;
    }
    
// gets rows affected by a query.
// will output error with oracle, as no function is defined for this process.
    
    function AffectedRows($result)
    {
        switch ($this->mType)
        {
            case 'mysql':
            case 'msql':
            case 'sybase':
            case 'ibase':
            case 'pg':
                eval('$num = '.$this->mType.'_affected_rows($result);');
                break;
            case 'mssql':
                $num = mssql_rows_affected($result);
                break;
            case 'oracle':
                print 'Cannot get affected rows. Function does not exist.';
                return;
                break;
        }
        return $num;
    }
    
// gets rows returned by a query.
// will output error with interbase, as no function is defined for this process.
    
    function NumRows($result)
    {
        switch ($this->mType)
        {
            case 'mysql':
            case 'msql':
            case 'pg':
            case 'mssql':
            case 'sybase':
                eval('$rows = '.$this->mType.'_num_rows($result);');
                break;
            case 'ora':
                $rows = ora_numrows($result);
                break;
            case 'ibase':
                print 'Cannot get number of rows. Function does not exist.';
                return;
                break;
        }
        return $rows;
    }
    
// gets either a specific query or the entire set
    
    function Queries($key=false)
    {
        if (false !== $key)
        {
            return $this->mQueries[$key];
        }
        else
        {
            return $this->mQueries;
        }
    }
    
// returns the last insert id.
// if the db type isn't mysql, this won't work
    
    function InsertId()
    {
        if ('mysql' == $this->mType)
        {
            return $this->mInsertId;
        }
        else
        {
            print 'Insert ID only works for MySQL. Sorry.';
            return;
        }
    }

}

?>