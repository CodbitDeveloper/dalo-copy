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

	isset($_REQUEST['name']) ? $name = $_REQUEST['name'] : $name = "";
	isset($_REQUEST['macaddress']) ? $macaddress = $_REQUEST['macaddress'] : $macaddress = "";
	isset($_REQUEST['geocode']) ? $geocode = $_REQUEST['geocode'] : $geocode = "";
	isset($_REQUEST['owner']) ? $owner = $_REQUEST['owner'] : $owner = "";
	isset($_REQUEST['email_owner']) ? $email_owner = $_REQUEST['email_owner'] : $email_owner = "";
	isset($_REQUEST['manager']) ? $manager = $_REQUEST['manager'] : $manager = "";
	isset($_REQUEST['email_manager']) ? $email_manager = $_REQUEST['email_manager'] : $email_manager = "";
	isset($_REQUEST['address']) ? $address = $_REQUEST['address'] : $address = "";
	isset($_REQUEST['company']) ? $company = $_REQUEST['company'] : $company = "";
	isset($_REQUEST['phone1']) ? $phone1 = $_REQUEST['phone1'] : $phone1 = "";
	isset($_REQUEST['phone2']) ? $phone2 = $_REQUEST['phone2'] : $phone2 = "";
	isset($_REQUEST['hotspot_type']) ? $hotspot_type = $_REQUEST['hotspot_type'] : $hotspot_type = "";
	isset($_REQUEST['companywebsite']) ? $companywebsite = $_REQUEST['companywebsite'] : $companywebsite = "";
	isset($_REQUEST['companyphone']) ? $companyphone = $_REQUEST['companyphone'] : $companyphone = "";
	isset($_REQUEST['companyemail']) ? $companyemail = $_REQUEST['companyemail'] : $companyemail = "";
	isset($_REQUEST['companycontact']) ? $companycontact = $_REQUEST['companycontact'] : $companycontact = "";

	$logAction = "";
	$logDebugSQL = "";

	if (isset($_POST["submit"])) {
		$name = $_REQUEST['name'];
		$macaddress = $_REQUEST['macaddress'];
		$geocode = $_REQUEST['geocode'];
		
		include 'library/opendb.php';

		$sql = "SELECT * FROM ".$configValues['CONFIG_DB_TBL_DALOHOTSPOTS']." WHERE name='".$dbSocket->escapeSimple($name)."'";
		$res = $dbSocket->query($sql);
		$logDebugSQL .= $sql . "\n";

		if ($res->numRows() == 0) {
			if (trim($name) != "" and trim($macaddress) != "") {

				$currDate = date('Y-m-d H:i:s');
				$currBy = $_SESSION['operator_user'];

				// insert hotspot info
				$sql = "INSERT INTO ".$configValues['CONFIG_DB_TBL_DALOHOTSPOTS'].
				" (id, name, mac, geocode, owner, email_owner, manager, email_manager, address, company, ".
				"  phone1, phone2, type, companywebsite, companyemail, companycontact, companyphone, ".
				"  creationdate, creationby, updatedate, updateby) ".
				" VALUES (0, '".$dbSocket->escapeSimple($name)."', '".
				$dbSocket->escapeSimple($macaddress)."', '".
				$dbSocket->escapeSimple($geocode)."','".$dbSocket->escapeSimple($owner)."','".
				$dbSocket->escapeSimple($email_owner)."','".$dbSocket->escapeSimple($manager)."','".
				$dbSocket->escapeSimple($email_manager)."','".
				$dbSocket->escapeSimple($address)."','".$dbSocket->escapeSimple($company)."','".
				$dbSocket->escapeSimple($phone1)."','".$dbSocket->escapeSimple($phone2)."','".
				$dbSocket->escapeSimple($hotspot_type)."','".$dbSocket->escapeSimple($companywebsite)."','".
				$dbSocket->escapeSimple($companyemail)."','".
				$dbSocket->escapeSimple($companycontact)."','".
				$dbSocket->escapeSimple($companyphone).	"', ".
				" '$currDate', '$currBy', NULL, NULL)";
				$res = $dbSocket->query($sql);
				$logDebugSQL .= $sql . "\n";

				$successMsg = "Added to database new hotspot: <b>$name</b>";
				$logAction .= "Successfully added new hotspot [$name] on page: ";
			} else {
				$failureMsg = "you must provide atleast a hotspot name and mac-address";	
				$logAction .= "Failed adding new hotspot [$name] on page: ";	
			}
		} else { 
			$failureMsg = "You have tried to add a hotspot that already exist in the database: $name";	
			$logAction .= "Failed adding new hotspot already in database [$name] on page: ";		
		}
	
		include 'library/closedb.php';

	}



	include_once('library/config_read.php');
    $log = "visited page: ";

?>
<?php include("menu-home.php"); ?>
<div class="page-content">
        <div class="container">
            <div class="content-area card">
                <div class="card-innr card-innr-fix">
					<div class="card-head">
						<h6 class="card-title">New User</h6>
                    </div>
                    <div class="gaps-1x"></div>
                    <ul class="nav nav-tabs nav-tabs-line" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#info">Hotspot Info</a></li>
                        </li>
					</ul>
					<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-validate validate-modern">
						<div class="tab-content">
                        	<div class="tab-pane fade active show" id="info">
								<div class="form-row">
									<div class="form-group col-md-4 col-sm-12">
										<div class="input-item input-with-label"><label class="input-item-label text-exlight">
												Hotspot Name
											</label>
											<div class="input-wrap"><input id="full-name" name="name"
													class="input-bordered required" type="text"></div>
										</div>
									</div>
									<div class="form-group col-md-4 col-sm-12">
										<div class="input-item input-with-label"><label
												class="input-item-label text-exlight">MAC 
											Address</label>
											<div class="input-wrap"><input id="full-name" name="macaddress"
													class="input-bordered required" type="text"></div>
										</div>
									</div>
									<div class="form-group col-md-4 col-sm-12">
										<div class="input-item input-with-label"><label class="input-item-label text-exlight">
												Geocode
											</label>
											<div class="input-wrap"><input id="full-name" name="geocode"
													class="input-bordered required" type="text"></div>
										</div>
									</div>
								</div>
								<div class="gaps-1x"></div>
                        	</div>
						</div>
						<div class="card-footer">
							<button type="submit" name="submit" class="btn btn-primary">Save Hotspot</button>
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
</body>
</html>





