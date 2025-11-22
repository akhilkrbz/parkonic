@extends('layouts.app')

@section('content')


    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper">

                @include('includes.filters')


                <div class="row">

                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Sessions Report</h4>
                                <p class="card-description">

                                </p>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Sl.No.</th>
                                                <th>In Time</th>
                                                <th>Out Time</th>
                                                <th>Location</th>
                                                <th>Building</th>
                                                <th>Entry Access Point Name</th>
                                                <th>Exit Access Point Name</th>
                                                <th>Plate</th>
                                                <th>Status</th>
                                                <th>Duration</th>
                                            </tr>
                                        </thead>
                                        <tbody class="report_html">
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <div class="pagination-wrapper" id="pagination_wrapper">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination" id="pagination_html">
                                            
                                        </ul>
                                    </nav>
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
        $(document).ready(function () {

            // Load report with pagination
            function loadReport(page = 1) {
                var form = $(".filterForm");
                var url = '{{ $filterAction }}';
                var formData = form.serialize() + '&page=' + page;
                
                $.ajax({
                    type: "GET",
                    url: url,
                    data: formData,
                    success: function (response) {
                        if (response.status === 'success') {
                            $(".report_html").html(response.report_html);
                            // Replace entire pagination wrapper with new pagination HTML
                            $("#pagination_wrapper").html(response.pagination_html);
                            
                            // Attach click handlers to pagination links
                            attachPaginationHandlers();
                        } else {
                            console.error('Error fetching report data:', response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            }

            // Attach pagination link click handlers
            function attachPaginationHandlers() {
                $(document).on('click', '.pagination a', function(e) {
                    e.preventDefault();
                    var url = $(this).attr('href');
                    // Extract page number from URL
                    var pageNum = new URL(url, window.location.origin).searchParams.get('page');
                    if (pageNum) {
                        loadReport(pageNum);
                        // Scroll to top of table
                        $('html, body').animate({scrollTop: $('.table-responsive').offset().top - 100}, 500);
                    }
                });
            }

            $(".filterForm").on("submit", function (e) {
                e.preventDefault();
                loadReport(1); // Reset to first page on filter submit
            });

            // Auto-submit the filter form on page load
            $(".filterForm").trigger('submit');

        });
    </script>

@endpush