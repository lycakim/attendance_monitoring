<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('List of Users') }}
            </h2>
            <button type="button" class="btn btn-primary" id="add" data-bs-toggle="modal" data-bs-target="#userModal">
                Add New User
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
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th data-orderable="false"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $record)
                                    <tr>
                                        <td>{{ $record->name }}</td>
                                        <td>{{ $record->email }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <button class="px-1 hover:text-green-700 btn-edit" id="edit"
                                                    data-id="{{ $record->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#userModal">
                                                    <svg xmlns=" http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                                    </svg>
                                                </button>
                                                @if($record->id != auth()->user()->id)
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
                                                @endif
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
    <div class="modal fade" id="userModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalLabel">Add New User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form">
                    <div class="modal-body overflow-y-auto" style="max-height: 500px;">
                        @csrf
                        <div class="mb-3 px-2">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control rounded" autocomplete="off" name="name" id="name"
                                placeholder="Full name">
                        </div>
                        <div class="mb-3 px-2">
                            <label for="email" class="form-label">Email <span
                                    class="text-gray-500">(Optional)</span></label>
                            <input type="email" class="form-control rounded" autocomplete="off" name="email" id="email"
                                placeholder="Email Address">
                        </div>
                        <div class="mb-3 px-2">
                            <label class="password">Password</label>
                            <input type="password" class="form-control rounded" autocomplete="off" name="password"
                                id="password" placeholder="Password">
                        </div>
                        <div class="mb-3 px-2">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select rounded @error('role') is-invalid @enderror" name="role"
                                id="role">
                                <option selected disabled>Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="create_user_btn">Create</button>
                        <button type="submit" class="btn btn-primary btn-update" id="update_user_btn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.querySelector('#form').addEventListener('submit', (e) => {
    e.preventDefault();
    var name = $('#name').val();
    var email = $('#email').val();
    var password = $('#password').val();
    var role = $('#role').val();
    $.ajax({
        type: "POST",
        url: "{{ route('user.store') }}",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            name: name,
            email: email,
            password: password,
            role: role,
        },
        success: function(response) {
            $('#userModal').modal('toggle');
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'User successfully added!',
                showConfirmButton: false,
                timer: 1500
            });
            setTimeout(function() {
                window.location.reload();
            }, 1500);
        },
        error: function(xhr, status, error) {
            var err = JSON.parse(xhr.responseText);
            if (err.errors.name) {
                $('#name').addClass('is-invalid');
            } else {
                $('#name').removeClass('is-invalid');
            }

            if (err.errors.name) {
                $('#email').addClass('is-invalid');
            } else {
                $('#email').removeClass('is-invalid');
            }

            if (err.errors.password) {
                $('#password').addClass('is-invalid');
            } else {
                $('#password').removeClass('is-invalid');
            }

            if (err.errors.role) {
                $('#role').addClass('is-invalid');
            } else {
                $('#role').removeClass('is-invalid');
            }
        }
    });
});

document.querySelector('#add').addEventListener('click', (e) => {
    e.preventDefault();
    $('.modal-title').text('Add New User');
    $('#id_number').val();
    $('#fullname').val();
    $('#email').val();
    $('#program').val();
    $('#year').val();
    $('#set').val();
    $('#create_user_btn').show();
    $('#update_user_btn').hide();
});

$(document).ready(function() {
    var id = null;
    var name = null;
    $('.btn-edit').click(function() {
        $('.modal-title').text('Edit User');

        id = $(this).data('id');

        var url = "{{ route('users.get', 'id') }}";
        url = url.replace('id', id);

        $.ajax({
            type: "GET",
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#name').val(response.name);
                $('#email').val(response.email);
                $('#role').val(response.role);
                $('#create_user_btn').hide();
                $('#update_user_btn').show();
            },
        });
    });

    $('.btn-update').click(function(e) {
        e.preventDefault();

        var url = "{{ route('user.update', 'id') }}";
        url = url.replace('id', id);

        var name = $('#name').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var role = $('#role').val();

        $.ajax({
            type: "PUT",
            url: url,
            data: {
                name: name,
                email: email,
                password: password,
                role: role,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#userModal').modal('toggle');
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Updated user details successfully',
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

        var url = "{{ route('user.destroy', 'id') }}";
        url = url.replace('id', id);

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger me-2'
            },
            buttonsStyling: false,
        })

        swalWithBootstrapButtons.fire({
            title: 'You are about to delete',
            text: 'User:' + name,
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
                            text: 'User: ' + name +
                                ' has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        id = null;
                        name = null;
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