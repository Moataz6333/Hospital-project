<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Login </title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
</head>
<body>

<div class="container">
    @if ($errors->any())
    <div class="alert alert-danger mt-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="row justify-content-center">
       
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-header text-center">
                    <h4>Login</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('login')}}">
                        @csrf
                        <!-- Email Input -->
                        <div class="form-group my-3">
                            <label for="email ">Email address</label>
                            <input type="email" class="form-control mt-2" id="email" name="email" placeholder="Enter email" required>
                        </div>

                        <!-- Password Input -->
                        <div class="form-group my-3">
                            <label for="password ">Password</label>
                            <input type="password" class="form-control mt-2" id="password" name="password" placeholder="Password" required>
                        </div>

                        <!-- Remember Me Checkbox -->
                        <div class="form-group form-check my-3">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember Me</label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary btn-block my-3">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


</body>
</html>
