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
                    <!-- Portfolio -->
                    <div class="mb-3">
                        <h5>Portfolio</h5>

                        @if($portfolios)
                            <input type="text" name="portfolios[id]" value="{{ $portfolios->id }}" hidden>
                            <div class="mb-3">
                                <label for="job_title" class="form-label">Job Title</label>
                                <input type="text" class="form-control" id="job_title" name="portfolios[job_title]" value="{{ $portfolios->job_title ?? '' }}">
                            </div>

                            <div class="mb-3">
                                <label for="company" class="form-label">Company</label>
                                <input type="text" class="form-control" id="company" name="portfolios[company]" value="{{ $portfolios->company ?? '' }}">
                            </div>

                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="portfolios[start_date]" value="{{ $portfolios->start_date ?? '' }}">
                            </div>

                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="portfolios[end_date]" value="{{ $portfolios->end_date ?? '' }}">
                            </div>

                            <!-- Portfolio-Details -->
                            <!-- Portfolio-Details -->
                            @foreach($portfolios->details as $index => $detail)
                                <div class="mb-3">
                                    <label for="type_{{ $index }}" class="form-label">Type</label>
                                    <input type="text" class="form-control" id="type_{{ $index }}" name="portfolios[details][{{ $index }}][type]" value="{{ $detail->type ?? '' }}">
                                </div>
                                <div class="mb-3">
                                    <label for="content_{{ $index }}" class="form-label">Content</label>
                                    <input type="text" class="form-control" id="content_{{ $index }}" name="portfolios[details][{{ $index }}][content]" value="{{ $detail->content ?? '' }}">
                                </div>
                            @endforeach


                        @else
                            <p>Noch kein Portfolio vorhanden.</p>
                        @endif

                        <button type="submit" class="btn btn-primary me-2">Save Changes</button>
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>