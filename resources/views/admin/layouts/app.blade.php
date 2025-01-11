<!DOCTYPE html>
<html lang="en">
    <head>
		<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
		<meta name="csrf-token" content="{{ csrf_token() }}">


        <title>Dashboard - Dine Queue Admin</title>
		
		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="assets/favicon.ico">
		
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
		
		<!-- Fontawesome CSS -->
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
		
		<!-- Lineawesome CSS -->
        <link rel="stylesheet" href="assets/css/line-awesome.min.css">
		
		<!-- Chart CSS -->
		<link rel="stylesheet" href="assets/plugins/morris/morris.css">
		
		<!-- Datatable CSS -->
		<link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">
		
		<!-- Select2 CSS -->
		<link rel="stylesheet" href="assets/css/select2.min.css">

		<!-- Main CSS -->
        <link rel="stylesheet" href="assets/css/style.css">
		
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->
    </head>
	
    <body>
		
		<!-- Main Wrapper -->
		<div class="main-wrapper">
		
			<!-- Header -->
			<div class="header">
			
				<!-- Logo -->
				<div class="header-left">
					<a href="index.html" class="logo">
						<img src="images/recLogo.png" height="50" alt="logo">
					</a>
				</div>
				<!-- /Logo -->
				
				<a id="toggle_btn" href="javascript:void(0);">
					<span class="bar-icon">
						<span></span>
						<span></span>
						<span></span>
					</span>
				</a>
				
				<!-- Header Title -->
				<div class="page-title-box">
					<h3>Admin Dashboard</h3>
				</div>
				<!-- /Header Title -->
				
				<a id="mobile_btn" class="mobile_btn" href="#sidebar"><i class="fa fa-bars"></i></a>
				
				
			</div>
			<!-- /Header -->
			
			<!-- Sidebar -->
			<div class="sidebar" id="sidebar">
				<div class="sidebar-inner slimscroll">
					<div id="sidebar-menu" class="sidebar-menu">
						<ul>
							
							<li class="menu-title"> 
								<span>Order</span>
							</li>
							<li> 
								<a href="/waitlist"><i class="la la-file-pdf-o"></i> <span>Waitlist</span></a>
							</li>
							<li> 
								<a href="/orderlist"><i class="la la-file-pdf-o"></i> <span>Order</span></a>
							</li>

							<li class="menu-title"> 
								<span>Manage</span>
							</li>
							<li> 
								<a href="/categories"><i class="la la-users"></i> <span>Category</span></a>
							</li>
							<li> 
								<a href="/products"><i class="la la-users"></i> <span>Product</span></a>
							</li>
							<li> 
								<a href="/tables"><i class="la la-users"></i> <span>Table</span></a>
							</li>
							
							
							
						</ul>
					</div>
				</div>
			</div>
			<!-- /Sidebar -->
			
			<!-- Page Wrapper -->
			@yield('content')
			<!-- /Page Wrapper -->
			
		</div>
		<!-- /Main Wrapper -->

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		
		<!-- jQuery -->
        <script src="assets/js/jquery-3.5.1.min.js"></script>
		
		<!-- Bootstrap Core JS -->
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
		
		<!-- Slimscroll JS -->
		<script src="assets/js/jquery.slimscroll.min.js"></script>
		
		<!-- Chart JS -->
		<script src="assets/plugins/morris/morris.min.js"></script>
		<script src="assets/plugins/raphael/raphael.min.js"></script>
		<script src="assets/js/chart.js"></script>
		
		<!-- Custom JS -->
		<script src="assets/js/app.js"></script>

		<!-- Datatable JS -->
		<script src="assets/js/jquery.dataTables.min.js"></script>
		<script src="assets/js/dataTables.bootstrap4.min.js"></script>
		
		<!-- Select2 JS -->
		<script src="assets/js/select2.min.js"></script>
		@yield('script')
    </body>
</html>