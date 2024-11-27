<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Reports') }}
            </h2>
            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateReports">
                    Filter
                </button>
                <button type="button" class="btn btn-success" id="export">
                    Export
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTableExample" class="table">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Name</th>
                                        <th>Program</th>
                                        <th>Year</th>
                                        <th>Set</th>
                                        <th>Consequence</th>
                                        <th>Event</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body">
                                    @foreach($monitoring_reports as $record)
                                    <tr>
                                        <td>{{ $record->students->id_number }}</td>
                                        <td>{{ $record->students->name }}</td>
                                        <td>{{ $record->students->program }}</td>
                                        <td>{{ $record->students->year }}</td>
                                        <td>{{ $record->students->set }}</td>
                                        <td class="font-semibold">{{ $record->consequence }}</td>
                                        <td>{{ $record->events->title }}</td>
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
    <div class="modal fade" id="generateReports" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalLabel">Filter this table</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="reportform">
                    <div class="modal-body overflow-y-auto" style="max-height: 500px;">
                        @csrf
                        <div class="mb-3 px-2">
                            <label for="event_id" class="form-label">Event</label>
                            <select class="form-select rounded @error('event_id') is-invalid @enderror" name="event_id"
                                id="event_id">
                                <option selected disabled>Select event</option>
                                @foreach ($events as $data)
                                <option value="{{ $data->id }}">{{ $data->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 px-2">
                            <label for="program" class="form-label">Program</label>
                            <select class="form-select rounded @error('program') is-invalid @enderror" name="program"
                                id="program">
                                <option selected value="All">All</option>
                                <option value="IC">IC</option>
                                <option value="ILEGG">ILEGG</option>
                                <option value="IAAS">IAAS</option>
                                <option value="ITED">ITED</option>
                            </select>
                        </div>
                        <div class="mb-3 px-2">
                            <label for="year" class="form-label">Year</label>
                            <select class="form-select rounded @error('year') is-invalid @enderror" name="year"
                                id="year">
                                <option selected value="All">All</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                            </select>
                        </div>
                        <div class="mb-3 px-2">
                            <label for="set" class="form-label">Set</label>
                            <select class="form-select rounded @error('set') is-invalid @enderror" name="set" id="set">
                                <option selected value="All">All</option>
                                @foreach($set_list as $set)
                                <option value="{{ $set->set }}">{{ $set->set }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="create_report_btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.querySelector('#reportform').addEventListener('submit', (e) => {
    e.preventDefault();
    var event = $('#event_id').val();
    var program = $('#program').val();
    var year = $('#year').val();
    var set = $('#set').val();
    $.ajax({
        type: "GET",
        url: "{{ route('generate.report') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            event_id: event,
            program: program,
            year: year,
            set: set
        },
        success: function(response) {
            var html = '';
            if (response.length > 0) {
                console.log(response);
                $.each(response, function(i, file) {
                    html +=
                        '<tr><td>' + file.students.id_number + ' ' + file.students.name +
                        '</td>' +
                        '<td>' + file.students.program + '</td>' +
                        '<td>' + file.students.year + '</td>' +
                        '<td>' + file.students.set + '</td>' +
                        '<td>' + file.events.title + '</td></tr>';
                });
                $('#table-body').empty().append(html);
                $('#program').val('All');
                $('#year').val("All");
                $('#set').val("All");
                $('#generateReports').modal('toggle');
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'warning',
                    title: 'No data found',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        },
        error: function(xhr, status, error) {
            var err = JSON.parse(xhr.responseText);
            if (err.errors.event_id) {
                $('#event_id').addClass('is-invalid');
            } else {
                $('#event_id').removeClass('is-invalid');
            }
        }
    });
});

$(document).ready(function() {
    $('#export').click(function() {
        var url = "{{ route('reports.export') }}";

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-primary me-2'
            },
            buttonsStyling: false,
        })

        swalWithBootstrapButtons.fire({
            title: 'You are about to export',
            icon: 'info',
            showCancelButton: true,
            confirmButtonClass: 'me-2',
            confirmButtonText: 'Confirm Export',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "GET",
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(response, status, xhr) {
                        var a = document.createElement('a');
                        var url = window.URL.createObjectURL(response);
                        a.href = url;
                        a.download = 'reports.csv';
                        document.body.append(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                        // Swal.fire({
                        //     position: 'center',
                        //     icon: 'success',
                        //     title: "Exported!",
                        //     showConfirmButton: false,
                        //     timer: 1500
                        // });
                    },
                    error: function(xhr, status, error) {
                        console.log('Error exporting CSV:', error);
                    }
                });
            } else if (
                result.dismiss === Swal.DismissReason.cancel
            ) {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: "Cancelled!",
                    text: id_number + ', ' + name + ' has been saved',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        })
    });
});
</script>