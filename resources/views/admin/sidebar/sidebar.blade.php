
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main</span>
                </li>
                <li class=" submenu">
                    <a href="#" class="">
                        <i class="la la-dashboard"></i>
                        <span> Dashboard</span> <span class="menu-arrow"></span>
                    </a>
                    <ul style="{{ request()->is('/*') ? 'display: block;' : 'display: none;' }}">
                        <li><a class="" href="">Admin Dashboard</a></li>
                        <li><a class="" href="">Employee Dashboard</a></li>
                    </ul>
                </li>
                @if (Auth::user()->role_name=='Admin')
                    <li class="menu-title"> <span>Authentication</span> </li>
                    <li class="{ submenu">
                        <a href="#" class="">
                            <i class="la la-user-secret"></i> <span> User Controller</span> <span class="menu-arrow"></span>
                        </a>
                        <ul style="{{ request()->is('/*') ? 'display: block;' : 'display: none;' }}">
                            <li><a class="" href="">All User</a></li>
                            <li><a class="" href="">Activity Log</a></li>
                            <li><a class="" href="">Activity User</a></li>
                        </ul>
                    </li>
                @endif
                <li class="menu-title"> <span>Employees</span> </li>
                <li class=" submenu">
                    <a href="#" class="">
                        <i class="la la-user"></i> <span> Employees</span> <span class="menu-arrow"></span>
                    </a>
                    <ul style="{{ request()->is('/*') ? 'display: block;' : 'display: none;' }}">
                        <li><a class="" href="">All Employees</a></li>
                        <li><a class="" href="">Holidays</a></li>
                        <li><a class="" href="">Leaves (Admin) 
                            <span class="badge badge-pill bg-primary float-right">1</span></a>
                        </li>
                        <li><a class="" href="">Leaves (Employee)</a></li>
                        <li><a class="" href="">Leave Settings</a></li>
                        <li><a class="" href="">Attendance (Admin)</a></li>
                        
                    </ul>
                </li>
                <li class="menu-title"> <span>Administration</span> </li>
                <li class=" submenu">
                    <a href="#" class=""><i class="la la-pie-chart"></i>
                    <span> Reports </span> <span class="menu-arrow"></span></a>
                    <ul style="{{ request()->is('/*') ? 'display: block;' : 'display: none;' }}">
                        <li><a class="" href=""> Leave Report </a></li>
                        <li><a class="" href=""> Daily Report </a></li>
                    </ul>
                </li>
                <li class="menu-title"> <span>Pages</span> </li>
                <li class="{{set_active(['employee/profile/*'])}} submenu">
                    <a href="#"><i class="la la-user"></i>
                        <span> Profile </span> <span class="menu-arrow"></span>
                    </a>
                    <ul style="display: none;">
                        <li><a class="" href=""> Employee Profile </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->