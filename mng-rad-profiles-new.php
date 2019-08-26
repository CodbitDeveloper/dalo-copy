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

	// declaring variables
	//	isset($_GET['profile']) ? $group = $_GET['profile'] : $profile = "";

	$logAction = "";
	$logDebugSQL = "";

	if (isset($_POST['submit'])) {
	
		$profile = $_POST['profile'];
		if ($profile != "") {

			include 'library/opendb.php';

			$attrCount = 0;					// counter for number of attributes
			foreach($_POST as $element=>$field) { 
				
				switch ($element) {
					case "submit":
					case "profile":
							$skipLoopFlag = 1; 
							break;
				}
		
				if ($skipLoopFlag == 1) {
					$skipLoopFlag = 0;             
					continue;
				}

				if (isset($field[0]))
					$attribute = $field[0];
				if (isset($field[1]))
					$value = $field[1];
				if (isset($field[2]))
					$op = $field[2];
				if (isset($field[3]))
					$table = $field[3];

				if ($table == 'check')
					$table = $configValues['CONFIG_DB_TBL_RADGROUPCHECK'];
				if ($table == 'reply')
					$table = $configValues['CONFIG_DB_TBL_RADGROUPREPLY'];


				if (!($value) || $table == '')
					continue;

				$sql = "INSERT INTO $table (id,GroupName,Attribute,op,Value) ".
						" VALUES (0, '".$dbSocket->escapeSimple($profile)."', '".
						$dbSocket->escapeSimple($attribute)."','".$dbSocket->escapeSimple($op)."', '".
						$dbSocket->escapeSimple($value)."')  ";
				$res = $dbSocket->query($sql);
				$logDebugSQL .= $sql . "\n";

				$attrCount++;				// increment attribute count

			}

			if ($attrCount == 0) {
				$failureMsg = "Failed adding profile name [$profile] - no attributes where provided by user";
				$logAction .= "Failed adding profile name [$profile] - no attributes where provided by user on page: ";
			} else {
				$successMsg = "Added to database new profile: <b> $profile </b>";
				$logAction .= "Successfully added new profile [$profile] on page: ";
			}

			include 'library/closedb.php';

		} else { // if $profile != ""
			$failureMsg = "profile name is empty";
			$logAction .= "Failed adding (possibly empty) profile name [$profile] on page: ";
		}

	}
	
	include_once('library/config_read.php');
    $log = "visited page: ";

	include("menu-home.php");

?>
<!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>

<script src="library/javascript/pages_common.js" type="text/javascript"></script>

<script type="text/javascript" src="library/javascript/ajax.js"></script>
<script type="text/javascript" src="library/javascript/dynamic_attributes.js"></script>

<title>daloRADIUS</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/1.css" type="text/css" media="screen,projection" />
</head>
 
<?php
	include ("menu-mng-rad-profiles.php");
?>
		
		<div id="contentnorightbar">
		
				<h2 id="Intro"><a href="#" onclick="javascript:toggleShowDiv('helpPage')"><?php echo t('Intro','mngradprofilesnew.php') ?>
				<h144>&#x2754;</h144></a></h2>


				<div id="helpPage" style="display:none;visibility:visible" >				
					<?php echo t('helpPage','mngradprofilesnew') ?>
					<br/>
				</div>
                <?php
					include_once('include/management/actionMessages.php');
                ?>
				
				<form name="newusergroup" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

        <fieldset>

                <h302> <?php echo t('title','ProfileInfo') ?> </h302>
                <br/>

                <label for='profile' class='form'>Profile Name</label>
                <input name='profile' type='text' id='profile' value='' tabindex=100 />
                <br />

                <br/><br/>
                <hr><br/>

                <input type='submit' name='submit' value='<?php echo t('buttons','apply') ?>' class='button' />

        </fieldset>


        <br/>


        <?php
			include_once('include/management/attributes.php');
        ?>
		
	</form>


<?php
	include('include/config/logging.php');
?>

		</div>

		<div id="footer">

<?php
	include 'page-footer.php';
?>


		</div>

</div>
</div>


</body>
</html-->
<div class="page-content">
        <div class="container">
            <div class="content-area card">
                <div class="card-innr card-innr-fix">
					<div class="card-head">
						<h6 class="card-title">New Group</h6>
                    </div>
                    <div class="gaps-1x"></div>
                    <ul class="nav nav-tabs nav-tabs-line" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#account">Group Info</a></li>
                        </li>
					</ul>
					<form  action="mng-rad-profiles-new.php" method="post" class="form-validate validate-modern">
						<div class="tab-content">
                        	<div class="tab-pane fade active show" id="account">
								<div class="form-row">
									<div class="form-group col-md-5 col-sm-12">
										<div class="input-item input-with-label"><label class="input-item-label text-exlight">
												Group Name
											</label>
											<div class="input-wrap"><input name='profile'
													class="input-bordered required" type="text"></div>
										</div>
									</div>
								</div>
								<div class="form-row">
									<div class="form-group col-md-4 col-sm-12">
										<div class="input-item input-with-label"><label class="input-item-label text-exlight">
												Select Vendor
											</label>
											<div class="select-wrapper">
												<select id='dictVendors0' onchange="getAttributesList(this,'dictAttributesDatabase')" class='form input-bordered' >
													<option value=''>Select Vendor...</option>
													<?php
														include 'library/opendb.php';

														$sql = "SELECT distinct(Vendor) as Vendor FROM ".
															$configValues['CONFIG_DB_TBL_DALODICTIONARY']." WHERE Vendor>'' ORDER BY Vendor ASC";
														$res = $dbSocket->query($sql);

														while($row = $res->fetchRow()) {
															echo "<option value=$row[0]>$row[0]</option>";
														}

														include 'library/closedb.php';
													?>
												</select>
											</div>
											
											<input disabled type='hidden' id='dictAttributesCustom'/>
										</div>
									</div>
									<div class="form-group col-md-4 col-sm-12">
										<div class="input-item input-with-label"><label class="input-item-label text-exlight">
												Attributes
											</label>
											<div class="select-wrapper">
												<select id='dictAttributesDatabase' class='form input-bordered' >
												</select>
											</div>
										</div>
									</div>
									<div class="form-group col-md-4 col-sm-12">
										<div class="input-item input-with-label"><label class="input-item-label text-exlight">
												&nbsp;
											</label>
											<div class="input-wrap">
												<button type="button" class="button btn btn-outline btn-primary" name='addAttributes' value='Add Attribute' id='addAttributesVendor'
												onclick="javascript:parseAttribute(1);">Add Attribute</button>
											</div>
										</div>
									</div>
								</div>
								<div class="form-row">
									<input type="hidden" value="0" id="divCounter" />
									<div id="divContainer"> </div> <br/>
								</div>
								<input type="hidden" value="0" id="divCounter" />
								<div id="divContainer"> </div> <br/>
								<div class="gaps-1x"></div>
                        	</div>
						</div>
						<div class="card-footer">
							<button type="submit" name="submit" class="btn btn-primary">Save Group</button>
						</div>
					</form>
                </div><!-- .card-innr -->
            </div><!-- .card -->
        </div><!-- .container -->
	</div><!-- .page-content -->
	<script src="assets/js/jquery.bundle49f7.js?ver=104"></script>	
	<script type="text/javascript" src="library/javascript/ajax.js"></script>
	<script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>
	<script src="library/javascript/pages_common.js" type="text/javascript"></script>
	<script src="library/javascript/productive_funcs.js" type="text/javascript"></script>
	<script type="text/javascript" src="library/javascript/dynamic_attributes.js"></script>
