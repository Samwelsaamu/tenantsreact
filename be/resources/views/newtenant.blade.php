@extends('layouts.adminheader')
@section('title','New Tenant | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-6">
    <h1 class="m-0">New Tenant </h1>
</div><!-- /.col -->
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
  <li class="breadcrumb-item"><a href="/properties">Properties (@forelse($propertyinfo as $property)
                        {{$loop->count}}
                            @break
                        @empty
                            0
                        @endforelse
                        )</a></li>
    <li class="breadcrumb-item"><a href="/properties/tenants">Tenants (@forelse($tenantsinfo as $tenants)
                        {{$loop->count}}
                            @break
                        @empty
                            0
                        @endforelse
                        )</a></li>
  <li class="breadcrumb-item active">New Tenant</li>
</ol>
</div><!-- /.col -->
@endsection
@section('content')
<div class="" style="">
    <div class="row justify-content-center" style="">
        <div class="col-md-12" style="">
            <div class="card" style="border: none;">
                <div class="card-header" style="background-color: transparent;">
                    <h5 style="text-align: center;">Create New Tenant
                    </h5>
                     @if ($errors->any())
                      <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                      </div><br />
                    @endif
                </div>

                <div class="card-body" style="padding-top: 10px;">
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
                    <form role="form" class="form-horizontal" method="POST" action="{{ route('tenant.store') }}">
                        <div class="row">
                        @csrf
                        <div class="col-sm-6">
                            <div class="card card-primary card-outline" style="margin-bottom: 5%;min-height: 250px;text-align: center;">
                              <div class="card-body">
                                <div class="form-group row">
                                    <label for="Fname" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>

                                    <div class="col-md-8">
                                        <input id="Fname" type="text" class="form-control @error('Fname') is-invalid @enderror" name="Fname" value="{{ old('Fname') }}" placeholder="First Name" required autocomplete="Fname" autofocus>

                                        @error('Fname')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Oname" class="col-md-4 col-form-label text-md-right">{{ __('Other Name') }}</label>

                                    <div class="col-md-8">
                                        <input id="Oname" type="text" class="form-control @error('Oname') is-invalid @enderror" name="Oname" value="{{ old('Oname') }}" placeholder="Other Name" required autocomplete="Oname" autofocus>

                                        @error('Oname')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Gender" class="col-md-4 col-form-label text-md-right">{{ __('Gender') }}</label>

                                    <div class="col-md-8">
                                        <label>
                                           <input type="radio" name="Gender" value="Male"/ required=""> Male 
                                        </label>
                                        <label>
                                           <input type="radio" name="Gender" value="Female"/ required=""> Female
                                        </label>
                                        <label>
                                           <input type="radio" name="Gender" value="Other"/ required=""> Other
                                        </label>
                                        @error('Gender')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="IDno" class="col-md-4 col-form-label text-md-right">{{ __('IDno') }}</label>

                                    <div class="col-md-8">
                                        <input id="IDno" type="text" class="form-control @error('IDno') is-invalid @enderror" name="IDno" value="{{ old('IDno') }}" placeholder="Tenant's Number(ID or Passport)" required autocomplete="IDno" autofocus>

                                        @error('IDno')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                

                              </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="card card-primary card-outline" style="margin-bottom: 5%;min-height: 250px;text-align: center;">
                              <div class="card-body">

                                <div class="form-group row">
                                    <label for="HudumaNo" class="col-md-4 col-form-label text-md-right">{{ __('HudumaNo') }}</label>

                                    <div class="col-md-8">
                                        <input id="HudumaNo" type="text" class="form-control @error('HudumaNo') is-invalid @enderror" name="HudumaNo" value="{{ old('HudumaNo') }}" placeholder="huduma number" required autocomplete="HudumaNo" autofocus>

                                        @error('HudumaNo')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>

                                    <div class="col-md-8">
                                        <input id="Phone" type="text" class="form-control @error('Phone') is-invalid @enderror" name="Phone" value="{{ old('Phone') }}" placeholder="7xxxxxxxx" required autocomplete="Phone" autofocus>

                                        @error('Phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>

                                    <div class="col-md-8">
                                        <input id="Email" type="email" class="form-control @error('Email') is-invalid @enderror" name="Email" value="{{ old('Email') }}" placeholder="Tenant's email" autocomplete="Email" autofocus>

                                        @error('Email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Status" class="col-md-4 col-form-label text-md-right">{{ __('Status') }}</label>

                                    <div class="col-md-8">
                                        <label>
                                           <input type="radio" name="Status" value="New"/ required=""> Tenant 
                                        </label>
                                        <label>
                                           <input type="radio" name="Status" value="Other"/ required=""> Other
                                        </label>
                                        @error('Gender')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <button  class="btn btn-success btn-small btn-block" name="submitplotbtn" id="submitplotbtn"  type="submit" >Save Tenant Information</button>
                                    </div>
                                </div>

                                
                              </div>
                            </div>
                        </div>
                        
                    </div>
                    </form>


                </div>


            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    
    $(document).on('change','#IDno', function(){ 
     var IDno=$('#IDno').val();
      $('#HudumaNo').val(IDno);
    
 });
</script>

@endpush