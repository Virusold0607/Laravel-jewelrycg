<x-app-layout page-title="Edit Password">
<div class="container">
    <div class="row">
        <div class="w-20 py-9">
            <nav class="navbar bg-light navbar-light">
                <div class="container-fluid">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == "account" ? "active" : "" }}" href="/user/{{Auth::id()}}?tab=account">Account</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == "security" ? "active" : "" }}" href="{{route('user.update.password')}}">Security</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == "address" ? "active" : "" }}" href="/user/{{Auth::id()}}?tab=address">Address</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-8 py-9 mx-auto">
            
            @if (session('success'))
                <h4 class="text-success mt-3">
                    {{session('success')}}
                </h4>
            @endif
            <form action="{{route('user.update.password')}}" method="post">
                @csrf
                @method('patch')
                <div class="card">
                    <div class="card-header">Change Password</div>
                    <div class="card-body">
                        @include('includes.validation-form')
                        <label for="old_password">Current Password:</label>
                        <input type="password" name="old_password" id="old_password" class="form-control">
                        <br>
                        <label for="new_password">New Password:</label>
                        <input type="password" name="new_password" id="new_password" class="form-control">
                        <br>
                        <label for="new_password_confirmation">Confirm New Password:</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control">
                        <br>
                        <div class="d-block">
                            <button type="submit" class="btn btn-primary">Edit Password</button>
                        </div>
                    </div>
                </div>
            </form>
            <form action="{{ route('user.disable') }}" method="post" class="d-inline">
                @csrf
                @method('post')
                <div class="card">
                    <div class="card-header">Deactive Account</div>
                    <div class="card-body">
                        <button type="submit" class="btn btn-outline-danger">
                            Deactive Account
                        </button>
                    </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>
