<!-- cards.blade.php -->
<!-- User Card section -->
<div class="container mt-4">
    <div class="row card-row" id="userCardRow">
        <div class="col-md-12">
            <h5 class="mb-4">Users</h5>
        </div>
        <!-- Search Form -->
        <div class="col-md-12">
            <form id="searchUserForm" action="{{ route('search.user') }}" method="GET">
                @csrf
                <div class="input-group mb-3">
                    <input id="searchUserInput" name="search" type="text"
                           class="form-control bg-transparent border-primary flatpickr-input"
                           placeholder="Suche nach Name, username, Job Titel, Company, email, phone und Stadt">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
            </form>
        </div>
        <!-- User Cards -->
        @foreach($users as $user)
            <div class="col-md-3 mb-4 user-card">
                <div class="card h-100 d-flex flex-column justify-content-between">
                    <div class="ratio ratio-1x1">
                        <img src="{{ !empty($user->photo) ? url('upload/user_images/'.$user->photo) : url('upload/no_image.jpg')}}"
                             class="card-img-top img-fluid" alt="{{ $user->name }}">
                    </div>
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">{{ $user->name }}</h6>
                        <p class="card-text">{{ $user->email }}</p>
                        <p class="card-text">{{ $user->portfolios->job_title ?? '' }}</p>
                    </div>
                    <div class="card-footer text-center">
                        <p class="mb-0 text-muted">Mehr über diesen Benutzer erfahren:</p>
                        <a href="{{ route('user.profile.request', $user->id) }}"
                           class="btn btn-outline-primary btn-sm rounded-0  mt-2">Profilanfrage senden</a>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-md-12 mt-4">
            <div class="d-flex justify-content-center">
                <a href="#" class="btn btn-outline-primary me-2" id="userPrevButton">Previous</a>
                <a href="#" class="btn btn-outline-primary" id="userNextButton">Next</a>
            </div>
        </div>
    </div>
</div>
<!-- End of User Card section -->

<!-- Company Card section -->
<div class="container mt-4">
    <div class="row card-row" id="companyCardRow">
        <div class="col-md-12">
            <h5 class="mb-4">Companies</h5>
        </div>
        <!-- Search Form -->
        <div class="col-md-12">
            <form id="searchCompanyForm" action="{{ route('search.company') }}" method="GET">
                @csrf
                <div class="input-group mb-3">
                    <input id="searchCompanyInput" name="search" type="text"
                           class="form-control bg-transparent border-primary flatpickr-input"
                           placeholder="Search Name...">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
            </form>
        </div>
        <!-- Company Cards $companiesProfile -->
        @foreach($companiesProfile as $company)
            <div class="col-md-3 mb-4 company-card">
                <div class="card h-100 d-flex flex-column justify-content-between">
                    <div class="ratio ratio-1x1">
                        <!-- Image Bereich -->
                        <img src="{{ !empty($company->photo) ? url('upload/company_images/'.$company->photo) : url('upload/no_image.jpg')}}"
                             class="card-img-top img-fluid" alt="{{ $company->name }}">
                    </div>
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">{{ $company->name }}</h6>
                        <p class="card-text">{{ $company->email }}</p>
                        <p class="card-text">{{ $company->companyProfile->company_website ?? '' }}</p>
                    </div>
                    <!-- gib die Company_profile falls es gibt-->
                    <div class="card-footer text-center">
                        <a href="{{ route('company.profile.view', $company->id) }}"
                           class="btn btn-outline-primary btn-sm rounded-0">View Profile</a>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-md-12 mt-4">
            <div class="d-flex justify-content-center">
                <a href="#" class="btn btn-outline-primary me-2" id="companyPrevButton">Previous</a>
                <a href="#" class="btn btn-outline-primary" id="companyNextButton">Next</a>
            </div>
        </div>
    </div>
</div>
<!-- End of Company Card section -->

<style>
    .card-row {
        background-color: #0c1427;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
    }

    .btn-outline-secondary:hover {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const userPrevButton = document.querySelector("#userPrevButton");
        const userNextButton = document.querySelector("#userNextButton");
        const companyPrevButton = document.querySelector("#companyPrevButton");
        const companyNextButton = document.querySelector("#companyNextButton");

        let userCurrentPage = 1;
        const userTotalPages = Math.ceil({{ $users->count() }} / 4);
        let companyCurrentPage = 1;
        const companyTotalPages = Math.ceil({{ $companiesProfile->count() }} / 4);

        userPrevButton.addEventListener("click", function () {
            if (userCurrentPage > 1) {
                userCurrentPage--;
                updateUserPageVisibility();
            }
        });

        userNextButton.addEventListener("click", function () {
            if (userCurrentPage < userTotalPages) {
                userCurrentPage++;
                updateUserPageVisibility();
            }
        });

        companyPrevButton.addEventListener("click", function () {
            if (companyCurrentPage > 1) {
                companyCurrentPage--;
                updateCompanyPageVisibility();
            }
        });

        companyNextButton.addEventListener("click", function () {
            if (companyCurrentPage < companyTotalPages) {
                companyCurrentPage++;
                updateCompanyPageVisibility();
            }
        });

        function updateUserPageVisibility() {
            const userCards = document.querySelectorAll(".user-card");

            userCards.forEach(function (card, index) {
                const start = (userCurrentPage - 1) * 4;
                const end = start + 4;

                if (index >= start && index < end) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            });
        }

        function updateCompanyPageVisibility() {
            const companyCards = document.querySelectorAll(".company-card");

            companyCards.forEach(function (card, index) {
                const start = (companyCurrentPage - 1) * 4;
                const end = start + 4;

                if (index >= start && index < end) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            });
        }

        updateUserPageVisibility();
        updateCompanyPageVisibility();
    });
</script>
