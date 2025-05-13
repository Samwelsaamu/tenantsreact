<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon.ico') }}">
      
      <!-- Font Awesome -->
      <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
      
      <!-- iCheck -->
      <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
      <!-- overlayScrollbars -->
      <link rel="stylesheet" href="{{ asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
      <!-- Daterange picker -->
      <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
        <!-- Select2 -->
      <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

      <link href="{{ asset('css/app.css') }}" rel="stylesheet">
      <link href="{{ asset('css/home.css') }}" rel="stylesheet">
      @yield('css')
</head>
<!-- <body class="hold-transition sidebar-collapse layout-top-nav"> -->
<body class="hold-transition sidebar-mini layout-fixed text-dark">
    <div class="wrapper">

      <!-- Navbar -->
      <nav class="main-header navbar navbar-expand navbar-info fixed-top">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <!-- <li class="nav-item">
          <a href="/home" class="nav-link">{{ Auth::user()->Userrole }}</a>
          </li> -->

          <li class="nav-item ml-4 mr-2">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
              <i class="fas fa-bars fa-2x"></i>
            </a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
            <a href="/profile" class="nav-link">{{ Auth::user()->Username }} (Admin)</a>
          </li>

          <!-- <li class="nav-item">
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-newdetails">
              <i class="fa fa-plus fa-2x"></i>
            </button>
            
          </li> -->
          <!-- <li class="nav-item">
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-updatedetails">
              <i class="fa fa-edit fa-2x"></i>
            </button> 
            
          </li> -->

        </ul>

        <!-- SEARCH FORM -->
        <form class="form-inline mx-auto">
          <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-navbar" type="submit">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        </form>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
          
          <!-- Notifications Dropdown Menu -->
          <!-- <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
              <i class="fa fa-trophy fa-1x"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
              <span class="dropdown-item dropdown-header">Extras</span>
              <div class="dropdown-divider"></div>
              <a href="/properties/messages" class="dropdown-item">
                <i class="fas fa-envelope mr-2"></i> Send Messages
              </a>
              <div class="dropdown-divider"></div>
              <a href="/properties/add/waterbill" class="dropdown-item">
                <i class="fas fa-wrench mr-2"></i> Add Waterbill
              </a>
              <div class="dropdown-divider"></div>
              <a href="/properties/upload/waterbill" class="dropdown-item">
                <i class="fas fa-wrench mr-2"></i> Upload Waterbill
              </a>

              <div class="dropdown-divider"></div>
              <a href="/properties/update/bills" class="dropdown-item">
                <i class="fas fa-wrench mr-2"></i> Update Bills
              </a>
            </div>
          </li> -->
          
          <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" style="font-size: 12px;">
              <i id="appbalance"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
              <span class="dropdown-item dropdown-header">Africas Talking Topups</span>
              <div class="dropdown-divider"></div>
              <a  class="dropdown-item">
                <b>Paybill</b>: 525900<br>
                <b>Acct No</b>: WAGITONGA.api
              </a>
              <div class="dropdown-divider"></div>
            </div>
          </li>

          <li class="nav-item dropdown">
              <a id="navbarDropdown" class="nav-link dropdown-toggle m-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                <img src="{{ asset('assets/img/avatar.png') }}" width="30px" class="brand-image img-circle m-0 p-0" alt="User Image">
              </a>

              <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown">
                 <a class="dropdown-item " href="/profile"><i class="fa fa-user text-lime"></i> Profile ({{ Auth::user()->Username }})</a>
                  <a class="dropdown-item " href="/profile/change-password"><i class="fa fa-lock text-warning"></i> Change Password</a>
                  <a class="dropdown-item " href="/homeusers/create"><i class="fa fa-plus nav-icon text-dark"></i> New User</a>
                  <a class="dropdown-item " href="/users"><i class="fa fa-users nav-icon text-dark"></i> Users</a>
                  <a class="dropdown-item " href="/agencyinfo"><i class="fa fa-tree text-warning"></i> Agency</a>
                  <a class="dropdown-item " href="{{ route('logout') }}"
                     onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();"><i class="fa fa-power-off text-danger"></i>
                      {{ __('Logout') }}
                  </a>

                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                      @csrf
                  </form>

              </div>
          </li>
          
        </ul>
      </nav>
      <!-- /.navbar -->

      <!-- Main Sidebar Container -->
      <aside class="main-sidebar main-sidebar-custom elevation-4">
        <!-- Brand Logo -->
        <a href="/dashboard" class="brand-link navbar-info row m-0 p-0">
          <div class="col-12 mt-2 image">
            <img src="{{ asset('assets/img/wagitonga1.png') }}" alt="Wagitonga Logo" class="brand-image elevation-3 m-0 " style="opacity: 1;width:90%;border-radius: 15px 15px 2px 2px;">
          </div>
          
          <div class="col-12 brand-text font-weight-bold text-sm text-white m-1 mt-2">{{ App\Models\Agency::getAgencyName() }}</div>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
          <!-- Sidebar user panel (optional) -->
          <!-- <div class="user-panel mt-1 pb-1 mb-1 d-flex">
            <div class="image">
              <img src="{{ asset('assets/img/avatar.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
              <a class="dropdown-item text-light" href="/profile">{{ Auth::user()->Username }}</a>
                 <a class="dropdown-item text-warning" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                 </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
          </div> -->

          <!-- SidebarSearch Form -->
          <!-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
              <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-sidebar">
                  <i class="fas fa-search fa-fw"></i>
                </button>
              </div>
            </div>
          </div> -->

          <!-- Sidebar Menu -->
          <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
              <!-- Add icons to the links using the .nav-icon class
                   with font-awesome or any other icon font library -->
              @yield('sidebarlinks')
              <!-- <li class="nav-item menu-open">
                <a href="#" class="nav-link bg-black text-light m-0 p-1">
                  <i class="nav-icon fa fa-cogs"></i>
                  <p>
                    Others
                    <i class="fas fa-angle-left right"></i>
                    
                  </p>
                </a>
                <ul class="nav nav-treeview navbar-info">
                  
                  <li class="nav-item">
                    <a href="/properties/frequentlyasked" class="nav-link">
                      <i class="nav-icon fas fa-th"></i>
                      <p>
                        FAQs
                      </p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="/profile/change-password">
                      <i class="fas fa-lock nav-icon"></i>
                      <p>Change Password</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="/homeusers/create">
                      <i class="fas fa-plus nav-icon nav-icon"></i> 
                      <p>New User</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="/users">
                      <i class="fas fa-users nav-icon nav-icon"></i> 
                      <p>Users</p>  
                  </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="/agencyinfo">
                      <i class="fas fa-tree nav-icon"></i> 
                      <p>Agency</p>
                    </a>
                  </li>

                </ul>
              </li> -->
              <!-- <div class="mb-2">
              
              </div>
               -->

                  

              <!-- <li class="nav-item">
                <a href="/dashboard" class="nav-link active">
                      <i class="fa fa-home nav-icon"></i>
                      <p>Dashboard</p>
                    </a>
              </li>

              <li class="nav-item menu-open">
                <a href="#" class="nav-link text-light">
                  <i class="nav-icon fa fa-university"></i>
                  <p>
                    Properties
                    <i class="fas fa-angle-left right"></i>
                    
                  </p>
                </a>
                <ul class="nav nav-treeview bg-primary">
                  <li class="nav-item">
                    <a href="/properties/manage" class="nav-link">
                      <i class="fa fa-sitemap nav-icon"></i>
                      <p>Manage Properties</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="/properties/update/bills" class="nav-link">
                      <i class="fa fa-briefcase nav-icon"></i>
                      <p>Update Monthly Bills</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="/properties/View/Reports" class="nav-link">
                      <i class="fa fa-truck nav-icon"></i>
                      <p>View Reports</p>
                    </a>
                  </li>

                </ul>
              </li>

              <li class="nav-item menu-open">
                <a href="#" class="nav-link text-light">
                  <i class="nav-icon fa fa-tint"></i>
                  <p>
                    Waterbill
                    <i class="fas fa-angle-left right"></i>
                    
                  </p>
                </a>
                <ul class="nav nav-treeview bg-primary">

                  <li class="nav-item">
                    <a href="/properties/add/waterbill" class="nav-link">
                      <i class="fa fa-life-ring nav-icon"></i>
                      <p>Add Waterbill</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="/properties/update/waterbill" class="nav-link">
                      <i class="fa fa-upload nav-icon"></i>
                      <p>Upload Waterbill</p>
                    </a>
                  </li>

                </ul>
              </li>

              <li class="nav-item menu-open">
                <a href="#" class="nav-link text-light">
                  <i class="nav-icon fa fa-comments"></i>
                  <p>
                    Messages
                    <i class="fas fa-angle-left right"></i>
                    
                  </p>
                </a>
                <ul class="nav nav-treeview bg-primary">
                  
                  <li class="nav-item">
                    <a href="/properties/messages" class="nav-link">
                      <i class="far fa-paper-plane nav-icon"></i>
                      <p>Send Messages</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="/properties/view/messages/others" class="nav-link">
                      <i class="fa fa-leaf nav-icon"></i>
                      <p>Messages Summary</p>
                    </a>
                  </li>     
                </ul>
              </li>


              <li class="nav-item menu-open">
                <a href="#" class="nav-link text-light">
                  <i class="nav-icon fa fa-envelope"></i>
                  <p>
                    Extras
                    <i class="fas fa-angle-left right"></i>
                    
                  </p>
                </a>
                <ul class="nav nav-treeview bg-primary">
                  <li class="nav-item">
                    <a href="/mail/getmail" class="nav-link">
                      <i class="fa fa-inbox nav-icon"></i>
                      <p>Mails</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="/properties/frequentlyasked" class="nav-link text-light">
                      <i class="nav-icon fas fa-th"></i>
                      <p>
                        FAQs
                      </p>
                    </a>
                  </li>
                </ul>
              </li> -->
              
              
            </ul>
          </nav>
          <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
        <div class="sidebar-custom navbar-info m-0 p-1">
          <!-- <a href="#" class="btn btn-link"><i class="fas fa-cogs"></i></a> -->
          <a class="btn btn-danger p-1 m-1 ml-2" href="{{ route('logout') }}" 
              onclick="event.preventDefault();
              document.getElementById('logout-form').submit();">
            <i class="fa fa-power-off nav-icon"></i>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
          </a>

          <a class="btn bg-light pos-right p-1 m-1 ml-2" href="/users" >
            <i class="fa fa-user nav-icon"></i>
          </a>
          <!-- <a href="#" class="btn btn-secondary hide-on-collapse pos-right">Help</a> -->
        </div>
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header" style="padding: 0px;padding-top:70px;margin: 0px;">
          <div class="">
            <div class="row mb-2">
              @yield('HeaderTitle')
            </div><!-- /.row -->
          </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">

        <div class="">
            @yield('content')
        </div>
        </section>
      </div>

      <!-- </div>    -->
        <main class="py-2">
            
        </main>
        <footer class="main-footer text-center bg-info" >
          <strong> &copy;  {{ App\Models\Agency::getAgencyName()}}.</strong> 
        </footer>
    </div>
    
    <!-- confimation to save Modal -->
    <div class="modal fade" id="modalconfirmation">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-info">
            <h4 class="modalconfirmation-title" id="modalconfirmation-title" style="text-align: center;">Continue Confirmation</h4>
            
          </div>
          <div class="modal-body" id="modalconfirmation-body">
            <p>Do You Want To Continue this Operation</p>
          </div>
          <div class="modal-footer justify-content-between bg-warning">
            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-outline-dark" id="ConfirmConfirmation">Confirm and Continue</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- start modal page -->
    <div class="modal fade" id="modalpage">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header m-0 p-2">
            <h6 class="modal-title mx-auto" id="modalpage-title">Message Title</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body bg-light m-0 p-1" id="modalpage-body" style="padding: 10px;margin:10px;max-height:calc(50vh);overflow-y: auto;">
            <p>Message Body</p>
          </div>
          <div class="modal-footer justify-content-center m-0 p-1">
            <button type="button" class="btn btn-danger float-sm-right" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- end modal page -->

    <!-- start modal newdetails -->

    <div class="modal fade" id="modal-newdetails">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-info m-0 p-1">
            <h4 class="modal-title mx-auto" id="modal-newdetails-title">Add New Details</h4>
            <button type="button" class="close m-0 p-2" data-dismiss="modal" aria-label="Close">
              <span class="fas fa-times mr-2 text-danger"></span>
            </button>
          </div>
          <div class="modal-body" id="modal-newdetails-body" style="max-height: 500px;overflow-y: auto;">
            <div class="row m-0 p-0">
            <div class="col-3 p-1">
              <a href="/properties/newtenant"class="m-0">
                <button class="btn btn-outline-info text-xs col-12 p-1 m-0">
                  <i class="fas fa-plus-circle mx-auto"></i> Tenant
                </button>
              </a>
            </div>
            <div class="col-3 p-1">
              <a href="/newproperties" class="m-0">
                <button class="btn btn-outline-info text-xs col-12 p-1 m-0">
                  <i class="fas fa-plus-circle mx-auto"></i> Property
                </button>
              </a>
            </div>
              

              <!-- @forelse($propertyinfo as $property)
                  <div class="col-3 p-1">
                    <a href="/properties/newhouse/{{$property->id}}" class="m-0"> 
                      <button class="btn btn-outline-info text-xs col-12 p-1 m-0">
                        <i class="fas fa-plus-circle mx-auto"></i> {{$property->Plotcode}} New
                      </button>
                    </a>
                  </div>
              @empty
              <div class="dropdown-divider"></div>  
                <i class="fas fa-plus-circle mr-2"></i> No Property
              @endforelse -->
            </div>
          </div>
          
        </div>
      </div>
    </div>

    <!-- end modal newdetails -->

    <!-- start modal newdetails -->

    <div class="modal fade" id="modal-updatedetails">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header bg-info text-center">
            <h4 class="modal-title text-center" id="modal-updatedetails-title">Update Details</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body p-1 m-1" id="modal-updatedetails-body" style="padding: 10px;margin:10px;max-height: 500px;overflow-y: auto;">
              <div class="card-header p-0 m-0" style="background-color: transparent;">
                    <div class="row p-0 m-0" style="padding: 0px;margin: 0px;">
                      <div class="col-sm-4 p-1">
                        <select class="form-control select2" name="alltenants" id="alltenants" onchange="location=this.value;" style="width: 100%;">
                          <option value="">Choose Tenant to Update</option>
                          
                        </select>
                      </div>
                      <div class="col-sm-4 p-1">
                        <select class="form-control select2" name="allproperties" id="allproperties" onchange="location=this.value;" style="width: 100%;">
                          <option value="">Update Property</option>
                          
                        </select>
                      </div>
                      <div class="col-sm-4 p-1">
                        <select class="form-control select2" name="allhouses" id="allhouses" onchange="location=this.value;" style="width: 100%;">
                          <option value="">Update House</option>
                            
                      </select>
                      </div>
                    </div>
                </div>
                <div class="card-body">
                  <h4 class="text-center">Please Choose from Either Tenant, Property or House to Update</h4>
                </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default float-sm-right" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- end modal newdetails -->


<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

<!-- Wagitonga App -->
<script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>
<!-- Wagitonga for demo purposes -->
<script src="{{ asset('assets/dist/js/demo.js') }}"></script>
<!-- Wagitonga dashboard demo (This is only for demo purposes) -->

<!-- <script src="{{ asset('js/app.js') }}" defer></script> -->
<script type="text/javascript">

  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  });

  load_balance();

  function load_balance(){
    $.ajax({
        url:"/getappdata",
        method:"GET",
        success:function(data){
          $('#appbalance').html(data);
              $('#appbalancemin').html(data);
        },
        error: function(xhr, status, error){
          var errorMessage = xhr.status + ': ' + xhr.statusText
          if (errorMessage=="0: error") {
            errorMessage="No Connection" 
          }
        $("#appbalance").html(errorMessage);
      }
    });
  }

// load_info_for_update();

  function load_info_for_update(){

  // $.ajax({
  //   url:"/properties/get-details/tenants",
  //     method:"GET",
  //     success:function(data){
  //       var alldata=JSON.parse(data);
  //       var alltenants=document.getElementById('alltenants');
  //       for (var i = 0; i < alldata.length; i++) {
  //         var sno=i+1;
  //           alltenants.innerHTML=alltenants.innerHTML+'<option value="/tenant/'+alldata[i].id+'/edit">'+sno+'. <span class="text-xs">'+alldata[i].Fname+' '+alldata[i].Oname+' ('+alldata[i].Status+')</span></option>';
  //       }
        
  //       if (alldata.length==0) {
  //           var noalltenants='<option value="">No Tenant Found</option>';
  //           $('#alltenants').html(noalltenants);
  //       }
        
  //     },
  //     error: function(xhr, status, error){
  //       var errorMessage = xhr.status + ': ' + xhr.statusText
  //       if (errorMessage=="0: error") {
  //           errorMessage="No Connection" 
  //       }
  //       var alltenants='<option value="">'+errorMessage+'</option>';
  //       $('#alltenants').html(alltenants);
        
  //   }
  // });

  // $.ajax({
  //   url:"/properties/get-details/houses",
  //     method:"GET",
  //     success:function(data){
  //       var alldata=JSON.parse(data);
  //       var allhouses=document.getElementById('allhouses');
  //       for (var i = 0; i < alldata.length; i++) {
  //         var sno=i+1;
  //           allhouses.innerHTML=allhouses.innerHTML+'<option value="/house/'+alldata[i].id+'/edit">'+sno+'. <span class="text-xs">'+alldata[i].Housename+' ('+alldata[i].Status+')</span></option>';
  //       }
        
  //       if (alldata.length==0) {
  //           var noallhouses='<option value="">No House Found</option>';
  //           $('#allhouses').html(noallhouses);
  //       }
        
  //     },
  //     error: function(xhr, status, error){
  //       var errorMessage = xhr.status + ': ' + xhr.statusText
  //       if (errorMessage=="0: error") {
  //           errorMessage="No Connection" 
  //       }
  //       var allhouses='<option value="">'+errorMessage+'</option>';
  //       $('#allhouses').html(allhouses);
        
  //   }
  // });

  // $.ajax({
  //   url:"/properties/get-details/properties",
  //     method:"GET",
  //     success:function(data){
  //       var alldata=JSON.parse(data);
  //       var allproperties=document.getElementById('allproperties');
  //       for (var i = 0; i < alldata.length; i++) {
  //         var sno=i+1;
  //           allproperties.innerHTML=allproperties.innerHTML+'<option value="/plot/'+alldata[i].id+'/edit">'+sno+'. <span class="text-xs">'+alldata[i].Plotname+' ('+alldata[i].Plotcode+')</span></option>';
  //       }
        
  //       if (alldata.length==0) {
  //           var noallproperties='<option value="">No Property Found</option>';
  //           $('#allproperties').html(noallproperties);
  //       }
        
  //     },
  //     error: function(xhr, status, error){
  //       var errorMessage = xhr.status + ': ' + xhr.statusText
  //       if (errorMessage=="0: error") {
  //           errorMessage="No Connection" 
  //       }
  //       var allproperties='<option value="">'+errorMessage+'</option>';
  //       $('#allproperties').html(allproperties);
        
  //   }
  // });
}
</script>
  @stack('scripts')
</body>
</html>
