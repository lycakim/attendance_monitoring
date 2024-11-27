<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('List of Students') }}
            </h2>
            <button type="button" class="btn btn-primary" id="add" data-bs-toggle="modal"
                data-bs-target="#staticBackdrop">
                Add New Student
            </button>
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
                                        <th>ID Num</th>
                                        <th>Name</th>
                                        <th>Program</th>
                                        <th>Year</th>
                                        <th>Section</th>
                                        <th data-orderable="false"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $record)
                                    <tr>
                                        <td>{{ $record->id_number }}</td>
                                        <td>{{ $record->name }}</td>
                                        <td>{{ $record->program }}</td>
                                        <td>{{ $record->year }}</td>
                                        <td>{{ $record->set }}</td>
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
                                                <button class="px-1 hover:text-yellow-700 btn-view" id="view"
                                                    data-id="{{ $record->id }}" data-name="{{ $record->name }}"
                                                    data-id_number="{{ $record->id_number }}" data-bs-toggle="modal"
                                                    data-bs-target="#studentInfoModal">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                    </svg>
                                                </button>
                                                <button class="px-1 hover:text-red-500 btn-delete" id="delete"
                                                    data-id="{{ $record->id }}" data-name="{{ $record->name }}"
                                                    data-id_number="{{ $record->id_number }}">
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
                            <label for="course" class="form-label">Course </label>
                            <input type="text" class="form-control rounded" name="course" id="course"
                                placeholder="Course">
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
                            <label for="set" class="form-label">Section <span
                                    class="text-gray-500">(Optional)</span></label>
                            <input type="text" class="form-control rounded" name="set" id="set" placeholder="Section">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="create_student_btn">Create</button>
                        <button type="submit" class="btn btn-primary btn-update" id="update_student_btn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="studentInfoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="studentInfoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title-student fs-5" id="modalLabel">Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form">
                    <div class="modal-body overflow-y-auto" style="max-height: 500px;">
                        @csrf
                        <div class="mb-3 px-2 row">
                            <div class="col">
                                <label for="d_name" class="text-sm">Name</label>
                                <p id="d_name" class="font-bold">Lyca Ubay</p>
                            </div>
                            <div class="col">
                                <label for="d_course" class="text-sm">Course</label>
                                <p id="d_course" class="font-bold">BSIT</p>
                            </div>
                            <div class="col">
                                <label for="d_year" class="text-sm">Year</label>
                                <p id="d_year" class="font-bold">1st Year</p>
                            </div>
                            <div class="col">
                                <label for="d_section" class="text-sm">Section</label>
                                <p id="d_section" class="font-bold">1</p>
                            </div>
                        </div>
                        <div class="mb-3 px-2 py-2 row border-t">
                            <div class="col-12 relative overflow-x-auto overflow-y-auto max-h-96">
                                <table class="detailTable"
                                    class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead
                                        class="text-gray-700 uppercase dark:bg-gray-700 dark:text-gray-400 sticky top-0">
                                        <tr>
                                            <th scope="col" class="w-1/2 py-3">
                                                Events
                                            </th>
                                            <th scope="col" class="py-3">
                                                Consequence (Community Service)
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <div class="bg-yellow-200 p-2 mb-2 flex">
                                    <h1 style="width: 41.3%;">Total</h1>
                                    <p id="totalcons" class="font-bold">0</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-close-modal"
                                    data-bs-dismiss="modal">Close</button>
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
    var course = $('#course').val();
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
            course: course,
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
    $('#course').val();
    $('#year').val();
    $('#set').val();
    $('#create_student_btn').show();
    $('#update_student_btn').hide();
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
                $('#course').val(response.course);
                $('#program').val(response.program);
                $('#year').val(response.year);
                $('#set').val(response.set);
                $('#create_student_btn').hide();
                $('#update_student_btn').show();
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
        var course = $('#course').val();
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
                course: course,
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

    $('.btn-close-modal').click(function() {
        $(".detailTable tbody tr").empty();
    });

    $('.btn-close').click(function() {
        $(".detailTable tbody tr").empty();
    });

    $('.btn-view').click(function() {
        id = $(this).data('id');

        var url = "{{ route('student.info', 'item_id') }}";
        url = url.replace('item_id', id);

        $.ajax({
            type: "GET",
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#d_name').text(response.student_data.name);
                $('#d_course').text(response.student_data.course);
                $('#d_year').text(response.student_data.year);
                $('#d_section').text(response.student_data.set);
                $('#totalcons').text(response.mon_data.total);
                for (let i = 0; i < response.mon_data.toString().length; ++i) {
                    $('.detailTable').find('tbody')
                        .append($('<tr class="eventTr">')
                            .append($(
                                    '<th scope="row" class="font-medium pb-2 text-gray-900 whitespace-nowrap dark:text-white">'
                                )
                                .text(response.mon_data[i].event_name))
                            .append($('<td scope="row">')
                                .text(response.mon_data[i].consequence +
                                    ' (' + response.mon_data[i].cons_remarks + ')'
                                )));
                };
                $('#totalcons').text(response.mon_data.total);
                id = null;
                name = null;
                id_number = null;
            },
        });
    });
});
</script>