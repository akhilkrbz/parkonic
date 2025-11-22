<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Filters</h4>
                <form class="form-sample filterForm" method="post">
                    @csrf
                    <p class="card-description">

                    </p>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">From Date</label>
                                <div class="col-sm-9">
                                    <input type="date" name="from_date" class="form-control" value="{{ date('Y-m-d', strtotime('-7 days')) }}" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">To Date</label>
                                <div class="col-sm-9">
                                    <input type="date" name="to_date" class="form-control" value="{{ date('Y-m-d') }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Location</label>
                                <div class="col-sm-9">
                                    <select name="location" class="js-example-basic-single w-100">
                                        <option value="All">All Locations</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Building</label>
                                <div class="col-sm-9">
                                    <select name="building" class="js-example-basic-single w-100">
                                        <option value="All">All Buildings</option>
                                        @foreach($buildings as $building)
                                        <option value="{{ $building->id }}">{{ $building->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Status</label>
                                <div class="col-sm-9">
                                    <select name="status" class="js-example-basic-single w-100">
                                        <option value="All">All</option>
                                        <option value="1">Active</option>
                                        <option value="2">Closed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary mr-2" style="margin-top: 10px;">Apply Filters</button>
                            </div>
                        </div>

                    </div>

                    
                </form>
            </div>
        </div>
    </div>
</div>