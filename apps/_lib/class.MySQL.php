<?php /* vim: set ts=4 sw=4 syntax=php fdm=marker: */

/**
 * MySQL Class
 * -----------
 * Desc				database connection class
 * first design		Chris chris13jr@gmail.com
 * Date				2011.09.20
 */

Class MySQL
{
	protected $host = NULL;
	protected $user = NULL;
	protected $passwd = NULL;
	protected $dbname = NULL;

	/**
	 * constructor
	 * -----------
	 * @param	array()
	 * 					host = access host
	 * 					user = access user
	 * 					passwd = access user password
	 * @return	void
	 */
	public function __construct ($dbi=array())
	{
		$this->host = $dbi['host'];
		$this->user = $dbi['user'];
		$this->passwd = $dbi['pass'];
		$this->dbname = $dbi['name'];
		$this->conn = "";
		$this->ridx = 0;
	}

	/**
	 * query(), 질의실행
	 * -----------------
	 * @param	string	$query	질의 SQL statment
	 * @return	array	$res	result set
	 */
	public function query ($query=NULL, $pconnect=false)
	{
		// 실행 쿼리
		$this->last_query = $query;
		if ( !preg_match("/^SELECT.*/", strtoupper(trim($query))) )
		{
			//error_log("$query\n", 3, "/home/rpluskorea/public_html/LOG/mysql_query_log_" . date("YmdH", strtotime("NOW")));
		}

		// 질의시 커넥션 연결
		$this->connection();

		$this->qid = NULL;
		$this->qid = mysql_query($query, $this->conn);
		if ( mysql_error($this->conn) ) print "query error !!! " . mysql_errno() . " - " . mysql_error() . " [" . $this->last_query . "] MySQL.class ";

		//if ($this->qid !== boolean) $this->ridx = 0;

		## if ( $pconnect == false ) $this->close();

		return $this->qid;
	}

	public function getCount ($table, $where=NULL)
	{
		$where = ($where != NULL && $where != "") ? "WHERE $where" : NULL;
		$query = "SELECT COUNT(*) AS cnt FROM $table AS nb $where";
		$this->query( $query );
		$res = $this->getRow();
		return $res['cnt'];
	}

	// DISTINCT COUNT (2012.03.28, YSOH) 
	public function getDistinctCount ($counter, $table, $where=NULL)
	{
		$where = ($where != NULL && $where != "") ? "WHERE $where" : NULL;
		$query = "SELECT COUNT( DISTINCT $counter) AS cnt FROM $table $where";
		$this->query( $query );
		$res = $this->getRow();
		return $res['cnt'];
	}

	// 결과셋 단일
	public function getRecord ($query)
	{	
		$this->query( $query );
		$res = $this->getRow();
		return $res !== false ? $res : array();
	}

	// 결과셋 리스트
	public function getRecordList ($query)
	{
		$this->query( $query );
		$res = $this->getAllRows();
		return $res;
	}

	public function getRow ($idx=-1)
	{
		if ($idx != -1) $this->ridx = $idx;
		
		if ($this->ridx <= $this->getCntOfResult() )
		{
			$idx = $this->ridx;

			if ( !mysql_data_seek($this->qid, $idx) )
			{
				echo "Cannot seek to row $idx: " . mysql_error() . "\n";
				return false;
			}
			if ( !($row = mysql_fetch_assoc($this->qid)) )
			{
				mysql_free_result($this->qid);
				return false;
			}

			$this->ridx++;
		}
		else return false;

		return $row;
	}

	public function getAllRows ()
	{
		$res = array();
		for ($i = 0; $i <= $this->getCntOfResult(); $i++) $res[] = $this->getRow($i);
		return $res;
	}

	// unused
	public function pquery ($query)
	{
		return $this->query($query, true);
	}

	/**
	 * getCntOfResult(), 질의에 대한 결과값 카운트
	 * -------------------------
	 * @return	integer
	 */
	public function getCntOfResult ()
	{
		return mysql_num_rows($this->qid) - 1;
	}

	/**
	 * getInsertId(), insert 쿼리 후 입력된 레코드의 번호
	 * -------------------------
	 * @return	integer
	 */
	public function getInsertId ()
	{
		return mysql_insert_id();
	}

	/**
	 * connection(), 디비 커넥션
	 * -------------------------
	 * @param	void
	 * @return	void
	 */
	private function connection ()
	{/*{{{*/
		if ( $this->host == NULL || $this->user == NULL || $this->passwd == NULL )
		{
			print " Require Input Database Infomation - MySQL.Class ";
			return false;
		}

		if ( $this->conn == NULL )
		{
			$this->conn = mysql_connect($this->host, $this->user, $this->passwd, true);
			@mysql_query("set names utf8",$this->conn);
		}
		//if ($this->dbname != NULL) $this->selectDB($this->dbname);
		$this->selectDB($this->dbname);
	}/*}}}*/

	/**
	 * selectDB(), 사용디비 선택
	 * -------------------------
	 * @param	string	$dbname		선택할 디비명
	 * @return	void
	 */
	protected function selectDB ($dbname=NULL)
	{/*{{{*/
		if ($dbname == NULL || $dbname == "")
		{
			print " Require Input Selected Database Name - MySQL.Class ";
			return false;
		}
		mysql_select_db($dbname); 
	}/*}}}*/

	/**
	 * close(), 커넥션 닫기
	 * -------------------------
	 * @param	void
	 * @return	void
	 */
	protected function close ()
	{/*{{{*/
		if ($this->conn != NULL)
		{
			mysql_close($this->conn);
			$this->conn = NULL;
		}
	}/*}}}*/

	public function __destruct ()
	{/*{{{*/
		$this->close();
	}/*}}}*/
}

?>
