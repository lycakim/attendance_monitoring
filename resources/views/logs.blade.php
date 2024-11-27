<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Logs') }}
            </h2>
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
                                        <th>Log Description</th>
                                        <th>By</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($logs as $record)
                                    <tr>
                                        <td>{{ $record->description }}</td>
                                        <td>{{ $record['created_by']['name'] ?? 'System' }}</td>
                                        <td>{{ $record->created_at->format("F j, Y, h:i A") }}
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
                    <h1 class="modal-title fs-5" id="modalLabel">Add New Student</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form">
                    <div class="modal-body overflow-y-auto" style="max-height: 500px;">
                        @csrf
                        <div class="mb-3 px-2">
                            <label for="id_number" class="form-label">ID Number</label>
                            <input type="text" class="form-control rounded" name="id_number" id="id_number"
                                autocomplete="off" placeholder="ID Number">
                        </div>
                        <div class="mb-3 px-2">
                            <label for="fullname" class="form-label">Full Name</label>
                            <input type="text" class="form-control rounded" name="fullname" id="fullname"
                                placeholder="Full name">
                        </div>
                        <div class="mb-3 px-2">
                            <label for="email" class="form-label">Email <span
                                    class="text-gray-500">(Optional)</span></label>
                            <input type="email" class="form-control rounded" name="email" id="email"
                                placeholder="Email Address">
                        </div>
                        <div class="mb-3 px-2">
                            <label for="program" class="form-label">Program</label>
                            <select class="form-select rounded @error('program') is-invalid @enderror" name="program"
                                id="program">
                                <option selected disabled>Select program</option>
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
                                <option selected disabled>Select year</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                            </select>
                        </div>
                        <div class="mb-3 px-2">
                            <label for="set" class="form-label">Set <span
                                    class="text-gray-500">(Optional)</span></label>
                            <input type="text" class="form-control rounded" name="set" id="set" placeholder="Set">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="create_btn">Create</button>
                        <button type="submit" class="btn btn-primary btn-update" id="update_btn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.querySelector('#form').addEventListener('submit', (e) => {
    e.preventDefault();
    var id_number = $('#id_number').val();
    var fullname = $('#fullname').val();
    var email = $('#email').val();
    var program = $('#program').val();
    var year = $('#year').val();
    var set = $('#set').val();
    $.ajax({
        type: "POST",
        url: "{{ route('student.store') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            id_number: id_number,
            fullname: fullname,
            email: email,
            program: program,
            year: year,
            set: set
        },
        success: function(response) {
            $('#staticBackdrop').modal('toggle');
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Student successfully added!',
                showConfirmButton: false,
                timer: 1500
            });
            setTimeout(function() {
                window.location.reload();
            }, 1500);
        },
        error: function(xhr, status, error) {
            var err = JSON.parse(xhr.responseText);
            if (err.errors.id_number) {
                $('#id_number').addClass('is-invalid');
            } else {
                $('#id_number').removeClass('is-invalid');
            }

            if (err.errors.fullname) {
                $('#fullname').addClass('is-invalid');
            } else {
                $('#fullname').removeClass('is-invalid');
            }

            if (err.errors.program) {
                $('#program').addClass('is-invalid');
            } else {
                $('#program').removeClass('is-invalid');
            }

            if (err.errors.year) {
                $('#year').addClass('is-invalid');
            } else {
                $('#year').removeClass('is-invalid');
            }
        }
    });
});

document.querySelector('#add').addEventListener('click', (e) => {
    e.preventDefault();
    $('.modal-title').text('Add New Student');
    $('#id_number').val();
    $('#fullname').val();
    $('#email').val();
    $('#program').val();
    $('#year').val();
    $('#set').val();
    $('#create_btn').show();
    $('#update_btn').hide();
});

$(document).ready(function() {
    var id = null;
    var name = null;
    var id_number = null;
    $('.btn-edit').click(function() {
        $('.modal-title').text('Edit Student');

        id = $(this).data('id');

        var url = "{{ route('student.get', 'item_id') }}";
        url = url.replace('item_id', id);

        $.ajax({
            type: "GET",
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#id_number').val(response.id_number);
                $('#fullname').val(response.name);
                $('#email').val(response.email);
                $('#program').val(response.program);
                $('#year').val(response.year);
                $('#set').val(response.set);
                $('#create_btn').hide();
                $('#update_btn').show();
            },
        });
    });

    $('.btn-update').click(function(e) {
        e.preventDefault();

        var url = "{{ route('student.update', 'item_id') }}";
        url = url.replace('item_id', id);

        var id_number = $('#id_number').val();
        var fullname = $('#fullname').val();
        var email = $('#email').val();
        var program = $('#program').val();
        var year = $('#year').val();
        var set = $('#set').val();

        $.ajax({
            type: "PUT",
            url: url,
            data: {
                id_number: id_number,
                fullname: fullname,
                email: email,
                program: program,
                year: year,
                set: set
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#staticBackdrop').modal('toggle');
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Student successfully updated!',
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
        name = $(this).data('name');
        id_number = $(this).data('id_number');

        var url = "{{ route('student.destroy', 'item_id') }}";
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
            text: id_number + ", " + name,
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
                            text: id_number + ', ' + name +
                                ' has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        id = null;
                        name = null;
                        id_number = null;
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
                    text: id_number + ', ' + name + ' has been saved',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        })
    });
});
</script>