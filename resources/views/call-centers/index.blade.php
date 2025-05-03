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
        Call-Center
    </title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/dashboard/">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    @vite(['resources/js/app.js'])
    <style>
        .active-li {
            background-color: rgb(125 203 255 / 50%);
        }

        .active-li:hover {
            background-color: rgb(125 203 255 / 90%)
        }

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

<body class=" bg-light">

    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 ">
        <a class="navbar-brand col-md-3 col-lg-3 me-0 px-3" href="{{ route('centers.index') }}">Call-Center </a>
        <div class="d-flex gap-3 p-2">
            <form class="d-flex" action="{{ route('logout') }}" method="post">
                @csrf
                <button class=" px-3 btn btn-outline-light">Log out <span data-feather="log-out"></span></button>
            </form>
            <button class="navbar-toggler d-md-none collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

    </header>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-4 overflow-auto   d-md-block bg-white sidebar collapse shadow">
                <div class="position-sticky pt-3">
                    <div class="p-2 border-bottom">
                        <h5 class="h5"> <i class="fa-solid fa-headset"></i> Chats</h5>
                    </div>
                    <ul id="chatsNav" class="nav flex-column-reverse list-group">
                        @foreach ($chats as $chat)
                            <li
                                class="list-group-item list-group-item-action @if (request()->route('id') == $chat->uuid) bg-dark-subtle @endif border-bottom d-flex flex-column justify-content-center align-items-center ">
                                <a href="{{ route('centers.chat', $chat->uuid) }}" class="nav-link">
                                    <div class="row w-100 p-2 m-0">
                                        {{-- image --}}
                                        <div class="col-3">
                                            <div class="w-100 border border-2 border-success"
                                                style="border-radius: 50%; overflow:hidden;">
                                                <img src="{{ asset('storage/profile.jfif') }}"
                                                    class="w-100 h-100 rounded-circle border border-2 border-white"
                                                    alt="user">
                                            </div>
                                        </div>
                                        {{-- text-content --}}
                                        <div class="col-8  d-flex flex-column justify-content-center">
                                            <h5><strong>{{ $chat->sender->name }} </strong></h5>
                                            @if ($chat->messages->last())
                                                <p class="text-secondary">{{ $chat->messages->last()->message }} </p>
                                            @endif
                                        </div>
                                        {{-- number --}}
                                        <div class="col-1 d-flex align-items-center">
                                            <div class="text-center  bg-info d-flex justify-content-center align-items-center"
                                                style="border-radius: 50%; width:20px; height:20px; padding:10px;">
                                                <span class="text-white">{{ count($chat->messages) }} </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end w-100">
                                        <p class="text-success p-0 me-2 mb-2">
                                            @if ($chat->messages->last())
                                                {{ Carbon\Carbon::create($chat->messages->last()->created_at)->diffForHumans() }}
                                            @endif
                                        </p>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </nav>

            <main class="col-md-8 ms-sm-auto  px-md-4" style="height: 90vh;">
                @include('inc.alerts')
            </main>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"
        integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous">
    </script>

    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete ?');
        }
    </script>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    {{-- pusher --}}
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            Pusher.logToConsole = false;
                var userId= {{auth()->user()->id}} ;
            window.Echo.channel(`new-chat${userId}`)
                .listen('.new.chat', (data) => {
                    let lastMessage = "";
                    let lastDate = "";
                    if (data.chat.messages[0] != null) {
                        lastMessage = data.chat.messages[0].message;
                        lastDate = timeAgo(data.chat.messages[0].created_at);
                    }
                        

                    $('#chatsNav').append(`
                            <li
                                class="list-group-item list-group-item-action  border-bottom d-flex flex-column justify-content-center align-items-center ">
                                <a href="chat/${data.chat.uuid}" }}" class="nav-link">
                                    <div class="row w-100 p-2 m-0">
                                        {{-- image --}}
                                        <div class="col-3">
                                            <div class="w-100 border border-2 border-success"
                                                style="border-radius: 50%; overflow:hidden;">
                                                <img src="{{ asset('storage/profile.jfif') }}"
                                                    class="w-100 h-100 rounded-circle border border-2 border-white"
                                                    alt="user">
                                            </div>
                                        </div>
                                        {{-- text-content --}}
                                        <div class="col-8  d-flex flex-column justify-content-center">
                                            <h5><strong>${ data.chat.sender.name } </strong></h5>
                                                <p class="text-secondary">${ lastMessage }  </p>
                                        </div>
                                        {{-- number --}}
                                        <div class="col-1 d-flex align-items-center">
                                            <div class="text-center  bg-info d-flex justify-content-center align-items-center"
                                                style="border-radius: 50%; width:20px; height:20px; padding:10px;">
                                                <span class="text-white">${ data.chat.messages.length } </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end w-100">
                                        <p class="text-success p-0 me-2 mb-2">
                                           ${ lastDate }
                                        </p>
                                    </div>
                                </a>
                            </li>
                    `);

                    function timeAgo(dateString) {
                        const now = new Date();
                        const date = new Date(dateString);
                        const secondsAgo = Math.floor((now - date) / 1000);

                        const rtf = new Intl.RelativeTimeFormat('en', {
                            numeric: 'auto'
                        });

                        const intervals = {
                            year: 31536000,
                            month: 2592000,
                            week: 604800,
                            day: 86400,
                            hour: 3600,
                            minute: 60,
                            second: 1,
                        };

                        for (const [unit, secondsInUnit] of Object.entries(intervals)) {
                            const delta = Math.floor(secondsAgo / secondsInUnit);
                            if (delta >= 1) {
                                return rtf.format(-delta, unit);
                            }
                        }

                        return 'just now';
                    }

                })
                .error(error => {
                    console.error("Subscription Error:", error);
                });


        });
    </script>
</body>

</html>
