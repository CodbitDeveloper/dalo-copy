<?php
/*
 *********************************************************************************************************
 * daloRADIUS - RADIUS Web Platform
 * Copyright (C) 2007 - Liran Tal <liran@enginx.com> All Rights Reserved.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 *********************************************************************************************************
 *
 * Authors:	Liran Tal <liran@enginx.com>
 *
 *********************************************************************************************************
 */

    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

	include('library/check_operator_perm.php');


	//setting values for the order by and order type variables
	isset($_REQUEST['orderBy']) ? $orderBy = $_REQUEST['orderBy'] : $orderBy = "username";
	isset($_REQUEST['orderType']) ? $orderType = $_REQUEST['orderType'] : $orderType = "asc";



	include_once('library/config_read.php');
    $log = "visited page: ";
    $logQuery = "performed query for listing of records on page: ";

	include ("menu-home.php");
?>
<div class="page-content">
	<div class="container">
		<div class="card content-area">
			<div class="card-innr">
				<div class="card-head">
					<h4 class="card-title">User group mapping</h4>
				</div>
<?php

	include 'library/opendb.php';
	include 'include/management/pages_numbering.php';		// must be included after opendb because it needs to read the CONFIG_IFACE_TABLES_LISTING variable from the config file

	//orig: used as maethod to get total rows - this is required for the pages_numbering.php page	
	$sql = "SELECT distinct(".$configValues['CONFIG_DB_TBL_RADUSERGROUP'].".UserName), ".$configValues['CONFIG_DB_TBL_RADUSERGROUP'].".GroupName, ".
		$configValues['CONFIG_DB_TBL_RADUSERGROUP'].".priority, ".$configValues['CONFIG_DB_TBL_DALOUSERINFO'].".firstname, ".
		$configValues['CONFIG_DB_TBL_DALOUSERINFO'].".lastname ".
		" FROM ".$configValues['CONFIG_DB_TBL_RADUSERGROUP']." LEFT JOIN ".$configValues['CONFIG_DB_TBL_DALOUSERINFO']." ON ".
		$configValues['CONFIG_DB_TBL_RADUSERGROUP'].".username=".$configValues['CONFIG_DB_TBL_DALOUSERINFO'].".username ".
			" GROUP BY UserName;";
	$res = $dbSocket->query($sql);
	$numrows = $res->numRows();

	
	$sql = "SELECT distinct(".$configValues['CONFIG_DB_TBL_RADUSERGROUP'].".UserName), ".$configValues['CONFIG_DB_TBL_RADUSERGROUP'].".GroupName, ".
		$configValues['CONFIG_DB_TBL_RADUSERGROUP'].".priority, ".$configValues['CONFIG_DB_TBL_DALOUSERINFO'].".firstname, ".
		$configValues['CONFIG_DB_TBL_DALOUSERINFO'].".lastname ".
		" FROM ".$configValues['CONFIG_DB_TBL_RADUSERGROUP']." LEFT JOIN ".$configValues['CONFIG_DB_TBL_DALOUSERINFO']." ON ".
		$configValues['CONFIG_DB_TBL_RADUSERGROUP'].".username=".$configValues['CONFIG_DB_TBL_DALOUSERINFO'].".username ".
			" GROUP BY UserName ORDER BY $orderBy $orderType LIMIT $offset, $rowsPerPage;";
	$res = $dbSocket->query($sql);
	$logDebugSQL = "";
	$logDebugSQL .= $sql . "\n";
	
	/* START - Related to pages_numbering.php */
	$maxPage = ceil($numrows/$rowsPerPage);
	/* END */

	echo "<form name='listallusergroup' method='post' action='mng-rad-usergroup-del.php'>";
	
	echo "<table border='0' class='table1 table table-striped'>\n<br/><br/>";

	if ($orderType == "asc") {
		$orderTypeNextPage = "desc";
	} else  if ($orderType == "desc") {
		$orderTypeNextPage = "asc";
	}

	echo "<thread> <tr>
		<th scope='col'>
		<input type='checkbox' onchange='handleChange(this)'>&nbsp;&nbsp;<a title='Sort' class='novisit' href=\"" . $_SERVER['PHP_SELF'] . "?orderBy=username&orderType=$orderTypeNextPage\">
		".t('all','Username')."</a>
		</th>

                <th scope='col'>
                <a title='Sort' class='novisit' href=\"" . $_SERVER['PHP_SELF'] . "?orderBy=firstname&orderType=$orderTypeNextPage\">
                ".t('all','Name')."</a>
                </th>

		<th scope='col'>
		<a title='Sort' class='novisit' href=\"" . $_SERVER['PHP_SELF'] . "?orderBy=groupname&orderType=$orderTypeNextPage\">
		".t('all','Groupname')."</a>
		</th>

		<th scope='col'>
		<a title='Sort' class='novisit' href=\"" . $_SERVER['PHP_SELF'] . "?orderBy=priority&orderType=$orderTypeNextPage\">
		".t('all','Priority')."</a>
		</th>

	</tr> </thread>";
	while($row = $res->fetchRow()) {
		echo "<tr>
			<td> <input type='checkbox' name='usergroup[]' value='$row[0]||$row[1]'>&nbsp;&nbsp; $row[0] </td>
			<td> $row[3] $row[4] </td>
			<td> <a class='tablenovisit' href='#'
								onclick='javascript:return false;'
                                tooltipText=\"
                                        <a class='toolTip' href='mng-rad-usergroup-edit.php?username=$row[0]&group=$row[1]'>".t('Tooltip','EditUserGroup')."</a>
					<br/>
                                        <a class='toolTip' href='mng-rad-usergroup-list-user.php?username=$row[0]&group=$row[1]'>".t('Tooltip','ListUserGroups')."</a>
                                        <br/>\"
					>$row[1]</a></td>
				<td> $row[2] </td>
		</tr>";
	}

	echo "
		<tfoot>
			<tr>
			<th colspan='10' align='left'>
	";
	setupLinks($pageNum, $maxPage, $orderBy, $orderType);
	echo "
			</th>
			</tr>
		</tfoot>
	";


	echo "</table></form>";

	include 'library/closedb.php';
?>


<?php
	include('include/config/logging.php');
?>
					</div>
				</div>
			</div>
		</div><!-- .card -->
	</div><!-- .container -->
</div><!-- .page-content -->
<script src="library/javascript/pages_common.js" type="text/javascript"></script>
<script src="library/javascript/rounded-corners.js" type="text/javascript"></script>
<script src="library/javascript/form-field-tooltip.js" type="text/javascript"></script>
<script type="text/javascript" src="library/javascript/ajax.js"></script>
<script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>
<script type="text/javascript">
	function handleChange(e){
		if(e.checked == true){
			SetChecked(1,'usergroup[]','listallusergroup')
		}else{
			SetChecked(0,'usergroup[]','listallusergroup')
		}
	}
</script>
</body>
</html>

