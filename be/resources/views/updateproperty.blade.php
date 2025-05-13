@extends('layouts.adminheader')
@section('title','Update Property | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-6">
    <h1 class="m-0">Update {{ $properties->Plotname }} Information</h1>
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
    <li class="breadcrumb-item"><a href="/properties/houses/{{$property->id}}">Houses</a></li>
   <li class="breadcrumb-item"><a href="/newproperties">New</a></li>
  <li class="breadcrumb-item active">Update {{ $properties->Plotname }}</li>
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
                          <option selected="">Update Property {{ $properties->Plotcode }}</option>
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
                           <h5 style="text-align: center;">Update: {{ $properties->Plotcode }} </h5>
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
                        <form role="form" class="form-horizontal" method="post" action="{{ route('plot.update',$properties->id) }}">
                        <div class="row">
                        @csrf
                        @method('PATCH')
                        <div class="col-sm-6">
                            <div class="card card-primary card-outline" style="margin-bottom: 5%;min-height: 300px;text-align: center;">
                              <div class="card-body">

                                <div class="form-group row">
                                    <label for="Plotname" class="col-md-4 col-form-label text-md-right">{{ __('Property Name') }}</label>

                                    <div class="col-md-8">
                                        <input id="Plotname" type="text" class="form-control @error('Plotname') is-invalid @enderror" name="Plotname" value="{{ $properties->Plotname }}" placeholder="Property Name" required autocomplete="Plotname" autofocus>

                                        @error('Plotname')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Plotarea" class="col-md-4 col-form-label text-md-right">{{ __('Property Area') }}</label>

                                    <div class="col-md-8">
                                        <input id="Plotarea" type="text" class="form-control @error('Plotarea') is-invalid @enderror" name="Plotarea" value="{{ $properties->Plotarea }}" placeholder="Property Area" required autocomplete="Plotarea" autofocus>

                                        @error('Plotarea')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Plotcode" class="col-md-4 col-form-label text-md-right">{{ __('Property Code') }}</label>

                                    <div class="col-md-8">
                                        <input id="Plotcode" type="text" class="form-control @error('Plotcode') is-invalid @enderror" name="Plotcode" value="{{ $properties->Plotcode }}" placeholder="Property Code" required autocomplete="Plotcode" autofocus>

                                        @error('Plotcode')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Plotaddr" class="col-md-4 col-form-label text-md-right">{{ __('Property Addr') }}</label>

                                    <div class="col-md-8">
                                        <textarea  id="Plotaddr" type="text" class="form-control @error('Plotaddr') is-invalid @enderror" name="Plotaddr" value="" placeholder="Property Address" required autocomplete="Plotaddr" autofocus>{{ $properties->Plotaddr }}</textarea>

                                        @error('Plotaddr')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Plotdesc" class="col-md-4 col-form-label text-md-right">{{ __('Property Desc') }}</label>

                                    <div class="col-md-8">
                                        <textarea id="Plotdesc" type="text" class="form-control @error('Plotdesc') is-invalid @enderror" name="Plotdesc" value="" placeholder="Property Description" required autocomplete="Plotdesc" autofocus>{{ $properties->Plotdesc }}</textarea>
                                        @error('Plotdesc')
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
                                    <label for="Waterbill" class="col-md-4 col-form-label text-md-right">{{ __('Water Bill') }}</label>

                                    <div class="col-md-8" style="background-color: white;color: black;padding-top: 10px;text-align:left;">
                                        @if($properties->Waterbill=='Monthly')
                                            <label style="margin-right: 8px;cursor: pointer;">
                                                <input type="radio" class="@error('Waterbill') is-invalid @enderror" checked name="Waterbill" value="Monthly" autocomplete="Waterbill"> Paid Monthly
                                            </label>
                                            <label style="margin-right: 8px;cursor: pointer;">
                                                <input type="radio" class="@error('Waterbill') is-invalid @enderror" name="Waterbill" value="None" autocomplete="Waterbill"> None
                                            </label>
                                        @else
                                                <label style="margin-right: 8px;cursor: pointer;">
                                                    <input type="radio" class="@error('Waterbill') is-invalid @enderror" name="Waterbill" value="Monthly" autocomplete="Waterbill"> Paid Monthly
                                                </label>
                                                <label style="margin-right: 8px;cursor: pointer;">
                                                    <input type="radio" class="@error('Waterbill') is-invalid @enderror" checked name="Waterbill" value="None" autocomplete="Waterbill"> None
                                                </label>
                                        @endif
                                        @error('Waterbill')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Waterdeposit" class="col-md-4 col-form-label text-md-right">{{ __('Water Deposit') }}</label>

                                    <div class="col-md-8"  style="background-color: white;color: black;padding-top: 10px;text-align:left;">
                                        @if($properties->Waterdeposit=='Once')
                                            <label style="margin-right: 8px;cursor: pointer;">
                                                 <input type="radio" class="@error('Waterdeposit') is-invalid @enderror" checked name="Waterdeposit" value="Once" autocomplete="Waterdeposit"> Paid Once
                                            </label>
                                            <label style="margin-right: 8px;cursor: pointer;">
                                                <input type="radio" class="@error('Waterdeposit') is-invalid @enderror" name="Waterdeposit" value="None" autocomplete="Waterdeposit"> None
                                            </label>
                                        @else
                                            <label style="margin-right: 8px;cursor: pointer;">
                                                 <input type="radio" class="@error('Waterdeposit') is-invalid @enderror" name="Waterdeposit" value="Once" autocomplete="Waterdeposit"> Paid Once
                                            </label>
                                            <label style="margin-right: 8px;cursor: pointer;">
                                                <input type="radio" class="@error('Waterdeposit') is-invalid @enderror" checked name="Waterdeposit" value="None" autocomplete="Waterdeposit"> None
                                            </label>
                                        @endif
                                        @error('Waterdeposit')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Deposit" class="col-md-4 col-form-label text-md-right">{{ __('Deposit') }}</label>

                                    <div class="col-md-8"  style="background-color: white;color: black;padding-top: 10px;text-align:left;">
                                        @if($properties->Deposit=='Once')
                                            <label style="margin-right: 8px;cursor: pointer;">
                                                 <input type="radio" class="@error('Deposit') is-invalid @enderror" checked name="Deposit" value="Once" autocomplete="Deposit"> Paid Once
                                            </label>
                                            <label style="margin-right: 8px;cursor: pointer;">
                                                <input type="radio" class="@error('Deposit') is-invalid @enderror" name="Deposit" value="None" autocomplete="Deposit"> None
                                            </label>
                                        @else
                                            <label style="margin-right: 8px;cursor: pointer;">
                                                 <input type="radio" class="@error('Deposit') is-invalid @enderror" name="Deposit" value="Once" autocomplete="Deposit"> Paid Once
                                            </label>
                                            <label style="margin-right: 8px;cursor: pointer;">
                                                <input type="radio" class="@error('Deposit') is-invalid @enderror" checked name="Deposit" value="None" autocomplete="Deposit"> None
                                            </label>
                                        @endif
                                        @error('Deposit')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Garbage" class="col-md-4 col-form-label text-md-right">{{ __('Garbage') }}</label>

                                    <div class="col-md-8"  style="background-color: white;color: black;padding-top: 10px;text-align:left;">
                                        @if($properties->Garbage=='Monthly')
                                            <label style="margin-right: 8px;cursor: pointer;">
                                                 <input type="radio" class="@error('Garbage') is-invalid @enderror" checked name="Garbage" value="Monthly" autocomplete="Garbage"> Paid Monthly
                                            </label>
                                            <label style="margin-right: 8px;cursor: pointer;">
                                                <input type="radio" class="@error('Garbage') is-invalid @enderror" name="Garbage" value="None" autocomplete="Garbage"> None
                                            </label>
                                        @else
                                            <label style="margin-right: 8px;cursor: pointer;">
                                                 <input type="radio" class="@error('Garbage') is-invalid @enderror" name="Garbage" value="Monthly" autocomplete="Garbage"> Paid Monthly
                                            </label>
                                            <label style="margin-right: 8px;cursor: pointer;">
                                                <input type="radio" class="@error('Garbage') is-invalid @enderror" checked name="Garbage" value="None" autocomplete="Garbage"> None
                                            </label>
                                        @endif
                                        @error('Garbage')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="Kplcdeposit" class="col-md-4 col-form-label text-md-right">{{ __('KPLC Deposit') }}</label>

                                    <div class="col-md-8"  style="background-color: white;color: black;padding-top: 10px;text-align:left;">
                                        @if($properties->Kplcdeposit=='Once')
                                            <label style="margin-right: 8px;cursor: pointer;">
                                                 <input type="radio" class="@error('Kplcdeposit') is-invalid @enderror" checked name="Kplcdeposit" value="Once" autocomplete="Kplcdeposit"> Paid Once
                                            </label>
                                            <label style="margin-right: 8px;cursor: pointer;">
                                                <input type="radio" class="@error('Kplcdeposit') is-invalid @enderror" name="Kplcdeposit" value="None" autocomplete="Kplcdeposit"> None
                                            </label>
                                        @else
                                            <label style="margin-right: 8px;cursor: pointer;">
                                                 <input type="radio" class="@error('Kplcdeposit') is-invalid @enderror" name="Kplcdeposit" value="Once" autocomplete="Kplcdeposit"> Paid Once
                                            </label>
                                            <label style="margin-right: 8px;cursor: pointer;">
                                                <input type="radio" class="@error('Kplcdeposit') is-invalid @enderror" checked name="Kplcdeposit" value="None" autocomplete="Kplcdeposit"> None
                                            </label>
                                        @endif
                                        @error('Kplcdeposit')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label for="Outsourced" class="col-md-4 col-form-label text-md-right">{{ __('Outsourced Water') }}</label>

                                    <div class="col-md-8"  style="background-color: white;color: black;padding-top: 10px;text-align:left;">
                                        @if($properties->Outsourced=='Yes')
                                            <label style="margin-right: 8px;cursor: pointer;">
                                                 <input type="radio" class="@error('Outsourced') is-invalid @enderror" checked name="Outsourced" value="Yes" autocomplete="Outsourced"> Yes
                                            </label>
                                            <label style="margin-right: 8px;cursor: pointer;">
                                                <input type="radio" class="@error('Outsourced') is-invalid @enderror" name="Outsourced" value="None" autocomplete="Outsourced"> None
                                            </label>
                                        @else
                                           <label style="margin-right: 8px;cursor: pointer;">
                                                 <input type="radio" class="@error('Outsourced') is-invalid @enderror" name="Outsourced" value="Yes" autocomplete="Outsourced"> Yes
                                            </label>
                                            <label style="margin-right: 8px;cursor: pointer;">
                                                <input type="radio" class="@error('Outsourced') is-invalid @enderror" checked name="Outsourced" value="None" autocomplete="Outsourced"> None
                                            </label>
                                        @endif
                                        @error('Outsourced')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                              </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <button  class="btn btn-warning btn-small float-right" name="submitplotbtn" id="submitplotbtn"  type="submit" >Update {{$properties->Plotname}}</button>
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
<script>
    $(document).ready(function() {
        $('#table').DataTable();
    });
$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
});
function confirmDelete(Plotname){
    $("#modal-body").html("Sure to Delete <b>"+ Plotname+" ?");
    $("#modal-info").modal('show');
    $('#Confirm').click(function(){
        $("#modal-info").modal('hide');
        // return true;
    });
    return false;
}
</script>

@endpush