<div class="tab-pane fade" id="tabs3" role="tabpanel" aria-labelledby="events-tab">
    <h1 class="tab-content-title">Manage Events</h1>
    <a href="{{ route('events.create') }}" class="btn btn-add mb-3">Create New Event</a>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Creator</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($events as $event)
                    <tr>
                        <td>{{ $event->name }}</td>
                        <td>{{ $event->description }}</td>
                        <td>{{ \Carbon\Carbon::parse($event->datetime)->format('F j, Y, g:i a') }}</td>
                        <td>{{ $event->creator->email }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                {{-- View button is always shown --}}
                                <a href="{{ route('showEvent', $event->id) }}" class="btn btn-success btn-sm">View</a>

                                {{-- Check if the current user is the creator of the event or is a superuser --}}
                                @if ($event->created_by == auth()->id() || auth()->user()->isSuperAdmin())
                                    <a href="{{ route('events.edit', $event->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#confirmDeleteEventModal" data-id="{{ $event->id }}">
                                        Delete
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No events available.</td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>
</div>

<!-- Modal for confirmation -->
<div class="modal fade" id="confirmDeleteEventModal" tabindex="-1" aria-labelledby="confirmDeleteEventModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteEventModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this event?
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


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var deleteModal = document.getElementById('confirmDeleteEventModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var eventId = button.getAttribute('data-id');
            var form = deleteModal.querySelector('#deleteForm');
            var url = '{{ route('events.delete', ':id') }}'.replace(':id', eventId);
            form.action = url; // Update the form action URL
        });
    });
</script>
