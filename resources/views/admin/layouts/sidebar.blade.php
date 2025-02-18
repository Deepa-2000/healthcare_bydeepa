<div class="sidebar">
    <div class="menu-btn"><i class="bi bi-list"></i></div>
    <div class="head">
        <div class="user-img">
            <img src="{{ asset('images/logo.svg') }}" alt="">
        </div>
    </div>
    <div class="nav">
        <div class="menu w-100">
            <ul class="p-0">
                <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
                    <a href="{{url('admin_panel')}}">
                        <i class="bi bi-house"></i><span class="text">Dashboard</span>
                    </a>
                </li>
                <li class="{{ request()->is('user_list') ? 'active' : '' }}">
                    <a href="{{url('doctor_index')}}">
                        <i class="bi bi-people"></i><span class="text">Doctors</span>
                    </a>
                </li>
                <li class="{{ request()->is('user_list') ? 'active' : '' }}">
                <a href="{{url('patient_index')}}">
                <i class="bi bi-people"></i><span class="text">Patients</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>