@extends('layouts.adminheader')
@section('title','Update {{ $thistenant->Fname }} | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-6">
    <h1 class="m-0">Update {{ $thistenant->Fname }} </h1>
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
  <li class="breadcrumb-item active">Update {{ $thistenant->Fname }}</li>
</ol>
</div><!-- /.col -->
@endsection
@section('content')
<div class="" style="">
    <div class="row justify-content-center" style="">
        <div class="col-md-12" style="">
            <div class="card" style="border: none;">
                <div class="card-header" style="background-color: transparent;">
                    <div class="row" style="padding: 0px;margin: 0px;">
                      <div class="col-sm-4">
                        <select class="form-control select2" name="alltenants" onchange="location=this.value;" style="width: 100%;">
                          <option value="">Update {{ $thistenant->Fname }}</option>
                          @forelse($tenantsinfo as $tenants)
                            <option value="{{ route('tenant.edit', $tenants->id)}}">{{ $loop->index+1 }}. {{ $tenants->Fname }} {{ $tenants->Oname }} ({{ $tenants->Status }})</option>
                          @empty
                            <option>No Tenant Found</option>
                          @endforelse
                        </select>
                      </div>
                      <div class="col-sm-3">
                        <select class="form-control select2" name="allproperties" onchange="location=this.value;" style="width: 100%;">
                          <option value="">Update Property</option>
                          @forelse($propertyinfo as $propertys)
                                <option value="{{ route('plot.edit', $propertys->id)}}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                          @empty
                            <option>No Property Found</option>
                          @endforelse
                        </select>
                      </div>
                      <div class="col-sm-3">
                        <select class="form-control select2" name="allhouses" onchange="location=this.value;" style="width: 100%;">
                          <option value="">Update House</option>
                            @forelse($propertyhouses as $dropphouses)
                                <option value="{{ route('house.edit', $dropphouses->id)}}">{{ $loop->index+1 }}. {{ $dropphouses->Housename }} ({{ $dropphouses->Status }})</option>
                            @empty
                              <option>No Tenant Found</option>
                            @endforelse
                      </select>
                      </div>
                      <div class="col-sm-2">
                          <h6 style="text-align: center;">Update {{ $thistenant->Fname }}
                          </h6>
                      </div>
                    </div>

                     
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
                    @if ($errors->any())
                      <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                      </div><br />
                    @endif
                    <form role="form" class="form-horizontal" method="POST" action="{{ route('tenant.update',$thistenant->id) }}">
                        <div class="row">
                        @csrf
                        @method('PATCH')
                        <div class="col-sm-6">
                            <div class="card card-primary card-outline" style="margin-bottom: 5%;min-height: 250px;text-align: center;">
                              <div class="card-body">
                                <div class="form-group row">
                                    <label for="Fname" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>

                                    <div class="col-md-8">
                                        <input id="Fname" type="text" class="form-control @error('Fname') is-invalid @enderror" name="Fname" value="{{ $thistenant->Fname }}" placeholder="First Name" required autocomplete="Fname" autofocus>

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
                                        <input id="Oname" type="text" class="form-control @error('Oname') is-invalid @enderror" name="Oname" value="{{ $thistenant->Oname }}" placeholder="Other Name" required autocomplete="Oname" autofocus>

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
                                        @if($thistenant->Gender=='Male')
                                            <label>
                                               <input type="radio" name="Gender" value="Male"/ checked required=""> Male 
                                            </label>
                                            <label>
                                               <input type="radio" name="Gender" value="Female"/ required=""> Female
                                            </label>
                                            <label>
                                               <input type="radio" name="Gender" value="Other"/ required=""> Other
                                            </label>
                                        @elseif($thistenant->Gender=='Female')
                                            <label>
                                               <input type="radio" name="Gender" value="Male"/ required=""> Male 
                                            </label>
                                            <label>
                                               <input type="radio" name="Gender" value="Female"/ checked required=""> Female
                                            </label>
                                            <label>
                                               <input type="radio" name="Gender" value="Other"/ required=""> Other
                                            </label>
                                        @else
                                            <label>
                                               <input type="radio" name="Gender" value="Male"/ required=""> Male 
                                            </label>
                                            <label>
                                               <input type="radio" name="Gender" value="Female"/ required=""> Female
                                            </label>
                                            <label>
                                               <input type="radio" name="Gender" value="Other"/ checked required=""> Other
                                            </label>
                                        @endif
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
                                        <input id="IDno" type="text" class="form-control @error('IDno') is-invalid @enderror" name="IDno" value="{{ $thistenant->IDno }}" placeholder="Tenant's Number(ID or Passport)" required autocomplete="IDno" autofocus>

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
                                        <input id="HudumaNo" type="text" class="form-control @error('HudumaNo') is-invalid @enderror" name="HudumaNo" value="{{ $thistenant->HudumaNo }}" placeholder="huduma number" required autocomplete="HudumaNo" autofocus>

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
                                        <input id="Phone" type="text" class="form-control @error('Phone') is-invalid @enderror" name="Phone" value="{{ $thistenant->Phone }}" placeholder="7xxxxxxxx" required autocomplete="Phone" autofocus>

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
                                        <input id="Email" type="email" class="form-control @error('Email') is-invalid @enderror" name="Email" value="{{ $thistenant->Email }}" placeholder="Tenant's email" autocomplete="Email" autofocus>

                                        @error('Email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <button  class="btn btn-warning btn-small float-right" name="submitplotbtn" id="submitplotbtn"  type="submit" >Update Tenant Information</button>
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
    $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
});

    $(document).on('change','#IDno', function(){ 
     var IDno=$('#IDno').val();
      $('#HudumaNo').val(IDno);
    
 });
</script>

@endpush