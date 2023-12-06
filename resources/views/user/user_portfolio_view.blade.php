@extends('user.user_dashboard')

@section('user')

    <div class="page-content">
        <div class="container-fluid">

            <!-- Your Header Code here -->

            <div class="row w-100 mx-0 auth-page">
                <div class="col-md-8 col-xl-6 mx-auto">
                    <div class="card">
                        <div class="row">
                            <div class="col-md-4 pe-md-0">
                                <div class="authLogin-side-wrapper">
                                </div>
                            </div>
                            <div class="col-md-8 ps-md-0">
                                <div class="auth-form-wrapper px-4 py-5">
                                    <h2>Create Portfolio</h2>
                                    <form method="post" action="{{ route('user.portfolio.store') }}"
                                          class="forms-sample"
                                          enctype="multipart/form-data" id="portfolioForm">
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

                                        <!-- description -->
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description:</label>
                                            <div class="descriptionContainer">
                                                <input id="description" type="text" name="portfolios[details][][type]" value="description" hidden>
                                                <input    type="text" name="portfolios[details][][content]" class="form-control" required>
                                            </div>
                                            <button type="button" class="btn btn-secondary" onclick="addDetail('descriptionContainer', 'description')">Add Description</button>
                                        </div>

                                        <!-- Responsibility -->
                                        <div class="mb-3">
                                            <label for="responsibility" class="form-label">Responsibility:</label>
                                            <div class="responsibilityContainer">
                                                <input id="responsibility" type="text" name="portfolios[details][][type]" value="responsibility" hidden>
                                                <input  type="text" name="portfolios[details][][content]" class="form-control" required>
                                            </div>
                                            <button type="button" class="btn btn-secondary" onclick="addDetail('responsibilityContainer', 'responsibility')">Add Responsibility</button>
                                        </div>

                                        <!-- Achievement -->
                                        <div class="mb-3">
                                            <label for="achievement" class="form-label">Achievement:</label>
                                            <div class="achievementContainer">
                                                <input id="achievement" type="text" name="portfolios[details][][type]" value="achievement" hidden>
                                                <input type="text" name="portfolios[details][][content]" class="form-control" required>
                                            </div>
                                            <button type="button" class="btn btn-secondary" onclick="addDetail('achievementContainer', 'achievement')">Add Achievement</button>
                                        </div>

                                        <!-- Skill -->
                                        <div class="mb-3">
                                            <label for="skill" class="form-label">Skill:</label>
                                            <div class="skillContainer">
                                                <input id="skill" type="text" name="portfolios[details][][type]" value="skill" hidden>
                                                <input type="text" name="portfolios[details][][content]" class="form-control" required>
                                            </div>
                                            <button type="button" class="btn btn-secondary" onclick="addDetail('skillContainer', 'skill')">Add Skill</button>
                                        </div>


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
    </div>

    <script>
        function addDetail(containerId, detailType) {
            var container = document.getElementById(containerId);
            var inputType = document.createElement("input");
            inputType.type = "text";
            inputType.name = "portfolios[details][][type]";
            inputType.value = detailType;
            inputType.hidden = true;
            container.appendChild(inputType);

            var inputContent = document.createElement("input");
            inputContent.type = "text";
            inputContent.name = "portfolios[details][][content]";
            inputContent.classList.add("form-control");
            inputContent.required = true;
            container.appendChild(inputContent);

            console.log("Added detail field for " + detailType);
        }
    </script>


@endsection
