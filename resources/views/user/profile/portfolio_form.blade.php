<div class="col-md-8 col-xl-8 middle-wrapper">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">User Profile</h6>

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

                        @if($portfolios)
                            <input type="text" name="portfolios[id]" value="{{ $portfolios->id }}" hidden>

                        <!-- Portfolio-Address -->
                            <div class="mb-3">
                                <label for="street" class="form-label">Street</label>
                                <input type="text" class="form-control" id="street" name="portfolios[street]" value="{{ $portfolios->street ?? '' }}">
                            </div>

                            <div class="mb-3">
                                <label for="street_number" class="form-label">Street Number</label>
                                <input type="text" class="form-control" id="street_number" name="portfolios[street_number]" value="{{ $portfolios->street_number ?? '' }}">
                            </div>

                            <div class="mb-3">
                                <label for="zipcode" class="form-label">Zipcode</label>
                                <input type="text" class="form-control" id="zipcode" name="portfolios[zipcode]" value="{{ $portfolios->zipcode ?? '' }}">
                            </div>

                            <div class="mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" id="city" name="portfolios[city]" value="{{ $portfolios->city ?? '' }}">
                            </div>

                            <div class="mb-3">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control" id="state" name="portfolios[state]" value="{{ $portfolios->state ?? '' }}">

                            </div>
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
                            <!-- Details Section -->
                            @if($portfolios && isset($portfolios->details) && count($portfolios->details) > 0)
                                <!-- Display Details Section -->
                                <div class="mb-3" id="detailsContainer">
                                    <label class="form-label"><h4>Details:</h4></label>
                                    @foreach($portfolios->details as $index => $detail)
                                        <div class="detailSet mb-3">
                                            <label class="form-label">Titel der Content: {{ $detail->type }}</label>
                                            <input type="hidden" name="portfolios[details][{{ $index }}][id]"
                                                   value="{{ $detail->id }}">
                                            <input type="text"
                                                   name="portfolios[details][{{ $index }}][type]"
                                                   value="{{ $detail->type }}" class="form-control">
                                            <label class="form-label mb-2">Content</label>
                                            <textarea name="portfolios[details][{{ $index }}][content]"
                                                      class="form-control"
                                                      required>{{ $detail->content }}</textarea>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

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