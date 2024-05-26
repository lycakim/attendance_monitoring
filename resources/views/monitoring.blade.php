<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Monitoring') }}
            </h2>
            <div>
                @if(Session::has('event_name') && Session::has('option'))
                <button type="button" class="btn btn-danger btn-end-monitoring" id="end_monitoring">
                    End Monitoring
                </button>
                @else
                <button type="button"
                    class="btn btn-primary @if(!Session::has('event_name') && Session::has('option')) hidden @endif"
                    id="start_monitoring" data-bs-toggle="modal" data-bs-target="#monitoringModal">
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
                        <div class="container">
                            <div class="row">
                                <div class="col-8 d-flex gap-2">
                                    <div
                                        class="d-flex text-center justify-content-center align-items-center align-self-center">
                                        <h2 class="fs-7 font-normal italic text-gray-500 @if(Session::has('event_name')) hidden @endif"
                                            id="default_name">No event
                                            selected
                                            yet
                                        </h2>
                                        @if(Session::has('event_name') && Session::has('option'))
                                        <h2 class="fs-3 font-semibold" id="event_name">
                                            {{ Session::get('event_name')}}</h2>
                                        <span class="badge text-bg-success ml-5 fs-5 capitalize"
                                            id="event_badge">{{ Session::get('option') }} in progress</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div
                                        class="d-flex text-center justify-content-center align-items-center align-self-center border rounded">
                                        <h1 class="font-semibold fs-2 12-hr-time"></h1>
                                    </div>
                                </div>
                            </div>
                            @if(Session::has('event_name') && Session::has('option'))
                            <div class="row mt-4" id="id_number_field">
                                <div class="col">
                                    <div class="input-group input-group-md">
                                        <span class="input-group-text" id="inputGroup-sizing-md">Enter ID Number</span>
                                        <input type="text" autofocus autocomplete="off"
                                            class="form-control rounded-r-lg border-gray-300 text-right"
                                            placeholder="Enter Student ID Number" id="mon_id_number"
                                            aria-label="Sizing example input" aria-describedby="inputGroup-sizing-md">
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="row mt-3">
                                <div class="col">
                                    <div class="table-responsive">
                                        <table id="dataTableExample" class="table">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Time</th>
                                                    <th>Event</th>
                                                    <th>Option</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody">
                                                @forelse($monitorings as $record)
                                                <tr>
                                                    @if($record->default == 'system' && $record->option == 'Login')
                                                    <td colspan="5" class="text-center" style="color: gray">
                                                        {{ $record->events->title }}
                                                        event has started its <b>{{ $record->option }} </b> Monitoring
                                                        on
                                                        {{ $record->created_at->format("F j, Y, h:i A") }}
                                                    </td>
                                                    @elseif($record->default == 'system' && $record->option == 'Logout')
                                                    <td colspan="5" class="text-center" style="color: gray">
                                                        {{ $record->events->title }}
                                                        event has ended its <b>{{ $record->option }} </b> Monitoring on
                                                        {{ $record->created_at->format("F j, Y, h:i A") }}
                                                    </td>
                                                    @else
                                                    <td>{{ $record->students->id_number }}</td>
                                                    <td>{{ $record->students->name }}</td>
                                                    <td>{{ $record->created_at->format("F j, Y, h:i A") }}</td>
                                                    <td>{{ $record->events->title }}</td>
                                                    <td>{{ $record->option }}</td>
                                                    @endif
                                                    @empty
                                                    <td colspan="5" class="text-center text-sm italic">No monitoring
                                                        data found
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                <form id="form">
                    <div class="modal-body overflow-y-auto" style="max-height: 500px;">
                        @csrf
                        <div class="mb-3 px-2">
                            <label for="events" class="form-label">Event</label>
                            <select class="form-select event_selected rounded @error('event') is-invalid @enderror"
                                name="event" id="event">
                                <option selected disabled>Select event</option>
                                @foreach($events as $record)
                                <option value="{{ $record->id }}">{{ $record->title }}
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
$(document).ready(function() {
    var date_time = null;
    setInterval(function() {
        var date = new Date();
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var seconds = date.getSeconds();
        var ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;
        var strTime = hours + ':' + minutes + ':' + seconds + ' ' + ampm;
        var strTimeForDB = date + " " + hours + ":" + minutes + ":" + seconds;
        date_time = strTimeForDB;
        $('.12-hr-time').text(strTime);
    }, 500);

    document.querySelector('#form').addEventListener('submit', (e) => {
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
                'option': att_option,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $("#form")[0].reset();
                $('#default_name').addClass('hidden')
                $('#event_name').text(event);
                $('#event_badge').text(att_option +
                    ' in progress');
                $('#event_name').removeClass('hidden');
                $('#id_number_field').removeClass('hidden');
                $('#event_badge').removeClass('hidden');
                $('#start_monitoring').addClass('hidden');
                $('#end_monitoring').removeClass('hidden');
                $('#staticBackdrop').modal('hide');
                setTimeout(function() {
                    window.location.reload();
                }, 150);
            },
        });
    });

    $('.btn-end-monitoring').click(function() {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger me-2'
            },
            buttonsStyling: false,
        })

        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "You are about to end this monitoring..",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'me-2',
            confirmButtonText: 'Confirm End',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('monitoring.destroy') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: "Ended Monitoring!",
                            text: "You have successfully ended this monitoring",
                            showConfirmButton: false,
                            timer: 1500
                        });
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
                    text: "Monitoring has been continued",
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        })
    });

    document.querySelector('#mon_id_number').addEventListener('keypress', (e) => {
        if (e.key === "Enter") {
            e.preventDefault();
            var id_number = e.target.value;
            $.ajax({
                type: "POST",
                url: "{{ route('monitoring.student.store') }}",
                data: {
                    'id_number': id_number,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#dataTableExample').load("{{ route('monitoring') }}" +
                        ' #dataTableExample');
                    Swal.fire({
                        position: 'top-right',
                        icon: 'success',
                        title: "Student Saved!",
                        text: "You have successfully " +
                            "{{ session()->get('option') }}!",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    setTimeout(function() {
                        window.location.reload();
                    }, 150);
                },
                error: function(xhr, desc, err) {
                    if (xhr.status == 404) {
                        Swal.fire({
                            position: 'top-right',
                            icon: 'warning',
                            title: "Not Found!",
                            text: "Student is not yet registered",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#mon_id_number').val("");
                    }
                },
            });
        }
    });
});
</script>