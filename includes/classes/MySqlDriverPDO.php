<?php
global $config;
@include("config/configure.php");
include("config/tables.php");

class MySqlDriverPDO
{
	var $sql;
	var $rs;
	var $numrows;
	var $limit;
   	var $noofpage;
	var $offset;
	var $page;
	var $style;
	var $parameter;
	var $activestyle;
	var $buttonstyle;
	var $field_values;
	var $tablename;
	var $dbh;
	private $host;
	private $user;
	private $pass;
	private $database;
	private $cnx;

    function __construct() {	
	    global $config; 
	    global $log;
        global $flag;	
        global $tags;
        global $type;	
		$this->host = $config['server'];
		$this->user = $config['user'];
		$this->pass = $config['pass'];
		$this->database = $config['database'];
		
		try {
     		$this->dbh = new PDO("mysql:host=$this->host;dbname=$this->database", $this->user, $this->pass);
		}
    	catch ( PDOException $e ) {
        	echo $e->getMessage();
    	}
    }	
			
	// Select Query
	function selectQry($table,$condition,$limitS,$limitE) 
	{	    
		if(!$condition){
			if((!$limitS) && (!$limitE))
				$sql="select * from ".$table;
			else
				$sql="select * from ".$table." limit ".$limitS.", ".$limitE;
		}
		else {
			if((!$limitS) && (!$limitE))
				$sql="select * from ".$table." where ".$condition;
			else
				$sql="select * from ".$table." where ".$condition." limit ".$limitS.", ".$limitE;
		}
		$dataSet=$this->executeQry($sql);
		return $dataSet;
	}
	
	// Select With Order By Condition//////////////////////////////////////////////////
	
	function selectOrderQry($table,$condition,$orderby,$limitS,$limitE) 
	{
		if(!$condition)
		{
			if((!$limitS) && (!$limitE))
				$sql="select * from ".$table." order by ".$orderby;
			else
				$sql="select * from ".$table." order by ".$orderby." limit ".$limitS.", ".$limitE;
		}
		else 
		{
			if((!$limitS) && (!$limitE))
				$sql="select * from ".$table." where ".$condition." order by ".$orderby;
			else
				$sql="select * from ".$table." where ".$condition." order by ".$orderby." limit ".$limitS.", ".$limitE;
		}
		$dataSet=$this->executeQry($sql);
		return $dataSet;
	}
	
	///////////////////////////////////////////////////////////////////////
	
	
	function editRec($table,$frmval,$condition)
	{
		$sql="update ".$table." set ".$frmval." where ".$condition;
		$dataSet=$this->executeQry($sql);
		return $dataSet;
	}

	function executeQry($sql) 
	{
		if (!is_string($sql)) {
            //throw new Exception("Illegal parameter. Must be string.");
			die("Illegal parameter. Must be string.");
        }
		else {	
			$rsSet = $this->dbh->prepare($sql); //mysql_query($sql);	
		}
		if (!$rsSet) 
			//$this->throwMysqlException();
			die(@$this->dbh->errorCode());			
		else
			return $rsSet;		
	}
	
	function insert_id()
	{
		return $this->dbh->lastInsertId();
	}
	
	function deleteval($tbl,$con){
		$sql="delete from ".$tbl." where ".$con;
		$result= $this->query($sql) or die(errorInfo());
	}	
	function getResultRow($DataSet) 
	{
		return $DataSet->fetch();
	}
	function getResultObject($DataSet) 
	{
		return $DataSet->fetch(PDO::FETCH_OBJ);
	}
	function getTotalRow($DataSet) {
		return $DataSet->rowCount();
	}
	function getTotalColumn($DataSet) {
		return $DataSet->columnCount();
	}
	function addParameter($param)
	{
		$allParam=$allParam;
	}
	
	function throwMysqlException($addToMessage = "") {
        if (is_string($addToMessage)) {
            $message = $addToMessage ." \n". @$this->dbh->errorInfo();
        }
        else {
            $message = @$this->dbh->errorInfo();
        }
        //throw new Exception($message, @mysql_errno($this->dbConnect()));
		die(@$this->dbh->errorInfo());
    }

    function fetchValue($tbl, $field, $con){
		$sql="select ".$field." from ".$tbl." where ".$con;	
        $result = $this->query($sql) or die(errorInfo());
		$rec=$result->fetch(PDO::FETCH_OBJ);
		$val=$rec->$field;
	    return $val;	
    }

    function updateValue($tbl,$con,$val){
		$sql="update ".$tbl." set ".$val." where ".$con;
		$result= $this->query($sql) or die(errorInfo());
    }	

    function singleValue($tbl,$con){
		$sql="select * from ".$tbl." where ".$con;
        $result = $this->query($sql) or die(errorInfo());
		$rec = $result->fetch(PDO::FETCH_OBJ);
	    return $rec;	
    }

    function deleteRec($table,$condition)
    {
		if(!$condition){
			$sql="delete from ".$table;
		}
		else{
			$sql="delete from ".$table." where ".$condition;
		}
		$dataSet=$this->executeQry($sql);
		return $dataSet;
    }
	
	function insertQry()
	{
		$str_f		=		"("	;
		$str_v		=		"("	; 
		$i			=		0	; 				
		foreach($this->field_values as $key => $val)
		{
			if($i==0){
				$key="`".$key."`";
				if($val == "now()")
				$val=$val;
				else
				$val="'".$val."'";
			}else{
				$key=", `".$key."`";
				if($val == "now()")
				$val=", ".$val;
				else
				$val=", '".$val."'";
			}
			$str_f.= $key;
			$str_v.= $val;
			$i++;
		}  
		$str_f		=		$str_f.")";
		$str_v		=		$str_v.")";					 
		$str		=		"INSERT INTO ".$this->tablename." ".$str_f." VALUES ".$str_v;
		$q[0]		= 		$this->executeQry($str); 
		$q[1]		=		$this->insert_id();

		return $q;
	}


	function updateQry()
	{
		$str="";
		$i=0; 				
		foreach($this->field_values as $key => $val)
		{
			if($i==0){
				if($val == "now()")
					$val=$val;
				else
					$val="'".$val."'";
			$key="`".$key."`";
			}else{
				if($val == "now()")
					$val	=	$val;
				else
					$val	=	"'".$val."'";
				$key=", `".$key."`";
			}
			$str.=$key." = ".$val;
			$i++;
		}  
		if($this->condition=='')
			$str		=	"UPDATE ".$this->tablename." "." SET ".$str;
		else
			$str		=	"UPDATE ".$this->tablename." "." SET ".$str." WHERE ".$this->condition;
		$result_up	=	$this->executeQry($str);
		return $result_up;
	}
	
	
// Paging Test	
	
	function paging($query) {
			$this->offset=0;
			$this->page=1;
			$this->sql=$query;
			$this->rs= $this->query($this->sql);
			$this->numrows=$this->rs->rowCount();
	}
	function getNumRows() {
			return $this->numrows;
	}
	function setLimit($no) {
	        if($no)  
			$this->limit=$no;
			else
			$this->limit=10;
	}
	function getLimit() {
			return $this->limit;
	}
	function getNoOfPages() {
			return ceil($this->noofpage=($this->getNumRows()/$this->getLimit()));
	} 
	function getPageNo() {
			
			if($this->getNumRows() > $this->getLimit()){
			
			$str="";
			$str=$str."<table width='100%' border='0' ><tr>";
			$str=$str."<td width='100%'  valign='center' height='25'>";
			if($this->getPage()>1) {
				$str=$str."<a href='".$_SERVER['PHP_SELF']."?page=".($this->getPage()-1).$this->getParameter()."' class='".$this->getStyle()."'>Prev</a>&nbsp;";
			}
			
			if($this->getPage() > 6)
			{   
			    $l = 1;
				for($i=$this->getPage()-1;$i>0;$i--) {
					$arr[] = "<a href='".$_SERVER['PHP_SELF']."?page=".$i.$this->getParameter()."' class='".$this->getStyle()."'>".$i."</a>&nbsp;";
					if($l == 5)
					break;
					$l++;
				}
				if($this->getNoOfPages()-$this->getPage() < 5)
				{
				   $start = $i -1;
				   $diff = $this->getNoOfPages()-$this->getPage();
				   $loop = 5-$diff;
				   for($m = 1; $m<=$loop; $m++) {
				     if($start>0)
				     $arr[] = "<a href='".$_SERVER['PHP_SELF']."?page=".$start.$this->getParameter()."' class='".$this->getStyle()."'>".$start."</a>&nbsp;"; 
				     $start--;
					} 
				}
				$arrrev = array_reverse($arr);
				foreach($arrrev as $val)
				  $str = $str.$val;
			}
			
			$current = $this->getPage();
			if($current > 6)
			{   
			    $k = 1;
				for($i=$current;$i<=$this->getNoOfPages();$i++) {
					if($i==$this->getPage()) {
						$str=$str."<span class='".$this->getActiveStyle()."'>".$i."&nbsp;</span>";
					}
					else {
						$str=$str."<a href='".$_SERVER['PHP_SELF']."?page=".$i.$this->getParameter()."' class='".$this->getStyle()."'>".$i."</a>&nbsp;";
					}
					if($k == 6)
					break;
					$k++;
				}
			}
			else
			{
				$j = 1;
				for($i=1;$i<=$this->getNoOfPages();$i++) {
					if($i==$this->getPage()) {
					if($i != "1"){
					$str=$str."|&nbsp";
					}
						$str=$str."&nbsp;<span class='".$this->getActiveStyle()."'>".$i."&nbsp;</span>";
					}
					else {
						$str=$str."|&nbsp;<a href='".$_SERVER['PHP_SELF']."?page=".$i.$this->getParameter()."' class='".$this->getStyle()."'>".$i."</a>&nbsp;";
					}
				   if($j == 11)
				   break;
				   $j++;
				}
			  if($this->getNoOfPages() > $i+1)
			  {
			    $str=$str."<a href='".$_SERVER['PHP_SELF']."?page=".($i+1).$this->getParameter()."' class='".$this->getStyle()."'>.. </a>";
			  }
				
			
			}
			
			if($this->getPage()<$this->getNoOfPages()) {
				$str=$str."|<a href='".$_SERVER['PHP_SELF']."?page=".($this->getPage()+1).$this->getParameter()."' class='".$this->getStyle()."'> Next</a>";
			}
			$str=$str."</td>";
			$str=$str."</tr></table>";
			}
			return @$str;
	}
	function getPageNo1() {
			$str="";
			$str=$str."<table   border='0' cellspacing='0' cellpadding='0'><tr>";
			$str=$str."<td width='100%'  valign='top' >";
			if($this->numrows==$_GET[limit])
			{
			$str=$str."<span class='blacktext2'><a href='".$_SERVER['PHP_SELF']."?page=1".$this->getParameter()."' class='blacktext2'> View in Paging</a>&nbsp;|&nbsp;</span>";
			}
			else 
			{
		$str=$str."<span class='blacktext2'><a href='".$_SERVER['PHP_SELF']."?limit=". $this->numrows."&".$this->getParameter()."' class='blacktext2'> View All</a>&nbsp;|&nbsp;</span>";
			}
			
			
			if($this->getPage()>1) {
				$str=$str."<a href='".$_SERVER['PHP_SELF']."?page=".($this->getPage()-1).$this->getParameter()."' class='".$this->getStyle()."'>Prev</a>|&nbsp;";
			}
			
			if($this->getPage() > 6)
			{   
			    $l = 1;
				for($i=$this->getPage()-1;$i>0;$i--) {
					$arr[] = "<a href='".$_SERVER['PHP_SELF']."?page=".$i.$this->getParameter()."' class='".$this->getStyle()."'>".$i."</a>&nbsp;";
					if($l == 5)
					break;
					$l++;
				}
				if($this->getNoOfPages()-$this->getPage() < 5)
				{
				   $start = $i -1;
				   $diff = $this->getNoOfPages()-$this->getPage();
				   $loop = 5-$diff;
				   for($m = 1; $m<=$loop; $m++) {
				     if($start>0)
				     $arr[] = "<a href='".$_SERVER['PHP_SELF']."?page=".$start.$this->getParameter()."' class='".$this->getStyle()."'>".$start."</a>&nbsp;"; 
				     $start--;
					} 
				}
				$arrrev = array_reverse($arr);
				foreach($arrrev as $val)
				  $str = $str.$val;
			}
			
			$current = $this->getPage();
			if($current > 6)
			{   
			    $k = 1;
				for($i=$current;$i<=$this->getNoOfPages();$i++) {
					if($i==$this->getPage()) {
						$str=$str."<span class='".$this->getActiveStyle()."'>".$i."&nbsp;</span>";
					}
					else {
						$str=$str."<a href='".$_SERVER['PHP_SELF']."?page=".$i.$this->getParameter()."' class='".$this->getStyle()."'>".$i."</a>&nbsp;";
					}
					if($k == 6)
					break;
					$k++;
				}
			}
			else
			{
				$j = 1;
				for($i=1;$i<=$this->getNoOfPages();$i++) {
					if($i==$this->getPage()) {
						$str=$str."<span class='".$this->getActiveStyle()."'>".$i."&nbsp;</span>";
					}
					else {
						$str=$str."<a href='".$_SERVER['PHP_SELF']."?page=".$i.$this->getParameter()."' class='".$this->getStyle()."'>".$i."</a>&nbsp;";
					}
				   if($j == 11)
				   break;
				   $j++;
				}
			  if($this->getNoOfPages() > $i+1)
			  {
			    $str=$str."<a href='".$_SERVER['PHP_SELF']."?page=".($i+1).$this->getParameter()."' class='".$this->getStyle()."'>.. </a>";
			  }

			
			}
			
			if($this->getPage()<$this->getNoOfPages()) {
				$str=$str."|<a href='".$_SERVER['PHP_SELF']."?page=".($this->getPage()+1).$this->getParameter()."' class='".$this->getStyle()."'> Next</a>";
			}
			$str=$str."</td>";
			$str=$str."</tr></table>";
			return $str;
	}
	
	function getPageNoUrlrewriting() {
	$_SERVER['PHP_SELF']=$_SERVER['REQUEST_URI'];
	
	   $page=explode("/",strrchr($_SERVER['PHP_SELF'], '/'));
	   if(!intval($page[1]))
	   {
	   $this->page=1;
	   }
	   else
	   {	   
	$this->page=$page[1];
	}
	
   	$_SERVER['PHP_SELF'] =substr($_SERVER['REQUEST_URI'],0,strripos($_SERVER['PHP_SELF'], '/'));
			$str="";
			$str=$str."<table   border='0' cellspacing='0' cellpadding='0'><tr>";
			$str=$str."<td width='100%'  valign='top' >";
			
			
			
			if($this->getPage()>1) {
				$str=$str."<a href='".$_SERVER['PHP_SELF'].$this->getParameter().($this->getPage()-1)."' class='".$this->getStyle()."'>Prev</a>|&nbsp;";
			}
			
			if($this->getPage() > 6)
			{   
			    $l = 1;
				for($i=$this->getPage()-1;$i>0;$i--) {
					$arr[] = "<a href='".$_SERVER['PHP_SELF'].$this->getParameter().$i."' class='".$this->getStyle()."'>".$i."</a>&nbsp;";
					if($l == 5)
					break;
					$l++;
				}
				if($this->getNoOfPages()-$this->getPage() < 5)
				{
				   $start = $i -1;
				   $diff = $this->getNoOfPages()-$this->getPage();
				   $loop = 5-$diff;
				   for($m = 1; $m<=$loop; $m++) {
				     if($start>0)
				     $arr[] = "<a href='".$_SERVER['PHP_SELF'].$this->getParameter().$start."' class='".$this->getStyle()."'>".$start."</a>&nbsp;"; 
				     $start--;
					} 
				}
				$arrrev = array_reverse($arr);
				foreach($arrrev as $val)
				  $str = $str.$val;
			}
			
			$current = $this->getPage();
			if($current > 6)
			{   
			    $k = 1;
				for($i=$current;$i<=$this->getNoOfPages();$i++) {
					if($i==$this->getPage()) {
						$str=$str."<span class='".$this->getActiveStyle()."'>".$i."&nbsp;</span>";
					}
					else {
						$str=$str."<a href='".$_SERVER['PHP_SELF'].$this->getParameter().$i."' class='".$this->getStyle()."'>".$i."</a>&nbsp;";
					}
					if($k == 6)
					break;
					$k++;
				}
			}
			else
			{
				$j = 1;
				for($i=1;$i<=$this->getNoOfPages();$i++) {
					if($i==$this->getPage()) {
						$str=$str."<span class='".$this->getActiveStyle()."'>".$i."&nbsp;</span>";
					}
					else {
						$str=$str."<a href='".$_SERVER['PHP_SELF'].$this->getParameter().$i."' class='".$this->getStyle()."'>".$i."</a>&nbsp;";
					}
				   if($j == 11)
				   break;
				   $j++;
				}
			  if($this->getNoOfPages() > $i+1)
			  {
			    $str=$str."<a href='".$_SERVER['PHP_SELF'].$this->getParameter().($i+1)."' class='".$this->getStyle()."'>.. </a>";
			  }

			
			}
			
			if($this->getPage()<$this->getNoOfPages()) {
				$str=$str."|<a href='".$_SERVER['PHP_SELF'].$this->getParameter().($this->getPage()+1)."' class='".$this->getStyle()."'>Next</a>";
			}
			$str=$str."</td>";
			$str=$str."</tr></table>";
			return $str;
	}
	
	function getOffset($page) {
			if($page>$this->getNoOfPages()) {
				$page=$this->getNoOfPages();
			}
			if($page=="") {
				$this->page=1;
				$page=1;
			}
			else {
				$this->page=$page;
			}
			if($page=="1") {
				$this->offset=0;
				return $this->offset;
			}
			else {
				for($i=2;$i<=$page;$i++) {
					$this->offset=$this->offset+$this->getLimit();
				}
				return $this->offset;
			}
	}
	function getQueryString()
	{
		$queryString_arr = explode('&',$_SERVER['QUERY_STRING']);
		$queryStringNew = "";		
		if(count($queryString_arr) > 0)
		{ 
			$srchString = "age=";
			$srchStringLimit = "limit=";
			foreach($queryString_arr as $queryString_arr2)
			{
				$posString = strpos($queryString_arr2,$srchString);
				$posStringLim = strpos($queryString_arr2,$srchStringLimit);				
				if($posString != 1 && $queryString_arr2 != '')
				$queryStringNew .= "&".$queryString_arr2;
			}
		}
		return $queryStringNew;
	}
	
	function getPage() {
			return $this->page;
	}
	function setStyle($style) {
			$this->style=$style;
	}
	function getStyle() {
			return $this->style;
	}
	function setActiveStyle($style) {
			$this->activestyle=$style;
	}
	function getActiveStyle() {
			return $this->activestyle;
	}
	function setButtonStyle($style) {
			$this->buttonstyle=$style;
	}
	function getButtonStyle() {
			return $this->buttonstyle;
	}
	function setParameter($parameter) {
			$this->parameter=$parameter;
	}
	function getParameter() {
			return $this->parameter;
	}
	
	public function errno() 
	{ 
		return $this->dbh->errorCode(); 
	} 
	public function error() 
	{ 
		return $this->dbh->errorInfo(); 
	} 
	public static function escape_string($string) 
	{ 
		return $this->dbh->quote($string); 
	} 
	
	function query($query) 
	{ 
		$stmt = $this->dbh->prepare($query);
		$stmt->execute();
		return $stmt;
	} 
	public function fetch_array($result) // , $array_type = MYSQL_BOTH) 
	{
		return $result->fetch();
	} 
	public function fetch_row($result) 
	{ 
	   return $result->fetchall(PDO::FETCH_ASSOC);
	} 
	function fetch_assoc($result) 
	{ 
	   return $result->fetch(PDO::FETCH_ASSOC);
	} 
	public function fetch_object($result) 
	{ 
		return $result->fetch(PDO::FETCH_OBJ);
	}
	public function num_rows($result) 
	{
		return $result->rowCount();
	}
	public function curPageInfo() {
		return substr($_SERVER["REQUEST_URI"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
	}
	
	//Get Current PHP page name
	 public	function curPageName() {
	 return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
	}

	
	/*------------------Update Log file--------------------------*/
	function logSuccessFail($status,$query) {
		$str = "insert into ".TBL_LOGDETAIL." set sesDetId = '".$_SESSION['SESSIONID']."', moduleId = '".$_SESSION['CURRENTMENUID']."', pageQuery = '".addslashes($query)."', pageEvent = '".strtoupper(substr($query,0,6))."', logDescription = '".$status."', moduleUrl = '".addslashes($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])."', dateTime = '".date('Y-m-d H:i:s')."'"; 
		$this->executeQry($str);
	
	}

	function checkExtensions($ext) {
		$IMAGE_EXTENSION = $this->fetchValue(TBL_SYSTEMCONFIG,"systemVal","systemName='IMAGE_EXTENSION'");
		if($IMAGE_EXTENSION != "") {
			$allExtnArr = explode(',',$IMAGE_EXTENSION);
			if(in_array($ext,$allExtnArr)) 
				return true;
			else 
				return false;			
		} else {
			return false;
		}
	}
	
	function findSize($width,$height,$defaultWidth,$defaultHeight) {
		$THUMB_WIDTH = $this->fetchValue(TBL_SYSTEMCONFIG,"systemVal","systemName='$width'");
		$newWidth = $THUMB_WIDTH?$THUMB_WIDTH:$defaultWidth;
		$THUMB_HEIGHT = $this->fetchValue(TBL_SYSTEMCONFIG,"systemVal","systemName='$height'");				
		$newHeigth = $THUMB_HEIGHT?$THUMB_HEIGHT:$defaultHeight;		
		
		return $newWidth."x".$newHeigth;
	}
	
	/*********************************/
	//some custom db function
	function db_fetch_assoc_array($query){
		// $result = $this->query($query);
		$dataArray = array();
		while($data = $this->fetch_array($query)){
			$dataArray[] = $data;
		}
		return $dataArray;
	}

	function db_fetch_assoc_array_my($query){
		$result = $this->dbh->prepare($query);
		$dataArray = array();
		while($data = $result->fetch(PDO::FETCH_ASSOC)){
			$dataArray[] = $data;
		}
		return $dataArray;
	}

	//For customising date
	function db_fetch_assoc_array_custom_date($query){
		$result = $this->query($query);
		$dataArray = array();
		$i = 0;
		while($data = $this->fetch_array($result)){
			$dataArray[] = $data;
			$dataArray[$i]['created'] = date('Y-m-d, H:i:s', $data['created']);
			$i++;
		}
		return $dataArray;
	}


	function db_fetch_number_array($query){
		$result = $this->query($query);
		$dataArray = array();
		while($data = $this->fetch_row($result)){
			$dataArray[] = $data;
		}
		return $dataArray;
	}

	function db_fetch_single_number_row($query){
		$result = $this->query($query);
		$dataArray = $this->fetch_row($result);
		return $dataArray;
	}

	function db_fetch_single_assoc_row($query){
		$result = $this->query($query);
		$dataArray = $this->fetch_array($result);
		return $dataArray;
	}

	function db_fetch_single_cell($query){
		$result = $this->query($query);
		$dataArray = $this->fetch_row($result);
		return $dataArray[0];
	}

	function fetchUserType($tbl,$field, $con){
		$stmt = $this->dbh->prepare("SELECT ".$field." FROM ".$tbl." WHERE ".$con.""); 
		$stmt->execute(); 
		$row = $stmt->fetch();
		return $row['0'];
	}

	function fetchCautionStatus($tbl,$field, $con){
		$stmt = $this->dbh->prepare('SELECT field = ? FROM tbl = ? WHERE con = ?');
		$stmt->bindParam(1, $field, PDO::PARAM_STR);
		$stmt->bindParam(2, $tbl, PDO::PARAM_STR);
		$stmt->bindParam(3, $con, PDO::PARAM_STR);
		$stmt->execute(); 
		$row = $stmt->fetchall(PDO::FETCH_ASSOC);
		return $row[0];
	}
	//some custom db function end here  
}
?>