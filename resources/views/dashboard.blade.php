<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class=" py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="card-body">
                        <div id='fullcalendar'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
$(document).ready(function() {
    var calendarEl = document.getElementById('fullcalendar');
    var curYear = moment().format('YYYY');
    var curMonth = moment().format('MM');
    var calendarEvents = {
        id: 1,
        backgroundColor: 'rgba(1,104,250, .15)',
        borderColor: '#0168fa',
        events: []
    };
    var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: "prev,today,next",
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        editable: false,
        droppable: false,
        fixedWeekCount: true,
        initialView: 'dayGridMonth',
        timeZone: 'UTC',
        hiddenDays: [],
        navLinks: 'true',
        dayMaxEvents: 2,
        events: [],
        eventSources: [{
            url: "{{ route('events.show_all') }}",
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            color: 'yellow',
            textColor: 'black',
        }],
    });

    calendar.render();
});
</script>