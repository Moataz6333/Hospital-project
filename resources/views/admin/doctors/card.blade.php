<div class="card border border-secondary" style="width: 18rem;">
                <img src="{{ $doctor->profile ? Storage::disk('doctors')->url($doctor->profile) : asset('storage/profile.jfif') }}"
                    class="card-img-top"
                    style=" height:15rem;     object-fit: cover;
                      object-position: center;"
                    alt="...">
                <div class="card-body ">
                    <h5 class="card-title">{{ $doctor->user->name }} </h5>
                    <p class="card-text">
                        <strong>specialty</strong> : {{ $doctor->specialty }} <br>
                        <strong>clinic</strong> : {{ $doctor->clinic->name }} <br>
                        <strong>phone</strong> : {{ $doctor->phone }} <br>
                        <strong>Age</strong> : {{ Carbon\Carbon::parse($doctor->birthdate)->age }} <br>
                        <strong>Price</strong> : {{ $doctor->price }} <br>
                        <strong>Salary</strong> : {{ $doctor->salary }} <br>
                    </p>
                    <div class="d-flex w-100 justify-content-between">
                        <form action="{{ route('doctors.destroy', $doctor->id) }}" method="post"
                            onsubmit="return confirmDelete()">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>

                        <a href="{{ route('doctors.timeTable', $doctor->id) }} " class="btn btn-success">Time Table</a>
                        <a href="{{ route('doctors.edit', $doctor->id) }} " class="btn btn-primary">Edit</a>
                    </div>
                </div>
            </div>