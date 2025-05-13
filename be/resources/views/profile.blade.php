@extends('layouts.adminheader')
@section('title','Profile | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-6">
    <h1 class="m-0">Profile Information</h1>
</div><!-- /.col -->
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
  <li class="breadcrumb-item"><a href="/users">Users</a></li>
  <li class="breadcrumb-item active">Profile</li>
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
                        <div class="col-sm-4">
                             <div class="card card-primary card-outline">
                              <div class="card-body box-profile">
                                <div class="text-center">
                                  <img class="profile-user-img img-fluid img-circle"
                                       src="public/assets/img/avatar.png"
                                       alt="User profile picture">
                                </div>

                                <h3 class="profile-username text-center">{{ Auth::user()->Fullname }}</h3>

                                <p class="text-muted text-center">{{ Auth::user()->Userrole }} (Last Updated: {{ Auth::user()->updated_at->diffForHumans() }})</p>
                                <ul class="list-group list-group-unbordered mb-3">
                                  <li class="list-group-item">
                                    <b>Username</b> <a class="float-right">{{ Auth::user()->Username }}</a>
                                  </li>
                                  <li class="list-group-item">
                                    <b>IDno</b> <a class="float-right">{{ Auth::user()->Idno }}</a>
                                  </li>
                                  <li class="list-group-item">
                                    <b>Email</b> <a class="float-right">{{ Auth::user()->email }}</a>
                                  </li>
                                  <li class="list-group-item">
                                    <b>Phone</b> <a class="float-right">{{ Auth::user()->Phone }}</a>
                                  </li>
                                  <li class="list-group-item">
                                    <b>Created At</b> <a class="float-right">{{ Auth::user()->created_at }}</a>
                                  </li>
                                </ul>

                              </div>
                              <!-- /.card-body -->
                            </div>
                        </div>
                        <div class="col-sm-8">

                            @if ($errors->any())
                              <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                      <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                              </div><br/>
                            @endif

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
                          <div class="row">
                            <div class="col-sm-12">
                            <form role="form" class="form-horizontal" method="post" action="{{ route('homeusers.update',Auth::user()->id) }}">
                                @csrf
                                @method('PATCH')
                            <div class="card card-primary card-outline" style="margin-bottom: 5%;min-height: 200px;text-align: center;">
                              <div class="card-body">
                                    <h5 style="text-align: center;">Update Information for:  {{ Auth::user()->Fullname }} </h5>

                                <div class="form-group row">
                                    <label for="Fullname" class="col-md-4 col-form-label text-md-right">{{ __('Fullname') }}</label>

                                    <div class="col-md-8">
                                        <input id="Fullname" type="text" class="form-control @error('Fullname') is-invalid @enderror" name="Fullname" value="{{ Auth::user()->Fullname }}" placeholder="Fullname" required autocomplete="Fullname" autofocus>

                                        @error('Fullname')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>

                                    <div class="col-md-8">
                                        <input id="Phone" type="text" class="form-control @error('Phone') is-invalid @enderror" name="Phone" value="{{ Auth::user()->Phone }}" placeholder="Phone" required autocomplete="Phone" autofocus>

                                        @error('Phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Idno" class="col-md-4 col-form-label text-md-right">{{ __('Idno') }}</label>

                                    <div class="col-md-8">
                                        <input id="Idno" type="text" class="form-control @error('Idno') is-invalid @enderror" name="Idno" value="{{ Auth::user()->Idno }}" placeholder="Idno" required autocomplete="Idno" autofocus>

                                        @error('Idno')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <h6 class="text-info"> <i class="fa fa-info-circle"></i> Please To Reset Password, Logout, and Click 'Forgot password' on Login Page</h6>
                                    </div>
                                </div>

                                <div class="form-group float-right">
                                    <div> 
                                        <button  class="btn btn-success btn-small btn-block" name="" id=""  type="submit" ><i class="fa fa-edit"></i> Update Your Information</button>
                                    </div>
                                </div>
                                 
                              </div>
                            </div>

                        </form>
                    </div>
                    <!-- end update personal details -->
                   
                </div>

               

                </div>

                
                

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
