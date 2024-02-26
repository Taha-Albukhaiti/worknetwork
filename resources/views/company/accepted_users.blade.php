@extends('company.company_dashboard')
@section('company')
    <!-- Tabelle über alle users, die die Anfrage aktzeptiert haben-->

    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title mb-4">Users Information</h2>

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
                                    <tbody>
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

                                                <a href="{{ route('company.user.accepted.show', $user->id) }}"
                                                   class="btn btn-outline-primary">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Funktion zum Ausführen der Suche beim Drücken der Eingabetaste
        document.getElementById("searchUserInput").addEventListener("keypress", function (e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Verhindert das Standardverhalten des Formulars
                document.getElementById("searchUserForm").submit(); // Sendet das Suchformular
            }
        });

        document.getElementById("searchCompanyInput").addEventListener("keypress", function (e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Verhindert das Standardverhalten des Formulars
                document.getElementById("searchCompanyForm").submit(); // Sendet das Suchformular
            }
        });
    </script>

@endsection