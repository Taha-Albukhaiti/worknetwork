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

                                    <!-- Job Title -->
                                    <div class="mb-3">
                                        <label for="job_title" class="form-label">Job Title:</label>
                                        <input type="text" id="job_title" name="portfolios[job_title]"
                                               class="form-control" required>
                                    </div>

                                    <!-- Company -->
                                    <div class="mb-3">
                                        <label for="company" class="form-label">Company:</label>
                                        <input type="text" id="company" name="portfolios[company]"
                                               class="form-control" required>
                                    </div>

                                    <!-- Start Date -->
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label">Start Date:</label>
                                        <input type="date" id="start_date" name="portfolios[start_date]"
                                               class="form-control" required>
                                    </div>

                                    <!-- End Date -->
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label">End Date:</label>
                                        <input type="date" id="end_date" name="portfolios[end_date]"
                                               class="form-control" required>
                                    </div>

                                    <!-- Details Section -->
                                    <div class="mb-3">
                                        <label class="form-label"><h4>Details:</h4></label>
                                        <div id="detailsContainer">
                                            <!-- Initial Detail Sets -->
                                            <div class="detailSet mb-3">
                                                <label class="form-label">Description:</label>
                                                <input type="text" name="portfolios[details][0][type]"
                                                       value="description" hidden>
                                                <input type="text" name="portfolios[details][0][content]"
                                                       class="form-control" required>
                                            </div>

                                            <div class="detailSet mb-3">
                                                <label class="form-label">Responsibility:</label>
                                                <input type="text" name="portfolios[details][1][type]"
                                                       value="responsibility" hidden>
                                                <input type="text" name="portfolios[details][1][content]"
                                                       class="form-control" required>
                                            </div>

                                            <div class="detailSet mb-3">
                                                <label class="form-label">Achievement:</label>
                                                <input type="text" name="portfolios[details][2][type]"
                                                       value="achievement" hidden>
                                                <input type="text" name="portfolios[details][2][content]"
                                                       class="form-control" required>
                                            </div>

                                            <div class="detailSet mb-3">
                                                <label class="form-label">Skill:</label>
                                                <input type="text" name="portfolios[details][3][type]" value="skill"
                                                       hidden>
                                                <input type="text" name="portfolios[details][3][content]"
                                                       class="form-control" required>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-secondary" onclick="addMoreDetails()">
                                            Add More Details
                                        </button>
                                    </div>

                                    <!-- File Upload -->
                                    <div class="mb-3">
                                        <label class="form-label" for="formFile">File upload</label>
                                        <input class="form-control" name="photo" type="file" id="image">
                                    </div>

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

        var detailIndex = 4; // Starting index for new details
        function addMoreDetails() {
            var container = document.getElementById('detailsContainer');

            // Labels and types for each detail
            var detailInfo = [
                {label: 'Description', type: 'description'},
                {label: 'Responsibility', type: 'responsibility'},
                {label: 'Achievement', type: 'achievement'},
                {label: 'Skill', type: 'skill'}
            ];

            // Create new sets of input fields for each detail type
            detailInfo.forEach(function (info) {
                var detailSet = document.createElement('div');
                detailSet.classList.add('detailSet', 'mb-3');

                var label = document.createElement('label');
                label.classList.add('form-label');
                label.textContent = info.label;

                var inputType = document.createElement('input');
                inputType.type = 'text';
                inputType.name = 'portfolios[details][' + detailIndex + '][type]';
                inputType.value = info.type;
                inputType.hidden = true;

                var inputContent = document.createElement('input');
                inputContent.type = 'text';
                inputContent.name = 'portfolios[details][' + detailIndex + '][content]';
                inputContent.classList.add('form-control');
                inputContent.required = true;

                // Append label and input fields to the new detail set
                detailSet.appendChild(label);
                detailSet.appendChild(inputType);
                detailSet.appendChild(inputContent);

                // Append the new detail set to the details container
                container.appendChild(detailSet);

                // Increment detailIndex for the next set of details
                detailIndex++;
            });
        }


    </script>
@endsection
