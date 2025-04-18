@extends('layouts.main')
@section('title')
    Employees
@endsection
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">All Employee</h1>
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
        <form action="{{ route('employees.search') }}" method="POST" class="row g-3 justify-content-center">
            @csrf
            <div class="col-7">
                <input type="text" id="searchInput" class="form-control" required placeholder="Search by name"
                    name="name">
            </div>

        </form>
    </div>
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Role</th>
                <th scope="col">Gender</th>
                <th scope="col">Salary</th>
                <th scope="col">Details</th>
            </tr>
        </thead>
        <tbody id="employeeTable">
            @foreach ($employees as $emp)
                <tr>
                    <td>
                        <div class="small-profile">
                            <img src="{{ $emp->profile ? Storage::disk('employees')->url($emp->profile) : asset('storage/profile.jfif') }}"
                                alt="">

                        </div>
                    </td>
                    <td style="align-content: center;">{{ $emp->user->name }} </td>
                    <td style="align-content: center;">{{ $emp->user->role }} </td>
                    <td style="align-content: center;">{{ $emp->gender }} </td>
                    <td style="align-content: center;">{{ $emp->salary }} </td>
                    <td style="align-content: center;"> <a href="{{ route('employees.show', $emp->id) }}"
                            class="btn btn-primary">show</a> </td>

                </tr>
            @endforeach

        </tbody>
    </table>
    <script src="{{asset('js/jquery-3.7.1.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                let query = $(this).val();

                if (query.length > 0) {
                    $.ajax({
                        url: "{{ route('employees.search') }}",
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
                    // Fetch all employees when search is cleared
                    $.ajax({
                        url: "{{ route('employees.json') }}",
                        method: "GET",
                        success: function(response) {
                            updateTable(response);
                        }
                    });
                }
            });

            function updateTable(employees) {
                let tbody = $('#employeeTable');
                tbody.empty(); // Clear existing rows

                if (employees.length === 0) {
                    tbody.append('<tr><td colspan="6" class="text-center">No employees found</td></tr>');
                    return;
                }

                employees.forEach(function(emp) {
                    let imageUrl = emp.profile ?
                        "{{ Storage::disk('employees')->url('') }}" + emp.profile :
                        "{{ asset('storage/profile.jfif') }}";

                    let row = `
                <tr>
                    <td><div class="small-profile"><img src="${imageUrl}" alt=""></div></td>
                    <td>${emp.user.name}</td>
                    <td>${emp.user.role}</td>
                    <td>${emp.gender}</td>
                    <td>${emp.salary}</td>
                    <td><a href="/employees/${emp.id}" class="btn btn-primary">Show</a></td>
                </tr>
            `;
                    tbody.append(row);
                });
            }
        });
    </script>
@endsection
