<div class="modal fade" id="exampleModalTransporte" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 mx-auto">
                    <div class="modal-content">
                        <div class="card">
                            <div class="card-body invoice-head">
                                <div class="row gy-2 align-items-center" id="date_1">

                                </div>
                                <!--end row-->
                            </div>
                            <!--end card-body-->
                            <div class="card-body" id="body">
                                <div class="row p-2">
                                    <div class="col-lg-12">
                                        <h5 class="bg-primary col-lg-12 mt-0 p-2 text-center text-white d-sm-inline-block">
                                            Owner</h5>
                                        <div class="table-responsive project-invoice">
                                            <table class="table table-bordered table-sm align-middle mb-0">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Document</th>
                                                        <th>Name</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="row1_1"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row p-2">
                                    <div class="col-lg-12">
                                        <h5 class="bg-info col-lg-12 mt-0 p-2 text-center text-white d-sm-inline-block">
                                            Driver</h5>
                                        <div class="table-responsive project-invoice">
                                            <table class="table table-bordered table-sm align-middle mb-0">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Document</th>
                                                        <th>Name</th>
                                                        <th>Phone</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="row2_2"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row p-2">
                                    <div class="col-lg-12">
                                        <h5 class="bg-warning col-lg-12 mt-0 p-2 text-center text-white d-sm-inline-block">
                                            Summary</h5>
                                        <div class="table-responsive project-invoice">
                                            <table class="table table-bordered table-sm align-middle mb-0">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Operation Type</th>
                                                        <th>Shipment Status</th>
                                                        <th>Advance Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="row3_3"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row p-2">
                                    <div class="col-lg-12">
                                        <h5 class="bg-secondary col-lg-12 mt-0 p-2 text-center text-white d-sm-inline-block">
                                            Route</h5>
                                        <div class="table-responsive project-invoice">
                                            <table class="table table-bordered table-sm align-middle mb-0">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Origin City</th>
                                                        <th>Origin Province</th>
                                                        <th>Origin Address</th>
                                                        <th>Route</th>
                                                        <th>Way</th>
                                                        <th>Destination City</th>
                                                        <th>Destination Province</th>
                                                        <th>Destination Address</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="row4_4"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row p-2">
                                    <div class="col-lg-12">
                                        <h5 class="bg-success col-lg-12 mt-0 p-2 text-center text-white d-sm-inline-block">
                                            Vehicle Information</h5>
                                        <div class="table-responsive project-invoice">
                                            <table class="table table-bordered table-sm align-middle mb-0">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>License Plate</th>
                                                        <th>Make</th>
                                                        <th>Color</th>
                                                        <th>Model</th>
                                                        <th>Trailer Number</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="row5_5"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!--end row-->


                                {{-- <div class="row justify-content-center">
                                    <div class="col-lg-12">
                                        <h5 class="mt-4"><i
                                                class="fas fa-divide mr-2 text-info font-16"></i>@lang('locale.Installments')
                                            :</h5>
                                    </div>
                                    <!--end col-->
                                </div> --}}
                                <!--end row-->
                                <div class="row d-flex justify-content-center">
                                    <div class="col-lg-12 col-xl-4 ml-auto align-self-center">
                                        <div class="text-center"><small class="font-12">Tractocar
                                                Logistics SAS.</small>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </div>
                            <!--end card-body-->
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="closet-modal" class="btn btn-secondary"
                                data-bs-dismiss="modal">Close</button>
                        </div>

                    </div>
                    <!--end card-->
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
    </div>
</div>