@extends('layouts.recip')
@section('title')
    Reciptionist
@endsection
@section('content')
    <h3 class="my-3 text-center h3">-Hospital Clinics-</h3>
    {{-- search --}}
    <div class="my-3">
        <form action="{{ route('clinics.search') }}" id="search-form" method="POST" class="row g-3 justify-content-center">
            @csrf
            <div class="col-7">
                <input type="text" class="form-control" required placeholder="Search by name" name="name"
                    id="search-input">
            </div>
           
        </form>
    </div>

    <div class="clinics d-grid   align-content-center my-4" id="search-results"
        style="    grid-template-columns: 1fr 1fr 1fr; row-gap: 30px;
    column-gap: 20px; ">
        @php
            $colors = ['primary', 'success', 'warning', 'danger', 'info', 'secondary', 'dark'];
        @endphp
        @for ($i = 0; $i < count($clinics); $i++)
            <a href="{{ route('clinic.show',$clinics[$i]->id) }}" class="btn btn-{{ $colors[$i % 7] }} d-flex justify-content-center align-items-center"
                style="height: 65px">{{ $clinics[$i]->name }} </a>
        @endfor

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#search-input').on('keyup', function() {
                let query = $(this).val().trim(); // Get the input value and trim spaces
                let resultsContainer = $('#search-results');

                if (query.length > 1) { // Start searching after 2+ characters
                    $.ajax({
                        url: "{{ route('clinics.search') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            name: query
                        },
                        success: function(response) {
                            resultsContainer.empty(); // Clear previous results

                            if (response.length > 0) {
                                let colors = ['primary', 'success', 'warning', 'danger', 'info',
                                    'secondary', 'dark'
                                ];

                                response.forEach(function(clinic, index) {
                                    let colorClass = colors[index % colors
                                    .length]; // Cycle through colors
                                    let clinicUrl = `/clinic/show/${clinic.id}`;
                                    resultsContainer.append(
                                        `<a href="${clinicUrl}" class="btn btn-${colorClass} d-flex justify-content-center align-items-center"
                                        style="height: 65px">${clinic.name}</a>`
                                    );
                                });
                            } else {
                                resultsContainer.html(
                                    '<p class="text-danger text-center">No clinics found</p>'
                                    );
                            }
                        }
                    });
                } else {
                    // If the input is empty, reload all clinics
                    loadAllClinics();
                }
            });

            // Function to load all clinics again
            function loadAllClinics() {
                $.ajax({
                    url: "{{ route('clinics.json') }}", // Make sure this route returns all clinics
                    method: "GET",
                    success: function(response) {
                        let resultsContainer = $('#search-results');
                        resultsContainer.empty(); // Clear previous results

                        if (response.length > 0) {
                            let colors = ['primary', 'success', 'warning', 'danger', 'info',
                                'secondary', 'dark'
                            ];

                            response.forEach(function(clinic, index) {
                                let colorClass = colors[index % colors
                                .length]; // Cycle through colors
                                let clinicUrl = `/clinic/show/${clinic.id}`;
                                resultsContainer.append(
                                `<a href="${clinicUrl}" class="btn btn-${colorClass} d-flex justify-content-center align-items-center"
                                    style="height: 65px">${clinic.name}</a>`
                                );
                            });
                        }
                    }
                });
            }

            // Prevent form submission on enter key press
            $('#search-form').on('submit', function(e) {
                e.preventDefault();
            });

            // Load all clinics on page load
            loadAllClinics();
        });
    </script>
@endsection
