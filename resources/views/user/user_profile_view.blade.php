@extends('user.user_dashboard')
@section('user')

    <!-- partial -->

    <div class="page-content">
        <div class="row profile-body justify-content-center align-items-center">
            <!-- left wrapper start -->
            @include('user.profile.profile_information')
            <!-- left wrapper end -->
        </div>

    </div>
    <!-- partial:../../partials/_footer.html -->
@endsection