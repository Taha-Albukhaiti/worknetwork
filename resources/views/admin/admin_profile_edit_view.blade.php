@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- partial -->

    <div class="page-content">
        <div class="row profile-body">
            <!-- left wrapper start -->
            <!-- left wrapper end -->
            <!-- middle wrapper start -->
            <div class="col-md-8 col-xl-8 middle-wrapper">
                <div class="row">
                    <div class="card">
                        <div class="card-body">

                            <h6 class="card-title">Update Admin Profile</h6>

                            <form method="post" action="{{ route('admin.profile.store') }}"
                                  class="forms-sample" enctype="multipart/form-data"><!-- ohne enctype kann kein Bild uploadet-->
                                @csrf
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" name="username" id="username"
                                           autocomplete="off" value="{{ $findUser->username }}">
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                           autocomplete="off" value="{{ $findUser->name }}">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                                    <input type="text" class="form-control" name="email" id="exampleInputEmail1"
                                           autocomplete="off" value="{{ $findUser->email }}">
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control" name="phone" id="phone"
                                           autocomplete="off" value="{{ $findUser->phone }}">
                                </div>
                                <!--
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" name="name" id="password"
                                           autocomplete="off" value="{{ $findUser->password }}">
                                </div>
                                -->
                                <div class="mb-3">
                                    <label class="form-label" for="formFile">File upload</label>
                                    <input class="form-control" name="photo" type="file" id="image">
                                </div>

                                <div class="mb-3">
                                    <img id="showImage" class="wd-80 rounded-circle"
                                         src=" {{ !empty($findUser->photo) ? url('upload/admin_images/'.$findUser->photo): url('upload/no_image.jpg')}}"
                                         alt="profile">
                                </div>

                                <button type="submit" class="btn btn-primary me-2">Save Changes</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- middle wrapper end -->

        </div>

    </div>
    <!-- partial:../../partials/_footer.html -->
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
    </script>
@endsection