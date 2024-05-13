 <!-- Topbar -->
 <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

     <!-- Sidebar Toggle (Topbar) -->
     <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
         <i class="fa fa-bars"></i>
     </button>

     <!-- Topbar Navbar -->
     <ul class="navbar-nav ml-auto">

         <!-- Nav Item - Search Dropdown (Visible Only XS) -->
         <li class="nav-item dropdown no-arrow d-sm-none">
             <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
                 aria-haspopup="true" aria-expanded="false">
                 <i class="fas fa-search fa-fw"></i>
             </a>
             <!-- Dropdown - Messages -->
             <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                 aria-labelledby="searchDropdown">
                 <form class="form-inline mr-auto w-100 navbar-search">
                     <div class="input-group">
                         <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                             aria-label="Search" aria-describedby="basic-addon2">
                         <div class="input-group-append">
                             <button class="btn btn-primary" type="button">
                                 <i class="fas fa-search fa-sm"></i>
                             </button>
                         </div>
                     </div>
                 </form>
             </div>
         </li>

         <!-- Nav Item - Alerts -->
         <li class="nav-item dropdown no-arrow mx-1">
             <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                 data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                 <i class="fas fa-bell fa-fw"></i>
                 <!-- Counter - Alerts -->
                 <span class="badge badge-danger badge-counter">{{ notifDiskon() }}</span>
             </a>
             <!-- Dropdown - Alerts -->
             <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="alertsDropdown">
                 <h6 class="dropdown-header">
                     Permintaan Diskon
                 </h6>
                 @php
                     $diskon = dataDiskon();
                 @endphp
                 @if (notifDiskon() !== '0')
                     @foreach ($diskon as $items)
                         <a class="dropdown-item d-flex align-items-center" target="_blank"
                             href="{{ route('diskon.confirm_diskon', $items->discount_id) }}">
                             <div class="mr-3">
                                 <div class="icon-circle bg-primary">
                                     <i class="fas fa-tag text-white"></i>
                                 </div>
                             </div>
                             <div>
                                 <div class="small text-gray-500">{{ $items->created_at }}</div>
                                 <span class="font-weight-bold">{{ $items->description }}</span>
                             </div>
                         </a>
                     @endforeach
                 @else
                     <div class="dropdown-item bg-gray-200 text-center py-3">
                         Tidak Ada Permintaan Diskon
                     </div>
                 @endif
             </div>
         </li>


         <div class="topbar-divider d-none d-sm-block"></div>

         <!-- Nav Item - User Information -->
         <li class="nav-item dropdown no-arrow">
             <div class="nav-link dropdown-toggle" style="align-items: !important center" id="userDropdown"
                 role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                 <div class="mr-2 d-none d-lg-inline text-gray-600 small">
                     <span>Welcome, {{ auth()->user()->name }}</span><br />
                     <span>Cabang : {{ userStore() }}</span>
                 </div>

                 <img class="img-profile rounded-circle" src="{{ asset('assets/img/profile.png') }}">
             </div>
             <!-- Dropdown - User Information -->
             <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                 <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                     <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                     Logout
                 </a>
             </div>
         </li>

     </ul>

 </nav>
 <!-- End of Topbar -->
