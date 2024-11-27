<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-center">
            <h2 class=" font-semibold uppercase text-xl text-green-600 dark:text-gray-200 leading-tight">
                {{ __('Monitoring In Progress') }}
            </h2>
        </div>
    </x-slot>
    <div class="mt-4">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8 flex justify-between gap-2">
            <div class="bg-white w-full md:w-2/3 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-3 mt-4 h-96 max-h-96 text-gray-900 dark:text-gray-100">
                    <div class="card-body">
                        <div class="container">
                            <h2 class="fs-3 font-semibold uppercase bg-[#8fd1d0] text-center rounded-lg py-2"
                                id="event_name">
                                {{ session()->get('event_name') }}
                            </h2>
                            <div class="my-3 text-center">
                                <h1 class="text-6xl font-bold uppercase tracking-wide my-5" id="d_name">
                                    {{ session()->has('student_name') ? session()->get('student_name') : '---' }}
                                </h1>
                                <div class="row my-4 gap-2">
                                    <div class="col border shadow-sm p-4 rounded-lg">
                                        <label for="d_course" class="text-xl">Course</label>
                                        <p id="d_course" class="font-bold text-3xl">
                                            {{ session()->has('student_course') ? session()->get('student_course') : '---' }}
                                        </p>
                                    </div>
                                    <div class="col border shadow-sm p-4 rounded-lg">
                                        <label for="d_section" class="text-xl">Section</label>
                                        <p id="d_section" class="font-bold text-3xl">
                                            {{ session()->has('student_section') ? session()->get('student_section') : '---' }}
                                        </p>
                                    </div>
                                    <div class="col border shadow-sm p-4 rounded-lg">
                                        <label for="d_year" class="text-xl">Year</label>
                                        <p id="d_year" class="font-bold text-3xl">
                                            {{ session()->has('student_year') ? session()->get('student_year') : '---' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white w-full md:w-1/3 dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-2 text-gray-900 dark:text-gray-100">
                    <div class="relative overflow-x-auto overflow-y-auto h-96 max-h-96">
                        <table id="data-table"
                            class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs rounded-lg text-gray-700 uppercase fs-6 bg-gray-200 dark:bg-gray-700 dark:text-gray-400 sticky top-0">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        ID #
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Login Time
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monitorings as $record)
                                <tr class="border-b @if($record->remarks == 'late') bg-red-200 @endif dark:bg-gray-800">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $record->students->id_number }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ $record->students->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $record->created_at->format("F j, Y, h:i A") }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8 flex justify-between gap-2 mt-2">
            <div class="w-full md:w-2/3 md:flex gap-2">
                <div class="bg-white p-5 shadow-sm rounded-lg w-1/2">
                    <div class="text-center justify-content-center align-items-center align-self-center">
                        <h1 class="pb-2">Current Time</h1>
                        <h1 class="font-semibold fs-1 12-hr-time">--:--:--</h1>
                    </div>
                </div>
                <div class="bg-white p-5 shadow-sm rounded-lg w-2/5">
                    <div class="text-center justify-content-center align-items-center align-self-center">
                        <h1 class="pb-2">Time In</h1>
                        <h1 class="font-semibold fs-1">{{ session()->get('time_in') }}</h1>
                    </div>
                </div>
                <div class="bg-white p-5 shadow-sm rounded-lg w-2/5">
                    <div class="text-center justify-content-center align-items-center align-self-center">
                        <h1 class="pb-2">Time Out</h1>
                        <h1 class="font-semibold fs-1">{{ session()->get('time_out') }}</h1>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-1/3 bg-white p-2 shadow-sm rounded-lg">
                <div class="text-center justify-content-center align-items-center align-self-center m-4">
                    <input autofocus type="text" autocomplete="off" style="height: 60px; font-size: 20px;"
                        class="form-control rounded-lg" id="mon_id_number" placeholder="Enter ID Number here">
                    <button type="button" class="btn btn-danger btn-end-monitoring mt-2.5" id="end_monitoring">
                        End Monitoring
                    </button>
                </div>
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
                            window.location.replace('/events');
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
                    $('#data-table').load("{{ route('monitoring') }}" +
                        ' #data-table');
                    $('#mon_id_number').val("");
                    Swal.fire({
                        position: 'top-right',
                        icon: 'success',
                        title: response,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
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