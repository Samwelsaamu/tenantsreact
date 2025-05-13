@extends('layouts.adminheader')
@section('title','New Houses Property | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-6">
    <h4 class="m-0">New House for: {{ $properties->Plotname }}</h4>
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
   <li class="breadcrumb-item"><a href="/properties/houses/{{$properties->id}}">Houses (@forelse($housesinfo as $houses)
                            {{$loop->count}}
                            @break
                        @empty
                            0
                        @endforelse
                        )</a></li>
  <li class="breadcrumb-item active">New Houses: {{ $properties->Plotname }}</li>
</ol>
</div><!-- /.col -->
@endsection
@section('content')
<div class="" style="">
    <div class="row justify-content-center" style="">
        <div class="col-md-12" style="">
            <div class="card" style="border: none;">
                <div class="card-header" style="background-color: transparent;">
                    <h5 style="text-align: center;">Create New House for {{ $properties->Plotname }} ( 
                    @forelse($housesinfo as $houses)
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
                    <form role="form" class="form-horizontal" method="POST" action="{{ route('house.store') }}">
                        <div class="row">
                        @csrf

                        <div class="col-sm-6">
                            <div class="card card-primary card-outline" style="margin-bottom: 5%;min-height: 300px;text-align: center;">
                              <div class="card-body">
                                 <input id="Plot" type="hidden" class="form-control @error('Plot') is-invalid @enderror" name="Plot" value="{{ $properties->id }}" placeholder="House Name" required autocomplete="Plot" autofocus>
                                 <input id="Plotcode" type="hidden" class="form-control @error('Plotcode') is-invalid @enderror" name="Plotcode" value="{{ $properties->Plotcode }}" placeholder="House Name" required autocomplete="Plotcode" autofocus>
                                <div class="form-group row">
                                    <label for="Housename" class="col-md-4 col-form-label text-md-right">{{ __('House Name') }}</label>

                                    <div class="col-md-8">
                                        <input id="Housename" type="text" class="form-control @error('Housename') is-invalid @enderror" name="Housename" value="{{ old('Housename') }}" placeholder="Code (No Property Code) i.e A2,D5,G4" required autocomplete="Housename" autofocus>

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
                                        <input id="Rent" type="text" class="form-control @error('Rent') is-invalid @enderror" name="Rent" value="{{ old('Rent') }}" placeholder="0.00" required autocomplete="Rent" autofocus>

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
                                        <input id="Deposit" type="text" class="form-control @error('Deposit') is-invalid @enderror" name="Deposit" value="{{ old('Deposit') }}" placeholder="0.00" required autocomplete="Deposit" autofocus>

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
                                         <input id="Kplc" type="text" class="form-control @error('Kplc') is-invalid @enderror" name="Kplc" value="{{ old('Kplc') }}" placeholder="0.00" required autocomplete="Kplc" autofocus>

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
                                        <input id="Water" type="text" class="form-control @error('Water') is-invalid @enderror" name="Water" value="{{ old('Water') }}" placeholder="0.00" required autocomplete="Water" autofocus>

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
                                        <input id="Lease" type="text" class="form-control @error('Lease') is-invalid @enderror" name="Lease" value="{{ old('Lease') }}" placeholder="0.00" required autocomplete="Lease" autofocus>

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
                                        <input id="Garbage" type="text" class="form-control @error('Garbage') is-invalid @enderror" name="Garbage" value="{{ old('Garbage') }}" placeholder="0.00" required autocomplete="Garbage" autofocus>

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
                                        <input id="DueDay" type="number" min="1" max="31" class="form-control @error('DueDay') is-invalid @enderror" name="DueDay" value="{{ old('DueDay') }}" placeholder="Due Day" required autocomplete="DueDay" autofocus>

                                        @error('DueDay')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <button  class="btn btn-success btn-small float-right" name="submitplotbtn" id="submitplotbtn"  type="submit" >Save House Information</button>
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
    
    $(document).on('change','#Rent', function(){ 
     var Rent=$('#Rent').val();
      $('#Deposit').val(Rent);
    });
</script>

@endpush