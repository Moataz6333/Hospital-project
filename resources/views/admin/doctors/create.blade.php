@extends('layouts.main')
@section('title')
    Doctors
@endsection
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Add New Doctors</h1>
    </div>
    @if (session('success'))
        <div class="w-100 my-2">
            <p class="text-center text-success">
                {{ session('success') }}
            </p>
        </div>
    @endif

    <form action="{{ route('doctors.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col">
                <label for="name" class="form-label">Name</label>
                @error('name')
                    <p class="text-danger "><small>{{ $message }}</small> </p>
                @enderror
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col">
                <label for="phone" class="form-label">Email</label>
                @error('email')
                    <p class="text-danger "><small>{{ $message }}</small> </p>
                @enderror
                <input type="email" class="form-control" name="email">
            </div>

        </div>
        <div class="row ">
            <div class="col">
                <label for="password" class="form-label">Password</label>
                @error('password')
                    <p class="text-danger "><small>{{ $message }}</small> </p>
                @enderror
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="col">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                @error('password_confirmation')
                    <p class="text-danger "><small>{{ $message }}</small> </p>
                @enderror
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

        </div>
        <div class="row">

            <div class="col">
                <label for="phone" class="form-label">phone</label>
                @error('phone')
                    <p class="text-danger "><small>{{ $message }}</small> </p>
                @enderror
                <input type="text" class="form-control" name="phone">
            </div>
            <div class="col">
                <label for="formFile" class="form-label">Profile Photo</label>
                <input class="form-control" type="file" id="formFile" name="profile">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <label for="national_id" class="form-label">National id</label>
                @error('national_id')
                    <p class="text-danger "><small>{{ $message }}</small> </p>
                @enderror
                <input type="text" name="national_id" class="form-control">
            </div>
            <div class="col">
                <label for="birthdate" class="form-label">birthdate</label>
                @error('birthdate')
                    <p class="text-danger "><small>{{ $message }}</small> </p>
                @enderror
                <input type="date" class="form-control" name="birthdate">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <label for="national_id" class="form-label"> specialty</label>
                @error('specialty')
                    <p class="text-danger "><small>{{ $message }}</small> </p>
                @enderror
                <select class="form-select" name="specialty">
                    <option value="" selected disabled>Choose a Specialty - اختر التخصص</option>
                    <option value="Internal Medicine">Internal Medicine - الطب الباطني</option>
                    <option value="Dentistry">Dentistry - طب الأسنان</option>
                    <option value="Pediatrics">Pediatrics - طب الأطفال</option>
                    <option value="Dermatology">Dermatology - الأمراض الجلدية</option>
                    <option value="Ophthalmology">Ophthalmology - طب العيون</option>
                    <option value="Orthopedics">Orthopedics - جراحة العظام</option>
                    <option value="Cardiology">Cardiology - أمراض القلب</option>
                    <option value="Neurology">Neurology - طب الأعصاب</option>
                    <option value="Psychiatry">Psychiatry - الطب النفسي</option>
                    <option value="ENT">ENT - الأنف والأذن والحنجرة</option>
                    <option value="Urology">Urology - طب المسالك البولية</option>
                    <option value="Gynecology and Obstetrics">Gynecology and Obstetrics - طب النساء والتوليد</option>
                    <option value="Endocrinology">Endocrinology - طب الغدد الصماء</option>
                    <option value="Gastroenterology">Gastroenterology - طب الجهاز الهضمي</option>
                    <option value="Pulmonology">Pulmonology - طب الأمراض الصدرية</option>
                    <option value="Nephrology">Nephrology - طب أمراض الكلى</option>
                    <option value="Rheumatology">Rheumatology - أمراض الروماتيزم</option>
                    <option value="Oncology">Oncology - طب الأورام</option>
                    <option value="Hematology">Hematology - أمراض الدم</option>
                    <option value="Allergy and Immunology">Allergy and Immunology - الحساسية والمناعة</option>
                    <option value="Plastic Surgery">Plastic Surgery - جراحة التجميل</option>
                    <option value="General Surgery">General Surgery - الجراحة العامة</option>
                    <option value="Physical Therapy and Rehabilitation">Physical Therapy and Rehabilitation - العلاج الطبيعي وإعادة التأهيل</option>
                </select>
                
            </div>
            <div class="col">
                <label for="birthdate" class="form-label">experiance</label>
                @error('experiance')
                    <p class="text-danger "><small>{{ $message }}</small> </p>
                @enderror
                <textarea class="form-control" name="experiance"></textarea>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <label for="national_id" class="form-label"> Clinic</label>
                @error('clinic_id')
                    <p class="text-danger "><small>{{ $message }}</small> </p>
                @enderror
                <select class="form-select" name="clinic_id">
                    <option value="" selected disabled>Choose a Clinic</option>
                    @foreach ($clinics as $clinic)
                        <option value={{ $clinic->id }}>{{ $clinic->name }}</option>
                    @endforeach

                </select>
            </div>


            <div class="col">
                <label for="salary" class="form-label">salary</label>
                @error('salary')
                    <p class="text-danger "><small>{{ $message }}</small> </p>
                @enderror
                <input type="text" name="salary" class="form-control">
            </div>
            <div class="col">
                <label for="price" class="form-label">price</label>
                @error('price')
                    <p class="text-danger "><small>{{ $message }}</small> </p>
                @enderror
                <input type="text" name="price" class="form-control">
            </div>

        </div>

        <div class="mt-3 d-flex w-100 justify-content-end">
            <button class="btn btn-primary ">Create</button>
        </div>
    </form>
@endsection
