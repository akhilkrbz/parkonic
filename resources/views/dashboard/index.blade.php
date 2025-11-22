@extends('layouts.app')

@section('content')


	<!-- partial -->
	<div class="container-fluid page-body-wrapper">
		<div class="main-panel">
			<div class="content-wrapper">

				@include('includes.filters')


				<div class="row">
					
					@include('includes.summary_kpis')
				</div>

				<div class="row">
					@include('includes.hourly_session_chart')

					@include('includes.sessions_by_building_chart')
				</div>

				<div class="row mt-5">
					@include('includes.sessions_per_locations')
					@include('includes.sessions_per_buildings')
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

		// Load hourly session data on filter change
		function loadHourlyData() {
			var formData = $(".filterForm").serialize();
			$.ajax({
				type: "GET",
				url: '{{ route("dailyHourlySessionTracker") }}',
				data: formData,
				success: function(response) {
					if(response.status === 'success') {
						updateHourlyChart(response.data);
					} else {
						console.error('Error fetching hourly data:', response.message);
					}
				},
				error: function(xhr, status, error) {
					console.error(error);
				}
			});
		}

		// Update the chart with new data
		function updateHourlyChart(data) {
			// Create hour labels (0, 1, 2, ..., 23)
			var hourLabels = [];
			for (var i = 0; i < 24; i++) {
				hourLabels.push(i);
			}

			var hourlyChartData = {
				labels: hourLabels,
				datasets: [{
					label: 'Sessions Started',
					data: data,
					backgroundColor: '#464dee',
					borderColor: '#464dee',
					borderWidth: 1,
					fill: false
				}]
			};

			var hourlyChartOptions = {
				scales: {
					xAxes: [{
						barPercentage: 0.8,
						position: 'bottom',
						display: true,
						gridLines: {
							display: false,
							drawBorder: false,
						},
						ticks: {
							display: true,
						}
					}],
					yAxes: [{
						display: true,
						gridLines: {
							drawBorder: false,
							display: true,
							color: "#f0f3f6",
							borderDash: [8, 4],
						},
						ticks: {
							beginAtZero: true,
							stepSize: 1,
							callback: function(value) {
								// Only show integer values
								if (Number.isInteger(value)) {
									return value;
								}
								return '';
							}
						},
					}]
				},
				legend: {
					display: false
				},
				tooltips: {
					backgroundColor: 'rgba(0, 0, 0, 1)',
				},
				plugins: {
					datalabels: {
						display: false,
						align: 'center',
						anchor: 'center'
					}
				}
			};

			if ($("#dailyHourlySessionTracker").length) {
				// Destroy existing chart if it exists
				if (window.hourlyChart) {
					window.hourlyChart.destroy();
				}
				var barChartCanvas = $("#dailyHourlySessionTracker").get(0).getContext("2d");
				window.hourlyChart = new Chart(barChartCanvas, {
					type: 'line',
					data: hourlyChartData,
					options: hourlyChartOptions
				});
			}
		}

		// Load hourly data on initial page load
		loadHourlyData();

		// Load building sessions data
		function loadBuildingData() {
			var formData = $(".filterForm").serialize();
			$.ajax({
				type: "GET",
				url: '{{ route("sessionsByBuilding") }}',
				data: formData,
				success: function(response) {
					if(response.status === 'success') {
						updateBuildingChart(response.building_names, response.session_counts);
					} else {
						console.error('Error fetching building data:', response.message);
					}
				},
				error: function(xhr, status, error) {
					console.error(error);
				}
			});
		}

		// Update the building sessions chart
		function updateBuildingChart(buildingNames, sessionCounts) {
			var buildingChartData = {
				labels: buildingNames,
				datasets: [{
					label: 'Session Count',
					data: sessionCounts,
					backgroundColor: '#ff6b6b',
					borderColor: '#ff6b6b',
					borderWidth: 1,
					fill: false
				}]
			};

			var buildingChartOptions = {
				scales: {
					xAxes: [{
						barPercentage: 0.8,
						position: 'bottom',
						display: true,
						gridLines: {
							display: false,
							drawBorder: false,
						},
						ticks: {
							display: true,
						}
					}],
					yAxes: [{
						display: true,
						gridLines: {
							drawBorder: false,
							display: true,
							color: "#f0f3f6",
							borderDash: [8, 4],
						},
						ticks: {
							beginAtZero: true,
							stepSize: 1,
							callback: function(value) {
								// Only show integer values
								if (Number.isInteger(value)) {
									return value;
								}
								return '';
							}
						},
					}]
				},
				legend: {
					display: false
				},
				tooltips: {
					backgroundColor: 'rgba(0, 0, 0, 1)',
				},
				plugins: {
					datalabels: {
						display: false,
						align: 'center',
						anchor: 'center'
					}
				}
			};

			if ($("#sessionsByBuildingChart").length) {
				// Destroy existing chart if it exists
				if (window.buildingChart) {
					window.buildingChart.destroy();
				}
				var barChartCanvas = $("#sessionsByBuildingChart").get(0).getContext("2d");
				window.buildingChart = new Chart(barChartCanvas, {
					type: 'bar',
					data: buildingChartData,
					options: buildingChartOptions
				});
			}
		}

		// Load building data on initial page load
		loadBuildingData();

		// Reload hourly data when filter form is submitted
		$(".filterForm").on("submit", function(e) {
			loadHourlyData();
			loadBuildingData();
		});
	});
</script>

@endpush