<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">
            {{ __('Interactive Calendar') }}
        </h2>
    </x-slot>

    <div class="pb-12 pt-4 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 md:p-8 relative z-10">
                
                <div id="calendar"></div>

            </div>
        </div>
    </div>

    <!-- MODAL -->
    <div id="eventModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden flex items-center justify-center z-50 transition-opacity">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden flex flex-col transform transition-all">
            
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center shrink-0">
                <h3 class="text-xl font-bold text-slate-800 truncate pr-4" id="modalTitle">Activity Title</h3>
                <button id="closeModalIcon" class="text-slate-400 hover:text-slate-600 transition text-2xl leading-none">&times;</button>
            </div>

            <div class="p-6 flex-1 overflow-y-auto flex flex-col justify-between">
                
                <div class="space-y-4 text-sm text-slate-600">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        <span id="modalCategory" class="font-medium"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span id="modalDate" class="font-medium"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span id="modalOwner" class="font-medium"></span>
                    </div>
                    
                    <div class="pt-4 border-t border-slate-100 mt-4">
                        <h4 class="text-[11px] uppercase tracking-[0.1em] font-bold text-slate-400 mb-2">Description</h4>
                        <p class="text-slate-700 leading-relaxed" id="modalDescription"></p>
                    </div>
                </div>
                
                <div class="mt-8 text-right shrink-0">
                    <button id="closeModalBtn" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 px-6 rounded-lg transition shadow-sm w-full sm:w-auto text-sm">
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>

    <style>
        /* Modern FullCalendar Overrides */
        .fc {
            font-family: inherit;
        }
        .fc-theme-standard th, .fc-theme-standard td, .fc-theme-standard .fc-scrollgrid {
            border-color: #f1f5f9; /* slate-100 */
        }
        .fc-col-header-cell {
            padding: 12px 0 !important;
            background-color: #f8fafc; /* slate-50 */
            color: #475569; /* slate-600 */
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #e2e8f0 !important; /* slate-200 */
        }
        .fc .fc-button-primary {
            background-color: #004cd4;
            border-color: #004cd4;
            border-radius: 0.5rem;
            font-weight: 600;
            text-transform: capitalize;
            padding: 0.4rem 1rem;
        }
        .fc .fc-button-primary:not(:disabled):active, 
        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background-color: #1e3a8a;
            border-color: #1e3a8a;
        }
        .fc .fc-button-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }
        .fc .fc-toolbar-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1e293b; /* slate-800 */
        }
        .fc-daygrid-day-number {
            color: #64748b; /* slate-500 */
            font-weight: 600;
            padding: 8px !important;
            text-decoration: none !important;
        }
        .fc-day-today {
            background-color: #eff6ff !important; /* blue-50 */
        }
        .fc-event {
            border-radius: 6px;
            padding: 3px 6px;
            font-size: 0.75rem;
            font-weight: 600;
            border: none;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            cursor: pointer;
            transition: transform 0.1s ease-in-out, box-shadow 0.1s ease-in-out;
        }
        .fc-event:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .fc-daygrid-dot-event {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .fc-daygrid-dot-event .fc-event-title {
            color: #334155;
            font-weight: 600;
        }
    </style>

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
                height: 'auto',
                contentHeight: 500,
                
                // EVENT CLICK LOGIC
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    var props = info.event.extendedProps;
                    document.getElementById('modalTitle').innerText = info.event.title;
                    document.getElementById('modalCategory').innerText = props.category;
                    document.getElementById('modalDate').innerText = props.formatted_date;
                    document.getElementById('modalOwner').innerText = props.owner;
                    document.getElementById('modalDescription').innerText = props.description;
                    modal.classList.remove('hidden');
                }
            });
            
            calendar.render();

            function closeModal() {
                modal.classList.add('hidden');
            }

            document.getElementById('closeModalBtn').addEventListener('click', closeModal);
            document.getElementById('closeModalIcon').addEventListener('click', closeModal);
            
            window.addEventListener('click', function(event) {
                if (event.target == modal) {
                    closeModal();
                }
            });
        });
    </script>
</x-app-layout>