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
                                         src="{{ !empty($user->photo) ? url('upload/user_images/'.$user->photo) : url('upload/no_image.jpg')}}"
                                         alt="profile">
                                    <span class="h4 ms-3">{{ $user->username }}</span>
                                </div>

                                <!-- Portfolio Form -->
                                <form method="post" action="{{ route('user.portfolio.store') }}"
                                      class="forms-sample" enctype="multipart/form-data" id="portfolioForm">
                                    @csrf

                                    <!-- User ID -->
                                    <input type="hidden" name="portfolios[user_id]" value="{{ $user->id }}">

                                    <!-- Username -->
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username:</label>
                                        <input type="text" id="username" name="username" class="form-control"
                                               value="{{ $user->username ?? '' }}" required>
                                    </div>

                                    <!-- Phone -->
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone:</label>
                                        <input type="text" id="phone" name="phone" class="form-control"
                                               value="{{ $user->phone ?? '' }}" required>
                                    </div>

                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email:</label>
                                        <input type="email" id="email" name="email" class="form-control"
                                               value="{{ $user->email ?? '' }}" required>
                                    </div>

                                    <!-- Address Section unabhängig vom Portfolio objekt-->
                                    <label class="form-label"><h4>Address:</h4></label>
                                    <!-- Address ID -->
                                    <input type="hidden" name="address[id]"
                                           value="{{ $address->id ?? '' }}">

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
                                        <label for="zip" class="form-label">Zipcode:</label>
                                        <input type="text" id="zip" name="address[zip]" class="form-control"
                                               value="{{ $address->zip ?? '' }}" required>
                                    </div>

                                    <!-- Portfolio ID -->
                                    <input type="hidden" name="portfolios[id]" value="{{ $portfolios->id ?? '' }}">

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
                                                    <br>
                                                    <h5><label class="form-label
                                                    ">Titel der Content: {{ $detail->type }}</label></h5>

                                                    <input type="hidden" name="portfolios[details][{{ $index }}][id]"
                                                           value="{{ $detail->id }}">

                                                    <input type="text"
                                                           name="portfolios[details][{{ $index }}][type]"
                                                           value="{{ $detail->type }}" class="form-control">

                                                    <h5><label class="form-label mb-2 mt-2">Content</label></h5>

                                                    <textarea name="portfolios[details][{{ $index }}][content]"
                                                              class="form-control"
                                                              required>{{ $detail->content }}</textarea>

                                                    <div class="detailSet mb-3">
                                                        <button type="button"
                                                                class="btn btn-outline-danger btn-sm delete-detail-btn mt-2"
                                                                data-detail-id="{{ $detail->id }}">Delete
                                                        </button>

                                                    </div>
                                                    <hr>
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
                                                     width="200" height="200">
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


        $(document).on('click', '.delete-detail-btn', function () {
            let detailId = $(this).data('detail-id');
            if (confirm('Are you sure to delete this detail?')) {
                $.ajax({
                    url: '{{ route('user.detail.delete', ['id' => '__detail_id__']) }}'.replace('__detail_id__', detailId), // Use route() helper function to generate the URL
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            $(this).closest('.detailSet').remove();
                            location.reload();
                        }
                    }


                });
            }
        });


        // Zeige die Dateien oder additonalen Bilder an
        $(document).on('change', '#image', function (e) {
            let reader = new FileReader();
            reader.onload = function (e) {
                $('#showImage').attr('src', e.target.result);
            }
            reader.readAsDataURL(e.target.files['0']);
        });

    </script>

@endsection
