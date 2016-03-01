<?php
/*
    [FRCMS] Copyright (c) 2010 Finereason.com
    This is NOT a freeware, use is subject to license.txt
*/

class db_mysql {
    var $connid;
    var $querynum = 0;
    var $expires;
    var $cursor = 0;
    var $cache_id = ''; 
    var $cache_file = '';
    var $cache_expires = '';
    var $halt = 0;
    var $result = array();

    function connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect = 0) {

        global $cfg;
        $this->expires = $cfg['db_expires'];
        $func = $pconnect == 1 ? 'mysql_pconnect' : 'mysql_connect';
        if(!$this->connid = $func($dbhost, $dbuser, $dbpw)) {
            $this->halt('Can not connect to MySQL server');
        }
        if($this->version() > '4.1' && $cfg['db_charset']) {
            mysql_query("SET NAMES '".$cfg['db_charset']."'" , $this->connid);
        }
        if($this->version() > '5.0') {
            mysql_query("SET sql_mode=''" , $this->connid);
        }
        if($dbname) {
            if(!mysql_select_db($dbname , $this->connid)) {
                $this->halt('Cannot use database '.$dbname);
            }
        }
        return $this->connid;
    }

    function select_db($dbname) {
        return mysql_select_db($dbname , $this->connid);
    }

    function query($sql , $type = '', $expires = 0, $save_id = false,$check = true) {
        if($check){
            $sql=checksql($sql);
        }
        if($type == 'CACHE' && stristr($sql, 'SELECT')) {
            $this->cursor = 0;
            $this->cache_id = md5($sql);
            $this->result = array();
            $this->cache_expires = $expires ? $expires + mt_rand(-9, 9) : $this->expires;
            return $this->_query($sql);
        }
        if(!$save_id) $this->cache_id = 0;
        $func = $type == 'UNBUFFERED' ? 'mysql_unbuffered_query' : 'mysql_query';
        if(!($query = $func($sql , $this->connid)) && $this->halt) {
            $this->halt('MySQL Query Error', $sql);
        }
        $this->querynum++;
        return $query;
    }

    function get_one($sql, $type = '', $expires = 0) {
        $query = $this->query($sql, $type, $expires);
        $r = $this->fetch_array($query);
        $this->free_result($query);
        return $r ;
    }

        //gaorong 2012-02-12
    function get_all($sql, $type = '', $expires = 0) {
        $result = array();
        $query = $this->query($sql, $type, $expires);   
        while($r = $this->fetch_array($query)){
            $result[] = $r; 
        }
        $this->free_result($query); 
        return $result;
        
    }
    
    function counter($table, $condition = '', $type = '', $expires = 0) {
        global $cfg;
        //$table = strpos($table, $cfg['tb_pre']) === false ? $cfg['tb_pre'].$table : $table;
        $sql = "SELECT COUNT(*) as num FROM {$table}";
        if($condition) $sql .= " WHERE $condition";
        $r = $this->get_one($sql, $type, $expires);
        return $r ? $r['num'] : 0;
    }

    function fetch_array($query, $result_type = MYSQL_ASSOC) {
        return $this->cache_id ? $this->_fetch_array($query) : @mysql_fetch_array($query, $result_type);
    }

    function affected_rows() {
        return mysql_affected_rows($this->connid);
    }

    function num_rows($query) {
        return mysql_num_rows($query);
    }

    function num_fields($query) {
        return mysql_num_fields($query);
    }
    function escape_string($str){
        return mysql_escape_string($str);
    }
    function result($query, $row) {
        return @mysql_result($query, $row);
    }

    function free_result($query) {
        return @mysql_free_result($query);
    }

    function insert_id() {
        return mysql_insert_id($this->connid);
    }

    function fetch_row($query) {
        return mysql_fetch_row($query);
    }

    function version() {
        return mysql_get_server_info($this->connid);
    }

    function close() {
        return mysql_close($this->connid);
    }

    function error() {
        return @mysql_error($this->connid);
    }

    function errno() {
        return intval(@mysql_errno($this->connid)) ;
    }

    function halt($message = '', $sql = '') {
        global $cfg;
        if($message) {
            if($cfg['errlog']) {
                $log = "query:$sql|errno:".$this->errno()."|error:".$this->error()."|errmsg:$message";
                log_write($log, 'sql');
            }
        }
        showmsg("MysqlError:$message",'-1');
        exit();
    }

    function _query($sql) {
        global $fr_time;
        $this->cache_file = CACHE_ROOT.'/sql/'.substr($this->cache_id, 0, 1).'/'.$this->cache_id.'.php';
        if(!is_file($this->cache_file) || ($fr_time - @filemtime($this->cache_file) > $this->cache_expires)) {
            $tmp = array(); 
            $result = $this->query($sql, '', '', true);
            while($r = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $tmp[] = $r; 
            }
            $this->result = $tmp;
            $this->free_result($result);
            file_put($this->cache_file, "<?php /*".( $fr_time+$this->cache_expires)."*/ return ".var_export($this->result, true).";\n?>");
        } else {
            $this->result = include $this->cache_file;
        }
        return $this->result;
    }

    function _fetch_array($query = array()) {
        if($query) $this->result = $query; 
        if(isset($this->result[$this->cursor])) {
            return $this->result[$this->cursor++];
        } else {
            $this->cursor = $this->cache_id = 0;
            return array();
        }
    }
}

function checksql($dbstr,$querytype='select'){
    $clean = '';
    $old_pos = 0;
    $pos = -1;
    //普通语句，直接过滤特殊语法
    if($querytype=='select'){
        $nastr = "/[^0-9a-z@\._-]{1,}(union|sleep|benchmark|load_file|outfile)[^0-9a-z@\.-]{1,}/i";
        if(preg_match($nastr,$dbstr)){
            log_write($dbstr,'sql');
            showmsg('SafeError:10001', 'javascript:;');
            exit();
        }
    }
    //完整的SQL检查
    while (true){
        $pos = strpos($dbstr, '\'', $pos + 1);
        if ($pos === false){
            break;
        }
        $clean .= substr($dbstr, $old_pos, $pos - $old_pos);
        while (true){
            $pos1 = strpos($dbstr, '\'', $pos + 1);
            $pos2 = strpos($dbstr, '\\', $pos + 1);
            if ($pos1 === false){
                break;
            }
            elseif ($pos2 == false || $pos2 > $pos1){
                $pos = $pos1;
                break;
            }
            $pos = $pos2 + 1;
        }
        $clean .= '$s$';
        $old_pos = $pos + 1;
    }
    $clean .= substr($dbstr, $old_pos);
    $clean = trim(strtolower(preg_replace(array('~\s+~s' ), array(' '), $clean)));
    if (strpos($clean, 'union') !== false && preg_match('~(^|[^a-z])union($|[^[a-z])~s', $clean) != 0){
        $fail = true;
    }
    elseif (strpos($clean, '/*') > 2 || strpos($clean, '--') !== false || strpos($clean, '#') !== false){
        $fail = true;
    }
    elseif (strpos($clean, 'sleep') !== false && preg_match('~(^|[^a-z])sleep($|[^[a-z])~s', $clean) != 0){
        $fail = true;
    }
    elseif (strpos($clean, 'benchmark') !== false && preg_match('~(^|[^a-z])benchmark($|[^[a-z])~s', $clean) != 0){
        $fail = true;
    }
    elseif (strpos($clean, 'load_file') !== false && preg_match('~(^|[^a-z])load_file($|[^[a-z])~s', $clean) != 0){
        $fail = true;
    }
    elseif (strpos($clean, 'into outfile') !== false && preg_match('~(^|[^a-z])into\s+outfile($|[^[a-z])~s', $clean) != 0){
        $fail = true;
    }
    elseif (preg_match('~\([^)]*?select~s', $clean) != 0){
        $fail = true;
    }
    if (!empty($fail)){
        log_write($dbstr,'sql');
        showmsg('SafeError:10002', 'javascript:;');exit;
    }
    else
    {
        return $dbstr;
    }
}
?>
