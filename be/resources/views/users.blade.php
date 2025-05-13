@extends('layouts.adminheader')
@section('title','Users | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-6">
    <h4 class="m-0">Users Information</h4>
</div><!-- /.col -->
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('homeusers.create')}}">New User</a></li>
  <li class="breadcrumb-item"><a href="/profile">Profile</a></li>
  <li class="breadcrumb-item active">Users</li>
</ol>
</div><!-- /.col -->
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
                        @if(\Session::has('success'))
                        <div class="alert alert-success" role="alert">
                            <h4>{{ \Session::get('success') }}</h4>
                        </div>
                        @endif
                        @if(\Session::has('dbError'))
                        <div class="alert alert-success" role="alert">
                            <h4>{{ \Session::get('dbError') }}</h4>
                        </div>
                        @endif
                        @foreach($usersinfo as $info)
                        <div class="col-sm-4">
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
                                  <li class="list-group-item">
                                    <b>Actions</b>
                                    <div class="float-right">
                                    <a href="/properties/users/profile/{{$info->id}}" class="btn btn-info btn-sm " style="padding: 3px;font-size: 12px;margin-right: 3px;color: white;"><i style="color: white;" class="fa fa-list-alt"></i> View</a>
                                    <form action="{{ route('homeusers.destroy', $info->id)}}" method="post" class="form-horizontal" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" type="submit" style="padding: 4px;font-size: 12px;display: inline;"><i class="fa fa-trash"></i> Delete</button>
                                    </form>
                                    </div>
                                  </li>
                                </ul>

                              </div>
                            </div>
                        </div>
                        @endforeach

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
