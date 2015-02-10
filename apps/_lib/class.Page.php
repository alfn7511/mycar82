<?php
/**
 * Paging Class using PHP4/5
 *
 * @author		Kang YongSeok <wyseburn@gmail.com>
 * @version		1.0
 */

Class Page
{
	/* 파라메타용 변수 */
	public $mCurPageNum;		//현제 페이지 번호
	public $mPageVar;			//페이지에 사용되는 변수명
	public $mExtraVar;			//추가 변수
	public $mTotalItem;		//글갯수
	public $mPerPage;			//출력 페이지수
	public $mPerItem;			//출력 글 수
	public $mPrevPage;			//[이전 페이지] text 또는 img tag
	public $mNextPage;			//[다음 페이지] text 또는 img tag
	public $mPrevPerPage;	//[이전 $mPerPage 페이지] text 또는 img tag
	public $mNextPerPage;	//[다음 $mPerPage 페이지] text 또는 img tag
	public $mFirstPage;		//[처음] 페이지 text 또는 img tag
	public $mLastPage;			//[마지막] 페이지 text 또는 img tag
	public $mPageCss;		//페이지 목록에 사용할 css
	public $mCurPageCss;	//현재 페이지에 사용할 css

	/* 내부사용 변수 */
	public $mPageCount;		//전체 페이지수
	public $mTotalBlock;		//전체 블럭수
	public $mBlock;				//현재 블럭수
	public $mFirstPerPage;	//한블럭의 첫 페이지번호
	public $mLastPerPage;	//한블럭의 마지막 페이지 번호
	private $rtv;

	/**
	* 생성자 - 온션을 성정하고 기본적인 페이지,블럭수 등을 계산
	* @param array $params
	*/
	public function __construct ()
	{
	}

	public function makePageValue ($params)
	{/*{{{*/
		if ( !count($params) ){
			echo "[YsPaging Error : 파라메터가 없습니다.]";
			return;
		}

		$this->mCurPageNum = $params['curPageNum'] ? $params['curPageNum'] : 1;
		$this->mPageVar = $params['pageVar'] ? $params['pageVar'] : 'pagenum';
		$this->mExtraVar = $params['extraVar'] ? $params['extraVar'] : '';
		$this->mTotalItem = $params['totalItem'] ? $params['totalItem'] : 0;
		$this->mPerPage = $params['perPage'] ? $params['perPage'] : 10;
		$this->mPerItem = $params['perItem'] ? $params['perItem'] : 15;
		$this->mPrevPage = $params['prevPage'] ? $params['prevPage'] : "";
		$this->mNextPage = $params['nextPage'] ? $params['nextPage'] : "";
		$this->mPrevPerPage = $params['prevPerPage'];
		$this->mNextPerPage = $params['nextPerPage'];
		$this->mFirstPage = $params['firstPage'];
		$this->mLastPage = $params['lastPage'];
		$this->mPageCss = $params['pageCss'];
		$this->mCurPageCss = $params['curPageCss'];

		$this->mPageCount = ceil($this->mTotalItem/$this->mPerItem);
		$this->mTotalBlock = ceil($this->mPageCount/$this->mPerPage);
		$this->mBlock = ceil($this->mCurPageNum/$this->mPerPage);
		$this->mFirstPerPage = ($this->mBlock-1)*$this->mPerPage;
		$this->mLastPerPage = $this->mTotalBlock<=$this->mBlock ? $this->mPageCount : $this->mBlock*$this->mPerPage;

	}/*}}}*/
	
	/**
	* 현재 글번호를 리턴
	* @return integer
	*/
	public function getItemNum()
	{
		return $this->mTotalItem-($this->mCurPageNum-1)*$this->mPerItem; // 현재 아이템 번호 계산
	}

	/**
	* 첫페이지 번호 링크를 리턴
	* @return string
	*/
	public function getFirstPage() {
		if(empty($this->mFirstPage) || $this->mCurPageNum == 1) return NULL;
		return '<a href="'.$_SERVER['PHP_SELF'].'?'.$this->mPageVar.'=1'.$this->mExtraVar.'" class="btn prev2">'.$this->mFirstPage.'</a> ';
	}

	/**
	* 끝페이지 번호 링크를 리턴
	* @return string
	*/
	public function getLastPage() {
		if(empty($this->mLastPage) || $this->mCurPageNum == $this->mPageCount) return NULL;
		return '<a href="'.$_SERVER['PHP_SELF'].'?'.$this->mPageVar.'='.$this->mPageCount.$this->mExtraVar.'" class="btn next2">'.$this->mLastPage.'</a> ';
	}

	/**
	* 이전블럭 링크를 리턴
	* @return string
	*/
	public function getPrevPerPage() {
		if(empty($this->mPrevPerPage) || $this->mBlock <= 1) return NULL;
		return '<a href="'.$_SERVER['PHP_SELF'].'?'.$this->mPageVar.'='.$this->mFirstPerPage.$this->mExtraVar.'" class="btn prev2">'.$this->mPrevPerPage.'</a> ';
	}

	/**
	* 다음블럭 링크를 리턴
	* @return string
	*/
	public function getNextPerPage() {
		if(empty($this->mNextPerPage) || $this->mBlock >= $this->mTotalBlock) return NULL;
		return '<a href="'.$_SERVER['PHP_SELF'].'?'.$this->mPageVar.'='.($this->mLastPerPage+1).$this->mExtraVar.'" class="btn next2">'.$this->mNextPerPage.'</a> ';
	}

	/**
	* 이전 페이지 링크를 리턴
	* @return string
	*/
	public function getPrevPage() {
		if($this->mCurPageNum > 1)
			return '<a href="'.$_SERVER['PHP_SELF'].'?'.$this->mPageVar.'='.($this->mCurPageNum-1).$this->mExtraVar.'" class="btn prev1">'.$this->mPrevPage.'</a> ';
		else
			return $this->mPrevPage;
	}

	/**
	* 다음 페이지 링크를 리턴
	* @return string
	*/
	public function getNextPage() {
		if($this->mCurPageNum != $this->mPageCount && $this->mPageCount)
			return '<a href="'.$_SERVER['PHP_SELF'].'?'.$this->mPageVar.'='.($this->mCurPageNum+1).$this->mExtraVar.'" class="btn next1">'.$this->mNextPage.'</a> ';
		else
			return $this->mNextPage;
	}

	/**
	* 페이지 목록 링크를 리턴
	* @return string
	*/
	public function getPageList() {
		$rtn = '';
		for($i=$this->mFirstPerPage+1;$i<=$this->mLastPerPage;$i++) {
			if($this->mCurPageNum == $i)
				if(empty($this->mCurPageCss))
					$rtn .= "<strong>".$i."</strong>";
				else
					$rtn .= '&nbsp;<strong class="'.$this->mCurPageCss.'">'.$i.'</strong>&nbsp;';
			else {
				$rtn .= '&nbsp;<a href="'.$_SERVER['PHP_SELF'].'?'.$this->mPageVar.'='.$i.$this->mExtraVar.'">';
				if(empty($this->mPageCss)) 
					$rtn .= $i.'</a>&nbsp;';
				else
					$rtn .= '<span class="'.$this->mPageCss.'">'.$i.'</span></a>&nbsp;';
			}
		}
		return $rtn;
	}

	/**
	* 기본 페이지를 프린트, 상속후 변경 가능
	*/
	public function printPaging() {
		//$rtv = $this->getFirstPage();	
		$rtv = $this->getPrevPerPage();
		$rtv .= $this->getPrevPage();
		$rtv .= $this->getPageList();
		$rtv .= $this->getNextPage();
		$rtv .= $this->getNextPerPage();
		//$rtv .= $this->getLastPage();
		return $rtv;
	}
}

?>
