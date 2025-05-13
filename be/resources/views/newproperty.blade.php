@extends('layouts.adminheader')
@section('title','New Properties | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-6">
    <h4 class="m-0">Add New Properties Information</h4>
</div><!-- /.col -->
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
  <li class="breadcrumb-item"><a href="/properties">Properties</a></li>
  <li class="breadcrumb-item active">New Property</li>
</ol>
</div><!-- /.col -->
@endsection
@section('content')
<div class="" style="">
    <div class="row justify-content-center" style="">
        <div class="col-md-12" style="">
            <div class="card" style="border: none;">
                <div class="card-header" style="background-color: transparent;">
                    <h5 style="text-align: center;">Create New Property Here ( 
                    @forelse($propertyinfo as $property)
                            {{$loop->count}}
                            @break
                        @empty
                            0
                        @endforelse
                        )
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
                        <form role="form" class="form-horizontal" method="POST" action="{{ route('plot.store') }}">
                        <div class="row">
                        @csrf
                        <div class="col-sm-6">
                            <div class="card card-primary card-outline" style="margin-bottom: 5%;min-height: 300px;text-align: center;">
                              <div class="card-body">

                                <div class="form-group row">
                                    <label for="Plotname" class="col-md-4 col-form-label text-md-right">{{ __('Property Name') }}</label>

                                    <div class="col-md-8">
                                        <input id="Plotname" type="text" class="form-control @error('Plotname') is-invalid @enderror" name="Plotname" value="{{ old('Plotname') }}" placeholder="Property Name" required autocomplete="Plotname" autofocus>

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
                                        <input id="Plotarea" type="text" class="form-control @error('Plotarea') is-invalid @enderror" name="Plotarea" value="{{ old('Plotarea') }}" placeholder="Property Area" required autocomplete="Plotarea" autofocus>

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
                                        <input id="Plotcode" type="text" class="form-control @error('Plotcode') is-invalid @enderror" name="Plotcode" value="{{ old('Plotcode') }}" placeholder="Property Code" required autocomplete="Plotcode" autofocus>

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
                                        <textarea  id="Plotaddr" type="text" class="form-control @error('Plotaddr') is-invalid @enderror" name="Plotaddr" placeholder="Property Address" required autocomplete="Plotaddr" autofocus>{{ old('Plotaddr') }}</textarea>

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
                                        <textarea id="Plotdesc" type="text" class="form-control @error('Plotdesc') is-invalid @enderror" name="Plotdesc" placeholder="Property Description" required autocomplete="Plotdesc" autofocus>{{ old('Plotdesc') }}</textarea>
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
                                        <label style="margin-right: 8px;cursor: pointer;">
                                             <input type="radio" class="@error('Waterbill') is-invalid @enderror" name="Waterbill" value="Monthly" autocomplete="Waterbill"> Paid Monthly
                                        </label>
                                        <label style="margin-right: 8px;cursor: pointer;">
                                            <input type="radio" class="@error('Waterbill') is-invalid @enderror" name="Waterbill" value="None" autocomplete="Waterbill"> None
                                        </label>
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
                                        <label style="margin-right: 8px;cursor: pointer;">
                                             <input type="radio" class="@error('Waterdeposit') is-invalid @enderror" name="Waterdeposit" value="Once" autocomplete="Waterdeposit"> Paid Once
                                        </label>
                                        <label style="margin-right: 8px;cursor: pointer;">
                                            <input type="radio" class="@error('Waterdeposit') is-invalid @enderror" name="Waterdeposit" value="None" autocomplete="Waterdeposit"> None
                                        </label>
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
                                        <label style="margin-right: 8px;cursor: pointer;">
                                             <input type="radio" class="@error('Deposit') is-invalid @enderror" name="Deposit" value="Once" autocomplete="Deposit"> Paid Once
                                        </label>
                                        <label style="margin-right: 8px;cursor: pointer;">
                                            <input type="radio" class="@error('Deposit') is-invalid @enderror" name="Deposit" value="None" autocomplete="Deposit"> None
                                        </label>
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
                                        <label style="margin-right: 8px;cursor: pointer;">
                                             <input type="radio" class="@error('Garbage') is-invalid @enderror" name="Garbage" value="Monthly" autocomplete="Garbage"> Paid Monthly
                                        </label>
                                        <label style="margin-right: 8px;cursor: pointer;">
                                            <input type="radio" class="@error('Garbage') is-invalid @enderror" name="Garbage" value="None" autocomplete="Garbage"> None
                                        </label>
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
                                        <label style="margin-right: 8px;cursor: pointer;">
                                             <input type="radio" class="@error('Kplcdeposit') is-invalid @enderror" name="Kplcdeposit" value="Once" autocomplete="Kplcdeposit"> Paid Once
                                        </label>
                                        <label style="margin-right: 8px;cursor: pointer;">
                                            <input type="radio" class="@error('Kplcdeposit') is-invalid @enderror" name="Kplcdeposit" value="None" autocomplete="Kplcdeposit"> None
                                        </label>
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
                                        <label style="margin-right: 8px;cursor: pointer;">
                                             <input type="radio" class="@error('Outsourced') is-invalid @enderror" name="Outsourced" value="Yes" autocomplete="Outsourced"> Yes
                                        </label>
                                        <label style="margin-right: 8px;cursor: pointer;">
                                            <input type="radio" class="@error('Outsourced') is-invalid @enderror" name="Outsourced" value="None" autocomplete="Outsourced"> None
                                        </label>
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
                            <button  class="btn btn-success btn-small btn-block" name="submitplotbtn" id="submitplotbtn"  type="submit" >Save Property Information</button>
                        </div>
                    </div>
                    </form>


                </div>


            </div>
        </div>
    </div>
</div>
@endsection