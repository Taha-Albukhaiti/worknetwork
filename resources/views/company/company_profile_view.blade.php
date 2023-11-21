@extends('company.company_dashboard')
@section('company')

    <!-- partial -->

    <div class="page-content">
        <div class="row profile-body justify-content-center align-items-center">
            <!-- left wrapper start -->
            <div class="d-none d-md-block col-md-6 col-xl-6 left-wrapper">
                <div class="card rounded">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">

                            <div>
                                <img class="wd-100 rounded-circle"
                                     src=" {{ !empty($data->photo) ? url('upload/company_images/'.$data->photo) : url('upload/no_image.jpg')}}"
                                     alt="profile">
                                <span class="h4 ms-3 ">{{ $data->username }}</span>
                            </div>

                            <div class="dropdown">
                                <a type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
                                   aria-expanded="false">
                                    <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('company.profile.edit') }}"><i
                                                data-feather="edit-2" class="icon-sm me-2"></i> <span
                                                class="">Edit</span></a>
                                    <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i
                                                data-feather="git-branch" class="icon-sm me-2"></i> <span class="">Update</span></a>
                                    <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i
                                                data-feather="eye" class="icon-sm me-2"></i> <span
                                                class="">View all</span></a>
                                </div>

                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="tx-11 fw-bolder mb-0 text-uppercase">Name:</label>
                            <p class="text-muted">{{ $data->name }}</p>
                        </div>
                        <div class="mt-3">
                            <label class="tx-11 fw-bolder mb-0 text-uppercase">Email:</label>
                            <p class="text-muted">{{ $data->email }}</p>
                        </div>
                        <div class="mt-3">
                            <label class="tx-11 fw-bolder mb-0 text-uppercase">phone:</label>
                            <p class="text-muted">{{ $data->phone }}</p>
                        </div>
                        <div class="mt-3 d-flex social-links">
                            <a href="javascript:;" class="btn btn-icon border btn-xs me-2">
                                <i data-feather="github"></i>
                            </a>
                            <a href="javascript:;" class="btn btn-icon border btn-xs me-2">
                                <i data-feather="twitter"></i>
                            </a>
                            <a href="javascript:;" class="btn btn-icon border btn-xs me-2">
                                <i data-feather="instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- left wrapper end -->
        </div>

    </div>
    <!-- partial:../../partials/_footer.html -->
@endsection