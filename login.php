<?php 
	isset($_REQUEST['error']) ? $error = $_REQUEST['error'] : $error = "";
	
	// clean up error code to avoid XSS
	$error = strip_tags(htmlspecialchars($error));
	include_once ("lang/main.php");
?>
<!DOCTYPE html>
<html lang="en" class="js">
<!-- Blank Template for Accra Poly Dashboard -->

<head>
    <meta charset="utf-8">
    <meta name="author" content="Codbit Ghana Limited">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Fully featured and complete Dashboard template for radius backend management.">
    <title>Radius Server Management</title>
    <link rel="stylesheet" href="assets/css/vendor.bundle49f7.css?ver=104">
    <link rel="stylesheet" href="assets/css/style49f7.css?ver=104" id="layoutstyle">
    <link rel="stylesheet" href="assets/css/utils.css">
</head>

<body class="page-ath">
    <div class="page-ath-wrap">
        <div class="page-ath-content">
            <div class="page-ath-header"><a href="javascript:void(0)" class="page-ath-logo"><img src="assets/images/atu.png" alt="logo" class="small-logo"></a></div>
            <div class="page-ath-form">
                <h2 class="page-ath-heading">Sign in</h2>
                <form action="dologin.php" method="POST">
                    <div class="input-item"><input name="operator_user" value="administrator" type="text" class="input-bordered" tabindex=1 /></div>
                    <div class="input-item"><input name="operator_pass" value="" type="password"  class="input-bordered" tabindex=2 /></div>
                    <div class="d-flex justify-content-between align-items-center">
						<?php
							if ($error) { 
								echo $error;
								echo t('messages','loginerror');
							}
						?>
                    </div><button class="btn btn-primary btn-block">Sign In</button>
                </form>
            </div>
        </div>
        <div class="page-ath-gfx">
            <div class="w-100 d-flex justify-content-center">
            </div>
        </div>
    </div><!-- JavaScript (include all script here) -->
    <script src="assets/js/jquery.bundle49f7.js?ver=104"></script>
</body>
</html>