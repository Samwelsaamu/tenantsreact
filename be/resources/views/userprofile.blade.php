@extends('layouts.adminheader')
@section('title','User Profile | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-6">
    <h4 class="m-0">Users Profile</h4>
</div><!-- /.col -->
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('homeusers.create')}}">New User</a></li>
  <li class="breadcrumb-item"><a href="/profile">Profile</a></li>
  <li class="breadcrumb-item"><a href="/users">Users</a></li>
  <li class="breadcrumb-item active">Users Profile</li>
</ol>
</div><!-- /.col -->
@endsection
@section('css')
    <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('public/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
@section('sidebarlinks')
  <li class="nav-item">
    <a href="/dashboard" class="nav-link">
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
  </li>
@endsection
@section('content')
<div class="" style="">
    <div class="row justify-content-center" style="">
        <div class="col-md-12" style="">
            <div class="card" style="border: none;">

                <div class="card-body" style="padding-top: 10px;">
                        <div class="row">
                       
                        @foreach($usersinfo as $info)
                         <div class="col-md-3">

                            <!-- Profile Image -->
                            <div class="card card-primary card-outline">
                              <div class="card-body box-profile">
                                <div class="text-center">
                                  <img class="profile-user-img img-fluid img-circle"
                                       src="public/assets/img/avatar.png"
                                       alt="User profile picture">
                                </div>

                                <h3 class="profile-username text-center">{{ $info->Fullname }}</h3>

                                <p class="text-muted text-center">{{ $info->Userrole }}(
                                    @if($info->isOnline())
                                      <span class="text-success">Online</span>
                                    @else
                                      <span class="text-muted">Offline</span>
                                    @endif
                                )</p>
                                <ul class="list-group list-group-unbordered mb-3">
                                  <li class="list-group-item">
                                    <b>Username</b> <a class="float-right">{{ $info->Username }}</a>
                                  </li>
                                  <li class="list-group-item">
                                    <b>Email</b> <a class="float-right">{{ $info->email }}</a>
                                  </li>
                                  <li class="list-group-item">
                                    <b>Last Login</b> <a class="float-right">
                                      @if($info->last_login_at)
                                        {{ $info->last_login_at->diffForHumans() }}
                                      @else
                                        Not Yet
                                      @endif
                                       </a>
                                  </li>
                                  <li class="list-group-item">
                                    <b>Last Activity</b> <a class="float-right">
                                      @if($info->current_activity_at)
                                        {{ $info->current_activity_at->diffForHumans() }}
                                      @else
                                        Not Yet
                                      @endif
                                       </a>
                                  </li>
                                </ul>

                              </div>
                            </div>
                            <!-- /.card -->

                           
                            <!-- /.card -->
                          </div>

                          <div class="col-md-9">
                            <div class="card">
                              <div class="card-header p-2">
                                <ul class="nav nav-pills">
                                  <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Activity</a></li>
                                  <li class="nav-item"><a class="nav-link" href="#logins" data-toggle="tab">Logins</a></li>
                                  <li class="nav-item"><a class="nav-link" href="#systems" data-toggle="tab">Systems</a></li>
                                  <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                                </ul>
                              </div><!-- /.card-header -->
                              <div class="card-body">
                                <div class="tab-content">
                                  <div class="active tab-pane" id="activity">
                                    <h4 class="text-info text-center">Logged User Activity </h4>
                                    <div class="">
                                      <table id="example1" class="table table-bordered table-striped">
                                      <thead>
                                        <tr style="background-color: #77B5ED;">
                                          <th>Sno</th>
                                          <th>Activity</th>
                                          <th>Date</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                      @forelse($logusersinfo as $loginfo)
                                        <tr>
                                          <td>{{ $loop->index+1 }} </td>
                                          <td>{{ $loginfo->Message }} </td>
                                          <td>{{ $loginfo->created_at }} </td>
                                        </tr>

                                      @empty
                                      <tr>
                                          <td colspan="3"><h4 class="text-warning">No Activity Found</h4></td>
                                      </tr>
                                        
                                      @endforelse
                                      </tbody>
                                      
                                    </table>
                                      </div>
                                  </div>
                                  <!-- /.tab-pane -->
                                  <div class="tab-pane" id="logins">
                                    <!-- The timeline -->
                                    <h4 class="text-info text-center">All Logins </h4>
                                    <div class="">
                                      <table id="example3" class="table table-bordered table-striped">
                                        <thead>
                                          <tr style="background-color: #77B5ED;">
                                            <th>Sno</th>
                                            <th>Date</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                      @forelse($loginusersinfo as $logininfo)
                                        <tr>
                                          <td>{{ $loop->index+1 }} </td>
                                          <td>{{ $logininfo->created_at }} </td>
                                        </tr>

                                      @empty
                                      <tr>
                                          <td colspan="3"><h4 class="text-warning">Has Not Logged in Yet</h4></td>
                                      </tr>
                                        
                                      @endforelse
                                      </tbody>
                                        
                                      </table>
                                      </div>
                                  </div>
                                  <!-- /.tab-pane -->

                                  <div class="tab-pane" id="systems">
                                    <h4 class="text-info text-center">Logged System Activity </h4>
                                    <div class="">
                                        <table id="example2" class="table table-bordered table-striped">
                                          <thead>
                                            <tr style="background-color: #77B5ED;">
                                              <th>Sno</th>
                                              <th>Activity</th>
                                              <th>Date</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                          @forelse($logsystemusersinfo as $logsysteminfo)
                                            <tr>
                                              <td>{{ $loop->index+1 }} </td>
                                              <td>{{ $logsysteminfo->Message }} </td>
                                              <td>{{ $logsysteminfo->created_at }} </td>
                                            </tr>

                                          @empty
                                          <tr>
                                              <td colspan="3"><h4 class="text-warning">No System Activity Found</h4></td>
                                          </tr>
                                            
                                          @endforelse
                                          </tbody>
                                          
                                        </table>
                                      </div>
                                  </div>

                                  <div class="tab-pane" id="settings">
                                    <h4>Coming Up</h4>
                                  </div>
                                  <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                              </div><!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                          </div>
                          <!-- /.col -->

                  
                        @endforeach

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables  & Plugins -->
  <script src="{{ asset('public/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('public/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('public/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('public/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script type="text/javascript">


$(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,"ordering":true
    });
    $("#example2").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,"ordering":true
    });
    $("#example3").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,"ordering":true
    });
    
});
</script>
@endpush
