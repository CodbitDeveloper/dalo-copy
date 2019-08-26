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
	isset($_REQUEST['orderBy']) ? $orderBy = $_REQUEST['orderBy'] : $orderBy = "GroupName";
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
					<h4 class="card-title">Groups</h4>
				</div>
<?php

	include 'library/opendb.php';
	include 'include/management/pages_numbering.php';		// must be included after opendb because it needs to read the CONFIG_IFACE_TABLES_LISTING variable from the config file

	//orig: used as maethod to get total rows - this is required for the pages_numbering.php page	
	$sql = "SELECT distinct(".$configValues['CONFIG_DB_TBL_RADGROUPREPLY'].".GroupName), count(distinct(".$configValues['CONFIG_DB_TBL_RADUSERGROUP'].".username)) ".
		" AS Users FROM ".
		$configValues['CONFIG_DB_TBL_RADGROUPREPLY']." LEFT JOIN ".$configValues['CONFIG_DB_TBL_RADUSERGROUP']." ON ".
		$configValues['CONFIG_DB_TBL_RADGROUPREPLY'].".GroupName=".$configValues['CONFIG_DB_TBL_RADUSERGROUP'].".GroupName GROUP BY ".
		$configValues['CONFIG_DB_TBL_RADGROUPREPLY'].".GroupName ".
		" UNION ".
		" SELECT distinct(".$configValues['CONFIG_DB_TBL_RADGROUPCHECK'].".GroupName), count(distinct(".$configValues['CONFIG_DB_TBL_RADUSERGROUP'].".username)) FROM ".
		$configValues['CONFIG_DB_TBL_RADGROUPCHECK']." LEFT JOIN ".$configValues['CONFIG_DB_TBL_RADUSERGROUP']." ON ".
		$configValues['CONFIG_DB_TBL_RADGROUPCHECK'].".GroupName=".$configValues['CONFIG_DB_TBL_RADUSERGROUP'].".GroupName GROUP BY ".
		$configValues['CONFIG_DB_TBL_RADGROUPCHECK'].".GroupName;";
	$res = $dbSocket->query($sql);
	$numrows = $res->numRows();

	
	$sql = "SELECT distinct(".$configValues['CONFIG_DB_TBL_RADGROUPREPLY'].".GroupName), count(distinct(".$configValues['CONFIG_DB_TBL_RADUSERGROUP'].".username)) ".
		" AS Users FROM ".
		$configValues['CONFIG_DB_TBL_RADGROUPREPLY']." LEFT JOIN ".$configValues['CONFIG_DB_TBL_RADUSERGROUP']." ON ".
		$configValues['CONFIG_DB_TBL_RADGROUPREPLY'].".GroupName=".$configValues['CONFIG_DB_TBL_RADUSERGROUP'].".GroupName GROUP BY ".
		$configValues['CONFIG_DB_TBL_RADGROUPREPLY'].".GroupName ".
		" UNION ".
		" SELECT distinct(".$configValues['CONFIG_DB_TBL_RADGROUPCHECK'].".GroupName), count(distinct(".$configValues['CONFIG_DB_TBL_RADUSERGROUP'].".username)) FROM ".
		$configValues['CONFIG_DB_TBL_RADGROUPCHECK']." LEFT JOIN ".$configValues['CONFIG_DB_TBL_RADUSERGROUP']." ON ".
		$configValues['CONFIG_DB_TBL_RADGROUPCHECK'].".GroupName=".$configValues['CONFIG_DB_TBL_RADUSERGROUP'].".GroupName GROUP BY ".
		$configValues['CONFIG_DB_TBL_RADGROUPCHECK'].".GroupName ".
		" ORDER BY $orderBy $orderType LIMIT $offset, $rowsPerPage;";
	$res = $dbSocket->query($sql);
	$logDebugSQL = "";
	$logDebugSQL .= $sql . "\n";
	
	/* START - Related to pages_numbering.php */
	$maxPage = ceil($numrows/$rowsPerPage);
	/* END */

	echo "<form name='listprofiles' method='post' action='mng-rad-profiles-del.php'>";
	
	echo "<table border='0' class='table1 table table-striped'>\n";
	echo "
			<br/><br/>
	";

	if ($orderType == "asc") {
		$orderTypeNextPage = "desc";
	} else  if ($orderType == "desc") {
		$orderTypeNextPage = "asc";
	}

	echo "<thread> <tr>
		<th scope='col'>
		<a title='Sort' class='novisit' href=\"" . $_SERVER['PHP_SELF'] . "?orderBy=groupname&orderType=$orderTypeNextPage\">
		".t('all','Groupname')."</a>
		</th>

		<th scope='col'>
		<a title='Sort' class='novisit' href=\"" . $_SERVER['PHP_SELF'] . "?orderBy=users&orderType=$orderTypeNextPage\">
		".t('all','TotalUsers')."</a>
		</th>

	</tr> </thread>";
	while($row = $res->fetchRow()) {
		echo "<tr>
			<td>
				<a class='tablenovisit' href='#'
								onclick='javascript:return false;'
                                tooltipText=\"
                                        <a class='toolTip' href='mng-rad-profiles-edit.php?profile=$row[0]'>".t('Tooltip','EditProfile')."</a>
                                        <br/>\"
				>$row[0]</a></td>
			<td>$row[1]</td>
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
<a href="mng-rad-profiles-new.php">
	<div class="fab">
		<i class="fas fa-plus"></i>
	</div>
</a>
</body>
</html>

