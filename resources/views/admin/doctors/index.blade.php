@extends('layouts.main')
@section('title')
    Doctors
@endsection
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"> Doctors</h1>
    </div>
    @if (session('success'))
        <div class="w-100 my-2">
            <p class="text-center text-success">
                {{ session('success') }}
            </p>
        </div>
    @endif
    {{-- search --}}
    <div class="my-3">
        <form id="search-form" action="{{ route('doctors.search') }}" method="POST" class="row g-3 justify-content-center">
            @csrf
            <div class="col-7">
                <input type="text" id="search-input" class="form-control" required placeholder="Search by name"
                    name="name">
            </div>
        </form>
    </div>

    <div class="w-100 my-3">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Clinic</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Price</th>
                    <th scope="col">Salary</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Time Table</th>
                    <th scope="col">Archive</th>
                    <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody id="doctors-table-body">
                @foreach ($doctors as $doctor)
                    <tr>
                        <td>
                            <div class="small-profile">
                                <img src="{{ $doctor->profile ? Storage::disk('doctors')->url($doctor->profile) : asset('storage/profile.jfif') }}"
                                    alt="">

                            </div>
                        </td>
                        <td style="align-content: center;">{{ $doctor->user->name }} </td>
                        <td style="align-content: center;">{{ $doctor->clinic->name }} </td>
                        <td style="align-content: center;">{{ $doctor->phone }} </td>
                        <td style="align-content: center;">{{ $doctor->price }} </td>
                        <td style="align-content: center;">{{ $doctor->salary }} </td>
                        <td style="align-content: center;"> <a href="{{ route('doctors.edit', $doctor->id) }}"
                                class="btn btn-primary">Edit</a> </td>
                        <td style="align-content: center;"> <a href="{{ route('doctors.timeTable', $doctor->id) }}"
                                class="btn btn-success">TimeTable</a> </td>
                        <td style="align-content: center;"> <a href="{{route('doctors.archive',$doctor->id)}}"
                                class="btn btn-dark">Archive</a> </td>
                        <td style="align-content: center;">
                            <form action="{{ route('doctors.destroy', $doctor->id) }}" method="post"
                                onsubmit="return confirmDelete()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>

                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#search-input').on('keyup', function() {
                let query = $(this).val();

                if (query.length > 0) {
                    $.ajax({
                        url: "{{ route('doctors.search') }}",
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
                    // Fetch all doctors when search is cleared
                    $.ajax({
                        url: "{{ route('doctors.json') }}",
                        method: "GET",
                        success: function(response) {
                            updateTable(response);
                        }
                    });
                }
            });

            function updateTable(doctors) {
                let tbody = $('#doctors-table-body');
                tbody.empty(); // Clear existing rows

                if (doctors.length === 0) {
                    tbody.append('<tr><td colspan="9" class="text-center">No doctors found</td></tr>');
                    return;
                }

                doctors.forEach(function(doctor) {
                    let imageUrl = doctor.profile ?
                        "{{ Storage::disk('doctors')->url('') }}" + doctor.profile :
                        "{{ asset('storage/profile.jfif') }}";

                    let row = `
                    <tr>
                        <td><div class="small-profile"><img src="${imageUrl}" alt=""></div></td>
                        <td>${doctor.user.name}</td>
                        <td>${doctor.clinic.name}</td>
                        <td>${doctor.phone}</td>
                        <td>${doctor.price}</td>
                        <td>${doctor.salary}</td>
                        <td><a href="/doctors/${doctor.id}/edit" class="btn btn-primary">Edit</a></td>
                        <td><a href="/doctors/${doctor.id}/timetable" class="btn btn-success">TimeTable</a></td>
                          <td style="align-content: center;"> <a href="/doctors/archive/${doctor.id}"
                                class="btn btn-dark">Archive</a> </td>
                        <td>
                            <form action="/doctors/${doctor.id}" method="post" onsubmit="return confirmDelete()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                `;
                    tbody.append(row);
                });
            }
        });
    </script>
@endsection
