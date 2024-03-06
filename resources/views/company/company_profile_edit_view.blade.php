@extends('company.company_dashboard')
@section('company')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- partial -->

    <div class="page-content">
        <div class="row profile-body">
            <!-- left wrapper start -->
            <div class="d-none d-md-block col-md-4 col-xl-4 left-wrapper">
                <div class="card rounded">
                    <div class="card-body">
                        <div class="d-flex  justify-content-between mb-2">

                            <div>
                                <img class="wd-100 rounded-circle"
                                     src=" {{ !empty($data->photo) ? url('upload/company_images/'.$data->photo) : url('upload/no_image.jpg')}}"
                                     alt="profile">
                                <span class="h4 ms-3 ">{{ $data->company_name }}</span>
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
                            <label class="tx-11 fw-bolder mb-0 text-uppercase">Phone:</label>
                            <p class="text-muted">{{ $data->phone }}</p>
                        </div>


                        <div class="mt-3">
                            <label class="tx-11 fw-bolder mb-0 text-uppercase">Address:</label>
                            <p class="text-muted ">{{ $address->street }} {{ $address->street_number }}</p>
                            <p class="text-muted
                            ">{{ $address->zip }} {{ $address->city }}</p>
                            <p class="text-muted
                            ">{{ $address->state }}</p>
                        </div>
                        <div class="mt-3">
                            <label class="tx-11 fw-bolder mb-0 text-uppercase">Description:</label>
                            <p class="text-muted
                            ">{{ $companyProfile->company_description }}</p>
                        </div>
                        <div class="mt-3">
                            <label class="tx-11 fw-bolder mb-0 text-uppercase">Website:</label>
                            <p class="text-muted
                            ">{{$companyProfile->company_website }}</p>
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
            <!-- middle wrapper start -->
            <div class="col-md-8 col-xl-8 middle-wrapper">
                <div class="row">
                    <div class="card">
                        <div class="card-body">

                            <h6 class="card-title">Update Admin Profile</h6>

                            <form method="post" action="{{ route('company.profile.store') }}"
                                  class="forms-sample" enctype="multipart/form-data">

                                @csrf

                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" name="username" id="username"
                                           autocomplete="off" value="{{ $data->username }}">
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                           autocomplete="off" value="{{ $data->name }}">
                                </div>

                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                                    <input type="text" class="form-control" name="email" id="exampleInputEmail1"
                                           autocomplete="off" value="{{ $data->email }}">
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control" name="phone" id="phone"
                                           autocomplete="off" value="{{ $data->phone }}">
                                </div>

                                <!-- Street -->
                                <div class="mb-3">
                                    <label for="street" class="form-label">Street:</label>
                                    <input type="text" id="street" name="address[street]" class="form-control"
                                           value="{{ $address->street ?? '' }}" required>
                                </div>

                                <!-- Street Number -->
                                <div class="mb-3">
                                    <label for="street_number" class="form-label">Street Number:</label>
                                    <input type="text" id="street_number" name="address[street_number]"
                                           class="form-control" value="{{ $address->street_number ?? '' }}"
                                           required>
                                </div>

                                <!-- City -->
                                <div class="mb-3">
                                    <label for="city" class="form-label">City:</label>
                                    <input type="text" id="city" name="address[city]" class="form-control"
                                           value="{{ $address->city ?? '' }}" required>
                                </div>

                                <!-- State -->
                                <div class="mb-3">
                                    <label for="state" class="form-label">State:</label>
                                    <input type="text" id="state" name="address[state]" class="form-control"
                                           value="{{ $address->state ?? '' }}" required>
                                </div>

                                <!-- Zipcode -->
                                <div class="mb-3">
                                    <label for="zipcode" class="form-label">Zipcode:</label>
                                    <input type="text" id="zipcode" name="address[zip]" class="form-control"
                                           value="{{ $address->zip ?? '' }}" required>
                                </div>

                                <!-- Company Website Link -->
                                <div class="mb-3">
                                    <label for="company_website" class="form-label">Company Website:</label>
                                    <input type="text" id="company_website" name="companyProfile[company_website]" class="form-control"
                                           value="{{ $companyProfile->company_website ?? '' }}" required>
                                </div>

                                <!-- Company Description -->
                                <div class="mb-3">
                                    <label for="company_description" class="form-label">Company Description:</label>
                                    <textarea id="company_description" name="companyProfile[company_description]" class="form-control"
                                              required>{{ $companyProfile->company_description ?? '' }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="formFile">File upload</label>
                                    <input class="form-control" name="photo" type="file" id="image">
                                </div>

                                <div class="mb-3">
                                    <img id="showImage" class="wd-80 rounded-circle"
                                         src=" {{ !empty($data->photo) ? url('upload/company_images/'.$data->photo): url('upload/no_image.jpg')}}"
                                         alt="profile">
                                </div>

                                <button type="submit" class="btn btn-primary me-2">Save Changes</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- middle wrapper end -->
        </div>
    </div>
    <!-- partial:../../partials/_footer.html -->
    <script>
        $(document).ready(function () {
            $('#image').change(function (e) {
                let reader = new FileReader();

                reader.onload = function (e) {
                    $('#showImage').attr('src', e.target.result);
                }

                reader.readAsDataURL(e.target.files['0']);
            });
        });
    </script>
@endsection
