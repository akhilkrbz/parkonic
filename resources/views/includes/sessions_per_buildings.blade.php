<div class="col-lg-6 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Total Sessions per Building</h4>
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
                                Building
                            </th>
                            <th>
                                Session
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($buildings as $key => $building)
                        <tr>
                            <td class="py-1">
                                {{ $key + 1 }}
                            </td>
                            <td>
                                {{ $building->name }}
                            </td>
                            <td>
                                {{ $building->parking_sessions_count }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>