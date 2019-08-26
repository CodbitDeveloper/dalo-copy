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

	$username = "";
	$password = "";
	$maxallsession = "";
	$expiration = "";
	$sessiontimeout = "";
	$idletimeout = "";
	$ui_changeuserinfo = "0";
	$bi_changeuserbillinfo = "0";
	
	$logAction = "";
	$logDebugSQL = "";

	if (isset($_POST['submit'])) {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$passwordType = $_POST['passwordType'];
		$groups = $_POST['groups'];
		$maxallsession = $_POST['maxallsession'] * 2592000;
		$expiration = $_POST['expiration'];
		$sessiontimeout = 86400;
		$idletimeout = 3600;
		$simultaneoususe = 4;


		isset($_POST['firstname']) ? $firstname = $_POST['firstname'] : $firstname = "";
		isset($_POST['lastname']) ? $lastname = $_POST['lastname'] : $lastname = " ";
		isset($_POST['email']) ? $email = $_POST['email'] : $email = "";
		isset($_POST['department']) ? $department = $_POST['department'] : $department = "";
		isset($_POST['company']) ? $company = $_POST['company'] : $company = "";
		isset($_POST['workphone']) ? $workphone = $_POST['workphone'] : $workphone =  "";
		isset($_POST['homephone']) ? $homephone = $_POST['homephone'] : $homephone = "";
		isset($_POST['mobilephone']) ? $mobilephone = $_POST['mobilephone'] : $mobilephone = "";
	    isset($_POST['address']) ? $address = $_POST['address'] : $address = "";
	    isset($_POST['city']) ? $city = $_POST['city'] : $city = "";
	    isset($_POST['state']) ? $state = $_POST['state'] : $state = "";
	    isset($_POST['country']) ? $country = $_POST['country'] : $country = "";
	    isset($_POST['zip']) ? $zip = $_POST['zip'] : $zip = "";
		isset($_POST['notes']) ? $notes = $_POST['notes'] : $notes = "";
		isset($_POST['changeuserinfo']) ? $ui_changeuserinfo = $_POST['ui_changeuserinfo'] : $ui_changeuserinfo = "0";
		isset($_POST['enableUserPortalLogin']) ? $ui_enableUserPortalLogin = $_POST['enableUserPortalLogin'] : $ui_enableUserPortalLogin = "0";
		isset($_POST['portalLoginPassword']) ? $ui_PortalLoginPassword = $_POST['portalLoginPassword'] : $ui_PortalLoginPassword = "";
		
	    isset($_POST['bi_contactperson']) ? $bi_contactperson = $_POST['bi_contactperson'] : $bi_contactperson = "";
	    isset($_POST['bi_company']) ? $bi_company = $_POST['bi_company'] : $bi_company = "";
	    isset($_POST['bi_email']) ? $bi_email = $_POST['bi_email'] : $bi_email = "";
	    isset($_POST['bi_phone']) ? $bi_phone = $_POST['bi_phone'] : $bi_phone = "";
	    isset($_POST['bi_address']) ? $bi_address = $_POST['bi_address'] : $bi_address = "";
	    isset($_POST['bi_city']) ? $bi_city = $_POST['bi_city'] : $bi_city = "";
	    isset($_POST['bi_state']) ? $bi_state = $_POST['bi_state'] : $bi_state = "";
	    isset($_POST['bi_country']) ? $bi_country = $_POST['bi_country'] : $bi_country = "";
	    isset($_POST['bi_zip']) ? $bi_zip = $_POST['bi_zip'] : $bi_zip = "";
	    isset($_POST['bi_paymentmethod']) ? $bi_paymentmethod = $_POST['bi_paymentmethod'] : $bi_paymentmethod = "";
	    isset($_POST['bi_cash']) ? $bi_cash = $_POST['bi_cash'] : $bi_cash = "";
	    isset($_POST['bi_creditcardname']) ? $bi_creditcardname = $_POST['bi_creditcardname'] : $bi_creditcardname = "";
	    isset($_POST['bi_creditcardnumber']) ? $bi_creditcardnumber = $_POST['bi_creditcardnumber'] : $bi_creditcardnumber = "";
	    isset($_POST['bi_creditcardverification']) ? $bi_creditcardverification = $_POST['bi_creditcardverification'] : $bi_creditcardverification = "";
	    isset($_POST['bi_creditcardtype']) ? $bi_creditcardtype = $_POST['bi_creditcardtype'] : $bi_creditcardtype = "";
	    isset($_POST['bi_creditcardexp']) ? $bi_creditcardexp = $_POST['bi_creditcardexp'] : $bi_creditcardexp = "";
	    isset($_POST['bi_notes']) ? $bi_notes = $_POST['bi_notes'] : $bi_notes = "";
	    isset($_POST['changeUserBillInfo']) ? $bi_changeuserbillinfo = $_POST['changeUserBillInfo'] : $bi_changeuserbillinfo = "0";
	    
		include 'library/opendb.php';
		
		$sql = "SELECT * FROM ".$configValues['CONFIG_DB_TBL_RADCHECK']." WHERE UserName='$username'";
		$res = $dbSocket->query($sql);
		$logDebugSQL .= $sql . "\n";

		if ($res->numRows() == 0) {
		
			if (trim($username) != "" and trim($password) != "") {

				$password = $dbSocket->escapeSimple($password);

                                switch($configValues['CONFIG_DB_PASSWORD_ENCRYPTION']) {
                                	case "cleartext":
                        	                $dbPassword = "'$password'";
                                                break;
                                        case "crypt":
                                                $dbPassword = "ENCRYPT('$password', 'SALT_DALORADIUS')";
                                                break;
                                        case "md5":
                                                $dbPassword = "MD5('$password')";
                                                break;
                                        default:
                                                $dbPassword = "'$password'";
                                }

				// insert username/password
				$sql = "INSERT INTO ".$configValues['CONFIG_DB_TBL_RADCHECK'].
						" (id,Username,Attribute,op,Value) ".
						" VALUES (0, '".$dbSocket->escapeSimple($username)."', '$passwordType', ".
						" ':=', $dbPassword)";
				$res = $dbSocket->query($sql);
				$logDebugSQL .= $sql . "\n";
	
				if ($maxallsession) {
					$sql = "INSERT INTO ".$configValues['CONFIG_DB_TBL_RADCHECK']." (id,Username,Attribute,op,Value) ".
							" VALUES (0, '".$dbSocket->escapeSimple($username)."', 'Max-All-Session', ':=', '".
							$dbSocket->escapeSimple($maxallsession)."')";
					$res = $dbSocket->query($sql);
					$logDebugSQL .= $sql . "\n";
				}

				if ($expiration) {
					$sql = "INSERT INTO ".$configValues['CONFIG_DB_TBL_RADCHECK']." (id,Username,Attribute,op,Value) ".
							" VALUES (0, '".$dbSocket->escapeSimple($username)."', 'Expiration', ':=', '".
							$dbSocket->escapeSimple($expiration)."')";
					$res = $dbSocket->query($sql);
					$logDebugSQL .= $sql . "\n";
				}

				if ($sessiontimeout) {
					$sql = "INSERT INTO ".$configValues['CONFIG_DB_TBL_RADREPLY']." (id,Username,Attribute,op,Value) ".
							" VALUES (0, '".$dbSocket->escapeSimple($username)."', 'Session-Timeout', ':=', '".
							$dbSocket->escapeSimple($sessiontimeout)."')";
					$res = $dbSocket->query($sql);
					$logDebugSQL .= $sql . "\n";
				}

				if ($idletimeout) {
					$sql = "INSERT INTO ".$configValues['CONFIG_DB_TBL_RADREPLY']." (id,Username,Attribute,op,Value) ".
							" VALUES (0, '".$dbSocket->escapeSimple($username)."', 'Idle-Timeout', ':=', '".
							$dbSocket->escapeSimple($idletimeout)."')";
					$res = $dbSocket->query($sql);
					$logDebugSQL .= $sql . "\n";
				}

				if ($simultaneoususe) {
					$sql = "INSERT INTO ".$configValues['CONFIG_DB_TBL_RADCHECK']." (id,Username,Attribute,op,Value) ".
							" VALUES (0, '".$dbSocket->escapeSimple($username)."', 'Simultaneous-Use', ':=', '".
							$dbSocket->escapeSimple($simultaneoususe)."')";
					$res = $dbSocket->query($sql);
					$logDebugSQL .= $sql . "\n";
				}

				if ($framedipaddress) {
					$sql = "INSERT INTO ".$configValues['CONFIG_DB_TBL_RADREPLY']." (id,Username,Attribute,op,Value) ".
							" VALUES (0, '".$dbSocket->escapeSimple($username)."', 'Framed-IP-Address', ':=', '".
							$dbSocket->escapeSimple($framedipaddress)."')";
					$res = $dbSocket->query($sql);
					$logDebugSQL .= $sql . "\n";
				}

				if (isset($groups)) {

		                        foreach ($groups as $group) {

		                                if (trim($group) != "") {
		                                        $sql = "INSERT INTO ".$configValues['CONFIG_DB_TBL_RADUSERGROUP']." (UserName,GroupName,priority) ".
		                                                " VALUES ('".$dbSocket->escapeSimple($username)."', '".$dbSocket->escapeSimple($group)."',0) ";
		                                        $res = $dbSocket->query($sql);
		                                        $logDebugSQL .= $sql . "\n";
		                                }
		                        }
				}

				//insert userinfo
				$currDate = date('Y-m-d H:i:s');
				$currBy = $_SESSION['operator_user'];

				$sql = "SELECT * FROM ".$configValues['CONFIG_DB_TBL_DALOUSERINFO'].
						" WHERE username='".$dbSocket->escapeSimple($username)."'";
				$res = $dbSocket->query($sql);
				$logDebugSQL .= $sql . "\n";

				// if there were no records for this user present in the userinfo table
				if ($res->numRows() == 0) {
					// insert user information table
					$sql = "INSERT INTO ".$configValues['CONFIG_DB_TBL_DALOUSERINFO'].
							" (id, username, firstname, lastname, email, department, company, workphone, homephone, ".
							" mobilephone, address, city, state, country, zip, notes, changeuserinfo, portalloginpassword, enableportallogin, creationdate, creationby, updatedate, updateby) ".
							" VALUES (0,
							'".$dbSocket->escapeSimple($username)."', '".$dbSocket->escapeSimple($firstname)."', '".
							$dbSocket->escapeSimple($lastname)."', '".$dbSocket->escapeSimple($email)."', '".
							$dbSocket->escapeSimple($department)."', '".$dbSocket->escapeSimple($company)."', '".
							$dbSocket->escapeSimple($workphone)."', '".$dbSocket->escapeSimple($homephone)."', '".
							$dbSocket->escapeSimple($mobilephone)."', '".$dbSocket->escapeSimple($address)."', '".
							$dbSocket->escapeSimple($city)."', '".$dbSocket->escapeSimple($state)."', '".
							$dbSocket->escapeSimple($country)."', '".
							$dbSocket->escapeSimple($zip)."', '".$dbSocket->escapeSimple($notes)."', '".
							$dbSocket->escapeSimple($ui_changeuserinfo)."', '".
							$dbSocket->escapeSimple($ui_PortalLoginPassword)."', '".$dbSocket->escapeSimple($ui_enableUserPortalLogin).
							"', '$currDate', '$currBy', NULL, NULL)";
					$res = $dbSocket->query($sql);
					$logDebugSQL .= $sql . "\n";

				}


		                $sql = "SELECT * FROM ".$configValues['CONFIG_DB_TBL_DALOUSERBILLINFO'].
		                                " WHERE username='".$dbSocket->escapeSimple($username)."'";
		                $res = $dbSocket->query($sql);
		                $logDebugSQL .= $sql . "\n";
		
		                // if there were no records for this user present in the userbillinfo table
		                if ($res->numRows() == 0) {
		                        // insert user billing information table
		                        $sql = "INSERT INTO ".$configValues['CONFIG_DB_TBL_DALOUSERBILLINFO'].
		                                " (id, username, contactperson, company, email, phone, ".
		                                " address, city, state, country, zip, ".
		                                " paymentmethod, cash, creditcardname, creditcardnumber, creditcardverification, creditcardtype, creditcardexp, ".
		                                " notes, changeuserbillinfo, ".
		                                " creationdate, creationby, updatedate, updateby) ".
		                                " VALUES (0,
		                                '".$dbSocket->escapeSimple($username)."', '".$dbSocket->escapeSimple($bi_contactperson)."', '".
		                                $dbSocket->escapeSimple($bi_company)."', '".$dbSocket->escapeSimple($bi_email)."', '".
		                                $dbSocket->escapeSimple($bi_phone)."', '".$dbSocket->escapeSimple($bi_address)."', '".
		                                $dbSocket->escapeSimple($bi_city)."', '".$dbSocket->escapeSimple($bi_state)."', '".
		                                $dbSocket->escapeSimple($bi_country)."', '".
		                                $dbSocket->escapeSimple($bi_zip)."', '".$dbSocket->escapeSimple($bi_paymentmethod)."', '".
		                                $dbSocket->escapeSimple($bi_cash)."', '".$dbSocket->escapeSimple($bi_creditcardname)."', '".
		                                $dbSocket->escapeSimple($bi_creditcardnumber)."', '".$dbSocket->escapeSimple($bi_creditcardverification)."', '".
	                	                $dbSocket->escapeSimple($bi_creditcardtype)."', '".$dbSocket->escapeSimple($bi_creditcardexp)."', '".
		                                $dbSocket->escapeSimple($bi_notes)."', '".
		                                $dbSocket->escapeSimple($bi_changeuserbillinfo).
		                                "', '$currDate', '$currBy', NULL, NULL)";
			                        $res = $dbSocket->query($sql);
		                        $logDebugSQL .= $sql . "\n";
		                }

				$successMsg = "Added to database new user: <b> $username </b>";
				$logAction .= "Successfully added new user [$username] on page: ";
			} else {
				$failureMsg = "username or password are empty";
				$logAction .= "Failed adding (possible empty user/pass) new user [$username] on page: ";
			}
		} else { 
			$failureMsg = "user already exist in database: <b> $username </b>";
			$logAction .= "Failed adding new user already existing in database [$username] on page: ";
		}
		
		include 'library/closedb.php';

	}




	include_once('library/config_read.php');
    $log = "visited page: ";

	
	if ($configValues['CONFIG_IFACE_PASSWORD_HIDDEN'] == "yes"){
		$hiddenPassword = "type=\"password\"";
	}
	
	include("menu-home.php"); ?>
    <div class="page-content">
        <div class="container">
            <div class="content-area card">
                <div class="card-innr card-innr-fix">
					<div class="card-head">
						<h6 class="card-title">New User</h6>
                    </div>
                    <div class="gaps-1x"></div>
                    <ul class="nav nav-tabs nav-tabs-line" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#account">Account Info</a></li>
                        </li>
					</ul>
					<form  action="mng-new-quick.php" method="post" class="form-validate validate-modern">
						<div class="tab-content">
                        	<div class="tab-pane fade active show" id="account">
								<div class="form-row">
									<div class="form-group col-md-4 col-sm-12">
										<div class="input-item input-with-label"><label class="input-item-label text-exlight">
												Username
											</label>
											<div class="input-wrap"><input id="full-name" name="username"
													class="input-bordered required" type="text"></div>
											<input type="hidden" name="passwordType" value="Cleartext-Password"/>
										</div>
									</div>
									<div class="form-group col-md-4 col-sm-12">
										<div class="input-item input-with-label"><label
												class="input-item-label text-exlight">Cleartext
												Password</label>
											<div class="input-wrap"><input id="full-name" name="password"
													class="input-bordered required" type="text"></div>
										</div>
									</div>
									<div class="form-group col-md-4 col-sm-12">
										<div class="input-item input-with-label"><label class="input-item-label text-exlight">
												Email
											</label>
											<div class="input-wrap"><input id="full-name" name="username"
													class="input-bordered required" type="email"></div>
										</div>
									</div>
								</div>
								<div class="form-row">
									
									<div class="form-group col-md-4 col-sm-12">
										<div class="input-item input-with-label"><label
												class="input-item-label text-exlight">Group
											</label>
											<div class="select-wrapper"><?php   
												include_once 'include/management/populate_selectbox.php';
												populate_groups("Select Groups","groups[]");
											?>
											</div>
										</div>
									</div>
									<div class="form-group col-md-4 col-sm-12">
										<div class="input-item input-with-label"><label class="input-item-label text-exlight">
												First Name
											</label>
											<div class="input-wrap"><input id="full-name" name="firstname"
													class="input-bordered required" type="text"></div>
										</div>
									</div>
									<div class="form-group col-md-4 col-sm-12">
										<div class="input-item input-with-label"><label
												class="input-item-label text-exlight">Last Name</label>
											<div class="input-wrap"><input id="full-name" name="lastname"
													class="input-bordered required" type="text"></div>
										</div>
									</div>
								</div>
								<div class="form-row">
									<div class="form-group col-md-4 col-sm-12">
										<div class="input-item input-with-label"><label
												class="input-item-label text-exlight">Expiration</label>
											<div class="input-wrap"><input id="full-name" name="expiration"
													class="input-bordered required" type="date" required></div>
										</div>
									</div>
									<div class="form-group col-md-4 col-sm-12">
										<div class="input-item input-with-label"><label
												class="input-item-label text-exlight">Max-All-Session (in months)</label>
											<div class="input-wrap"><input id="full-name" name="maxallsession"
													class="input-bordered required" type="number" required></div>
										</div>
									</div>
								</div>
								<div class="gaps-1x"></div>
                        	</div>
						</div>
						<div class="card-footer">
							<button type="submit" name="submit" class="btn btn-primary">Save User</button>
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




