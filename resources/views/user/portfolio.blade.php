<style>
    .card {
        padding: 30px;
        margin-bottom: 30px;
    }
    .card-body {
        font-size: 18px;
    }
    .card-title {
        font-size: 40px;
        margin-bottom: 20px;
    }
    .card p {
        margin-bottom: 10px;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <!-- Profilbild -->
                    <div class="text-center mb-4">
                        <img class="wd-100 rounded-circle" src="{{ !empty($data->photo) ? url('upload/user_images/'.$data->photo) : url('upload/no_image.jpg')}}"
                             alt="profile">
                    </div>

                    <h2 class="text-center mb-4"><strong>Portfolio</strong></h2>

                    <div class="row">
                        <!-- Benutzerinformationen -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h2 class="card-title mb-4"><strong>Benutzerinformationen</strong></h2>
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
                        <!-- Portfolio -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h2 class="card-title mb-4"><strong>Portfolio</strong></h2>
                                    @if($portfolios)
                                        <p><strong>Job Title:</strong> {{ $portfolios->job_title }}</p>
                                        <p><strong>Company:</strong> {{ $portfolios->company }}</p>
                                        <p><strong>Start Date:</strong> {{ $portfolios->start_date }}</p>
                                        <p><strong>End Date:</strong> {{ $portfolios->end_date }}</p>
                                        <hr>
                                        @if($portfolios->details->isNotEmpty())
                                            <h4 class="mb-2">Details:</h4>
                                            @foreach($portfolios->details as $detail)
                                                <p><strong>{{ $detail->type }}:</strong> {{ $detail->content }}</p>
                                            @endforeach
                                        @else
                                            <p>Noch keine Details vorhanden.</p>
                                        @endif
                                        <hr>
                                        <!-- Bilder -->
                                        @if($portfolios->media()->where('type', 'image')->exists())
                                            <h4 class="mb-2">Zusätzliche Bilder:</h4>
                                            <div class="row mb-3">
                                                @foreach($portfolios->media()->where('type', 'image')->get() as $image)
                                                    <div class="col-md-6">
                                                        <img src="{{ url('upload/portfolio_images/'.$image->filename) }}"
                                                             alt="portfolio-image" class="img-fluid">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                        <hr>
                                        <!-- Dateien -->
                                        @if($portfolios->media()->where('type', 'file')->exists())
                                            <h4 class="mb-2">Zusätzliche Dateien:</h4>
                                            <ul>
                                                @foreach($portfolios->media()->where('type', 'file')->get() as $file)
                                                    <li><a href="{{ url('upload/portfolio_files/'.$file->filename) }}"
                                                           target="_blank">{{ $file->filename }}</a></li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    @else
                                        <p>Noch kein Portfolio vorhanden.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
