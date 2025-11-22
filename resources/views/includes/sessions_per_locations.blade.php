<div class="col-lg-6 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Total Sessions per Location</h4>
            <p class="card-description">
                
            </p>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>
                                Sl.No.
                            </th>
                            <th>
                                Location
                            </th>
                            <th>
                                Session
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($locations as $key => $location)
                        <tr>
                            <td class="py-1">
                                {{ $key + 1 }}
                            </td>
                            <td>
                                {{ $location->name }}
                            </td>
                            <td>
                                {{ $location->parking_sessions_count }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>