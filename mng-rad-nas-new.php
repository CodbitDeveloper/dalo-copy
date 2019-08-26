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
	$nashost = "";
	$nassecret = "";
	$nasname = "";
	$nasports = "";
	$nastype = "";
	$nasdescription = "";
	$nascommunity = "";
	$nasvirtualserver = "";

	$logAction = "";
	$logDebugSQL = "";

	if (isset($_POST['submit'])) {
	
		$nashost = $_POST['nashost'];
		$nassecret = $_POST['nassecret'];
		$nasname = $_POST['nasname'];
		$nasports = $_POST['nasports'];
		$nastype = $_POST['nastype'];
		$nasdescription = $_POST['nasdescription'];
		$nascommunity = $_POST['nascommunity'];
		$nasvirtualserver = $_POST['nasvirtualserver'];

		include 'library/opendb.php';

		$sql = "SELECT * FROM ".$configValues['CONFIG_DB_TBL_RADNAS'].
				" WHERE nasname='".$dbSocket->escapeSimple($nashost)."'";
		$res = $dbSocket->query($sql);
		$logDebugSQL .= $sql . "\n";

		if ($res->numRows() == 0) {

			if (trim($nashost) != "" and trim($nassecret) != "") {

				if (!$nasports) {
					$nasports = 0;
				}
				
				if (!$nasvirtualserver) {
                      $nasvirtualserver = '';
               }

				// insert nas details
				$sql = "INSERT INTO ".$configValues['CONFIG_DB_TBL_RADNAS'].
					" (id,nasname,shortname,type,ports,secret,server,community,description) ".
					" values (0, '".$dbSocket->escapeSimple($nashost)."', '".$dbSocket->escapeSimple($nasname).
					"', '".$dbSocket->escapeSimple($nastype)."', '".$dbSocket->escapeSimple($nasports).
					"', '".$dbSocket->escapeSimple($nassecret)."', '".$dbSocket->escapeSimple($nasvirtualserver).
					"', '".$dbSocket->escapeSimple($nascommunity)."', '".$dbSocket->escapeSimple($nasdescription)."')";
				$res = $dbSocket->query($sql);
				$logDebugSQL .= $sql . "\n";
			
				$successMsg = "Added new NAS to database: <b> $nashost </b>  ";
				$logAction .= "Successfully added nas [$nashost] on page: ";
			} else {
				$failureMsg = "no NAS Host or NAS Secret was entered, it is required that you specify both NAS Host and NAS Secret";
				$logAction .= "Failed adding (missing nas/secret) nas [$nashost] on page: ";
			}
		} else {
			$failureMsg = "The NAS IP/Host $nashost already exists in the database";	
			$logAction .= "Failed adding already existing nas [$nashost] on page: ";
		}

		include 'library/closedb.php';
	}
	

	include_once('library/config_read.php');
    $log = "visited page: ";

	
	
	include("menu-home.php"); 

?>
<!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>

<script src="library/javascript/pages_common.js" type="text/javascript"></script>

<title>daloRADIUS</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/1.css" type="text/css" media="screen,projection" />

</head>


	<div id="contentnorightbar">
	
		<h2 id="Intro"><a href="#" onclick="javascript:toggleShowDiv('helpPage')"><?php echo t('Intro','mngradnasnew.php') ?>
		<h144>&#x2754;</h144></a></h2>

		<div id="helpPage" style="display:none;visibility:visible" >				
			<?php echo t('helpPage','mngradnasnew') ?>
			<br/>
		</div>
		<?php
			include_once('include/management/actionMessages.php');
		?>
				

                <form name="newnas" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<div class="tabber">

     <div class="tabbertab" title="<?php echo t('title','NASInfo'); ?>">

	<fieldset>

		<h302> <?php echo t('title','NASInfo') ?> </h302>
		<br/>

                <label for='nashost' class='form'><?php echo t('all','NasIPHost') ?></label>
                <input name='nashost' type='text' id='nashost' value='' tabindex=100 />
                <br />


                <label for='nassecret' class='form'><?php echo t('all','NasSecret') ?></label>
                <input name='nassecret' type='text' id='nassecret' value='' tabindex=101 />
                <br />


                <label for='nastype' class='form'><?php echo t('all','NasType') ?></label>
                <input name='nastype' type='text' id='nastype' value='' tabindex=102 />
                <select onChange="javascript:setStringText(this.id,'nastype')" id="optionSele" tabindex=103 class='form'>
					<option value="">Select Type...</option>
	                <option value="other">other</option>
	                <option value="cisco">cisco</option>
	                <option value="livingston">livingston</option>
        	        <option value="computon">computon</option>
					<option value="max40xx">max40xx</option>
					<option value="multitech">multitech</option>
					<option value="natserver">natserver</option>
					<option value="pathras">pathras</option>
					<option value="patton">patton</option>
	                <option value="portslave">portslave</option>
	                <option value="tc">tc</option>
	                <option value="usrhiper">usrhiper</option>
       	        </select>
                <br />
		

                <label for='nasname' class='form'><?php echo t('all','NasShortname') ?></label>
                <input name='nasname' type='text' id='nasname' value='' tabindex=104 />
                <br />

                <br/><br/>
                <hr><br/>

                <input type='submit' name='submit' value='<?php echo t('buttons','apply') ?>' class='button' />

        </fieldset>


     </div>
     <div class="tabbertab" title="<?php echo t('title','NASAdvanced'); ?>">

	<fieldset>

		<h302> <?php echo t('title','NASAdvanced') ?> </h302>
		<br/>

                <label for='nasports' class='form'><?php echo t('all','NasPorts') ?></label>
                <input name='nasports' type='text' id='nasports' value='0' tabindex=105 />
                <br />

                <label for='nascommunity' class='form'><?php echo t('all','NasCommunity') ?></label>
                <input name='nascommunity' type='text' id='nascommunity' value='' tabindex=106 />
                <br />

                <label for='nasvirtualserver' class='form'><?php echo t('all','NasVirtualServer') ?></label>
                <input name='nasvirtualserver' type= 'text' id='nasvirtualserver' value='' tabindex=107 >
                <br />

                <label for='nasdescription' class='form'><?php echo t('all','NasDescription') ?></label>
                <textarea class='form' name='nasdescription' id='nasdescription' value='' tabindex=108 ></textarea>
                <br />

                <br/><br/>
                <hr><br/>

                <input type='submit' name='submit' value='<?php echo t('buttons','apply') ?>' class='button' />

        </fieldset>

	</div>
</div>
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
						<h6 class="card-title">New NAS</h6>
                    </div>
                    <div class="gaps-1x"></div>
                    <ul class="nav nav-tabs nav-tabs-line" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#account">NAS Info</a></li>
                        </li>
					</ul>
					<form  action="mng-rad-nas-new.php" method="post" class="form-validate validate-modern">
						<div class="tab-content">
                        	<div class="tab-pane fade active show" id="account">
								<div class="form-row">
									<div class="form-group col-md-4 col-sm-12">
										<div class="input-item input-with-label"><label class="input-item-label text-exlight">
												NAS Host (I.P)
											</label>
											<div class="input-wrap"><input id="full-name" name="nashost"
													class="input-bordered required" type="text"></div>
										</div>
									</div>
									<div class="form-group col-md-4 col-sm-12">
										<div class="input-item input-with-label"><label
												class="input-item-label text-exlight">NAS Secret</label>
											<div class="input-wrap"><input id="full-name" name='nassecret' value=''
													class="input-bordered required" type="password"></div>
										</div>
									</div>
									<div class="form-group col-md-4 col-sm-12">
										<div class="input-item input-with-label"><label
												class="input-item-label text-exlight">NAS Type
											</label>
											<div class="select-wrapper">
												<select class='form input-bordered' name="nastype">
													<option value="" selected disabled hidden>Select Type...</option>
													<option value="other">other</option>
													<option value="cisco">cisco</option>
													<option value="livingston">livingston</option>
													<option value="computon">computon</option>
													<option value="max40xx">max40xx</option>
													<option value="multitech">multitech</option>
													<option value="natserver">natserver</option>
													<option value="pathras">pathras</option>
													<option value="patton">patton</option>
													<option value="portslave">portslave</option>
													<option value="tc">tc</option>
													<option value="usrhiper">usrhiper</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="form-row">
									
									<div class="form-group col-md-4 col-sm-12">
										<div class="input-item input-with-label"><label
												class="input-item-label text-exlight">NAS Name
											</label>
											<div class="input-wrap"><input id="full-name" name="nasname"
													class="input-bordered required" type="text"></div>
										</div>
									</div>
									<div class="form-group col-md-4 col-sm-12">
										<div class="input-item input-with-label"><label class="input-item-label text-exlight">
												NAS Ports
											</label>
											<div class="input-wrap"><input id="full-name" name="nasports"
													class="input-bordered required" type="text"></div>
										</div>
									</div>
									<div class="form-group col-md-4 col-sm-12">
										<div class="input-item input-with-label"><label
												class="input-item-label text-exlight">NAS Community</label>
											<div class="input-wrap"><input id="full-name" name="nascommunity"
													class="input-bordered required" type="text"></div>
										</div>
									</div>
								</div>
								<div class="form-row">
									<div class="form-group col-md-4 col-sm-12">
										<div class="input-item input-with-label"><label
												class="input-item-label text-exlight">NAS Virtual Server</label>
											<div class="input-wrap"><input id="full-name" name="nasvirtualserver"
													class="input-bordered required" type="text"></div>
										</div>
									</div>
									<div class="form-group col-md-8 col-sm-12">
										<div class="input-item input-with-label"><label
												class="input-item-label text-exlight">Description</label>
											<div class="input-wrap"><input id="full-name" name="nasdescription"
													class="input-bordered required" type="text"></div>
										</div>
									</div>
								</div>
								<div class="gaps-1x"></div>
                        	</div>
						</div>
						<div class="card-footer">
							<button type="submit" name="submit" class="btn btn-primary">Save NAS</button>
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