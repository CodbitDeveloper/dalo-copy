<!--div id="header">
				
								<span id="login_data">
									Welcome, <b></b>. <a href="logout.php" title="Logout">&#x274E;</a>
									<br>
									Location: <b><?php /* echo $_SESSION['location_name'] ?></b>.
								</span>
								
								<span id="sep">
									&nbsp;
								</span>

                                <form action="mng-search.php">
									<input name="username"
										value=""
										placeholder="<?php echo t('button','SearchUsers') ?>"
										title="<?php echo t('Tooltip','Username') . '. ' . t('Tooltip','UsernameWildcard'); ?>"
									/>
                                </form>
																
								<span id="sep">
									&nbsp;
								</span>

                                <h1><a href="index.php"> <img src="images/daloradius_small.png" border=0/></a></h1>

                                <h2>
									<?php echo t('all','copyright1'); ?>
				                </h2>

                                <ul id="nav">
				<a name='top'></a>

				<li><a href="index.php" <?php echo ($m_active == "Home") ? "class=\"active\"" : ""?>></a></li>
				<li><a href="mng-main.php" <?php echo ($m_active == "Management") ? "class=\"active\"" : "" ?>><?php echo t('menu','Managment'); ?></a></li>
				<li><a href="rep-main.php" <?php echo ($m_active == "Reports") ? "class=\"active\"" : "" ?>><?php echo t('menu','Reports'); ?></a></li>
				<li><a href="acct-main.php" <?php echo ($m_active == "Accounting") ? "class=\"active\"" : "" ?>><?php echo t('menu','Accounting'); ?></a></li>
				<li><a href="bill-main.php" <?php echo ($m_active == "Billing") ? "class=\"active\"" : "" ?>><?php echo t('menu','Billing'); ?></a></li>
				<li><a href="gis-main.php" <?php echo ($m_active == "Gis") ? "class=\"active\"" : ""?>><?php echo t('menu','Gis'); ?></a></li>
				<li><a href="graph-main.php" <?php echo ($m_active == "Graphs") ? "class=\"active\"" : ""?>><?php echo t('menu','Graphs'); ?></li>
				<li><a href="config-main.php" <?php echo ($m_active == "Config") ? "class=\"active\"" : ""?>><?php echo t('menu','Config'); ?></li>
				<li><a href="help-main.php" <?php echo ($m_active == "Help") ? "class=\"active\"" : ""?>><?php echo t('menu','Help'); */ ?></a></li>

                                </ul-->
	<div class="topbar-wrap">
        <div class="topbar is-sticky">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <ul class="topbar-nav d-lg-none">
                        <li class="topbar-nav-item relative"><a class="toggle-nav" href="#">
                                <div class="toggle-icon"><span class="toggle-line"></span><span
                                        class="toggle-line"></span><span class="toggle-line"></span><span
                                        class="toggle-line"></span></div>
                            </a></li><!-- .topbar-nav-item -->
                    </ul><!-- .topbar-nav --><a class="topbar-logo" href="javascript:void(0)">&nbsp;</a>
                    <ul class="topbar-nav">
                        <li class="topbar-nav-item relative"><span
                                class="user-welcome d-none d-lg-inline-block">Welcome! <?php echo $operator; ?></span><a
                                class="toggle-tigger user-thumb" href="#"><em class="ti ti-user"></em></a>
                        </li><!-- .topbar-nav-item -->
                    </ul><!-- .topbar-nav -->
                </div>
            </div><!-- .container -->
        </div><!-- .topbar -->
        <div class="navbar">
            <div class="container">
                <div class="navbar-innr">
                    <ul class="navbar-menu">
						<li><a href="index.php"><em class="ikon ikon-dashboard"></em> Home</a></li>
						<li class="has-dropdown page-links-all"><a class="drop-toggle" href="#"><em
                                    class="ikon ikon-exchange"></em> Management</a>
                            <ul class="navbar-dropdown">
                                <li><a href="users.html">Users</a></li>
                                <li><a href="javascript:void(0)">Hotspots</a></li>
                                <li><a href="javascript:void(0)">NAS</a></li>
                                <li><a href="javascript:void(0)">User Groups</a></li>
                                <li><a href="javascript:void(0)">Profiles</a></li>
                                <li><a href="javascript:void(0)">Huntgroups</a></li>
                                <li><a href="javascript:void(0)">Attributes</a></li>
                                <li><a href="javascript:void(0)">Realms/Proxy</a></li>
                                <li><a href="javascript:void(0)">IP Pool</a></li>
                            </ul>
                        </li>
                        <li><a href="javascript:void(0)"><em class="ikon ikon-distribution"></em>
                                Reports</a></li>
                        <li><a href="javascript:void(0)"><em class="ikon ikon-transactions"></em> 
                            Accounting</a></li>
                        <li><a href="javascript:void(0)"><em class="ikon ikon-transactions"></em> 
                            Billing</a></li>
                    </ul>
                    <ul class="navbar-btns">
                        <li class=""><a href="logout.php"><span class="badge badge-outline badge-danger badge-lg"><em
									class="text-danger ti ti-files mgr-1x"></em><span class="text-danger">Sign Out</span></span></a>
								</li>
                    </ul>
                </div><!-- .navbar-innr -->
            </div><!-- .container -->
        </div><!-- .navbar -->
    </div>