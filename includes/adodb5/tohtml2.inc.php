<?php 
/*
  V4.93 10 Oct 2006  (c) 2000-2011 John Lim (jlim#natsoft.com). All rights reserved.
  Released under both BSD license and Lesser GPL library license. 
  Whenever there is any discrepancy between the two licenses, 
  the BSD license will take precedence.
  
  Some pretty-printing by Chris Oxenreider <oxenreid@state.net>
*/ 

// specific code for tohtml
GLOBAL $gSQLMaxRows,$gSQLBlockRows,$ADODB_ROUND;

$ADODB_ROUND=4; // rounding
$gSQLMaxRows = 1000; // max no of rows to download
$gSQLBlockRows=20; // max no of rows per table block

// RecordSet to HTML Table
//------------------------------------------------------------
// Convert a recordset to a html table. Multiple tables are generated
// if the number of rows is > $gSQLBlockRows. This is because
// web browsers normally require the whole table to be downloaded
// before it can be rendered, so we break the output into several
// smaller faster rendering tables.
//
// $rs: the recordset
// $ztabhtml: the table tag attributes (optional)
// $zheaderarray: contains the replacement strings for the headers (optional)
//
//  USAGE:
//	include('adodb.inc.php');
//	$db = ADONewConnection('mysql');
//	$db->Connect('mysql','userid','password','database');
//	$rs = $db->Execute('select col1,col2,col3 from table');
//	rs2html($rs, 'BORDER=2', array('Title1', 'Title2', 'Title3'));
//	$rs->Close();
//
// RETURNS: number of rows displayed
function rs2table(&$rs,$htmlspecialchars=true)
{
    $s ='';$rows=0;
    GLOBAL $gSQLMaxRows,$gSQLBlockRows,$ADODB_ROUND;

	if (!$rs) 
	{
		printf(ADODB_BAD_RS,'rs2html');
		return false;
	}
	$typearr = array();
	$ncols = $rs->FieldCount();
	$numoffset = isset($rs->fields[0]) ||isset($rs->fields[1]) || isset($rs->fields[2]);
	while (!$rs->EOF) {
		
		$s .= "<TR valign=top>\n";
		
		for ($i=0; $i < $ncols; $i++) {
			if ($i===0) $v=($numoffset) ? $rs->fields[0] : reset($rs->fields);
			else $v = ($numoffset) ? $rs->fields[$i] : next($rs->fields);
			
			$type = $typearr[$i];
			switch($type) {
			case 'D':
				if (strpos($v,':') !== false);
				else {
					if (empty($v)) {
					$s .= "<TD> &nbsp; </TD>\n";
					} else {
						$s .= "	<TD>".$rs->UserDate($v,"D d, M Y") ."</TD>\n";				
					}
					break;
				}
			case 'T':
				if (empty($v)) $s .= "<TD> &nbsp; </TD>\n";
				else $s .= "	<TD>".$rs->UserTimeStamp($v,"D d, M Y, H:i:s") ."</TD>\n";
			break;
			
			case 'N':
				if (abs(abs($v) - round($v,0)) < 0.00000001)
					$v = round($v);
				else
					$v = round($v,$ADODB_ROUND);
			case 'I':
				$vv = stripslashes((trim($v)));
				if (strlen($vv) == 0) $vv .= '&nbsp;';
				$s .= "	<TD align=right>".$vv ."</TD>\n";
			   	
			break;
			default:
				if ($htmlspecialchars) $v = htmlspecialchars(trim($v));
				$v = trim($v);
				if (strlen($v) == 0) $v = '&nbsp;';
				$s .= "	<TD><a href=''>". str_replace("\n",'<br>',stripslashes($v)) ."</a></TD>\n";
			  
			}
		} // for
		$s .= "</TR>\n\n";
			  
		$rows += 1;
		if ($rows >= $gSQLMaxRows) {
			$rows = "<p>Truncated at $gSQLMaxRows</p>";
			break;
		} // switch

		$rs->MoveNext();
	} // while
	return $s;
 }
?>