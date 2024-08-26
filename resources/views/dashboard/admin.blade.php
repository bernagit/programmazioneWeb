@extends('layouts.app')

@section('title', 'Dashboard')

@section('additionalstyles')
    <style>
        .nav-item .nav-link.active {
            background-color: #FCEC52;
            color: black;
            border: 0;
        }

        .nav-item .nav-link {
            color: black;
            border: 0;
        }

        .nav-item .nav-link:hover {
            background-color: #ADE8F4;
            color: black;
        }

        .btn-add {
            background-color: #FCEC52;
            color: black;
            border: 0;
        }

        .btn-add:hover {
            background-color: #ADE8F4;
            color: black;
        }

        /* Responsive behavior: stack tabs vertically on small screens */
        @media (max-width: 768px) {
            .nav-pills {
                flex-direction: column;
            }

            .nav-item {
                margin-bottom: 10px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <!-- Flash Message Section -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-3 col-md-4">
                <!-- Tabs navigation -->
                <ul class="nav nav-pills flex-column mb-4" id="sa-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" href="#tabs1" role="tab"
                            aria-controls="tabs1" aria-selected="true">Dashboard</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="users-tab" data-bs-toggle="tab" href="#tabs2" role="tab"
                            aria-controls="tabs2" aria-selected="false">Users</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="events-tab" data-bs-toggle="tab" href="#tabs3" role="tab"
                            aria-controls="tabs3" aria-selected="false">Manage Events</a>
                    </li>
                    {{-- <li class="nav-item" role="presentation">
                        <a class="nav-link" id="statistics-tab" data-bs-toggle="tab" href="#tabs4" role="tab"
                            aria-controls="tabs4" aria-selected="false">Statistics</a>
                    </li> --}}
                </ul>
            </div>
            <div class="col-lg-9 col-md-8">
                <div class="tab-content" id="content-tabs">
                    <div class="tab-pane fade show active" id="tabs1" role="tabpanel" aria-labelledby="dashboard-tab">
                        <h1 class="tab-content-title">Dashboard</h1>
                        <hr class="my-4">
                        <div class="row">
                            <div class="col-md-6">
                                <h2 class="tab-content-title">Change password</h2>
                                @include('user.change_pwd')
                            </div>
                        </div>
                    </div>

                    @include('dashboard.admin.users', ['users' => $users])
                    @include('dashboard.admin.events', ['events' => $events])
                </div>
            </div>
        </div>
    </div>
@endsection

@section('additionalscripts')
    <!-- Add any additional scripts here if needed -->
@endsection
