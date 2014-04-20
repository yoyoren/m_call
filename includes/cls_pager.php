<?php
/**
* @package
* @subpackage
* @version Thu Sep 09 11:31:02 CST 2010
*/
class page{
	var $QueryStr;
	var $page;
	var $pageSize;
	var $Totalpages;
	var $TotalRows;
	var $Url;
	var $R;

	function selectPage($QueryStr,$page,$pageSize,$Url)
	{
		$this->R = @mssql_query($QueryStr);
		if($this->R)
		{
			$this->TotalRows =  mssql_num_rows($this->R);
			$this->Totalpages = ceil(mssql_num_rows($this->R)/$pageSize);
			$this->Url = $Url.(stristr($Url,"?")?"&":"?")."page=";
			if($page<=0||empty($page))
			{ 
			   $this->page=1;
			}
			else
			{
			  $this->page=$page;
			};
			if($page>$this->Totalpages)   $page = $this->Totalpages;
			$begin   =   (   $page-1   )   *   $pageSize;
			$ReArr=array();
			$index=0;
			if($pageSize>0)   @mssql_data_seek($this->R,$begin);
			while($ResultArr=@mssql_fetch_array($this->R))
			{
				if($pageSize>0)
				if($index>$pageSize-1)   break;
				$ReArr[$index]=$ResultArr;
				$index++;
			}
			return   $ReArr;
		}
		else
		return   false;
	}
	function getpagenev()
	{
		if($this->Totalpages<=$this->page)
		{
			$this->page=$this->Totalpages;
		}
		$pagenev= "总条数:".$this->TotalRows."条&nbsp;&nbsp;共".$this->Totalpages."页"."&nbsp;&nbsp;"."当前第".$this->page."页&nbsp;&nbsp;";
		if($this->Totalpages>1)
		{
			if($this->page==1)
			{
				$pagenev.= "<a href=".$this->Url.strval($this->page+1).">下一页</a>&nbsp;&nbsp;<a href=".$this->Url.$this->Totalpages.">末页</a>";
			}
			elseif($this->page==$this->Totalpages)
			{
				$pagenev.= "<a href=".$this->Url.">首页</a>&nbsp;&nbsp;<a href=".$this->Url.strval($this->page-1).">上一页</a>";
			}
			else
			{
				$pagenev.= "<a href=".$this->Url.">首页</a>&nbsp;&nbsp;<a href=".$this->Url.strval($this->page-1).">上一页</a>&nbsp;&nbsp;<a href=".$this->Url.strval($this->page+1).">下一页</a>&nbsp;&nbsp;<a href=".$this->Url.$this->Totalpages.">末页</a>";
			}
			$pagenev.="&nbsp;&nbsp;转到<select name='sel_page' onchange='javascript:location=this.options[this.selectedIndex].value;'><option value=''></option> ";
			for($i=1;$i<=$this->Totalpages;$i++)
			{
				$pagenev.="<option value=".$this->Url.strval($i).">".$i."</option>";
			}
			$pagenev.="页</select> ";
			return  $pagenev;
		}
	}
	function getparam()
	{
	   $arr = array();
	   $arr['recordcount'] = $this->TotalRows;
	   $arr['pagecount']   = $this->Totalpages;
	   $arr['page']        = $this->page;
	   return $arr;
	}
}
//使用方法
/*
	$sql = "select * from tb ORDER BY id DESC"; 
	$page = new page();
	$res = $page->selectPage($SqlStr,@$_GET[page],5,'link.php?act=xx');
		
    foreach($res as $key=>$val)
	{
		.......................
		.......................
		
	}
	echo $page->getpagenev();
*/
?>