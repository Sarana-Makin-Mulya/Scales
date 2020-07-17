<li class="nav-item dropdown user-menu">
    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
        <img src="{{ asset('images/user-flat.png') }}" class="user-image img-circle elevation-2 mr-2" alt="User Image">
        <span class="d-md-inline">
            {!! getAuthDescription(Auth::user()) !!}
        </span>
    </a>

    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <!-- User image -->
        <li class="user-header bg-info">
            <img src="{{ asset('images/user-flat.png') }}" class="img-circle elevation-2" alt="User Image">
            <p>
                <div>{!! Auth::user()->employee_nik !!}</div>
                <div>{!! getEmployeeFullName(Auth::user()->employee_nik) !!} - {!! getHREmployeeDepartmentName(Auth::user()->employee_nik) !!}</>
            </p>
        </li>

        <!-- Menu Body -->
        <li class="user-body">
            <div class="row">
                <div class="col-6 text-center">
                    <a href="#" class="btn btn-link disabled"><i class="fas fa-camera"></i> Ganti Foto Profil</a>
                </div>
                <div class="col-6 text-center">
                    <a href="#" class="btn btn-link disabled"><i class="fas fa-cog"></i> Pengaturan</a>
                </div>
            </div>
        </li>

        <!-- Menu Footer-->
        <li class="user-footer">
            <a
                href="#"
                class="btn btn-success btn-flat"
            >
                <i class="fas fa-user"></i>
                {{ __('Profile') }}
            </a>

            <a
                href="{{ route('logout') }}"
                class="btn btn-default btn-flat float-right"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            >
                <i class="fas fa-sign-out-alt"></i> {{ __('Keluar') }}
            </a>
        </li>
    </ul>
</li>

{{-- Logout form --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
