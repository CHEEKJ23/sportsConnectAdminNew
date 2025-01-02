@extends('adminlte::page')

@section('title', 'Court Booking Calendar')

@section('content_header')
    <h1>Court Booking Calendar</h1>
@stop

@section('content')
<head>
    <!-- Bootstrap CSS -->
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    
    </head>
<!-- Button to trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal">
    Create Court Booking
</button>

<!-- Modal Structure -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingModalLabel">Create Court Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="bookingForm" action="{{ route('admin.bookings.create') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="user_id" class="form-label">User ID</label>
                        <input type="number" class="form-control" id="user_id" name="user_id" required>
                    </div>
                    <div class="mb-3">
                        <label for="sport_center_id" class="form-label">Sport Center</label>
                        <select class="form-control" id="sport_center_id" name="sport_center_id" required>
                            <option value="">Select Sport Center</option>
                            @foreach($sportCenters as $center)
                                <option value="{{ $center->id }}">{{ $center->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="court_type" class="form-label">Court Type</label>
                        <select class="form-control" id="court_type" name="court_type" required>
                            <option value="">Select Court Type</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="court_id" class="form-label">Court Number</label>
                        <select class="form-control" id="court_id" name="court_id" required>
                            <option value="">Select Court Number</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="startTime" class="form-label">Start Time</label>
                        <input type="time" class="form-control" id="startTime" name="startTime" required>
                    </div>
                    <div class="mb-3">
                        <label for="endTime" class="form-label">End Time</label>
                        <input type="time" class="form-control" id="endTime" name="endTime" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Booking</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Calendar Element -->
    <div id="calendar"></div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: @json($events),
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                }
            });

            calendar.render();
        });
    </script> --}}
    <script>
         document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: @json($events), // Ensure $events is passed from the controller
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                }
            });

            calendar.render();
        });
        $(document).ready(function() {
            // Set up CSRF token for AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
    
            $('#sport_center_id').change(function() {
                var sportCenterId = $(this).val();
                if (sportCenterId) {
                    $.ajax({
                        url: '/admin/sport-centers/' + sportCenterId + '/court-types',
                        type: 'GET',
                        success: function(data) {
                            $('#court_type').empty().append('<option value="">Select Court Type</option>');
                            $.each(data, function(index, type) {
                                $('#court_type').append('<option value="' + type + '">' + type + '</option>');
                            });
                        }
                    });
                }
            });
    
            $('#court_type').change(function() {
                var sportCenterId = $('#sport_center_id').val();
                var courtType = $(this).val();
                if (sportCenterId && courtType) {
                    $.ajax({
                        url: '/admin/sport-centers/' + sportCenterId + '/courts/' + courtType,
                        type: 'GET',
                        success: function(data) {
                            $('#court_id').empty().append('<option value="">Select Court Number</option>');
                            $.each(data, function(index, court) {
                                $('#court_id').append('<option value="' + court.id + '">' + court.number + '</option>');
                            });
                        }
                    });
                }
            });
    
            $('#bookingForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route('admin.bookings.create') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response.message);
                        $('#bookingForm')[0].reset();
                        // Optionally, update the calendar or display a success message
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseJSON.message);
                        // Optionally, log the error or display it on the page
                    }
                });
            });
        });
    </script>
@stop
