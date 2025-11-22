@extends('layouts.app')

@section('content')


	<!-- partial -->
	<div class="container-fluid page-body-wrapper">
		<div class="main-panel">
			<div class="content-wrapper">

				@include('includes.filters')


				<div class="row">
					<div class="col-sm-12 flex-column d-flex stretch-card">
						<div class="row">

							<div class="col-lg-3 d-flex grid-margin stretch-card">
								<div class="card sale-visit-statistics-border" style="background-color: #87f5be !important;">
									<div class="card-body">
										<h3 class="text-dark mb-2 font-weight-bold total_active_sessions">0</h3>
										<h4 class="card-title mb-2">Total Active Sessions</h4>
										<small class="text-muted"></small>
									</div>
								</div>
							</div>
							<div class="col-lg-3 d-flex grid-margin stretch-card">
								<div class="card sale-visit-statistics-border" style="background-color: #ff708b !important;">
									<div class="card-body">
										<h3 class="text-dark mb-2 font-weight-bold total_closed_sessions">0</h3>
										<h4 class="card-title mb-2">Total Closed Sessions</h4>
										<small class="text-muted"></small>
									</div>
								</div>
							</div>
							<div class="col-lg-3 d-flex grid-margin stretch-card">
								<div class="card sale-visit-statistics-border">
									<div class="card-body">
										<h3 class="text-dark mb-2 font-weight-bold avg_parking_duration_formatted">0 Mins</h3>
										<h4 class="card-title mb-2">Average Parking Duration</h4>
										<small class="text-muted"></small>
									</div>
								</div>
							</div>
							<div class="col-lg-3 d-flex grid-margin stretch-card">
								<div class="card sale-visit-statistics-border" style="background-color: #f0e586 !important;">
									<div class="card-body">
										<h3 class="text-dark mb-2 font-weight-bold top_vehicle">N/A</h3>
										<h4 class="card-title mb-2"> Top Vehicle by Session Count</h4>
										<small class="text-muted"></small>
									</div>
								</div>
							</div>
						</div>

					</div>

				</div>

			</div>
			<!-- content-wrapper ends -->
			<!-- partial:partials/_footer.html -->
			<footer class="footer">
				<div class="footer-wrap">
					<div class="d-sm-flex justify-content-center justify-content-sm-between">
						<span class="text-muted text-center text-sm-left d-block d-sm-inline-block"></span>

					</div>
				</div>
			</footer>
			<!-- partial -->
		</div>
		<!-- main-panel ends -->
	</div>
	<!-- page-body-wrapper ends -->

@endsection


@push('scripts')

<script>
	$(document).ready(function() {

		$(".filterForm").on("submit", function(e) {
			e.preventDefault();
			var form = $(this);
			var url = '{{ $filterAction }}';
			var formData = form.serialize();
			$.ajax({
				type: "GET",
				url: url,
				data: formData,
				success: function(response) {
					if(response.status === 'success') {
						$(".total_active_sessions").text(response.total_active_sessions);
						$(".total_closed_sessions").text(response.total_closed_sessions);
						$(".avg_parking_duration_formatted").text(response.avg_parking_duration_formatted);
						$(".top_vehicle").html(`Vehicle No. <b>${response.top_vehicle.plate}</b><br> No. of Sessions: <b>${response.top_vehicle.session_count}</b>`);
					} else {
						console.error('Error fetching dashboard summary:', response.message);
					}
				},
				error: function(xhr, status, error) {
					// Handle any errors that occurred during the request
					console.error(error);
				}
			});
		});

		// Auto-submit the filter form on page load
		$(".filterForm").trigger('submit');
	});
</script>

@endpush