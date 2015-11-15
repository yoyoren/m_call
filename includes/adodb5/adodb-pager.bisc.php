<?php
/*
	V5.14 8 Sept 2011   (c) 2000-2011 John Lim (jlim#natsoft.com). All rights reserved.
	  Released under both BSD license and Lesser GPL library license. 
	  Whenever there is any discrepancy between the two licenses, 
	  the BSD license will take precedence. 
	  Set tabs to 4 for best viewing.

  	This class provides recordset pagination with 
	First/Prev/Next/Last links. 
	
	Feel free to modify this class for your own use as
	it is very basic. To learn how to use it, see the 
	example in adodb/tests/testpaging.php.
	
	"Pablo Costa" <pablo@cbsp.com.br> implemented Render_PageLinks().
	
	Please note, this class is entirely unsupported, 
	and no free support requests except for bug reports
	will be entertained by the author.
	20120214 bisc redo it again

*/
//-----------------------------------
// Call this class to draw everything.
function Pager($sql,$rows=10,$page,$cache=false)
{
	if ($cache)	$rs = $db->CachePageExecute($cache,$sql,$rows,$page);
	else $rs = $GLOBALS['db']->PageExecute($sql,$rows,$page);
	$ncols = $rs->FieldCount();
	for ($i=0; $i < $ncols; $i++) 
	{	
		$field = $rs->FetchField($i);
		if ($field) 
		{
			$arky[] = $field->name;
		} 
		else 
		{
			$fname = 'Field '.($i+1);
		}
	}
	$numoffset = isset($rs->fields[0]) ||isset($rs->fields[1]) || isset($rs->fields[2]);
	while (!$rs->EOF) 
	{
		for ($i=0; $i < $ncols; $i++) 
		{
			if ($i===0)
			{
			  $v =  ($numoffset) ? $rs->fields[0] : reset($rs->fields);
			}
			else 
			{
			  $v = ($numoffset) ? $rs->fields[$i] : next($rs->fields);
			}
			$a[$arky[$i]] = $v;
		} // for
		$res[] = $a;
		$rs->MoveNext();
	} // while
  
    $arr = array('list' => $res, 'page_count' => $rs->LastPageNo(), 'record_count' => $rs->maxRecordCount());
    return $arr;
		
	$rs->Close();
	$this->rs = false;
}
?>