@extends('layouts.adminheader')
@section('title','Agency | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-6">
    <h1 class="m-0">Agency Information</h1>
</div><!-- /.col -->
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
  <li class="breadcrumb-item active">Agency</li>
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
                    
                    @forelse($agencyinfo as $agency)
                    <form role="form" class="form-horizontal" method="post" action="{{ route('agency.update',$agency->id) }}">

                        <div class="row">
                        @csrf
                        @method('PATCH')
                        <div class="col-sm-6">
                            <img src="{{ asset('public/assets/img/logo-replaced.png') }}" alt="Wagitonga Logo" class=" elevation-3" style="width:100%;opacity: .8;">
                            <h2>Agency Information</h2>
                            <p class="texts-white">Enter Agency Information Here</p>
                            <p class="title-black">The Agency Name Will appear on all Titles as Company Name</p>
                            <p class="title-black">Phone Will Appear on All Contact US</p>
                            <p class="title-black">Town And Address will Appear on all Addresses and location Details</p>
                            <p class="title-black">Email Will be used for all mailto, incoming emails and sending email </p>

                        </div>
                        <div class="col-sm-6">
                            <div class="card card-primary card-outline bg-info" style="margin-bottom: 5%;min-height: 300px;text-align: center;">
                                <h3 style="text-align: center;"> Update Agency Information</h3>
                                @if ($errors->any())
                                  <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                          <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                  </div><br />
                                @endif
                            <div class="card-body">
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

                                <div class="form-group row">
                                    <label for="Names" class="col-md-4 col-form-label text-md-right">{{ __('Agency Name') }}</label>

                                    <div class="col-md-8">
                                        <input id="Names" type="text" class="form-control @error('Names') is-invalid @enderror" name="Names" value="{{ $agency->Names }}" placeholder="Agency FullNames" required autocomplete="Names" autofocus>

                                        @error('Names')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Address" class="col-md-4 col-form-label text-md-right">{{ __('Agency Address') }}</label>

                                    <div class="col-md-8">
                                        <input id="Address" type="text" class="form-control @error('Address') is-invalid @enderror" name="Address" value="{{ $agency->Address }}" placeholder="Agency Address" required autocomplete="Address" autofocus>

                                        @error('Address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Town" class="col-md-4 col-form-label text-md-right">{{ __('Agency Town') }}</label>

                                    <div class="col-md-8">
                                        <input id="Town" type="text" class="form-control @error('Town') is-invalid @enderror" name="Town" value="{{ $agency->Town }}" placeholder="Agency Town" required autocomplete="Town" autofocus>

                                        @error('Town')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Phone" class="col-md-4 col-form-label text-md-right">{{ __('Agency Phone') }}</label>

                                    <div class="col-md-8">
                                        <input id="Phone" type="text" class="form-control @error('Phone') is-invalid @enderror" name="Phone" value="{{ $agency->Phone }}" placeholder="Agency Phone" required autocomplete="Phone" autofocus>

                                        @error('Phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Email" class="col-md-4 col-form-label text-md-right">{{ __('Agency Email') }}</label>

                                    <div class="col-md-8">
                                        <input id="Email" type="email" class="form-control @error('Email') is-invalid @enderror" name="Email" value="{{ $agency->Email }}" placeholder="Agency Email" required autocomplete="Email" autofocus>
                                        @error('Email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            <div>
                              
                            </div>



                              </div>
                            </div>
                            <div class="form-group has-feedback">
                               <button class="btn btn-block btn-warning" name="saveaddnewuserbtn" id="saveaddnewuserbtn" type="submit">Update Agency Information</button>
                            </div>
                        </div>

                    </div>
                    </form>
                    @empty
                        <form role="form" class="form-horizontal" method="POST" action="{{ route('agency.store') }}">

                        <div class="row">
                        @csrf

                        <div class="col-sm-6">
                            <img src="{{ asset('public/assets/img/logo-replaced.png') }}" alt="Wagitonga Logo" class=" elevation-3" style="width:100%;opacity: .8;">
                            <h2>Agency Information</h2>
                            <p class="texts-white">Enter Agency Information Here</p>
                            <p class="title-black">The Agency Name Will appear on all Titles as Company Name</p>
                            <p class="title-black">Phone Will Appear on All Contact US</p>
                            <p class="title-black">Town And Address will Appear on all Addresses and location Details</p>
                            <p class="title-black">Email Will be used for all mailto, incoming emails and sending email </p>

                        </div>
                        <div class="col-sm-6">
                            <div class="card card-primary card-outline bg-info" style="margin-bottom: 5%;min-height: 300px;text-align: center;">
                                <h3 style="text-align: center;"> Create New Agency Information</h3>
                                @if ($errors->any())
                                  <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                          <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                  </div><br />
                                @endif
                            <div class="card-body">
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

                                <div class="form-group row">
                                    <label for="Names" class="col-md-4 col-form-label text-md-right">{{ __('Agency Name') }}</label>

                                    <div class="col-md-8">
                                        <input id="Names" type="text" class="form-control @error('Names') is-invalid @enderror" name="Names" value="{{ old('Names') }}" placeholder="Agency FullNames" required autocomplete="Names" autofocus>

                                        @error('Names')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Address" class="col-md-4 col-form-label text-md-right">{{ __('Agency Address') }}</label>

                                    <div class="col-md-8">
                                        <input id="Address" type="text" class="form-control @error('Address') is-invalid @enderror" name="Address" value="{{ old('Address') }}" placeholder="Agency Address" required autocomplete="Address" autofocus>

                                        @error('Address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Town" class="col-md-4 col-form-label text-md-right">{{ __('Agency Town') }}</label>

                                    <div class="col-md-8">
                                        <input id="Town" type="text" class="form-control @error('Town') is-invalid @enderror" name="Town" value="{{ old('Town') }}" placeholder="Agency Town" required autocomplete="Town" autofocus>

                                        @error('Town')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Phone" class="col-md-4 col-form-label text-md-right">{{ __('Agency Phone') }}</label>

                                    <div class="col-md-8">
                                        <input id="Phone" type="text" class="form-control @error('Phone') is-invalid @enderror" name="Phone" value="{{ old('Phone') }}" placeholder="Agency Phone" required autocomplete="Phone" autofocus>

                                        @error('Phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Email" class="col-md-4 col-form-label text-md-right">{{ __('Agency Email') }}</label>

                                    <div class="col-md-8">
                                        <input id="Email" type="email" class="form-control @error('Email') is-invalid @enderror" name="Email" value="{{ old('Email') }}" placeholder="Agency Email" required autocomplete="Email" autofocus>
                                        @error('Email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            <div>
                              
                            </div>



                              </div>
                            </div>
                            <div class="form-group has-feedback">
                               <button class="btn btn-block btn-success" name="saveaddnewuserbtn" id="saveaddnewuserbtn" type="submit">Save Agency Information</button>
                            </div>
                        </div>

                    </div>
                    </form>
                    @endforelse





                </div>
            </div>
        </div>
    </div>
</div>
@endsection
