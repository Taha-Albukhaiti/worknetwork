<style>
    .card {
        padding: 30px;
        margin-bottom: 30px;
    }
    .card-body {
        font-size: 18px;
    }
    .card p {
        margin-bottom: 10px;
    }
    .profile-img {
        width: 950px;
        height: 450px;
        object-fit: cover;
        border-radius: 50%;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <!-- Profilbild -->
                    <div class="text-center mb-4">
                        <img class="profile-img" src="{{ !empty($data->photo) ? url('upload/company_images/'.$data->photo) : url('upload/no_image.jpg')}}"
                             alt="profile">
                    </div>

                    <h2 class="text-center mb-4">  {{ $data->username ?? '' }}</h2>

                    <div class="row">
                        <!-- Company Kontaktdaten -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h2 style="font-size: 30px;" class="card-title mb-4"><strong>Company Kontaktdaten</strong></h2>
                                    <p><strong>Username:</strong> {{ $data->username ?? '' }}</p>
                                    <p><strong>Name:</strong> {{ $data->name ?? '' }}</p>
                                    <p><strong>Email:</strong> {{ $data->email ?? '' }}</p>
                                    <p><strong>Phone:</strong> {{ $data->phone ?? '' }}</p>
                                    <hr>
                                    <p>
                                        <strong>Adresse:</strong> {{ $address->street ?? '' }} {{ $address->street_number ?? '' }}
                                    </p>
                                    <p><strong>PLZ:</strong> {{ $address->zip ?? '' }}</p>
                                    <p><strong>Stadt:</strong> {{ $address->city ?? '' }}</p>
                                    <p><strong>Land:</strong> {{ $address->state ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Company Profile Information -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h2 style="font-size: 30px;" class="card-title mb-4"><strong>Company Profile Information</strong></h2>
                                    <p><strong>Company Website:</strong> {{ $companyProfile->company_website ?? '' }}</p>
                                    <p><strong>Company Description:</strong> {{ $companyProfile->company_description ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
