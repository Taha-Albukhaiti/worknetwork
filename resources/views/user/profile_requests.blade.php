@extends('user.user_dashboard')

@section('user')
    <div class="page-content">
        <div class="row profile-body">
            <div class="col-md-12">
                <div class="card rounded">
                    <div class="card-body">
                        <h2 class="card-title mb-4">Profile Requests</h2>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Unternehmen Name</th>
                                    <th>Website</th>
                                    <th>Email</th>
                                    <th>Telefon</th>
                                    <th>Adresse</th>
                                    <th>Aktionen</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($profileRequests as $request)
                                    <tr>
                                        <td>{{ $request->requestedUser->name }}</td>
                                        <td>{{ $request->requestedUser->companyProfile->company_website }}</td>
                                        <td>{{ $request->requestedUser->email }}</td>
                                        <td>{{ $request->requestedUser->phone }}</td>
                                        <td>{{ $request->requestedUser->address->full_address }}</td>
                                        <td>
                                            <a href="{{ route('user.company.profile.show', $request->requestedUser->id) }}" class="btn btn-outline-primary">Zum Profil</a>
                                            <form action="{{ route('user.accept_profile_request', $request->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success">Anfrage akzeptieren</button>
                                            </form>
                                            <form action="{{ route('user.reject_profile_request', $request->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger">Anfrage ablehnen</button>
                                            </form>
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
@endsection
