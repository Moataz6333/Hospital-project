<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @yield('title')
    </title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/dashboard/">



    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    @vite(['resources/js/app.js'])
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>


    <!-- Custom styles for this template -->
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
</head>

<body>

    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="{{ route('dashboard') }}">Hospital </a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

    </header>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link @if(Route::is('dashboard')) active @endif" aria-current="page" href="{{ route('dashboard') }}">
                                <span data-feather="hash"></span>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Route::is('hospital')) active @endif " href="{{ route('hospital') }}">
                                <span data-feather="home"></span>
                                Hospital
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Route::is('clinics.index') ||Route::is('clinics.edit') ) active @endif " href="{{ route('clinics.index') }}">
                                <span data-feather="layers"></span>
                                Clinics
                            </a>
                        </li>
                       
                        <li class="nav-item">
                            <a class="nav-link @if(Route::is('doctors.index') ||Route::is('doctors.create') ||Route::is('doctors.edit')  ||Route::is('doctors.timeTable')  ||Route::is('doctors.archive') ) active @endif" data-bs-toggle="collapse" href="#doctors" role="button"
                                aria-expanded="false" aria-controls="doctors">
                                <span data-feather="user"></span>
                                Doctors
                            </a>
                            <div class="collapse" id="doctors">
                                <ul class="nav flex-column ">
                                    <li class="nav-item ms-2">
                                        <a class="nav-link" href="{{ route('doctors.index') }}">
                                            <span data-feather="hash"></span>
                                            all doctors
                                        </a>
                                    </li>
                                    <li class="nav-item ms-2">
                                        <a class="nav-link" href="{{ route('doctors.create') }}">
                                            <span data-feather="user-plus"></span>
                                            add doctor
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(Route::is('employees.index') ||Route::is('employees.show') ||Route::is('employees.create') ||Route::is('employees.salaries') ) active @endif" data-bs-toggle="collapse" href="#emps" role="button"
                                aria-expanded="false" aria-controls="doctors">
                                <span data-feather="users"></span>
                                Employees
                            </a>
                            <div class="collapse" id="emps">
                                <ul class="nav flex-column ">
                                    <li class="nav-item ms-2">
                                        <a class="nav-link" href="{{ route('employees.index') }}">
                                            <span data-feather="hash"></span>
                                            all employees
                                        </a>
                                    </li>
                                    <li class="nav-item ms-2">
                                        <a class="nav-link" href="{{ route('employees.create') }}">
                                            <span data-feather="user-plus"></span>
                                            add employee
                                        </a>
                                    </li>
                                    <li class="nav-item ms-2">
                                        <a class="nav-link" href="{{ route('employees.salaries') }}">
                                            <span data-feather="alert-circle"></span>
                                            Salaries
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link @if(Route::is('reports.index')) active @endif" href="{{route('reports.index')}}">
                                <span data-feather="bar-chart-2"></span>
                                Reports
                            </a>
                        </li>

                    </ul>
                    <hr>
                    <form action="{{ route('logout') }}" method="post">
                        @csrf


                        <button class="nav-link px-3">Log out <span data-feather="log-out"></span></button>
                    </form>

                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

                @yield('content')
            </main>
        </div>
    </div>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"
        integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous">
    </script>
    
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete ?');
        }
    </script>
   
</body>

</html>
