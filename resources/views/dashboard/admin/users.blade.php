<div class="tab-pane fade" id="tabs2" role="tabpanel" aria-labelledby="users-tab">
    <h1 class="tab-content-title">Users</h1>
    <!-- Search input for Users -->
    <div class="mb-3">
        <input type="text" id="search-users" class="form-control" placeholder="Search Users">
    </div>
    <div class="table-responsive">
        <table class="table table-striped" id="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Registered at</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loop through users -->
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at }}</td>
                        {{-- <td>{{ $user->status }}</td> --}}
                        <td>
                            <!-- Responsive button group -->
                            <div class="btn-group" role="group">
                                @isset($authUser)
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#confirmChangePermission" data-id="{{ $user->id }}">
                                        Upgrade to Admin
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#confirmDeleteModal" data-id="{{ $user->id }}">
                                        Delete User
                                    </button>
                                @else
                                    No actions available
                                @endisset
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @isset($authUser)
        <hr class="my-4">
        <h1 class="tab-content-title">Admins</h1>
        <!-- Search input for Admins -->
        <div class="mb-3">
            <input type="text" id="search-admins" class="form-control" placeholder="Search Admins">
        </div>
        <div class="table-responsive">
            <table class="table table-striped" id="admins-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        {{-- <th>Status</th> --}}
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($admins as $admin)
                        <tr>
                            <td>{{ $admin->id }}</td>
                            <td>{{ $admin->name }}</td>
                            <td>{{ $admin->email }}</td>
                            {{-- <td>{{ $admin->status }}</td> --}}
                            <td>
                                <!-- Responsive button group -->
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#confirmChangePermission" data-id="{{ $admin->id }}">
                                        Downgrade to User
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#confirmDeleteModal" data-id="{{ $admin->id }}">
                                        Delete Admin
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endisset
</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this user?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmChangePermission" tabindex="-1" aria-labelledby="confirmChangePermissionLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmChangePermissionLabel">Confirm Change Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to change this user's permission?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="changePermissionForm" method="POST" action="">
                    @csrf
                    <button type="submit" class="btn btn-success">Change Permission</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var deleteModal = document.getElementById('confirmDeleteModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var userId = button.getAttribute('data-id');
            var form = deleteModal.querySelector('#deleteForm');
            var url = '{{ route('users.delete', ':id') }}'.replace(':id', userId);
            form.action = url;
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var changePermissionModal = document.getElementById('confirmChangePermission');
        changePermissionModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var userId = button.getAttribute('data-id');
            var form = changePermissionModal.querySelector('#changePermissionForm');
            var url = '{{ route('users.change_permission', ':id') }}'.replace(':id', userId);
            form.action = url;
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        function filterTable(inputId, tableId) {
            const input = document.getElementById(inputId);
            const table = document.getElementById(tableId);
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            input.addEventListener('input', function() {
                const filter = input.value.toLowerCase();
                for (let i = 0; i < rows.length; i++) {
                    const cells = rows[i].getElementsByTagName('td');
                    let found = false;
                    for (let j = 0; j < cells.length; j++) {
                        if (cells[j].textContent.toLowerCase().includes(filter)) {
                            found = true;
                            break;
                        }
                    }
                    rows[i].style.display = found ? '' : 'none';
                }
            });
        }

        filterTable('search-users', 'users-table');
        filterTable('search-admins', 'admins-table');
    });
</script>
