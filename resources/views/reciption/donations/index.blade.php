@extends('layouts.recip')
@section('title')
    Donations
@endsection
@section('content')
    <div class="d-flex gap-2 my-3 align-items-center">
        <h2>
            <a href="{{ route('reception.index') }}" class="btn btn-dark"><span data-feather="arrow-left"></span></a>
            Donations
        </h2>
    </div>
    <hr>
    {{-- search --}}
    <div class="">
        <form action="{{ route('donations.search') }}" id="search-form" method="POST" class="row g-3 justify-content-center">
            @csrf
            <div class="col-7">
                <input type="text" class="form-control" required placeholder="Search by name or national id"
                    name="name" id="searchInput">
            </div>

        </form>
    </div>

    @if (count($donations) != 0)
        <table class="table table-striped my-4">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>
                        Name
                    </th>
                    <th>Phone</th>
                    <th>National_id</th>
                    <th>Value</th>
                    <th>Payment Method</th>
                    <th>Registration Method</th>
                    <th>Paid</th>
                    <th>Date</th>
                    <th>Show</th>
                </tr>
            </thead>
            <tbody id="tbody">
                @foreach ($donations as $donation)
                    <tr>
                        <th scope="row" class="align-content-center">
                            {{ $donation->id }}
                        </th>
                        <td>
                            {{ $donation->patient->name }}
                        </td>
                        <td>
                            {{ $donation->patient->phone }}
                        </td>
                        <td>
                            {{ $donation->patient->national_id }}
                        </td>


                        <td>
                            <strong>{{ $donation->value . ' ' . $donation->currency }} </strong>
                        </td>
                        <td>
                            {{ $donation->payment_method }}
                        </td>
                        <td>
                            {{ $donation->registeration_method }}
                        </td>
                        <td>
                            <strong class="text-{{ $donation->paid ? 'success' : 'danger' }}">
                                {{ $donation->paid ? 'Yes' : 'No' }}</strong>
                        </td>
                        <td>
                            <strong>
                                {{ date_create($donation->created_at)->format('d-m-Y h:i a') }}</strong>
                        </td>
                        <td>
                            <a href="{{ route('donations.show', $donation->id) }}" class="btn btn-primary">show</a>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    @else
        <h6 class="text-secondary text-center my-4">No donations !</h6>
    @endif

    <script src="{{asset('js/jquery-3.7.1.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                let query = $(this).val();

                if (query.length > 0) {
                    $.ajax({
                        url: "{{ route('donations.search') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            name: query
                        },
                        success: function(response) {
                            updateTable(response);
                        }
                    });
                } else {
                    // Fetch all donations when search is cleared
                    $.ajax({
                        url: "{{ route('donations.json') }}",
                        method: "GET",
                        success: function(response) {
                            updateTable(response);
                        }
                    });
                }
            });

            function updateTable(donations) {
                let tbody = $('#tbody');
                tbody.empty(); // Clear existing rows

                if (donations.length === 0) {
                    tbody.append('<tr><td colspan="6" class="text-center">No donations found</td></tr>');
                    return;
                }

                donations.forEach(function(donation) {
                    paid = `<strong class="text-danger">No</strong>`;
                    if (donation.paid) {
                        paid = `<strong class="text-success">Yes</strong>`;
                    }


                    let row = `
                <tr>
                     <th scope="row" class="align-content-center">
                           ${donation.id}
                        </th>
                    <td>${donation.patient.name}</td>
                    <td>${donation.patient.phone}</td>
                    <td>${donation.patient.national_id}</td>
                    <td><strong>${donation.value} ${donation.currency}</strong></td>
                    <td>${donation.payment_method}</td>
                    <td>${donation.registeration_method}</td>
                    <td>${paid}</td>
                    <td><strong>${formatCreatedAt(donation.created_at)}</strong></td>
                    <td><a href="/donations/${donation.id}" class="btn btn-primary">Show</a></td>
                </tr>
            `;
                    tbody.append(row);
                });
            }

            function formatCreatedAt(dateString) {
                const date = new Date(dateString);

                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0'); // months are 0-indexed
                const year = date.getFullYear();

                let hours = date.getHours();
                const minutes = String(date.getMinutes()).padStart(2, '0');

                const ampm = hours >= 12 ? 'pm' : 'am';
                hours = hours % 12 || 12; // convert 0 to 12 for 12-hour format
                const formattedHours = String(hours).padStart(2, '0');

                return `${day}-${month}-${year} ${formattedHours}:${minutes} ${ampm}`;
            }

            

        });
    </script>

@endsection
