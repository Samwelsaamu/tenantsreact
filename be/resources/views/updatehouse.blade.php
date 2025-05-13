@extends('layouts.adminheader')
@section('title','Update Houses Property | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-6">
    <h4 class="m-0">Update House: {{ $thishouse->Housename }}</h4>
</div><!-- /.col -->
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="/properties">Properties (@forelse($propertyinfo as $property)
                            {{$loop->count}}
                            @break
                        @empty
                            0
                        @endforelse
                        )</a></li>
   <li class="breadcrumb-item"><a href="/properties/houses/{{$thishouse->Plot}}">Houses </a></li>
  <li class="breadcrumb-item active">Update Houses: {{ $thishouse->Housename }}</li>
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
                          <option value="">Update Tenant</option>
                          @forelse($tenantsinfo as $tenants)
                          <a href="{{ route('tenant.edit', $tenants->id)}}" class="btn btn-primary btn-sm" style="padding: 4px;font-size: 12px;"><i class="fa fa-edit"></i></a>
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
                          <option selected="">Update House {{ $thishouse->Housename }}</option>
                            @forelse($propertyhouses as $dropphouses)
                                <option value="{{ route('house.edit', $dropphouses->id)}}">{{ $loop->index+1 }}. {{ $dropphouses->Housename }} ({{ $dropphouses->Status }})</option>
                            @empty
                              <option>No Tenant Found</option>
                            @endforelse
                      </select>
                      </div>
                      <div class="col-sm-2">
                          <h6 style="text-align: center;">Update {{ $thishouse->Housename }}  </h6>
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

                    <form role="form" class="form-horizontal" method="POST" action="{{ route('house.update',$thishouse->id) }}">
                        <div class="row">
                        @csrf
                        @method('PATCH')
                        <div class="col-sm-6">
                            <div class="card card-primary card-outline" style="margin-bottom: 5%;min-height: 300px;text-align: center;">
                              <div class="card-body">
                                 <input id="Plot" type="hidden" class="form-control @error('Plot') is-invalid @enderror" name="Plot" value="{{ $thishouse->Plot }}" placeholder="House Name" required autocomplete="Plot" autofocus>
                                 
                                <div class="form-group row">
                                    <label for="Housename" class="col-md-4 col-form-label text-md-right">{{ __('House Name') }}</label>

                                    <div class="col-md-8">
                                        <input id="Housename" type="text" class="form-control @error('Housename') is-invalid @enderror" name="Housename" value="{{ $thishouse->Housename }}" placeholder="House Name" required autocomplete="Housename" autofocus>

                                        @error('Housename')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Rent" class="col-md-4 col-form-label text-md-right">{{ __('House Rent') }}</label>

                                    <div class="col-md-8">
                                        <input id="Rent" type="text" class="form-control @error('Rent') is-invalid @enderror" name="Rent" value="{{ $thishouse->Rent }}" placeholder="0.00" required autocomplete="Rent" autofocus>

                                        @error('Rent')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Deposit" class="col-md-4 col-form-label text-md-right">{{ __('House Deposit') }}</label>

                                    <div class="col-md-8">
                                        <input id="Deposit" type="text" class="form-control @error('Deposit') is-invalid @enderror" name="Deposit" value="{{ $thishouse->Deposit }}" placeholder="0.00" required autocomplete="Deposit" autofocus>

                                        @error('Deposit')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label for="Kplc" class="col-md-4 col-form-label text-md-right">{{ __('KPLC Deposit') }}</label>

                                    <div class="col-md-8">
                                         <input id="Kplc" type="text" class="form-control @error('Kplc') is-invalid @enderror" name="Kplc" value="{{ $thishouse->Kplc }}" placeholder="0.00" required autocomplete="Kplc" autofocus>

                                        @error('Kplc')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Water" class="col-md-4 col-form-label text-md-right">{{ __('Water Deposit') }}</label>

                                    <div class="col-md-8">
                                        <input id="Water" type="text" class="form-control @error('Water') is-invalid @enderror" name="Water" value="{{ $thishouse->Water }}" placeholder="0.00" required autocomplete="Water" autofocus>

                                        @error('Water')
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
                            <div class="card card-primary card-outline" style="margin-bottom: 5%;min-height: 300px;text-align: center;">
                              <div class="card-body">

                                <div class="form-group row">
                                    <label for="Lease" class="col-md-4 col-form-label text-md-right">{{ __('Lease Amount') }}</label>

                                    <div class="col-md-8">
                                        <input id="Lease" type="text" class="form-control @error('Lease') is-invalid @enderror" name="Lease" value="{{ $thishouse->Lease }}" placeholder="0.00" required autocomplete="Lease" autofocus>

                                        @error('Lease')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Garbage" class="col-md-4 col-form-label text-md-right">{{ __('Garbage Deposit') }}</label>

                                    <div class="col-md-8">
                                        <input id="Garbage" type="text" class="form-control @error('Garbage') is-invalid @enderror" name="Garbage" value="{{ $thishouse->Garbage }}" placeholder="0.00" required autocomplete="Garbage" autofocus>

                                        @error('Garbage')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="DueDay" class="col-md-4 col-form-label text-md-right">{{ __('Due Date') }}</label>

                                    <div class="col-md-8">
                                        <input id="DueDay" type="number" min="1" max="31" class="form-control @error('DueDay') is-invalid @enderror" name="DueDay" value="{{ $thishouse->DueDay }}" placeholder="Due Day" required autocomplete="DueDay" autofocus>

                                        @error('DueDay')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <button  class="btn btn-warning btn-small float-right" name="submitplotbtn" id="submitplotbtn"  type="submit" >Update House {{$thishouse->Housename}}</button>
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
    $(document).on('change','#Rent', function(){ 
     var Rent=$('#Rent').val();
      $('#Deposit').val(Rent);
    
 });
</script>

@endpush