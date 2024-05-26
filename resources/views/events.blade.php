<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('List of Events') }}
            </h2>
            <button type="button" class="btn btn-primary" id="add" data-bs-toggle="modal"
                data-bs-target="#staticBackdrop">
                Add New Event
            </button>
        </div>
    </x-slot>

    <div class=" py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTableExample" class="table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Fines</th>
                                        <th>Event Date</th>
                                        <th>Login Start</th>
                                        <th>Login Finish</th>
                                        <th>Logout Start</th>
                                        <th>Logout Finish</th>
                                        <th data-orderable="false"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($events as $record)
                                    <tr>
                                        <td>{{ $record->title }}</td>
                                        <td>{{ $record->fines }}</td>
                                        <td>{{ $record->event_date }}</td>
                                        <td>{{ $record->login_start }}</td>
                                        <td>{{ $record->login_finish }}</td>
                                        <td>{{ $record->logout_start }}</td>
                                        <td>{{ $record->logout_finish }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <button class="px-1 hover:text-green-700 btn-edit" id="edit"
                                                    data-id="{{ $record->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#staticBackdrop">
                                                    <svg xmlns=" http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                                    </svg>
                                                </button>
                                                <button class="px-1 hover:text-red-500 btn-delete" id="delete"
                                                    data-id="{{ $record->id }}" data-title="{{ $record->title }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                            </div>
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
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalLabel">Add New Event</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form">
                    <div class="modal-body overflow-y-auto" style="max-height: 500px;">
                        @csrf
                        <div class="mb-3 px-2">
                            <label for="title" class="form-label">Name</label>
                            <input type="text" class="form-control rounded" name="title" id="title" autocomplete="off"
                                placeholder="Event Name">
                        </div>
                        <div class="mb-3 px-2">
                            <label for="description" class="form-label">Description <span
                                    class="text-gray-500">(Optional)</span></label>
                            <input type="text" class="form-control rounded" name="description" id="description"
                                placeholder="Event Description">
                        </div>
                        <div class="mb-3 px-2">
                            <label for="fines" class="form-label">Fines</label>
                            <input type="text" class="form-control rounded" name="fines" id="fines"
                                placeholder="Event Fines">
                        </div>
                        <div class="mb-3 px-2">
                            <label for="event_date" class="form-label">Event Date</label>
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <input type="text" class="form-control rounded" name="event_date" id="event_date"
                                    placeholder="Select Event Date" data-input>
                            </div>
                        </div>
                        <div class="mb-3 px-2">
                            <label for="login_start" class="form-label">Login Start</label>
                            <div class="input-group flatpickr" id="flatpickr-time">
                                <input type="text" class="form-control rounded" name="login_start" id="login_start"
                                    placeholder="Select Login Start" data-input>
                            </div>
                        </div>
                        <div class="mb-3 px-2">
                            <label for="login_finish" class="form-label">Login Finish</label>
                            <div class="input-group flatpickr" id="flatpickr-time">
                                <input type="text" class="form-control rounded" name="login_finish" id="login_finish"
                                    placeholder="Select Login Finish" data-input>
                            </div>
                        </div>
                        <div class="mb-3 px-2">
                            <label for="logout_start" class="form-label">Logout Start</label>
                            <div class="input-group flatpickr" id="flatpickr-time">
                                <input type="text" class="form-control rounded" name="logout_start" id="logout_start"
                                    placeholder="Select Logout Start" data-input>
                            </div>
                        </div>
                        <div class="mb-3 px-2">
                            <label for="logout_finish" class="form-label">Logout Finish</label>
                            <div class="input-group flatpickr" id="flatpickr-time">
                                <input type="text" class="form-control rounded" name="logout_finish" id="logout_finish"
                                    placeholder="Select Logout Finish" data-input>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="create_event_btn">Create</button>
                        <button type="submit" class="btn btn-primary btn-update" id="update_event_btn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.querySelector('#form').addEventListener('submit', (e) => {
    e.preventDefault();
    var title = $('#title').val();
    var description = $('#description').val();
    var fines = $('#fines').val();
    var event_date = $('#event_date').val();
    var login_start = $('#login_start').val();
    var login_finish = $('#login_finish').val();
    var logout_start = $('#logout_start').val();
    var logout_finish = $('#logout_finish').val();
    $.ajax({
        type: "POST",
        url: "{{ route('event.store') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            title: title,
            description: description,
            fines: fines,
            event_date: event_date,
            login_start: login_start,
            login_finish: login_finish,
            logout_start: logout_start,
            logout_finish: logout_finish
        },
        success: function(response) {
            $('#staticBackdrop').modal('toggle');
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Event successfully created!',
                showConfirmButton: false,
                timer: 1500
            });
            setTimeout(function() {
                window.location.reload();
            }, 1500);
        },
        error: function(xhr, status, error) {
            var err = JSON.parse(xhr.responseText);
            if (err.errors.title) {
                $('#title').addClass('is-invalid');
            } else {
                $('#title').removeClass('is-invalid');
            }

            if (err.errors.fines) {
                $('#fines').addClass('is-invalid');
            } else {
                $('#fines').removeClass('is-invalid');
            }

            if (err.errors.event_date) {
                $('#event_date').addClass('is-invalid');
            } else {
                $('#event_date').removeClass('is-invalid');
            }

            if (err.errors.login_start) {
                $('#login_start').addClass('is-invalid');
            } else {
                $('#login_start').removeClass('is-invalid');
            }

            if (err.errors.login_finish) {
                $('#login_finish').addClass('is-invalid');
            } else {
                $('#login_finish').removeClass('is-invalid');
            }

            if (err.errors.logout_start) {
                $('#logout_start').addClass('is-invalid');
            } else {
                $('#logout_start').removeClass('is-invalid');
            }

            if (err.errors.logout_finish) {
                $('#logout_finish').addClass('is-invalid');
            } else {
                $('#logout_finish').removeClass('is-invalid');
            }
        }
    });
});

document.querySelector('#add').addEventListener('click', (e) => {
    e.preventDefault();
    $('.modal-title').text('Add New Event');
    $('#title').val();
    $('#description').val();
    $('#fines').val();
    $('#login_finish').val();
    $('#logout_start').val();
    $('#logout_finish').val();
    $('#create_event_btn').show();
    $('#update_event_btn').hide();
});

$(document).ready(function() {
    var id = null;
    var title = null;
    $('.btn-edit').click(function() {
        $('.modal-title').text('Edit Event Information');

        id = $(this).data('id');

        var url = "{{ route('event.get', 'item_id') }}";
        url = url.replace('item_id', id);

        $.ajax({
            type: "GET",
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#title').val(response.title);
                $('#description').val(response.description);
                $('#fines').val(response.fines);
                $('#event_date').val(response.event_date);
                $('#login_start').val(response.login_start);
                $('#login_finish').val(response.login_finish);
                $('#logout_start').val(response.logout_start);
                $('#logout_finish').val(response.logout_finish);
                $('#create_event_btn').hide();
                $('#update_event_btn').show();
            },
        });
    });

    $('.btn-update').click(function(e) {
        e.preventDefault();

        var url = "{{ route('event.update', 'item_id') }}";
        url = url.replace('item_id', id);

        var title = $('#title').val();
        var description = $('#description').val();
        var fines = $('#fines').val();
        var event_date = $('#event_date').val();
        var login_start = $('#login_start').val();
        var login_finish = $('#login_finish').val();
        var logout_start = $('#logout_start').val();
        var logout_finish = $('#logout_finish').val();

        $.ajax({
            type: "PUT",
            url: url,
            data: {
                title: title,
                description: description,
                fines: fines,
                event_date: event_date,
                login_start: login_start,
                login_finish: login_finish,
                logout_start: logout_start,
                logout_finish: logout_finish
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#staticBackdrop').modal('toggle');
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Event successfully updated!',
                    showConfirmButton: false,
                    timer: 1500
                });
                id = null;
                setTimeout(function() {
                    window.location.reload();
                }, 1500);
            },
            error: function(xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                console.log(err)
            }
        });
    });

    $('.btn-delete').click(function() {
        id = $(this).data('id');
        title = $(this).data('title');

        var url = "{{ route('event.destroy', 'item_id') }}";
        url = url.replace('item_id', id);

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger me-2'
            },
            buttonsStyling: false,
        })

        swalWithBootstrapButtons.fire({
            title: 'You are about to delete',
            text: title,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'me-2',
            confirmButtonText: 'Confirm Delete!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "DELETE",
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: "Deleted!",
                            text: title +
                                ' has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        id = null;
                        title = null;
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    },
                });
            } else if (
                result.dismiss === Swal.DismissReason.cancel
            ) {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: "Cancelled!",
                    text: title + ' has been saved',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        })
    });
});
</script>