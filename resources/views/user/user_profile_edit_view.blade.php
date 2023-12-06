@extends('user.user_dashboard')

@section('user')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- partial -->

    <div class="page-content">
        <div class="row profile-body">
            <!-- left wrapper start -->
            <div class="d-none d-md-block col-md-4 col-xl-4 left-wrapper">
                <div class="card rounded">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <img class="wd-100 rounded-circle"
                                     src="{{ !empty($data->photo) ? url('upload/user_images/'.$data->photo) : url('upload/no_image.jpg')}}"
                                     alt="profile">
                                <span class="h4 ms-3">{{ $data->username }}</span>
                            </div>

                            <div class="dropdown">
                                <a type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
                                   aria-expanded="false">
                                    <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item d-flex align-items-center"
                                       href="{{ route('user.profile.edit') }}"><i
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
            <!-- middle wrapper start -->
            <div class="col-md-8 col-xl-8 middle-wrapper">
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Update Admin Profile</h6>

                            <form method="post" action="{{ route('user.profile.store') }}" class="forms-sample"
                                  enctype="multipart/form-data">
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

                                <div class="mb-3">
                                    <label class="form-label" for="formFile">File upload</label>
                                    <input class="form-control" name="photo" type="file" id="image">
                                </div>

                                <div class="mb-3">
                                    <img id="showImage" class="wd-80 rounded-circle"
                                         src="{{ !empty($data->photo) ? url('upload/user_images/'.$data->photo) : url('upload/no_image.jpg')}}"
                                         alt="profile">
                                </div>

                                <!-- Portfolio -->
                                <div class="mb-3">
                                    <h5>Portfolio</h5>

                                    @if($portfolios->count() > 0)
                                        @foreach($portfolios as $portfolio)
                                            <div class="mb-3">
                                                <label for="job_title" class="form-label">Job Title</label>
                                                <input type="text" class="form-control"
                                                       id="job_title"
                                                       name="portfolios[{{ $portfolio->id }}][id]"
                                                       value="{{ $portfolio->id }}" hidden>
                                                <input type="text" class="form-control"
                                                       id="job_title"
                                                       name="portfolios[{{ $portfolio->id }}][job_title]"
                                                       value="{{ $portfolio->job_title ?? '' }}">
                                            </div>

                                            <div class="mb-3">
                                                <label for="company" class="form-label">Company</label>
                                                <input type="text" class="form-control"
                                                       id="company"
                                                       name="portfolios[{{ $portfolio->id }}][company]"
                                                       value="{{ $portfolio->company ?? '' }}">
                                            </div>

                                            <div class="mb-3">
                                                <label for="start_date" class="form-label">Start Date</label>
                                                <input type="date" class="form-control"
                                                       id="start_date"
                                                       name="portfolios[{{ $portfolio->id }}][start_date]"
                                                       value="{{ $portfolio->start_date ?? '' }}">
                                            </div>

                                            <div class="mb-3">
                                                <label for="end_date" class="form-label">End Date</label>
                                                <input type="date" class="form-control"
                                                       id="end_date"
                                                       name="portfolios[{{ $portfolio->id }}][end_date]"
                                                       value="{{ $portfolio->end_date ?? '' }}">
                                            </div>

                                            <!-- Portfolio-Details -->
                                            <!-- partial -->
                                            <div class="mb-3">
                                                <label for="responsibility" class="form-label">Responsibility</label>
                                                <div class="responsibility-container">
                                                    @foreach($portfolio->details->where('type', 'responsibility') as $detail)
                                                        <input type="text" class="form-control"
                                                               id="responsibility"
                                                               name="portfolios[{{ $portfolio->id }}][details][{{ $detail->type }}][]"
                                                               value="{{ $detail->content }}">
                                                    @endforeach
                                                </div>
                                                <button type="button"
                                                        class="btn btn-secondary btn-sm add-responsibility">Add More
                                                </button>
                                            </div>

                                            <div class="mb-3">
                                                <label for="achievement" class="form-label">Achievement</label>
                                                <div class="achievement-container">
                                                    @foreach($portfolio->details->where('type', 'achievement') as $detail)
                                                        <input type="text" class="form-control"
                                                               id="achievement"
                                                               name="portfolios[{{ $portfolio->id }}][details][achievement][]"
                                                               value="{{ $detail->content }}">
                                                    @endforeach
                                                </div>
                                                <button type="button" class="btn btn-secondary btn-sm add-achievement">
                                                    Add More
                                                </button>
                                            </div>

                                            <div class="mb-3">
                                                <label for="skill" class="form-label">Skills</label>
                                                <div class="skill-container">
                                                    @foreach($portfolio->details->where('type', 'skill') as $detail)
                                                        <input type="text" class="form-control"
                                                               id="skill"
                                                               name="portfolios[{{ $portfolio->id }}][details][skill][]"
                                                               value="{{ $detail->content }}">
                                                    @endforeach
                                                </div>
                                                <button type="button" class="btn btn-secondary btn-sm add-technology">
                                                    Add More
                                                </button>
                                            </div>
                                        @endforeach
                                    @else
                                        <p>Noch kein Portfolio vorhanden.</p>
                                    @endif
                                </div>
                                <!-- ... (andere Portfolio-Felder) ... -->
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

        $(document).ready(function () {
            $('.add-responsibility').click(function () {
                let input = '<input type="text" class="form-control" name="portfolios[' + portfolio_Id + '][details][responsibility][]" value="">';
                $('.responsibility-container').append(input);
            });

            $('.add-achievement').click(function () {
                let input = '<input type="text" class="form-control" name="portfolios[' + portfolio_Id + '][details][achievement][]" value="">';
                $('.achievement-container').append(input);
            });

            $('.add-technology').click(function () {
                let input = '<input type="text" class="form-control" name="portfolios[' + portfolio_Id + '][details][skill][]" value="">';
                $('.skill-container').append(input);
            });
        });

    </script>
@endsection
