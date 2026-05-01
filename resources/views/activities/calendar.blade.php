<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Interactive Calendar') }}
        </h2>
    </x-slot>

    <div class="py-12 relative">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative z-10">
                
                <div id="calendar"></div>

            </div>
        </div>
    </div>

    <div id="eventModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center z-50 transition-opacity">
        
        <div class="bg-white rounded-lg shadow-xl w-96 h-96 mx-4 overflow-hidden flex flex-col">
            
            <div class="bg-blue-600 px-4 py-3 flex justify-between items-center shrink-0">
                <h3 class="text-lg font-bold text-white truncate pr-2" id="modalTitle">Activity Title</h3>
                <button id="closeModalIcon" class="text-white hover:text-gray-200 text-2xl leading-none">&times;</button>
            </div>

            <div class="p-6 flex-1 overflow-y-auto flex flex-col justify-between">
                
                <div class="space-y-3 text-sm text-gray-700">
                    <p><strong class="text-gray-900">Category:</strong> <span id="modalCategory"></span></p>
                    <p><strong class="text-gray-900">Date:</strong> <span id="modalDate"></span></p>
                    <p><strong class="text-gray-900">Posted By:</strong> <span id="modalOwner"></span></p>
                    <hr class="my-2 border-gray-200">
                    <p class="text-gray-800 leading-relaxed" id="modalDescription"></p>
                </div>
                
                <div class="mt-4 pt-4 border-t text-right shrink-0">
                    <button id="closeModalBtn" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition w-full sm:w-auto">
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var modal = document.getElementById('eventModal');
            
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: @json($events), 
                eventColor: '#2563eb', // Tailwind blue-600
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                height: 650,
                
                // EVENT CLICK LOGIC
                eventClick: function(info) {
                    // Prevent the browser from jumping to the top of the page
                    info.jsEvent.preventDefault();

                    // 1. Get the custom data we passed from the controller
                    var props = info.event.extendedProps;

                    // 2. Inject the data into our HTML Modal
                    document.getElementById('modalTitle').innerText = info.event.title;
                    document.getElementById('modalCategory').innerText = props.category;
                    document.getElementById('modalDate').innerText = props.formatted_date;
                    document.getElementById('modalOwner').innerText = props.owner;
                    document.getElementById('modalDescription').innerText = props.description;

                    // 3. Un-hide the modal
                    modal.classList.remove('hidden');
                }
            });
            
            calendar.render();

            // LOGIC TO CLOSE THE MODAL
            function closeModal() {
                modal.classList.add('hidden');
            }

            document.getElementById('closeModalBtn').addEventListener('click', closeModal);
            document.getElementById('closeModalIcon').addEventListener('click', closeModal);
            
            // Close modal if user clicks outside of the box
            window.addEventListener('click', function(event) {
                if (event.target == modal) {
                    closeModal();
                }
            });
        });
    </script>
</x-app-layout>