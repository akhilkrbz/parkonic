<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport"
			content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Parkonic Reports Demo</title>
		<!-- base:css -->
		<link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
		<link rel="stylesheet" href="{{ asset('assets/vendors/base/vendor.bundle.base.css') }}">
		<!-- endinject -->
		<!-- plugin css for this page -->
		<link rel="stylesheet" href="{{ asset('assets/vendors/select2/select2.min.css') }}">
  		<link rel="stylesheet" href="{{ asset('assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
		<!-- End plugin css for this page -->
		<!-- inject:css -->
		<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
		<!-- endinject -->
		<link rel="shortcut icon" href="{{ asset('assets/images/favicon.png')}}" />
	</head>
	<body>
		<div class="container-scroller">
			
			<!-- partial:partials/_horizontal-navbar.html -->
			<div class="horizontal-menu">
				<nav class="navbar top-navbar col-lg-12 col-12 p-0">
					<div class="container-fluid">
						<div
							class="navbar-menu-wrapper d-flex align-items-center justify-content-between">
							
							<!-- <button
								class="navbar-toggler navbar-toggler-right d-lg-none align-self-center"
								type="button" data-toggle="horizontal-menu-toggle">
								<span class="mdi mdi-menu"></span>
							</button> -->
						</div>
					</div>
				</nav>
				<nav class="bottom-navbar">
					<div class="container">
						<ul class="nav page-navigation">
							<li class="nav-item">
								<a class="nav-link" href="index.html">
									<i class="mdi mdi-file-document-box menu-icon"></i>
									<span class="menu-title">Dashboard</span>
								</a>
							</li>
							
							<li class="nav-item">
								<a href="pages/forms/basic_elements.html" class="nav-link">
									<i class="mdi mdi-chart-areaspline menu-icon"></i>
									<span class="menu-title">Sessions Report</span>
									<i class="menu-arrow"></i>
								</a>
							</li>
							
						</ul>
					</div>
				</nav>
			</div>
            @yield('content')
		</div>
		<!-- container-scroller -->
		<!-- base:js -->
		<script src="{{ asset('assets/vendors/base/vendor.bundle.base.js') }}"></script>
		<!-- endinject -->
		<!-- Plugin js for this page-->
		<!-- End plugin js for this page-->
		<!-- inject:js -->
		<script src="{{ asset('assets/js/template.js') }}"></script>
		<!-- endinject -->
		<!-- plugin js for this page -->
		<!-- End plugin js for this page -->
		<script src="{{ asset('assets/vendors/chart.js/Chart.min.js') }}"></script>
		<script src="{{ asset('assets/vendors/progressbar.js/progressbar.min.js') }}"></script>
		<script src="{{ asset('assets/vendors/chartjs-plugin-datalabels/chartjs-plugin-datalabels.js') }}"></script>
		<script src="{{ asset('assets/vendors/justgage/raphael-2.1.4.min.js') }}"></script>
		<script src="{{ asset('assets/vendors/justgage/justgage.js') }}"></script>
		<script src="{{ asset('assets/js/jquery.cookie.js') }}" type="text/javascript"></script>
		<!-- Custom js for this page-->
		<script src="{{ asset('assets/js/dashboard.js') }}"></script>
		<!-- End custom js for this page-->
		<script src="{{ asset('assets/vendors/select2/select2.min.js') }}"></script>
		<script src="{{ asset('assets/js/select2.js') }}"></script>
		@stack('scripts')
	</body>
</html>