@extends('layouts.app')

@section('title', 'Dashboard')

@section('additionalstyles')
    <style>
        .sidebar {
            height: 100vh;
            position: fixed;
            /* top: 0; */
            left: 0;
            width: 250px;
            background-color: #343a40;
            padding-top: 20px;
        }

        .sidebar a {
            color: white;
            display: block;
            padding: 10px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .content {
            margin-left: 260px;
            padding: 20px;
        }
    </style>

@endsection

@section('content')
    <div class="sidebar">
        <h3 class="text-center text-white">Super Admin</h3>
        <a href="#user-requests">User Requests</a>
        <a href="#manage-events">Manage Events</a>
        <a href="#statistics">Statistics</a>
        <a href="#manage-profile">Manage Profile</a>
    </div>

    <div class="content">
        <h1>Super Admin Dashboard</h1>

        <!-- User Requests Section -->
        <section id="user-requests">
            <h2>User Requests</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through user requests -->
                    @foreach ($requests as $request)
                        <tr>
                            <td>{{ $request->id }}</td>
                            <td>{{ $request->name }}</td>
                            <td>{{ $request->email }}</td>
                            <td>{{ $request->status }}</td>
                            <td>
                                <button class="btn btn-success">Approve</button>
                                <button class="btn btn-danger">Reject</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <!-- Manage Events Section -->
        <section id="manage-events">
            <h2>Manage Events</h2>
            <a href="{{ url('/createEvent') }}">
                <button class="btn btn-primary">Create New Event</button>
            </a>
            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through events -->
                    @foreach ($events as $event)
                        <tr>
                            <td>{{ $event->name }}</td>
                            <td>{{ $event->description }}</td>
                            <td>{{ $event->date }}</td>
                            <td>
                                <button class="btn btn-info">Edit</button>
                                <button class="btn btn-danger">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <!-- Statistics Section -->
        <section id="statistics">
            <h2>Statistics</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-header">Total Users</div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $totalUsers }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-header">Total Events</div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $totalEvents }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-warning mb-3">
                        <div class="card-header">Pending Requests</div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $pendingRequests }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Manage Profile Section -->
        <section id="manage-profile">
            <h2>Manage Profile</h2>
            <form method="POST">
                {{-- <form action="{{ route('updateProfile') }}" method="POST"> --}}
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    {{-- <input type="text" class="form-control" id="name" name="name" value="{{ $admin->name }}"> --}}
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    {{-- <input type="email" class="form-control" id="email" name="email" value="{{ $admin->email }}"> --}}
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </section>
    </div>

@endsection

@section('additionalscripts')

@endsection
