@extends('user.user_dashboard')

@section('user')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="auth-form-wrapper px-4 py-5">
                                <h2>Create Portfolio</h2>
                                <br>

                                <!-- Profile Image and Username -->
                                <div class="mb-5 d-flex align-items-center">
                                    <img class="wd-100 rounded-circle"
                                         src="{{ !empty($data->photo) ? url('upload/user_images/'.$data->photo) : url('upload/no_image.jpg')}}"
                                         alt="profile">
                                    <span class="h4 ms-3">{{ $data->username }}</span>
                                </div>

                                <!-- Portfolio Form -->
                                <form method="post" action="{{ route('user.portfolio.store') }}"
                                      class="forms-sample" enctype="multipart/form-data" id="portfolioForm">
                                    @csrf

                                    <!-- Street -->
                                    <div class="mb-3">
                                        <label for="street" class="form-label">Street:</label>
                                        <input type="text" id="street" name="portfolios[street]" class="form-control"
                                               value="{{ $portfolios->street ?? '' }}" required>
                                    </div>

                                    <!-- Street Number -->
                                    <div class="mb-3">
                                        <label for="street_number" class="form-label">Street Number:</label>
                                        <input type="text" id="street_number" name="portfolios[street_number]"
                                               class="form-control" value="{{ $portfolios->street_number ?? '' }}"
                                               required>
                                    </div>

                                    <!-- City -->
                                    <div class="mb-3">
                                        <label for="city" class="form-label">City:</label>
                                        <input type="text" id="city" name="portfolios[city]" class="form-control"
                                               value="{{ $portfolios->city ?? '' }}" required>
                                    </div>

                                    <!-- State -->
                                    <div class="mb-3">
                                        <label for="state" class="form-label">State:</label>
                                        <input type="text" id="state" name="portfolios[state]" class="form-control"
                                               value="{{ $portfolios->state ?? '' }}" required>
                                    </div>

                                    <!-- Zipcode -->
                                    <div class="mb-3">
                                        <label for="zipcode" class="form-label">Zipcode:</label>
                                        <input type="text" id="zipcode" name="portfolios[zipcode]" class="form-control"
                                               value="{{ $portfolios->zipcode ?? '' }}" required>
                                    </div>

                                    <!-- Job Title -->
                                    <div class="mb-3">
                                        <label for="job_title" class="form-label">Job Title:</label>
                                        <input type="text" id="job_title" name="portfolios[job_title]"
                                               class="form-control" value="{{ $portfolios->job_title ?? '' }}" required>
                                    </div>

                                    <!-- Company -->
                                    <div class="mb-3">
                                        <label for="company" class="form-label">Company:</label>
                                        <input type="text" id="company" name="portfolios[company]"
                                               class="form-control" value="{{ $portfolios->company ?? '' }}" required>
                                    </div>

                                    <!-- Start Date -->
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label">Start Date:</label>
                                        <input type="date" id="start_date" name="portfolios[start_date]"
                                               class="form-control" value="{{ $portfolios->start_date ?? '' }}"
                                               required>
                                    </div>

                                    <!-- End Date -->
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label">End Date:</label>
                                        <input type="date" id="end_date" name="portfolios[end_date]"
                                               class="form-control" value="{{ $portfolios->end_date ?? '' }}" required>
                                    </div>

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

                                    <!-- Add More Details Button -->
                                    <button type="button" class="btn btn-outline-primary btn-icon-text mb-3"
                                            onclick="addMoreDetails()">Add More Details
                                    </button>

                                    <!-- File Upload for Profil Image -->
                                    <div class="mb-3">
                                        <label class="form-label" for="formFile">File upload for Profile Image</label>
                                        <input class="form-control" name="photo" type="file" id="image">
                                    </div>


                                    <!-- File Upload for Images -->
                                    <div class="mb-3">
                                        <label class="form-label" for="image">Additional Images:</label>
                                        <input class="form-control" name="images[]" type="file" id="image" multiple>
                                    </div>

                                    <!-- File Upload for Files -->
                                    <div class="mb-3">
                                        <label class="form-label" for="file">Additional Files:</label>
                                        <input class="form-control" name="files[]" type="file" id="file" multiple>
                                    </div>

                                    <!-- Show Image -->
                                    <!-- File Upload for Images -->
                                    @if($portfolios && $portfolios->media()->where('type', 'image')->exists())
                                        <div class="mb-3">
                                            <label class="form-label">Additional Images:</label>
                                            <br>
                                            @foreach($portfolios->media()->where('type', 'image')->get() as $image)
                                                <img src="{{ url('upload/portfolio_images/'.$image->filename) }}"
                                                     alt="portfolio-image" class="img-fluid"
                                                     width="400" height="400">
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Display Additional Files -->
                                    @if($portfolios && $portfolios->media()->where('type', 'file')->exists())
                                        <div class="mb-3">
                                            <label class="form-label">Additional Files:</label>
                                            @foreach($portfolios->media()->where('type', 'file')->get() as $file)
                                                <a href="{{ url('upload/portfolio_files/'.$file->filename) }}"
                                                   target="_blank">{{ $file->filename }}</a>
                                            @endforeach
                                        </div>
                                    @endif
                                    <!-- Submit Button -->
                                    <button type="submit" id="submitBtn"
                                            class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                                        Save Portfolio
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        let detailIndex = {{ $portfolios && $portfolios->details ? count($portfolios->details) : 0 }};
        $(document).ready(function () {
            $('#image').change(function (e) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#showImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });

        function addMoreDetails() {
            let detailsContainer = document.getElementById('detailsContainer');

            // Überprüfen, ob das Detail-Container-Element existiert. Wenn nicht, initialisieren Sie es.
            if (!detailsContainer) {
                detailsContainer = document.createElement('div');
                detailsContainer.id = 'detailsContainer';
                // Fügen Sie das Container-Element direkt vor dem Submit-Button ein
                document.querySelector('#portfolioForm > button').before(detailsContainer);
            }

            let detailSet = document.createElement('div');
            detailSet.classList.add('detailSet', 'mb-3');
            let detailLabel = document.createElement('label');
            detailLabel.classList.add('form-label');
            detailLabel.innerHTML = 'Titel der Content:';
            let detailInput = document.createElement('input');
            detailInput.type = 'text';
            detailInput.classList.add('form-control');
            detailInput.name = 'portfolios[details][' + detailIndex + '][type]';
            detailInput.required = true;
            let detailContentLabel = document.createElement('label');
            detailContentLabel.classList.add('form-label', 'mb-2');
            detailContentLabel.innerHTML = 'Content';
            let detailContent = document.createElement('textarea');
            detailContent.classList.add('form-control');
            detailContent.name = 'portfolios[details][' + detailIndex + '][content]';
            detailContent.required = true;
            detailSet.appendChild(detailLabel);
            detailSet.appendChild(detailInput);
            detailSet.appendChild(detailContentLabel);
            detailSet.appendChild(detailContent);
            detailsContainer.appendChild(detailSet);

            detailIndex++;
        }



    </script>

@endsection
