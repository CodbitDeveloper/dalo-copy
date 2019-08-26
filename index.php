<?php
	error_reporting(E_ERROR | E_PARSE);
	include ("library/checklogin.php");
	include("include/management/pages_common.php");
	
    $operator = $_SESSION['operator_user'];

    include ("menu-home.php");

	include_once('library/config_read.php');
    $log = "visited page: ";
	include('include/config/logging.php');
	
	// Display uptime system
	// @return string Return uptime system
	function uptime() {
		$file_name = "/proc/uptime";

		$fopen_file = fopen($file_name, 'r');
		$buffer = explode(' ', fgets($fopen_file, 4096));
		fclose($fopen_file);

		$sys_ticks = trim($buffer[0]);
		$min = $sys_ticks / 60;
		$hours = $min / 60;
		$days = floor($hours / 24);
		$hours = floor($hours - ($days * 24));
		$min = floor($min - ($days * 60 * 24) - ($hours * 60));
		$result = "";

		if ($days != 0) {
			if ($days > 1)
				$result = "$days " . " days ";
			else
				$result = "$days " . " day ";
		}

		if ($hours != 0) {
			if ($hours > 1)
				$result .= "$hours " . " hours ";
			else
				$result .= "$hours " . " hour ";
		}

		if ($min > 1 || $min == 0)
			$result .= "$min " . " minutes ";
		elseif ($min == 1)
			$result .= "$min " . " minute ";

		return $result;
	}


	// Display hostname system
	// @return string System hostname or none
	function get_hostname() {
		$file_name = "/proc/sys/kernel/hostname";

		if ($fopen_file = fopen($file_name, 'r')) {
			$result = trim(fgets($fopen_file, 4096));
			fclose($fopen_file);
		} else {
			$result = "(none)";
		}

		return $result;
	}


	// Display currenty date/time
	// @return string Current system date/time or none
	function get_datetime() {
		if ($today = date("F j, Y, g:i a")) {
			$result = $today;
		} else {
			$result = "(none)";
		}

		return $result;
	}



	// Get System Load Average
	// @return array System Load Average
	function get_system_load() {
		$file_name = "/proc/loadavg";
		$result = "";
		$output = "";

		// get the /proc/loadavg information
		if ($fopen_file = fopen($file_name, 'r')) {
			$result = trim(fgets($fopen_file, 256));
			fclose($fopen_file);
		} else {
			$result = "(none)";
		}

		$loadavg = explode(" ", $result);
		$output .= $loadavg[0] . " " . $loadavg[1] . " " . $loadavg[2] . "<br/>";


		// get information the 'top' program
		$file_name = "top -b -n1 | grep \"Tasks:\" -A1";
		$result = "";

		if ($popen_file = popen($file_name, 'r')) {
			$result = trim(fread($popen_file, 2048));
			pclose($popen_file);
		} else {
			$result = "(none)";
		}

		$result = str_replace("\n", "<br/>", $result);
		$output .= $result;

		return $output;
	}


	// Get Memory System MemTotal|MemFree
	// @return array Memory System MemTotal|MemFree
	function get_memory() {
		$file_name = "/proc/meminfo";
		$mem_array = array();

		$buffer = file($file_name);

		while (list($key, $value) = each($buffer)) {
			if (strpos($value, ':') !== false) {
				$match_line = explode(':', $value);
				$match_value = explode(' ', trim($match_line[1]));
				if (is_numeric($match_value[0])) {
					$mem_array[trim($match_line[0])] = trim($match_value[0]);
				}
			}
		}

		return $mem_array;
	}


	//Get FreeDiskSpace
	function get_hdd_freespace() {
	$df = disk_free_space("/");
	return $df;
	}


	// Convert value to MB
	// @param decimal $value
	// @return int Memory MB
	function convert_ToMB($value) {
		return round($value / 1024) . " MB\n";
	}



	// Get all network names devices (eth[0-9])
	// @return array Get list network name interfaces
	function get_interface_list() {
		$devices = array();
		$file_name = "/proc/net/dev";

		if ($fopen_file = fopen($file_name, 'r')) {
			while ($buffer = fgets($fopen_file, 4096)) {
				if (preg_match("/eth[0-9][0-9]*/i", trim($buffer), $match)) {
					$devices[] = $match[0];
				}
			}
			$devices = array_unique($devices);
			sort($devices);
			fclose ($fopen_file);
		}
		return $devices;
	}



	// Get ip address
	// @param string $ifname
	// @return string Ip address or (none)
	function get_ip_addr($ifname) {
		$command_name = "/sbin/ifconfig $ifname";
		$ifip = "";

		exec($command_name , $command_result);

		$ifip = implode($command_result, "\n");
		if (preg_match("/inet addr:[0-9\.]*/i", $ifip, $match)) {
			$match = explode(":", $match[0]);
			return $match[1];
		} elseif (preg_match("/inet [0-9\.]*/i", $ifip, $match)) {
			$match = explode(" ", $match[0]);
			return $match[1];
		} else {
			return "(none)";
		}
	}

	// Get mac address
	// @param string $ifname
	// @return string Mac address or (none)
	function get_mac_addr($ifname) {
		$command_name = "/sbin/ifconfig $ifname";
		$ifip = "";

		exec($command_name , $command_result);

		$ifmac = implode($command_result, "\n");
		if (preg_match("/hwaddr [0-9A-F:]*/i", $ifmac, $match)) {
			$match = explode(" ", $match[0]);
			return $match[1];
		} elseif (preg_match("/ether [0-9A-F:]*/i", $ifmac, $match)) {
			$match = explode(" ", $match[0]);
			return $match[1];
		} else {
			return "(none)";
		}
	}


	// Get netmask address
	// @param string $ifname
	// @return string Netmask address or (none)
	function get_mask_addr($ifname) {
		$command_name = "/sbin/ifconfig $ifname";
		$ifmask = "";

		exec($command_name , $command_result);

		$ifmask = implode($command_result, "\n");
		if (preg_match("/mask:[0-9\.]*/i", $ifmask, $match)) {
			$match = explode(":", $match[0]);
			return $match[1];
		} elseif (preg_match("/netmask [0-9\.]*/i", $ifmask, $match)) {
			$match = explode(" ", $match[0]);
			return $match[1];
		} else {
			return "(none)";
		}
	}

	function check_service($sname) {
		if ($sname != '') {
			system("pgrep ".escapeshellarg($sname)." >/dev/null 2>&1", $ret_service);
			if ($ret_service == 0) {
				return "Enabled";
			} else {
				return "Disabled";
			}
		} else {
			return "no service name";
		}
	}

	$meminfo = get_memory();
?>
		
		
		<div class="page-content">
        	<div class="container">
				<?php
					/*include 'library/exten-welcome_page.php';*/
				?>
				<div class="row">
					<div class="col-lg-4">
						<div class="token-statistics card card-token height-auto">
							<div class="card-innr">
								<div class="token-balance token-balance-with-icon">
									<div class="token-balance-icon"><i class="fas fa-server"></i></div>
									<div class="token-balance-text">
										<h6 class="card-sub-title">Uptime</h6>
										<span class="lead"><?php echo uptime(); ?></span>
									</div>
								</div>
								<div class="token-balance token-balance-s2">
									<h6 class="card-sub-title">Server Information</h6>
									<ul class="token-balance-list">
										<li class="token-balance-sub"><span class="lead"><?php echo convert_ToMB ($meminfo['MemTotal']); ?></span><span
												class="sub">Total Memory</span></li>
										<li class="token-balance-sub"><span class="lead"><?php echo convert_ToMB ($meminfo['MemFree']); ?></span><span
												class="sub">Free Memory</span></li>
										<li class="token-balance-sub"><span class="lead"><?php echo toxbyte (get_hdd_freespace()); ?></span><span
												class="sub">Hard Drive</span></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-8">
						<div class="token-information card card-full-height">
							<div class="row no-gutters height-100">
								<div class="col-md-6 text-center">
									<div class="token-info"><i class="token-info-icon fas fa-calendar"></i>
										<div class="gaps-2x"></div>
										<h1 class="token-info-head text-light"><?php echo get_datetime(); ?></h1>
										<h5 class="token-info-sub">Server Date Time</h5>
									</div>
								</div>
								<div class="col-md-6">
									<div class="token-info bdr-tl">
										<div>
											<ul class="token-info-list">
												<li><span>RADIUS:</span><?php echo check_service("radius"); ?></li>
												<li><span>MYSQL:</span><?php echo check_service("mysql"); ?></li>
											</ul> <a href="#" class="btn btn-primary"><em
													class="fas fa-download mr-3"></em>Download Service Status</a>
										</div>
									</div>
								</div>
							</div>
						</div><!-- .card -->
					</div>
            	</div>
			</div>
		</div>
		
</div>
</div>


</body>
</html>
