<?php
/*** prepare版本 ****/
class MyPDO{

    private $db = null;
	private $error = array();
    private $debug = 0;	  //打开sql debug
    private $debug_stop = 0;	//是否中止sql
	private $lastid ;	//最后新增id
    private $dbname = null;

    function __construct($dbhost, $dbuser, $dbpass, $dbname, $dbtype = 'mysql', $charset = 'utf8', $dbport = '3306') {
		if ($dbtype == 'mysql') {
			try {
				$this->db = new PDO($dbtype . ':host=' . $dbhost . ';port=' . $dbport . ';dbname=' . $dbname, $dbuser, $dbpass, array(PDO::ATTR_PERSISTENT => true));
			} catch (PDOException $e) {
				die($e->getMessage());
			}
			$this->dbname = $dbname;
			$this->db->exec("SET NAMES '".$charset."';");
		} elseif ($dbtype == 'oracle') {
			//php_pdo_oci.dll  windows/oci.dll
			try {
				$this->db = new PDO('oci:dbname=' . $dbname, $dbuser, $dbpass);
			//$dbh = new PDO("OCI:dbname=accounts;charset=UTF8", "username", "password");
			} catch (PDOException $e) {
				die($e->getMessage());
			}
			//putenv("NLS_LANG=simplified chinese_china.zhs16gbk");   //防止乱码
		}
	
		if (!$this->db) {
			die("Can not connect the database!");
		}
		$this->db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
    }

    function __destruct() {
	unset($this->db);
    }

    /**
     * 	get one field data
     * 	@param string $wholesql or field 
     * 	@param string $table 
     * 	@param array $where[] = array(field,value,sign) 
     * 	@return int/string field data
     **/
    function getOne($sql,$table='',$where='') {
		//兼容以前直接$sql模式
		if($table && is_array($where)){
			$wherefield = '';
			$value = array();
			$num = 0;
			$sign = '';
			foreach ($where as $v) {
				//$vv = array("字段","值","符号,默认=");
				$num ++;
				$new_key = ":where{$num}";
				$sign = $v[2] ? $v[2] : '=';
				$wherefield .= $wherefield ? " AND {$v[0]} {$sign} {$new_key}" : "{$v[0]} {$sign} {$new_key}";
				$value[$new_key] = $v[1];
			}
			$sql = "SELECT {$sql} FROM " . $table . " WHERE ".$wherefield;
			//dump($value);
			$result = $this->db->prepare($sql);
			$this->checkDebug($sql,$value);
			$rs = $result->execute($value);
			
		}else{
			$this->checkDebug($sql);
			$result = $this->db->prepare($sql);
			$rs = $result->execute();
		}
		if ($rs)
		return $result->fetchColumn();
		
    }
   
   
    /**
     * 	get row data
     * 	@param string $wholesql or field 
     * 	@param string $table 
     * 	@param array $where[] = array(field,value,sign) 
     * 	@return array row data
     **/
    function getRow($sql, $table='',$where='') {
		if($table && is_array($where)){
			$wherefield = '';
			$value = array();
			$num = 0;
			$sign = '';
			foreach ($where as $v) {
				//$vv = array("字段","值","符号,默认=");
				$num ++;
				$new_key = ":where{$num}";
				$sign = $v[2] ? $v[2] : '=';
				$wherefield .= $wherefield ? " AND {$v[0]} {$sign} {$new_key}" : "{$v[0]} {$sign} {$new_key}";
				$value[$new_key] = $v[1];
			}
			$sql = "SELECT {$sql} FROM " . $table . " WHERE ".$wherefield;
			//dump($value);
			$result = $this->db->prepare($sql);
			$this->checkDebug($sql);
			$rs =$result->execute($value);
			
		}else{
			$this->checkDebug($sql);
			$result = $this->db->prepare($sql);
			$rs =$result->execute();
		}
		if ($rs)
		return $result->fetch(PDO::FETCH_ASSOC);
    }

     /**
     * 	get array data
     * 	@param string $wholesql or field 
     * 	@param string $table 
     * 	@param array $where[] = array(field,value,sign) 
	 * 	@param string $order 
     * 	@return array data
     **/
    function getAll($sql,$table='',$where='', $order='') {
		//die($sql);
		if($table && is_array($where)){
			$wherefield = '';
			$value = array();
			$num = 0;
			$sign = '';
			foreach ($where as $v) {
				//$vv = array("字段","值","符号,默认=");
				$num ++;
				$new_key = ":where{$num}";
				$sign = $v[2] ? $v[2] : '=';
				$wherefield .= $wherefield ? " AND {$v[0]} {$sign} {$new_key}" : "{$v[0]} {$sign} {$new_key}";
				$value[$new_key] = $v[1];
			}
			$sql = "SELECT {$sql} FROM " . $table . " WHERE ".$wherefield;
			
			if($order){
				$sql .= " ORDER BY ".$order;
			}
			//dump($value);
			$result = $this->db->prepare($sql);
			$this->checkDebug($sql);
			$rs = $result->execute($value);
			
		}else{
			$this->checkDebug($sql);
			$result = $this->db->prepare($sql);
			$rs = $result->execute();
		}
		if ($rs)
		return $result->fetchAll(PDO::FETCH_ASSOC);	

    }

	 /**
     * 	insert data
     * 	@param string $table 
     * 	@param array $data = array(key=>value) 
     * 	@return int lastInsertId
     **/
    function insert($table, $data) {
		//没作oracle 处理了, 自行把set改成 value形式吧
		if (!is_array($data) || !$table) {
			die("参数有误");
		}
		$field = '';
		$value = array();
		$num = 0;
		foreach ($data as $k => $v) {
			$num ++;
			$new_key = ":insert{$num}";
			$field .= $field ? ",{$k}={$new_key}" : "{$k}={$new_key}";
			$value[$new_key] = $v;
		}
		$sql = "INSERT INTO `" . $table . "` SET ".$field;
		$result = $this->db->prepare($sql);
		$this->checkDebug($sql,$value);
		$rs = $result->execute($value);
		if($rs){
			$this->lastid = $this->db->lastInsertId();
			return $this->lastid;
		}else{
			//$result->errorCode();
			$errors = $this->error;
			$errors[] = $result->errorInfo();
			$this->error = $errors;
			//$this->error =array_merge($this->error,$result->errorInfo());
			return false;
		}
    }
	
	//只作兼容原来代码,待删除
	function getInsertId() {
		return $this->lastid;
    }
	
	/**
     * 	delete data
     * 	@param string $table 
     * 	@param string/array  wholesql  or $where[] = array(field,value,sign)  
     * 	@return int affected count
     **/
	function delete($table, $where) {
		if ($table && is_array($where)) {
		   	$wherefield = '';
			$value = array();
			$num = 0;
			$sign = '';
			foreach ($where as $v) {
				//$v = array("字段","值","符号,默认=");
				$num ++;
				$new_key = ":where{$num}";
				$sign = $v[2] ? $v[2] : '=';
				$wherefield .= $wherefield ? " AND {$v[0]} {$sign} {$new_key}" : "{$v[0]} {$sign} {$new_key}";
				$value[$new_key] = $v[1];
			}
			$sql = "DELETE FROM `" . $table . "` WHERE ".$wherefield;
			//dump($value);
			$result = $this->db->prepare($sql);
			$this->checkDebug($sql,$value);
			$rs = $result->execute($value);
		}else{
			$sql = "DELETE FROM `" . $table . "` WHERE " . $where;
			$result = $this->db->prepare($sql);
			$this->checkDebug($sql);
			$rs = $result->execute();
		}

		if($rs){
			return $result->rowCount();;
		}else{
			//$result->errorCode();
			$errors = $this->error;
			$errors[] = $result->errorInfo();
			$this->error = $errors;
			return false;
			//$this->error =array_merge($this->error,$result->errorInfo());
		}
    }
	
	/**
     * 	update data
     * 	@param string $table 
	 * 	@param array $data = array(key=>value) 
     * 	@param string/array  wholesql  or $where[] = array(field,value,sign)  
     * 	@return int affected count
     **/
    function update($table, $data, $where) {
		if (!is_array($data) || !$table || !$where) {
			die("参数有误");
		}
		$list = '';
		
		//兼容直接where 字符串模式.但要注意注入问题
		if(is_array($where)){
			$field = '';
			$wherefield = '';
			$value = array();
			$num = 0;
			$sign = '';
			foreach ($data as $k => $v) {
				$num ++;
				$new_key = ":update{$num}";
				$field .= $field ? ",{$k}={$new_key}" : "{$k}={$new_key}";
				$value[$new_key] = $v;
			}
			
			$num = 0;
			foreach ($where as $vv) {
				//$vv = array("字段","值","符号,默认=");
				$num ++;
				$new_key = ":where{$num}";
				$sign = $vv[2] ? $vv[2] : '=';
				$wherefield .= $wherefield ? " AND {$vv[0]} {$sign} {$new_key}" : "{$vv[0]} {$sign} {$new_key}";
				$value[$new_key] = $vv[1];
			}
			
			$sql = "UPDATE `" . $table . "` SET ".$field . " WHERE ".$wherefield;
			//dump($value);
			$result = $this->db->prepare($sql);
			$this->checkDebug($sql,$value);
			$rs = $result->execute($value);
		}else{
			foreach ($data as $k => $v) {
				if (strstr($v, $k)) {
				$list .= '`' . $k . '` = ' . $v . ',';      //处理增量方式
				} else {
				$list .= '`' . $k . '` = "' . $v. '" ,';
				}
			}
			$list = rtrim($list, ',');
			$sql = "UPDATE `" . $table . "` SET " . $list . " WHERE " . $where;
			
			$result = $this->db->prepare($sql);
			$this->checkDebug($sql);
			$rs = $result->execute();
		
		}
		
		if($rs){
			return $result->rowCount();;
		}else{
			//$result->errorCode();
			$errors = $this->error;
			$errors[] = $result->errorInfo();
			$this->error = $errors;
			return false;
			//$this->error =array_merge($this->error,$result->errorInfo());
		}
    }
	
	/**
     * 	get error - use after the execute
     * 	@return array errorinfo
     **/
	
	public function error() {
		return $this->error;
    }

	/**
     * 	get the Field from table
	 * 	@param string $table 
     * 	@return array field name row
     **/
    private function getField($table) {
		$sql = "SELECT COLUMN_NAME,COLUMN_TYPE FROM `information_schema`.`COLUMNS` WHERE `TABLE_SCHEMA`='" . $this->dbname . "' AND `TABLE_NAME`='" . $table . "'";
		$result = $this->db->prepare($sql);
		$rs = $result->execute();
		if ($rs)
			return $result->fetchAll($type);
    }

    /**
     * 	check whether post the table has ,then output these data
	 * 	@param string $table 
	 * 	@param array $post data 
     * 	@return array data row
     **/
    public function getFormData($table, $post) {
		$fields = $this->getField($table);
		
		$data = array();
		if ($fields) {
			foreach ($fields as $v) {
			$filed = $v['COLUMN_NAME'];
			if (isset($post[$filed]) && !is_array($post[$filed])) {   //数组值不处理
				$data[$filed] = $this->formatType($v['COLUMN_TYPE'],$post[$filed]);
			}
			}
		}
		return $data;
    }

	/**
     * 	formatType ,get the default value
	 * 	@param string $type 
	 * 	@param string $value 
     * 	@return string formatdata
     **/
	private function formatType($type,$value){
		if($type=='date'){
			return $value ? $value : '0000-00-00';
		}elseif($type=='datetime'){
			return $value ? $value : '0000-00-00 00:00:00';
		}elseif(strstr($type,'decimal')){
			return $value ? floatval($value) : '0';
		}elseif(strstr($type,'int')){
			return intval($value);	
		}else{
			return trim($value);
		}
	}

    /**
     * 	checkdebug
	 * 	@param string $sql 
	 * 	@param string $valuelist  	
     **/
    private function checkDebug($sql,$value=null) {
	if ($this->debug) {
	    echo $sql . '<br />';
		if($value){
			echo "<pre>";	
			print_r($value);
			echo "</pre>";
		}
	}
	if ($this->debug_stop) {
	    exit;
	}
    }

    /**
     * 	set if debug  -setup before execute
	 * 	@param int $type 
	 * 	@param int $stop  	
     **/
    public function debug($type = 1, $stop = 0) {
	$this->debug = intval($type);
	if (intval($stop)) {
	    $this->debug_stop = 1;
	}
    }

    /**
     * 	safe code
	 * 	@param string $str 
	 * 	@return string $str  	
     **/
    public function safe($str) {
	    return $this->db->quote($str);
    }


     /**
     * 	beginTransaction	
     **/
    public function begin() {
	$this->db->beginTransaction();
    }

    /**
     * 	rollBack	
     **/
    public function back() {
	$this->db->rollBack();
    }

     /**
     * 	commit	
     **/
    public function commit() {
	$this->db->commit();
    }

}

/* 
header('Content-type: text/html; charset=utf-8'); 
require('mysql_pdo_class.php');						//注意名称与实例名有可能不一样			
$db = new MyPDO($cf_db_setting['host'], $cf_db_setting['user'], $cf_db_setting['pwd'], $cf_db_setting['dbname']);

１.添加
$insert1 = array(
	'au_spid'=>'1','au_content'=>'abc'
);
$rs1 = $db->insert($table,$insert1);
返回最后插入的id

２.修改
$update = array(
    'au_spid'=>'1','au_content'=>'abc'
);
$where[]= array('au_id','10');         //第三个参数默认为'='
$where[]= array('au_spid','2');
$rs1 = $db->update($table, $update, $where);
          返回影响结果数

３.删除
$where[]= array('au_id','10');
$rs1 = $db->delete($table, $where);
          返回影响结果数年

４.取一个字段
$field = "au_id";
$where[]= array('au_id','10');
$where[]= array('au_spid','2','>');
$data = $db->getOne($field,$table,$where);
          返回第一个字段的值

５.取一维数组，一条记录数据
$field = "au_id,au_title";
$where[]= array('au_id','10');
$where[]= array('au_title','%str%','like');
$data = $db->getRow($field,$table,$where);
          返回一组数据

６.取二维数据，多条数据
$field = "au_id,au_title";
$where[]= array('au_id','10');
$order = "au_id desc";
$data = $db->getAll($field,$table,$where, $order);
          返回多组数据

７.事务
$db->begin();                //开始事务
$rs = $db->insert(xxxx)    //操作1
.....                                        //操作2
if($ok){
    $db->commit();        //提交
}else{
    $db->back();            //回滚 ,取消
}

８.debug 查看sql
        $db->debug(1,1);            //第二个参数是否中止, 必须要执行操作之前
        $rs = $db->insert(xxxx)    //操作
        //以下为显示 sql 及数据

９.打印错误
$rs = $db->insert(xxxx)    //操作  
if($rs===false){
	print_r($db->error());	    //如事务失败后打印错误
}

10. 懒人获取数据, 注意安全~ , 真实的映射就自行编写~~
a) 表单中的表单名与数据表$table 字段名相同 
b) 提交目标加上 $data = $db->getFormData($table,$_POST);
c) 此时$data 就是数据表$tabale与表单 相同字段的数据, 然后就可以直接插入数据库了.
$rs = $db->insert($table,$data);
*/
?>
