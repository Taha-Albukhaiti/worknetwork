<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title mb-4">Users Information</h2>
                    <form id="searchUserForm" action="{{ route('admin.search.user') }}" method="GET">
                        @csrf
                        <div class="input-group mb-3">
                            <input id="searchUserInput" name="searchUser" type="text"
                                   class="form-control bg-transparent border-primary flatpickr-input"

                                   placeholder="Search..." value="{{ request()->input('searchUser') }}">
                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="userTableBody">
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        <img src="{{ !empty($user->photo) ? url('upload/user_images/'.$user->photo) : url('upload/no_image.jpg')}}"
                                             alt="profile" class="img-thumbnail"
                                             style="max-width: 50px; max-height: 50px;">
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>
                                        <a href="{{ route('admin.user.show', $user->id) }}"
                                           class="btn btn-outline-primary">View</a>
                                        <hr>
                                        <form action="{{ route('admin.user.delete', $user->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <div class="text-center">
                        <button id="loadMoreUsersBtn" class="btn btn-primary">Load More</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<br>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title mb-4">Company Information</h2>
                    <form id="searchCompanyForm" action="{{ route('admin.search.company') }}" method="GET">
                        @csrf
                        <div class="input-group mb-3">
                            <input id="searchCompanyInput" name="searchCompany" type="text"
                                   class="form-control bg-transparent border-primary flatpickr-input"
                                   placeholder="Search name or username..." value="{{ request()->input('searchCompany') }}">
                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-hover" id="companyTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="companyTableBody">
                            @foreach($companies as $company)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        <img src="{{ !empty($company->photo) ? url('upload/company_images/'.$company->photo) : url('upload/no_image.jpg')}}"
                                             alt="profile" class="img-thumbnail"
                                             style="max-width: 50px; max-height: 50px;">
                                    </td>
                                    <td>{{ $company->name }}</td>
                                    <td>{{ $company->username }}</td>
                                    <td>{{ $company->email }}</td>
                                    <td>{{ $company->phone }}</td>
                                    <td>{{ $company->role }}</td>
                                    <td>
                                        <a href="{{ route('admin.company.show', $company->id) }}"
                                           class="btn btn-outline-primary">View</a>
                                        <hr>
                                        <form action="{{ route('admin.company.delete', $company->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <div class="text-center">
                        <button id="loadMoreCompaniesBtn" class="btn btn-primary">Load More</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var userLoading = false;
    var userPage = 1;

    $(document).ready(function () {
        $("#loadMoreUsersBtn").on('click', function () {
            if (!userLoading) {
                userLoading = true;
                userPage++;
                loadMoreUsers(userPage);
            }
        });
    });

    function loadMoreUsers(page) {
        var skip = (page - 1) * 4;
        $.ajax({
            url: "{{ route('admin.load.more.users') }}",
            type: "GET",
            data: {skip: skip}, // Übergabe des Parameters skip
            success: function (response) {
                if (response.users.length > 0) {
                    appendUsersToTable(response.users);
                }
                userLoading = false;
            },
            error: function (xhr) {
                console.log(xhr.responseText);
            }
        });
    }

    function appendUsersToTable(users) {
        var tableBody = $("#userTableBody");
        users.forEach(function (user) {
            var row = `
        <tr>
            <td>${user.id}</td>
            <td><img src="{{ url('upload/user_images/') }}/${user.photo}" alt="profile" class="img-thumbnail" style="max-width: 50px; max-height: 50px;"></td>
            <td>${user.name}</td>
            <td>${user.username}</td>
            <td>${user.email}</td>
            <td>${user.phone}</td>
            <td>${user.role}</td>
            <td>
                <a href="{{ route('admin.user.show', '') }}/${user.id}" class="btn btn-outline-primary">View</a> <hr>
                <form action="{{ route('admin.user.delete', '') }}/${user.id}" method="POST">
                    @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">Delete</button>
        </form>
    </td>
    </tr>`;
            tableBody.append(row);
        });
    }

    var companyLoading = false;
    var companyPage = 1;

    $(document).ready(function () {
        $("#loadMoreCompaniesBtn").on('click', function () {
            if (!companyLoading) {
                companyLoading = true;
                companyPage++;
                loadMoreCompanies(companyPage);
            }
        });
    });

    function loadMoreCompanies(page) {
        var skip = (page - 1) * 4;
        $.ajax({
            url: "{{ route('admin.load.more.companies') }}",
            type: "GET",
            data: {skip: skip}, // Übergabe des Parameters skip
            success: function (response) {
                if (response.companies.length > 0) {
                    appendCompaniesToTable(response.companies);
                }
                companyLoading = false;
            },
            error: function (xhr) {
                console.log(xhr.responseText);
            }
        });
    }

    function appendCompaniesToTable(companies) {
        var tableBody = $("#companyTableBody");
        companies.forEach(function (company) {
            var row = `
        <tr>
            <td>${company.id}</td>
            <td><img src="{{ url('upload/company_images/') }}/${company.photo}" alt="profile" class="img-thumbnail" style="max-width: 50px; max-height: 50px;"></td>
            <td>${company.name}</td>
            <td>${company.username}</td>
            <td>${company.email}</td>
            <td>${company.phone}</td>
            <td>${company.role}</td>
            <td>
                <a href="{{ route('admin.company.show', '') }}/${company.id}" class="btn btn-outline-primary">View</a> <hr>
                <form action="{{ route('admin.company.delete', '') }}/${company.id}" method="POST">
                    @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">Delete</button>
        </form>
    </td>
</tr>`;
            tableBody.append(row);
        });
    }



</script>

