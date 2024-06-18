<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('List of Events') }}
            </h2>
            <div>
                <button type="button" class="btn btn-primary" id="add" data-bs-toggle="modal"
                    data-bs-target="#staticBackdrop">
                    Add New Event
                </button>
                @if(! Session::has('monitoring_progress') && \App\Models\Event::count() > 0)
                <button type="button" class="btn btn-success" id="start_monitoring" data-bs-toggle="modal"
                    data-bs-target="#monitoringModal">
                    Start Monitoring
                </button>
                @endif
            </div>
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
                                        <th>Event Date</th>
                                        <th>Consequence</th>
                                        <th>Setting</th>
                                        <th data-orderable="false"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($events as $record)
                                    <tr>
                                        <td>{{ $record->title }}</td>
                                        <td>{{ $record->event_date }}</td>
                                        <td>Community Service Hrs: {{ $record->consequence }}</td>
                                        <td>{{ $record->settings == 'wday' ? 'Whole Day' : 'Half Day' }}</td>
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
                                                @if($record->is_turn_on)
                                                <a href="/monitoring" class="px-1 hover:text-yellow-500"
                                                    target="_blank">
                                                    <svg xmlns=" http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                                    </svg>
                                                </a>
                                                @endif
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
        <div class="modal-dialog modal-lg">
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
                            <label for="setting" class="form-label">Event Setting</label>
                            <select class="form-select rounded @error('setting') is-invalid @enderror" name="setting"
                                id="setting">
                                <option selected value="wday">Whole Day</option>
                                <option value="hday">Half Day</option>
                            </select>
                        </div>
                        <div class="mb-3 px-2">
                            <label for="consequence" class="form-label">Community Service Render Hour(s)<span
                                    class="text-gray-500 text-sm"> Consequence on Late/Absent </span></label>
                            <input type="number" min="1" max="25" autocomplete="off" class="form-control rounded"
                                name="consequence" id="consequence"
                                placeholder="How many hours should render community service?">
                        </div>
                        <div class="mb-3 px-2">
                            <label for="event_date" class="form-label">Event Date</label>
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <input type="text" class="form-control rounded" name="event_date" id="event_date"
                                    placeholder="Select Event Date" data-input>
                            </div>
                        </div>
                        <div id="wday">
                            <!-- Morning -->
                            <div class="mb-3 px-2">
                                <h1 class="mb-3 px-2 font-semibold border bg-green-100 p-2 rounded-lg">Morning</h1>
                                <div class="flex">
                                    <div class="mb-3 px-2 w-full">
                                        <label for="morning_login_start" class="form-label">Login Start</label>
                                        <div class="input-group flatpickr" id="flatpickr-time">
                                            <input type="text" class="form-control rounded" name="morning_login_start"
                                                id="morning_login_start" placeholder="Select Login Start" data-input>
                                        </div>
                                    </div>
                                    <div class="mb-3 px-2 w-full">
                                        <label for="morning_login_finish" class="form-label">Login Finish</label>
                                        <div class="input-group flatpickr" id="flatpickr-time">
                                            <input type="text" class="form-control rounded" name="morning_login_finish"
                                                id="morning_login_finish" placeholder="Select Login Finish" data-input>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex">
                                    <div class="mb-3 px-2 w-full">
                                        <label for="morning_logout_start" class="form-label">Logout
                                            Start</label>
                                        <div class="input-group flatpickr" id="flatpickr-time">
                                            <input type="text" class="form-control rounded" name="morning_logout_start"
                                                id="morning_logout_start" placeholder="Select Logout Start" data-input>
                                        </div>
                                    </div>
                                    <div class="mb-3 px-2 w-full">
                                        <label for="morning_logout_finish" class="form-label">Logout Finish</label>
                                        <div class="input-group flatpickr" id="flatpickr-time">
                                            <input type="text" class="form-control rounded" name="morning_logout_finish"
                                                id="morning_logout_finish" placeholder="Select Logout Finish"
                                                data-input>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Afternoon -->
                            <div class="mb-3 px-2">
                                <h1 class="mb-3 px-2 font-semibold border bg-green-100 p-2 rounded-lg">Afternoon</h1>
                                <div class="flex">
                                    <div class="mb-3 px-2 w-full">
                                        <label for="afternoon_login_start" class="form-label">Login Start</label>
                                        <div class="input-group flatpickr" id="flatpickr-time">
                                            <input type="text" class="form-control rounded" name="afternoon_login_start"
                                                id="afternoon_login_start" placeholder="Select Login Start" data-input>
                                        </div>
                                    </div>
                                    <div class="mb-3 px-2 w-full">
                                        <label for="afternoon_login_finish" class="form-label">Login Finish</label>
                                        <div class="input-group flatpickr" id="flatpickr-time">
                                            <input type="text" class="form-control rounded"
                                                name="afternoon_login_finish" id="afternoon_login_finish"
                                                placeholder="Select Login Finish" data-input>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex">
                                    <div class="mb-3 px-2 w-full">
                                        <label for="afternoon_logout_start" class="form-label">Logout Start</label>
                                        <div class="input-group flatpickr" id="flatpickr-time">
                                            <input type="text" class="form-control rounded"
                                                name="afternoon_logout_start" id="afternoon_logout_start"
                                                placeholder="Select Logout Start" data-input>
                                        </div>
                                    </div>
                                    <div class="mb-3 px-2 w-full">
                                        <label for="afternoon_logout_finish" class="form-label">Logout Finish</label>
                                        <div class="input-group flatpickr" id="flatpickr-time">
                                            <input type="text" class="form-control rounded"
                                                name="afternoon_logout_finish" id="afternoon_logout_finish"
                                                placeholder="Select Logout Finish" data-input>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Half Day -->
                        <div id="hday">
                            <div class="mb-3 px-2">
                                <h1 class="mb-3 px-2 font-semibold border bg-green-100 p-2 rounded-lg">Half Day</h1>
                                <div class="flex">
                                    <div class="mb-3 px-2 w-full">
                                        <label for="halfday_login_start" class="form-label">Login Start</label>
                                        <div class="input-group flatpickr" id="flatpickr-time">
                                            <input type="text" class="form-control rounded" name="halfday_login_start"
                                                id="halfday_login_start" placeholder="Select Login Start" data-input>
                                        </div>
                                    </div>
                                    <div class="mb-3 px-2 w-full">
                                        <label for="halfday_login_finish" class="form-label">Login Finish</label>
                                        <div class="input-group flatpickr" id="flatpickr-time">
                                            <input type="text" class="form-control rounded" name="halfday_login_finish"
                                                id="halfday_login_finish" placeholder="Select Login Finish" data-input>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex">
                                    <div class="mb-3 px-2 w-full">
                                        <label for="halfday_logout_start" class="form-label">Logout
                                            Start</label>
                                        <div class="input-group flatpickr" id="flatpickr-time">
                                            <input type="text" class="form-control rounded" name="halfday_logout_start"
                                                id="halfday_logout_start" placeholder="Select Logout Start" data-input>
                                        </div>
                                    </div>
                                    <div class="mb-3 px-2 w-full">
                                        <label for="halfday_logout_finish" class="form-label">Logout Finish</label>
                                        <div class="input-group flatpickr" id="flatpickr-time">
                                            <input type="text" class="form-control rounded" name="halfday_logout_finish"
                                                id="halfday_logout_finish" placeholder="Select Logout Finish"
                                                data-input>
                                        </div>
                                    </div>
                                </div>
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
    <div class="modal fade" id="monitoringModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalLabel">Start Monitoring</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-monitoring">
                    <div class="modal-body overflow-y-auto" style="max-height: 500px;">
                        @csrf
                        <div class="mb-3 px-2">
                            <label for="events" class="form-label">Event</label>
                            <select class="form-select event_selected rounded @error('event') is-invalid @enderror"
                                name="event" id="event">
                                <option selected disabled>Select event</option>
                                @foreach($events as $record)
                                <option value="{{ $record->id }}" data-settings="{{ $record->settings }}">
                                    {{ $record->title }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 px-2">
                            <label for="att_option" class="form-label">Attendance Option</label>
                            <select class="form-select rounded @error('att_option') is-invalid @enderror"
                                name="att_option" id="att_option">
                                <option selected disabled>Select attendance option</option>
                                <option value="Login">Login</option>
                                <option value="Logout">Logout</option>
                                <option value="Login Afternoon" id="hday-login">Login (Afternoon)</option>
                                <option value="Logout Afternoon" id="hday-logout">Logout (Afternoon)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="confirm_start">Start</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.querySelector('#form-monitoring').addEventListener('submit', (e) => {
    e.preventDefault()

    var event_id = $('#event').val();
    var event = $('#event option:selected').text();
    var att_option = $('#att_option').val();

    if (event == null) {
        $('#event').addClass('is-invalid');
        return;
    } else {
        $('#event').removeClass('is-invalid');
    }

    if (att_option == null) {
        $('#att_option').addClass('is-invalid');
        return;
    } else {
        $('#att_option').removeClass('is-invalid');
    }

    $.ajax({
        type: "POST",
        url: "{{ route('monitoring.store') }}",
        data: {
            'event_id': event_id,
            'event_name': event,
            'option': att_option
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#monitoringModal').modal('hide');
            setTimeout(function() {
                window.location.replace('/monitoring');
            }, 150);
        },
    });
});

document.querySelector('#form').addEventListener('submit', (e) => {
    e.preventDefault();
    var title = $('#title').val();
    var description = $('#description').val();
    var setting = $('#setting').val();
    var consequence = $('#consequence').val();
    var event_date = $('#event_date').val();
    // morning
    if (setting == 'wday') {
        var morning_login_start = $('#morning_login_start').val();
        var morning_login_finish = $('#morning_login_finish').val();
        var morning_logout_start = $('#morning_logout_start').val();
        var morning_logout_finish = $('#morning_logout_finish').val();
        //afternoon
        var afternoon_login_start = $('#afternoon_login_start').val();
        var afternoon_login_finish = $('#afternoon_login_finish').val();
        var afternoon_logout_start = $('#afternoon_logout_start').val();
        var afternoon_logout_finish = $('#afternoon_logout_finish').val();
    } else {
        //halfday
        var morning_login_start = $('#halfday_login_start').val();
        var morning_login_finish = $('#halfday_login_finish').val();
        var morning_logout_start = $('#halfday_logout_start').val();
        var morning_logout_finish = $('#halfday_logout_finish').val();
    }
    $.ajax({
        type: "POST",
        url: "{{ route('event.store') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            title: title,
            description: description,
            settings: setting,
            consequence: consequence,
            event_date: event_date,
            morning_login_start: morning_login_start,
            morning_login_finish: morning_login_finish,
            morning_logout_start: morning_logout_start,
            morning_logout_finish: morning_logout_finish,
            afternoon_login_start: afternoon_login_start,
            afternoon_login_finish: afternoon_login_finish,
            afternoon_logout_start: afternoon_logout_start,
            afternoon_logout_finish: afternoon_logout_finish,
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

            if (err.errors.consequence) {
                $('#consequence').addClass('is-invalid');
            } else {
                $('#consequence').removeClass('is-invalid');
            }

            if (err.errors.event_date) {
                $('#event_date').addClass('is-invalid');
            } else {
                $('#event_date').removeClass('is-invalid');
            }
        }
    });
});

document.querySelector('#add').addEventListener('click', (e) => {
    e.preventDefault();
    $('.modal-title').text('Add New Event');
    $('#title').val('');
    $('#description').val('');
    $('#setting').val('wday');
    $('#wday').show();
    $('#hday').hide();
    $('#consequence').val('');
    $('#event_date').val('');
    $('#morning_login_start').val('');
    $('#morning_login_finish').val('');
    $('#morning_logout_start').val('');
    $('#morning_logout_finish').val('');
    $('#afternoon_login_start').val('');
    $('#afternoon_login_finish').val('');
    $('#afternoon_logout_start').val('');
    $('#afternoon_logout_finish').val('');
    $('#halfday_login_start').val('');
    $('#halfday_login_finish').val('');
    $('#halfday_logout_start').val('');
    $('#halfday_logout_finish').val('');
    $('#create_event_btn').show();
    $('#update_event_btn').hide();
});

$(document).ready(function() {
    $('#hday').hide(); // for half day
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
                $('#setting').val(response.settings);
                $('#consequence').val(response.consequence);
                $('#event_date').val(response.event_date);
                if (response.settings == 'wday') {
                    $('#wday').show();
                    $('#hday').hide();
                    $('#morning_login_start').val(response.morning_login_start);
                    $('#morning_login_finish').val(response.morning_login_finish);
                    $('#morning_logout_start').val(response.morning_logout_start);
                    $('#morning_logout_finish').val(response.morning_logout_finish);
                    $('#afternoon_login_start').val(response.afternoon_login_start);
                    $('#afternoon_login_finish').val(response.afternoon_login_finish);
                    $('#afternoon_logout_start').val(response.afternoon_logout_start);
                    $('#afternoon_logout_finish').val(response.afternoon_logout_finish);
                } else {
                    $('#hday').show();
                    $('#wday').hide();
                    $('#halfday_login_start').val(response.morning_login_start);
                    $('#halfday_login_finish').val(response.morning_login_finish);
                    $('#halfday_logout_start').val(response.morning_logout_start);
                    $('#halfday_logout_finish').val(response.morning_logout_finish);
                }
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
        var setting = $('#setting').val();
        var consequence = $('#consequence').val();
        var event_date = $('#event_date').val();
        // morning
        if (setting == 'wday') {
            var morning_login_start = $('#morning_login_start').val();
            var morning_login_finish = $('#morning_login_finish').val();
            var morning_logout_start = $('#morning_logout_start').val();
            var morning_logout_finish = $('#morning_logout_finish').val();
            //afternoon
            var afternoon_login_start = $('#afternoon_login_start').val();
            var afternoon_login_finish = $('#afternoon_login_finish').val();
            var afternoon_logout_start = $('#afternoon_logout_start').val();
            var afternoon_logout_finish = $('#afternoon_logout_finish').val();
        } else {
            //halfday
            var morning_login_start = $('#halfday_login_start').val();
            var morning_login_finish = $('#halfday_login_finish').val();
            var morning_logout_start = $('#halfday_logout_start').val();
            var morning_logout_finish = $('#halfday_logout_finish').val();
        }

        $.ajax({
            type: "PUT",
            url: url,
            data: {
                title: title,
                description: description,
                settings: setting,
                consequence: consequence,
                event_date: event_date,
                morning_login_start: morning_login_start,
                morning_login_finish: morning_login_finish,
                morning_logout_start: morning_logout_start,
                morning_logout_finish: morning_logout_finish,
                afternoon_login_start: afternoon_login_start,
                afternoon_login_finish: afternoon_login_finish,
                afternoon_logout_start: afternoon_logout_start,
                afternoon_logout_finish: afternoon_logout_finish,
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
                if (err.errors.title) {
                    $('#title').addClass('is-invalid');
                } else {
                    $('#title').removeClass('is-invalid');
                }

                if (err.errors.consequence) {
                    $('#consequence').addClass('is-invalid');
                } else {
                    $('#consequence').removeClass('is-invalid');
                }

                if (err.errors.event_date) {
                    $('#event_date').addClass('is-invalid');
                } else {
                    $('#event_date').removeClass('is-invalid');
                }
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

    $('#setting').change(function() {
        $('#' + $(this).val()).show();
        $('#' + $('#setting option:not(:selected)').val()).hide();
    });

    $('.event_selected').change(function() {
        var selected = $(this).find('option:selected');
        var extra = selected.data('settings');
        console.log(extra);
        if (extra == 'hday') {
            $('#hday-login').hide();
            $('#hday-logout').hide();
        }
    });
});
</script>