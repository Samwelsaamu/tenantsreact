@extends('layouts.adminheader')
@section('title','Add Waterbill | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-6">
    <h5 class="m-0">Add Waterbill to 
                            @if($thisothers!="")
                                {{$thisothers}}
                              @endif
                            @if($thisproperty!="")
                              {{$thisproperty->Plotcode}}/
                            @endif
                            {{$watermonth}}
                             @if($thishouse!="")
                                  /{{$thishouse->Housename}}
                                @endif
                            @if($thistenant!="")
                              /{{$thistenant->Fname}}
                            @endif
    </h5>
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
  <li class="breadcrumb-item"><a href="/properties/tenants">Tenants </a></li>
  <li class="breadcrumb-item active">Waterbill(
                              @if($thisothers!="")
                                {{$thisothers}}
                              @endif
                              @if($thisproperty!="")
                              {{$thisproperty->Plotcode}}
                                  @endif
                                 /{{$watermonth}}
                              @if($thishouse!="")
                                    /{{$thishouse->Housename}}
                                  @endif
                              @if($thistenant!="")
                                /{{$thistenant->Fname}}
                              @endif)</li>
</ol>
</div><!-- /.col -->
@endsection
@section('css')
     <!-- DataTables -->
     
@endsection
@section('sidebarlinks')
  <!-- <li class="nav-item">
    <a href="/dashboard" class="nav-link">
          <i class="fa fa-home nav-icon"></i>
          <p>Dashboard</p>
        </a>
  </li> -->

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
            <a href="/dashboard" class="nav-link">
            <i class="fa fa-home nav-icon"></i>
            <p>Dashboard</p>
            </a>
      </li>

      <li class="nav-item">
        <a href="/properties/manage" class="nav-link">
          <i class="fa fa-sitemap nav-icon"></i>
          <p>Manage Properties</p>
        </a>
      </li>

      <li class="nav-item">
        <a href="/properties/mgr/tenants" class="nav-link">
          <i class="fa fa-users nav-icon"></i>
          <p>Manage Tenants</p>
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
        <a href="/properties/add/waterbill" class="nav-link active">
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
                <div class="card-header" style="padding-top: 10px;">
                    
                    <div class="form-group row">
                      <div class="col-sm-4 col-6">
                        <select class="form-control select2" name="allproperties" onchange="location=this.value;" style="width: 100%;">
                          <option value="">Select Property</option>
                          @if($thisothers!="" && $watermonth!="")
                            <option value="/properties/add/waterbill/Others/{{$watermonth}}" selected>0. Other Waterbill</option>
                          @elseif($thisothers=="" && $watermonth!="")
                            <option value="/properties/add/waterbill/Others/{{$watermonth}}">0. Other Waterbill</option>
                          @else($thisothers=="" && $watermonth=="")
                            <option value="/properties/add/waterbill/Others/{{App\Http\Controllers\TenantController::getAddWaterMonthsThis()}}">0. Other Waterbill</option>
                          @endif
                          @forelse($propertyinfo as $propertys)
                            @if($thisproperty!="" && $watermonth!="")
                              @if($thisproperty->id==$propertys->id)
                                <option value="/properties/add/waterbill/{{ $propertys->id }}/{{$watermonth}}" selected>{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                              @else
                                <option value="/properties/add/waterbill/{{ $propertys->id }}/{{$watermonth}}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                              @endif
                            @elseif($thisproperty!="" && $watermonth=="")
                              @if($thisproperty->id==$propertys->id)
                                <option value="/properties/add/waterbill/{{ $propertys->id }}/{{App\Http\Controllers\TenantController::getAddWaterMonthsThis()}}" selected>{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                              @else
                                <option value="/properties/add/waterbill/{{ $propertys->id }}/{{App\Http\Controllers\TenantController::getAddWaterMonthsThis()}}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                              @endif
                            @else
                              <option value="/properties/add/waterbill/{{$propertys->id}}/{{App\Http\Controllers\TenantController::getAddWaterMonthsThis()}}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                            @endif
                          @empty
                            <option>No Property Found</option>
                          @endforelse
                        </select>
                      </div>


                    <div class="col-sm-4 col-6">
                      <select class="form-control select2" name="allproperties" onchange="location=this.value;" style="width: 100%;">
                        <option value="">Choose Month</option>
                          @if($thisproperty!="" && $watermonth!="")
                            {{App\Http\Controllers\TenantController::getAddWaterMonths($thisproperty->id,$watermonth)}}
                          @elseif($thisproperty!="" && $watermonth=="")
                            {{App\Http\Controllers\TenantController::getAddWaterMonths($thisproperty->id,App\Http\Controllers\TenantController::getAddWaterMonthsThis())}}
                          @elseif($thisothers!="" && $watermonth!="")
                            {{App\Http\Controllers\TenantController::getAddWaterOtherMonths($watermonth)}}
                          @endif
                      </select>
                    </div>

                    <div class="col-sm-4 col-6">
                      <select class="form-control select2" name="allproperties" onchange="location=this.value;" style="width: 100%;">
                        <option value="">Choose House/ Person</option>
                          @if($thisothers!="" && $watermonth!="")
                            @forelse($tenantinfo as $tenants)
                              @if($thistenant!="")
                                @if($thistenant->id==$tenants->id)
                                  <option value="/properties/add/waterbill/Others/{{$watermonth}}/{{$tenants->id}}" selected>{{ $loop->index+1 }}. {{ $tenants->Fname.' '.$tenants->Oname }}</option>
                                @else
                                  <option value="/properties/add/waterbill/Others/{{$watermonth}}/{{$tenants->id}}">{{ $loop->index+1 }}. {{ $tenants->Fname.' '.$tenants->Oname }}</option>
                                @endif
                              @else
                                <option value="/properties/add/waterbill/Others/{{$watermonth}}/{{$tenants->id}}">{{ $loop->index+1 }}. {{ $tenants->Fname.' '.$tenants->Oname }}</option>
                              @endif
                            @empty
                              <option value="">No Tenant Found</option>
                            @endforelse
                          @endif
                          @if($thisproperty!="" && $watermonth!="")
                            @forelse($houseinfo as $houses)
                              @if($thishouse!="")
                                @if($thishouse->id==$houses->id)
                                  <option value="/properties/add/waterbill/{{ $thisproperty->id }}/{{$watermonth}}/{{$houses->id}}" selected>{{ $loop->index+1 }}. {{ $houses->Housename }} ({{ $houses->Status }})-->{{App\Http\Controllers\TenantController::getRecordedStatus($houses->id,$watermonth)}}</option>
                                @else
                                  <option value="/properties/add/waterbill/{{ $thisproperty->id }}/{{$watermonth}}/{{$houses->id}}">{{ $loop->index+1 }}. {{ $houses->Housename }} ({{ $houses->Status }})-->{{App\Http\Controllers\TenantController::getRecordedStatus($houses->id,$watermonth)}}</option>
                                @endif
                              @else
                                <option value="/properties/add/waterbill/{{ $thisproperty->id }}/{{$watermonth}}/{{$houses->id}}">{{ $loop->index+1 }}. {{ $houses->Housename }} ({{ $houses->Status }})-->{{App\Http\Controllers\TenantController::getRecordedStatus($houses->id,$watermonth)}}</option>
                              @endif
                            @empty
                              <option value="">No House Found</option>
                            @endforelse
                          @else
                              <option value="">No Property or Month Selected</option>
                          @endif
                      </select>
                    </div>

                    </div>
                </div>

                <div class="card-body" style="padding-top: 2px;">
                    @if(\Session::has('success'))
                    <div class="alert alert-success" role="alert">
                        <h4>{{ \Session::get('success') }}</h4>
                    </div>
                    @endif
                    @if(\Session::has('error'))
                    <div class="alert alert-danger" role="alert">
                        <h4>{{ \Session::get('error') }}</h4>
                    </div>
                    @endif
                    @if(\Session::has('dbError'))
                    <div class="alert alert-danger" role="alert">
                        <h4>{{ \Session::get('dbError') }}</h4>
                    </div>
                    @endif
                    <div class="row">
                   
                    <div class="col-sm-12" style="padding: 2px;margin: 0px;">
                     
                      <div class="card direct-chat direct-chat-primary" style="padding: 2px;margin: 5px;">
                      <div class="card-header bg-info" style="padding: 4px;">
                        
                        <span class="" style="font-size: 15px;">Add Waterbill / 
                                    @if($thisothers!="")
                                      {{$thisothers}}
                                    @endif
                                    @if($thisproperty!="")
                                      {{$thisproperty->Plotname}}
                                    @endif
                                    /
                                    @if($watermonth!="")
                                      {{$watermonth}}
                                    @endif
                                    /@if($thishouse!="")
                                      {{$thishouse->Housename}}
                                    @endif
                                  @if($thistenant!="")
                                      /{{$thistenant->Fname}}
                                    @endif
                        </span>

                        <div class="card-tools">
                          <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                          </button>
                          <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                          </button>
                        </div>
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body">
                        <div class="row">
                        <div class="col-sm-12">
                        <div class="card-body bg-light" style="padding: 5px;margin: 3px;">
                          <span class="text-light"><b>
                          </span>
                          <div class="card-body">
                            <div class="direct-chat-msg right">
                              @if($thisothers!="" && $watermonth!="" && $thistenant=="")
                                <div class="row">
                                <div class="col-sm-12">
                                  <table border="1" class="table table-hover" id="example"><thead>
                                    <tr style="color:white;background-color:#77B5ED;">
                                        <th>Sno</th>
                                        <th>Tenant</th>
                                        <th>Month</th>
                                        <th>Previous</th>
                                        <th>Current</th>
                                        <th>Cost</th>
                                        <th>Units</th>
                                        <th>Total</th>
                                      </tr></thead><tbody>
                                      @if($waterbill!="")
                                        @foreach($waterbill as $waterpreview)
                                            <tr style="padding:0px;margin:2px;background-color:#FFFFFF;">
                                              <td>{{$loop->index+1}}</td>
                                              <td>{{$waterpreview['tenantname']}}</td>
                                              <td>{{$waterpreview['Month']}}</td>
                                              <td>{{$waterpreview['Previous']}}</td>
                                              <td>{{$waterpreview['Current']}}</td>
                                              <td>{{$waterpreview['Cost']}}</td>
                                              <td>{{$waterpreview['Units']}}</td>
                                              <td>{{$waterpreview['Total']+$waterpreview['Total_OS']}}</td>
                                          </tr>
                                        @endforeach
                                      @else
                                        <h4>Please Select Property, Month and House to Enter Wtaer Bill</h4>
                                      @endif
                                    </tbody>
                                  </table>
                                </div>
                                </div>

                              @elseif($thisothers!="" && $watermonth=="")
                                  <h4>Please Select Month</h4>
                              @endif

                              <!-- start selcted house details -->
                              @if($thishouse!="")
                              @forelse($waterbill as $previouswater)
                              @if($thismonthwaterstatus=='')
                              <form role="form" class="form-horizontal" method="POST" action="/properties/save/waterbill/new" onsubmit="return confirmOperation(this,'Save Tenant Water Bill : @if($thishouse!=''){{$thishouse->Housename}}({{$thishouse->Status}}) @endif','Save Water Bill for @if($thisproperty!=''){{$thisproperty->Plotname}}@endif @if($thishouse!='')({{$thishouse->Housename}}) @endif <br/> for Month of {{$watermonth}} ');">
                                @csrf
                                <div class="row">
                                
                                <input type="hidden" name="pid" value="{{$thisproperty->id}}">
                                <input type="hidden" name="month" value="{{$watermonth}}">
                                <input type="hidden" name="hid" value="{{$thishouse->id}}">
                                <input type="hidden" name="Total_OS" value="0.00">


                                <div class="col-sm-6">
                                    <div class="card card-primary card-outline bg-info" style="margin-bottom: 5%;text-align: center;">
                                      <div class="card-body">
                                        <div class="form-group row">
                                            <label for="Previous" class="col-md-4 col-form-label text-md-right">{{ __('Previous') }}</label>

                                            <div class="col-md-8">
                                                <input id="Previous" type="text" class="form-control @error('Previous') is-invalid @enderror" name="Previous" value="{{ $previouswater->Current }}" placeholder="Previous" required autocomplete="Previous" autofocus>

                                                @error('Previous')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="Current" class="col-md-4 col-form-label text-md-right">{{ __('Current') }}</label>

                                            <div class="col-md-8">
                                                <input id="Current" type="text" class="form-control @error('Current') is-invalid @enderror" name="Current" value="{{ old('Current') }}" placeholder="0.00" required autocomplete="Current" autofocus>

                                                @error('Current')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="Cost" class="col-md-4 col-form-label text-md-right">{{ __('Cost') }}</label>

                                            <div class="col-md-8">
                                                <input id="Cost" type="text" class="form-control @error('Cost') is-invalid @enderror" name="Cost" value="{{ $previouswater->Cost }}" placeholder="0.00" required autocomplete="Cost" autofocus>

                                                @error('Cost')
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
                                    <div class="card card-primary card-outline bg-info" style="margin-bottom: 5%;text-align: center;">
                                      <div class="card-body">

                                        <div class="form-group row">
                                            <label for="NPUnits" class="col-md-4 col-form-label text-md-right">{{ __('Units') }}</label>

                                            <div class="col-md-8">
                                                <input id="Units" type="text" class="form-control @error('Units') is-invalid @enderror" name="Units" value="{{ old('Units') }}" placeholder="0" readonly required autocomplete="Units" autofocus>

                                                @error('Units')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="Total" class="col-md-4 col-form-label text-md-right">{{ __('Total') }}</label>

                                            <div class="col-md-8">
                                                <input id="Total" type="text" class="form-control @error('Total') is-invalid @enderror" name="Total" value="{{ old('Total') }}" placeholder="0.00" readonly required autocomplete="Total" autofocus>

                                                @error('Total')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                         @if($thisproperty->Outsourced=="Yes")
                                        <div class="form-group row">
                                            <label for="Total_OS" class="col-md-4 col-form-label text-md-right">{{ __('Total_OS') }}</label>

                                            <div class="col-md-8">
                                                <input id="Total_OS" type="text" class="form-control @error('Total_OS') is-invalid @enderror" name="Total_OS" value="0" placeholder="0.00" required autocomplete="Total_OS" autofocus>

                                                @error('Total_OS')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        @endif

                                        <div class="form-group row">
                                            <label for="Tenant" class="col-md-4 col-form-label text-md-right">{{ __('Tenant') }}</label>

                                            <div class="col-md-8">
                                                <select class="form-control select2" name="Tenant" required style="width: 100%;">
                                                    @forelse($agreements as $tenants)
                                                          @if(App\Http\Controllers\TenantController::TenantStatus($tenants->Tenant)!="Vacated")
                                                            @if(App\Http\Controllers\TenantController::checkCurrentAssigned($tenants->Tenant,$thishouse->id,$tenants->DateAssigned)=="Current")
                                                              <option value="{{$tenants->Tenant}}">
                                                                {{App\Http\Controllers\TenantController::TenantNames($tenants->Tenant)}}({{App\Http\Controllers\TenantController::TenantStatus($tenants->Tenant)}}) {{App\Http\Controllers\TenantController::checkCurrentAssigned($tenants->Tenant,$thishouse->id,$tenants->DateAssigned)}}
                                                              </option>
                                                            @endif
                                                          @endif
                                                    @empty
                                                      <option value="{{$thishouse->id}}">No Tenant</option>
                                                    @endforelse
                                                  </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <button  class="btn btn-success btn-small" name="submitplotbtn" id="submitplotbtn"  type="submit" >Save Waterbill for @if($watermonth!="")
                                              {{$watermonth}}
                                            @endif
                                            /@if($thishouse!="")
                                              {{$thishouse->Housename}}({{$thishouse->Status}})
                                            @endif</button>
                                            </div>
                                        </div>

                                        
                                      </div>
                                    </div>
                                </div>
                                
                            </div>
                            </form>
                            @else
                              @forelse($thiswaterbill as $currentwater)
                              <form role="form" class="form-horizontal" method="POST" action="/properties/save/waterbill/update" onsubmit="return confirmOperation(this,'Update Tenant Water Bill : @if($thishouse!=''){{$thishouse->Housename}}({{$thishouse->Status}}) @endif','Update Water Bill for @if($thisproperty!=''){{$thisproperty->Plotname}}@endif @if($thishouse!='')({{$thishouse->Housename}}) @endif <br/> for Month of {{$watermonth}} ');">
                                <div class="row">
                                @csrf
                                  <input type="hidden" name="pid" value="{{$thisproperty->id}}">
                                  <input type="hidden" name="waterid" value="{{$currentwater->id}}">
                                  <input type="hidden" name="month" value="{{$watermonth}}">
                                  <input type="hidden" name="hid" value="{{$thishouse->id}}">
                                  <input type="hidden" name="Tenant" value="{{$currentwater->Tenant}}">
                                  <input type="hidden" name="Total_OS" value="0.00">
                                <div class="col-sm-6">
                                    <div class="card card-primary card-outline bg-warning" style="margin-bottom: 5%;text-align: center;">
                                      <div class="card-body">
                                        <div class="form-group row">
                                            <label for="Previous" class="col-md-4 col-form-label text-md-right">{{ __('Previous') }}</label>

                                            <div class="col-md-8">
                                                <input id="UPPrevious" type="text" class="form-control @error('Previous') is-invalid @enderror" name="Previous" value="{{ $currentwater->Previous }}" placeholder="Previous" required autocomplete="Previous" autofocus>

                                                @error('Previous')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="Current" class="col-md-4 col-form-label text-md-right">{{ __('Current') }}</label>

                                            <div class="col-md-8">
                                                <input id="UPCurrent" type="text" class="form-control @error('Current') is-invalid @enderror" name="Current" value="{{ $currentwater->Current }}" placeholder="0.00" required autocomplete="Current" autofocus>

                                                @error('Current')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="Cost" class="col-md-4 col-form-label text-md-right">{{ __('Cost') }}</label>

                                            <div class="col-md-8">
                                                <input id="UPCost" type="text" class="form-control @error('Cost') is-invalid @enderror" name="Cost" value="{{ $currentwater->Cost }}" placeholder="0.00" required autocomplete="Cost" autofocus>

                                                @error('Cost')
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
                                    <div class="card card-primary card-outline bg-warning" style="margin-bottom: 5%;text-align: center;">
                                      <div class="card-body">

                                        <div class="form-group row">
                                            <label for="Units" class="col-md-4 col-form-label text-md-right">{{ __('Units') }}</label>

                                            <div class="col-md-8">
                                                <input id="UPUnits" type="text" class="form-control @error('Units') is-invalid @enderror" name="Units" value="{{ $currentwater->Units }}" placeholder="0" readonly required autocomplete="Units" autofocus>

                                                @error('Units')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="Total" class="col-md-4 col-form-label text-md-right">{{ __('Total') }}</label>

                                            <div class="col-md-8">
                                                <input id="UPTotal" type="text" class="form-control @error('Total') is-invalid @enderror" name="Total" value="{{ $currentwater->Total }}" placeholder="0.00" readonly required autocomplete="Total" autofocus>

                                                @error('Total')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                         @if($thisproperty->Outsourced=="Yes")
                                          <div class="form-group row">
                                              <label for="Total_OS" class="col-md-4 col-form-label text-md-right">{{ __('Total_OS') }}</label>

                                              <div class="col-md-8">
                                                  <input id="UPTotal_OS" type="text" class="form-control @error('Total_OS') is-invalid @enderror" name="Total_OS" value="{{ $currentwater->Total_OS }}" placeholder="0.00" required autocomplete="Total_OS" autofocus>

                                                  @error('Total_OS')
                                                      <span class="invalid-feedback" role="alert">
                                                          <strong>{{ $message }}</strong>
                                                      </span>
                                                  @enderror
                                              </div>
                                          </div>
                                          @endif

                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <button  class="btn btn-danger btn-small " name="submitplotbtn" id="submitplotbtn"  type="submit" >Update Waterbill for @if($watermonth!="")
                                              {{$watermonth}}
                                            @endif
                                            /@if($thishouse!="")
                                              {{$thishouse->Housename}}({{$thishouse->Status}})
                                            @endif</button>
                                            </div>
                                        </div>

                                        
                                      </div>
                                    </div>
                                </div>
                                
                            </div>
                            </form>
                            @empty

                            @endforelse
                            @endif

                            @empty
                              @if($thismonthwaterstatus=='')
                              <!-- //No Previpous records and not recorded -->
                              <form role="form" class="form-horizontal" method="post" action="/properties/save/waterbill/new">
                                @csrf
                                <div class="row">
                                <input type="hidden" name="pid" value="{{$thisproperty->id}}">
                                <input type="hidden" name="month" value="{{$watermonth}}">
                                <input type="hidden" name="hid" value="{{$thishouse->id}}">
                                <input type="hidden" name="Total_OS" value="0.00">
                                <div class="col-sm-6">
                                    <div class="card card-primary card-outline bg-info" style="margin-bottom: 5%;text-align: center;">
                                      <div class="card-body">
                                        <div class="form-group row">
                                            <label for="Previous" class="col-md-4 col-form-label text-md-right">{{ __('Previous') }}</label>

                                            <div class="col-md-8">
                                                <input id="NPPrevious" type="text" class="form-control @error('Previous') is-invalid @enderror" name="Previous" value="0.00" placeholder="Previous" required autocomplete="Previous" autofocus>

                                                @error('Previous')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="Current" class="col-md-4 col-form-label text-md-right">{{ __('Current') }}</label>

                                            <div class="col-md-8">
                                                <input id="NPCurrent" type="text" class="form-control @error('Current') is-invalid @enderror" name="Current" value="0.00" placeholder="0.00" required autocomplete="Current" autofocus>

                                                @error('Current')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="Cost" class="col-md-4 col-form-label text-md-right">{{ __('Cost') }}</label>

                                            <div class="col-md-8">
                                                <input id="NPCost" type="text" class="form-control @error('Cost') is-invalid @enderror" name="Cost" value="0.00" placeholder="0.00" required autocomplete="Cost" autofocus>

                                                @error('Cost')
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
                                    <div class="card card-primary card-outline bg-info" style="margin-bottom: 5%;text-align: center;">
                                      <div class="card-body">

                                        <div class="form-group row">
                                            <label for="Units" class="col-md-4 col-form-label text-md-right">{{ __('Units') }}</label>

                                            <div class="col-md-8">
                                                <input id="NPUnits" type="text" class="form-control @error('Units') is-invalid @enderror" name="Units" value="0.00" placeholder="0" required autocomplete="Units" autofocus>

                                                @error('Units')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="Total" class="col-md-4 col-form-label text-md-right">{{ __('Total') }}</label>

                                            <div class="col-md-8">
                                                <input id="NPTotal" type="text" class="form-control @error('Total') is-invalid @enderror" name="Total" value="0.00" placeholder="0.00" required autocomplete="Total" autofocus>

                                                @error('Total')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        @if($thisproperty->Outsourced=="Yes")
                                        <div class="form-group row">
                                            <label for="Total_OS" class="col-md-4 col-form-label text-md-right">{{ __('Total_OS') }}</label>

                                            <div class="col-md-8">
                                                <input id="NPTotal_OS" type="text" class="form-control @error('Total_OS') is-invalid @enderror" name="Total_OS" value="0" placeholder="0.00" required autocomplete="Total_OS" autofocus>

                                                @error('Total_OS')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        @endif

                                        <div class="form-group row">
                                            <label for="Tenant" class="col-md-4 col-form-label text-md-right">{{ __('Tenant') }}</label>

                                            <div class="col-md-8">
                                                <select class="form-control select2" name="Tenant" required style="width: 100%;">
                                                      @forelse($agreements as $tenants)
                                                          @if(App\Http\Controllers\TenantController::TenantStatus($tenants->Tenant)!="Vacated")
                                                            @if(App\Http\Controllers\TenantController::checkCurrentAssigned($tenants->Tenant,$thishouse->id,$tenants->DateAssigned)=="Current")
                                                              <option value="{{$tenants->Tenant}}">
                                                                {{App\Http\Controllers\TenantController::TenantNames($tenants->Tenant)}}({{App\Http\Controllers\TenantController::TenantStatus($tenants->Tenant)}}) {{App\Http\Controllers\TenantController::checkCurrentAssigned($tenants->Tenant,$thishouse->id,$tenants->DateAssigned)}}
                                                              </option>
                                                            @endif
                                                          @endif
                                                      @empty
                                                        <option value="{{$thishouse->id}}">No Tenant</option>
                                                      @endforelse
                                                    </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <button  class="btn btn-success btn-small" name="submitplotbtn" id="submitplotbtn"  type="submit" >Save Waterbill Without Previous for @if($watermonth!="")
                                              {{$watermonth}}
                                            @endif
                                            /@if($thishouse!="")
                                              {{$thishouse->Housename}}({{$thishouse->Status}})
                                            @endif</button>
                                            </div>
                                        </div>

                                        
                                      </div>
                                    </div>
                                </div>
                                
                            </div>
                            </form>
                            @else
                            <!-- recorded but has no previous records -->
                             @forelse($thiswaterbill as $currentwater)
                              <form role="form" class="form-horizontal" method="POST" action="/properties/save/waterbill/update">
                                <div class="row">
                                @csrf
                                  <input type="hidden" name="pid" value="{{$thisproperty->id}}">
                                  <input type="hidden" name="waterid" value="{{$currentwater->id}}">
                                  <input type="hidden" name="month" value="{{$watermonth}}">
                                  <input type="hidden" name="hid" value="{{$thishouse->id}}">
                                  <input type="hidden" name="Tenant" value="{{$currentwater->Tenant}}">
                                  <input type="hidden" name="Total_OS" value="0.00">
                                <div class="col-sm-6">
                                    <div class="card card-primary card-outline bg-warning" style="margin-bottom: 5%;text-align: center;">
                                      <div class="card-body">
                                        <div class="form-group row">
                                            <label for="Previous" class="col-md-4 col-form-label text-md-right">{{ __('Previous') }}</label>

                                            <div class="col-md-8">
                                                <input id="UPPrevious" type="text" class="form-control @error('Previous') is-invalid @enderror" name="Previous" value="{{ $currentwater->Previous }}" placeholder="Previous" required autocomplete="Previous" autofocus>

                                                @error('Previous')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="Current" class="col-md-4 col-form-label text-md-right">{{ __('Current') }}</label>

                                            <div class="col-md-8">
                                                <input id="UPCurrent" type="text" class="form-control @error('Current') is-invalid @enderror" name="Current" value="{{ $currentwater->Current }}" placeholder="0.00" required autocomplete="Current" autofocus>

                                                @error('Current')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="Cost" class="col-md-4 col-form-label text-md-right">{{ __('Cost') }}</label>

                                            <div class="col-md-8">
                                                <input id="UPCost" type="text" class="form-control @error('Cost') is-invalid @enderror" name="Cost" value="{{ $currentwater->Cost }}" placeholder="0.00" required autocomplete="Cost" autofocus>

                                                @error('Cost')
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
                                    <div class="card card-primary card-outline bg-warning" style="margin-bottom: 5%;text-align: center;">
                                      <div class="card-body">

                                        <div class="form-group row">
                                            <label for="Units" class="col-md-4 col-form-label text-md-right">{{ __('Units') }}</label>

                                            <div class="col-md-8">
                                                <input id="UPUnits" type="text" class="form-control @error('Units') is-invalid @enderror" name="Units" value="{{ $currentwater->Units }}" placeholder="0" readonly required autocomplete="Units" autofocus>

                                                @error('Units')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="Total" class="col-md-4 col-form-label text-md-right">{{ __('Total') }}</label>

                                            <div class="col-md-8">
                                                <input id="UPTotal" type="text" class="form-control @error('Total') is-invalid @enderror" name="Total" value="{{ $currentwater->Total }}" placeholder="0.00" readonly required autocomplete="Total" autofocus>

                                                @error('Total')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                         @if($thisproperty->Outsourced=="Yes")
                                          <div class="form-group row">
                                              <label for="Total_OS" class="col-md-4 col-form-label text-md-right">{{ __('Total_OS') }}</label>

                                              <div class="col-md-8">
                                                  <input id="UPTotal_OS" type="text" class="form-control @error('Total_OS') is-invalid @enderror" name="Total_OS" value="{{ $currentwater->Total_OS }}" placeholder="0.00" required autocomplete="Total_OS" autofocus>

                                                  @error('Total_OS')
                                                      <span class="invalid-feedback" role="alert">
                                                          <strong>{{ $message }}</strong>
                                                      </span>
                                                  @enderror
                                              </div>
                                          </div>
                                          @endif

                                          <div class="form-group row">
                                            <label for="Tenant" class="col-md-4 col-form-label text-md-right">{{ __('Tenant') }}</label>

                                            <div class="col-md-8">
                                                <select class="form-control select2" name="Tenant" required style="width: 100%;">
                                                      @forelse($agreements as $tenants)
                                                          @if(App\Http\Controllers\TenantController::TenantStatus($tenants->Tenant)!="Vacated")
                                                            @if(App\Http\Controllers\TenantController::checkCurrentAssigned($tenants->Tenant,$thishouse->id,$tenants->DateAssigned)=="Current")
                                                              <option value="{{$tenants->Tenant}}">
                                                                {{App\Http\Controllers\TenantController::TenantNames($tenants->Tenant)}}({{App\Http\Controllers\TenantController::TenantStatus($tenants->Tenant)}}) {{App\Http\Controllers\TenantController::checkCurrentAssigned($tenants->Tenant,$thishouse->id,$tenants->DateAssigned)}}
                                                              </option>
                                                            @endif
                                                          @endif
                                                      @empty
                                                        <option value="{{$thishouse->id}}">No Tenant</option>
                                                      @endforelse
                                                    </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <button  class="btn btn-danger btn-small " name="submitplotbtn" id="submitplotbtn"  type="submit" >Update Waterbill for @if($watermonth!="")
                                              {{$watermonth}}
                                            @endif
                                            /@if($thishouse!="")
                                              {{$thishouse->Housename}}({{$thishouse->Status}})
                                            @endif</button>
                                            </div>
                                        </div>

                                        
                                      </div>
                                    </div>
                                </div>
                                
                            </div>
                            </form>
                            @empty
                            @endforelse

                            @endif
                            @endforelse

                            @else
                                <!-- <h4>No Thing Here</h4> -->
                              @if($thisothers=="")
                                <div class="row">
                                <div class="col-sm-12" style="overflow-x:auto;">
                                  <table border="1" class="table table-hover table-bordered table-striped text-xs" id="example1"><thead>
                                    <tr style="color:white;background-color:#77B5ED;">
                                        <th>Sno</th>
                                        <th>House</th>
                                        <th>Month</th>
                                        <th>Previous</th>
                                        <th>Current</th>
                                        <th>Cost</th>
                                        <th>Units</th>
                                        <th>Total</th>
                                      </tr></thead><tbody>
                                      @if($waterbill!="")
                                        @foreach($waterbill as $waterpreview)
                                            <tr style="padding:0px;margin:2px;background-color:#FFFFFF;">
                                              <td>{{$loop->index+1}}</td>
                                              <td>{{App\Models\Property::getHouseName($waterpreview['hid'])}}</td>
                                              <td>{{$waterpreview['Month']}}</td>
                                              <td>{{$waterpreview['Previous']}}</td>
                                              <td>{{$waterpreview['Current']}}</td>
                                              <td>{{$waterpreview['Cost']}}</td>
                                              <td>{{$waterpreview['Units']}}</td>
                                              <td>{{$waterpreview['Total']+$waterpreview['Total_OS']}}</td>
                                          </tr>
                                        @endforeach
                                      @else
                                         <h4>Please Select Property, Month and House to Enter Wtaer Bill</h4>
                                      @endif
                                    </tbody>
                                  </table>
                                </div>
                                </div>
                              @endif
                                <!-- end waterbill for this month -->
                            @endif
                            <!-- end house selected -->
                            <!-- start others tenant selected -->
                            @if($thisothers!="" && $thistenant!="")
                              @forelse($waterbill as $previouswater)
                              @if($thismonthwaterstatus=='')
                              <form role="form" class="form-horizontal" method="POST" action="/properties/save/waterbill/othernew">
                                @csrf
                                <div class="row">
                                
                                <input type="hidden" name="month" value="{{$watermonth}}">
                                <input type="hidden" name="Tenant" value="{{$thistenant->id}}">


                                <div class="col-sm-6">
                                    <div class="card card-primary card-outline bg-info" style="margin-bottom: 5%;text-align: center;">
                                      <div class="card-body">
                                        <div class="form-group row">
                                            <label for="Previous" class="col-md-4 col-form-label text-md-right">{{ __('Previous') }}</label>

                                            <div class="col-md-8">
                                                <input id="Previous" type="text" class="form-control @error('Previous') is-invalid @enderror" name="Previous" value="{{ $previouswater->Current }}" placeholder="Previous" required autocomplete="Previous" autofocus>

                                                @error('Previous')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="Current" class="col-md-4 col-form-label text-md-right">{{ __('Current') }}</label>

                                            <div class="col-md-8">
                                                <input id="Current" type="text" class="form-control @error('Current') is-invalid @enderror" name="Current" value="{{ old('Current') }}" placeholder="0.00" required autocomplete="Current" autofocus>

                                                @error('Current')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="Cost" class="col-md-4 col-form-label text-md-right">{{ __('Cost') }}</label>

                                            <div class="col-md-8">
                                                <input id="Cost" type="text" class="form-control @error('Cost') is-invalid @enderror" name="Cost" value="{{ $previouswater->Cost }}" placeholder="0.00" required autocomplete="Cost" autofocus>

                                                @error('Cost')
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
                                    <div class="card card-primary card-outline bg-info" style="margin-bottom: 5%;text-align: center;">
                                      <div class="card-body">

                                        <div class="form-group row">
                                            <label for="NPUnits" class="col-md-4 col-form-label text-md-right">{{ __('Units') }}</label>

                                            <div class="col-md-8">
                                                <input id="Units" type="text" class="form-control @error('Units') is-invalid @enderror" name="Units" value="{{ old('Units') }}" placeholder="0" readonly required autocomplete="Units" autofocus>

                                                @error('Units')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="Total" class="col-md-4 col-form-label text-md-right">{{ __('Total') }}</label>

                                            <div class="col-md-8">
                                                <input id="Total" type="text" class="form-control @error('Total') is-invalid @enderror" name="Total" value="{{ old('Total') }}" placeholder="0.00" readonly required autocomplete="Total" autofocus>

                                                @error('Total')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="Total_OS" class="col-md-4 col-form-label text-md-right">{{ __('Total_OS') }}</label>

                                            <div class="col-md-8">
                                                <input id="Total_OS" type="text" class="form-control @error('Total_OS') is-invalid @enderror" name="Total_OS" value="0" placeholder="0.00" required autocomplete="Total_OS" autofocus>

                                                @error('Total_OS')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <button  class="btn btn-success btn-small" name="submitplotbtn" id="submitplotbtn"  type="submit" >Save Waterbill for @if($watermonth!="")
                                              {{$watermonth}}
                                            @endif
                                            /@if($thistenant!="")
                                              {{$thistenant->Fname}}({{$thistenant->Status}})
                                            @endif</button>
                                            </div>
                                        </div>

                                        
                                      </div>
                                    </div>
                                </div>
                                
                            </div>
                            </form>
                            @else
                              @forelse($thiswaterbill as $currentwater)
                              <form role="form" class="form-horizontal" method="POST" action="/properties/save/waterbill/otherupdate">
                                <div class="row">
                                @csrf
                                  <input type="hidden" name="waterid" value="{{$currentwater->id}}">
                                  <input type="hidden" name="month" value="{{$watermonth}}">
                                  <input type="hidden" name="Tenant" value="{{$currentwater->Tenant}}">
                                <div class="col-sm-6">
                                    <div class="card card-primary card-outline bg-warning" style="margin-bottom: 5%;text-align: center;">
                                      <div class="card-body">
                                        <div class="form-group row">
                                            <label for="Previous" class="col-md-4 col-form-label text-md-right">{{ __('Previous') }}</label>

                                            <div class="col-md-8">
                                                <input id="UPPrevious" type="text" class="form-control @error('Previous') is-invalid @enderror" name="Previous" value="{{ $currentwater->Previous }}" placeholder="Previous" required autocomplete="Previous" autofocus>

                                                @error('Previous')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="Current" class="col-md-4 col-form-label text-md-right">{{ __('Current') }}</label>

                                            <div class="col-md-8">
                                                <input id="UPCurrent" type="text" class="form-control @error('Current') is-invalid @enderror" name="Current" value="{{ $currentwater->Current }}" placeholder="0.00" required autocomplete="Current" autofocus>

                                                @error('Current')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="Cost" class="col-md-4 col-form-label text-md-right">{{ __('Cost') }}</label>

                                            <div class="col-md-8">
                                                <input id="UPCost" type="text" class="form-control @error('Cost') is-invalid @enderror" name="Cost" value="{{ $currentwater->Cost }}" placeholder="0.00" required autocomplete="Cost" autofocus>

                                                @error('Cost')
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
                                    <div class="card card-primary card-outline bg-warning" style="margin-bottom: 5%;text-align: center;">
                                      <div class="card-body">

                                        <div class="form-group row">
                                            <label for="Units" class="col-md-4 col-form-label text-md-right">{{ __('Units') }}</label>

                                            <div class="col-md-8">
                                                <input id="UPUnits" type="text" class="form-control @error('Units') is-invalid @enderror" name="Units" value="{{ $currentwater->Units }}" placeholder="0" readonly required autocomplete="Units" autofocus>

                                                @error('Units')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="Total" class="col-md-4 col-form-label text-md-right">{{ __('Total') }}</label>

                                            <div class="col-md-8">
                                                <input id="UPTotal" type="text" class="form-control @error('Total') is-invalid @enderror" name="Total" value="{{ $currentwater->Total }}" placeholder="0.00" readonly required autocomplete="Total" autofocus>

                                                @error('Total')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                          <div class="form-group row">
                                              <label for="Total_OS" class="col-md-4 col-form-label text-md-right">{{ __('Total_OS') }}</label>

                                              <div class="col-md-8">
                                                  <input id="UPTotal_OS" type="text" class="form-control @error('Total_OS') is-invalid @enderror" name="Total_OS" value="{{ $currentwater->Total_OS }}" placeholder="0.00" required autocomplete="Total_OS" autofocus>

                                                  @error('Total_OS')
                                                      <span class="invalid-feedback" role="alert">
                                                          <strong>{{ $message }}</strong>
                                                      </span>
                                                  @enderror
                                              </div>
                                          </div>

                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <button  class="btn btn-danger btn-small " name="submitplotbtn" id="submitplotbtn"  type="submit" >Update Waterbill for @if($watermonth!="")
                                              {{$watermonth}}
                                            @endif
                                            /@if($thistenant!="")
                                              {{$thistenant->Fname}}({{$thistenant->Status}})
                                            @endif</button>
                                            </div>
                                        </div>

                                        
                                      </div>
                                    </div>
                                </div>
                                
                            </div>
                            </form>
                            @empty

                            @endforelse
                            @endif

                            @empty
                              @if($thismonthwaterstatus=='')
                              <!-- //No Previpous records and not recorded -->
                              <form role="form" class="form-horizontal" method="post" action="/properties/save/waterbill/othernew">
                                @csrf
                                <div class="row">
                                <input type="hidden" name="month" value="{{$watermonth}}">
                                <input type="hidden" name="Tenant" value="{{$thistenant->id}}">
                                <div class="col-sm-6">
                                    <div class="card card-primary card-outline bg-info" style="margin-bottom: 5%;text-align: center;">
                                      <div class="card-body">
                                        <div class="form-group row">
                                            <label for="Previous" class="col-md-4 col-form-label text-md-right">{{ __('Previous') }}</label>

                                            <div class="col-md-8">
                                                <input id="NPPrevious" type="text" class="form-control @error('Previous') is-invalid @enderror" name="Previous" value="0.00" placeholder="Previous" required autocomplete="Previous" autofocus>

                                                @error('Previous')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="Current" class="col-md-4 col-form-label text-md-right">{{ __('Current') }}</label>

                                            <div class="col-md-8">
                                                <input id="NPCurrent" type="text" class="form-control @error('Current') is-invalid @enderror" name="Current" value="0.00" placeholder="0.00" required autocomplete="Current" autofocus>

                                                @error('Current')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="Cost" class="col-md-4 col-form-label text-md-right">{{ __('Cost') }}</label>

                                            <div class="col-md-8">
                                                <input id="NPCost" type="text" class="form-control @error('Cost') is-invalid @enderror" name="Cost" value="0.00" placeholder="0.00" required autocomplete="Cost" autofocus>

                                                @error('Cost')
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
                                    <div class="card card-primary card-outline bg-info" style="margin-bottom: 5%;text-align: center;">
                                      <div class="card-body">

                                        <div class="form-group row">
                                            <label for="Units" class="col-md-4 col-form-label text-md-right">{{ __('Units') }}</label>

                                            <div class="col-md-8">
                                                <input id="NPUnits" type="text" class="form-control @error('Units') is-invalid @enderror" name="Units" value="0.00" placeholder="0" required autocomplete="Units" autofocus>

                                                @error('Units')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="Total" class="col-md-4 col-form-label text-md-right">{{ __('Total') }}</label>

                                            <div class="col-md-8">
                                                <input id="NPTotal" type="text" class="form-control @error('Total') is-invalid @enderror" name="Total" value="0.00" placeholder="0.00" required autocomplete="Total" autofocus>

                                                @error('Total')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="Total_OS" class="col-md-4 col-form-label text-md-right">{{ __('Total_OS') }}</label>

                                            <div class="col-md-8">
                                                <input id="NPTotal_OS" type="text" class="form-control @error('Total_OS') is-invalid @enderror" name="Total_OS" value="0" placeholder="0.00" required autocomplete="Total_OS" autofocus>

                                                @error('Total_OS')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <button  class="btn btn-success btn-small" name="submitplotbtn" id="submitplotbtn"  type="submit" >Save Waterbill Without Previous for @if($watermonth!="")
                                              {{$watermonth}}
                                            @endif
                                            /@if($thistenant!="")
                                              {{$thistenant->Fname}}({{$thistenant->Status}})
                                            @endif</button>
                                            </div>
                                        </div>

                                        
                                      </div>
                                    </div>
                                </div>
                                
                            </div>
                            </form>
                            @else
                            <!-- recorded but has no previous records -->
                             @forelse($thiswaterbill as $currentwater)
                              <form role="form" class="form-horizontal" method="POST" action="/properties/save/waterbill/otherupdate">
                                <div class="row">
                                @csrf
                                  <input type="hidden" name="waterid" value="{{$currentwater->id}}">
                                  <input type="hidden" name="month" value="{{$watermonth}}">
                                  <input type="hidden" name="Tenant" value="{{$currentwater->Tenant}}">
                                <div class="col-sm-6">
                                    <div class="card card-primary card-outline bg-warning" style="margin-bottom: 5%;text-align: center;">
                                      <div class="card-body">
                                        <div class="form-group row">
                                            <label for="Previous" class="col-md-4 col-form-label text-md-right">{{ __('Previous') }}</label>

                                            <div class="col-md-8">
                                                <input id="UPPrevious" type="text" class="form-control @error('Previous') is-invalid @enderror" name="Previous" value="{{ $currentwater->Previous }}" placeholder="Previous" required autocomplete="Previous" autofocus>

                                                @error('Previous')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="Current" class="col-md-4 col-form-label text-md-right">{{ __('Current') }}</label>

                                            <div class="col-md-8">
                                                <input id="UPCurrent" type="text" class="form-control @error('Current') is-invalid @enderror" name="Current" value="{{ $currentwater->Current }}" placeholder="0.00" required autocomplete="Current" autofocus>

                                                @error('Current')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="Cost" class="col-md-4 col-form-label text-md-right">{{ __('Cost') }}</label>

                                            <div class="col-md-8">
                                                <input id="UPCost" type="text" class="form-control @error('Cost') is-invalid @enderror" name="Cost" value="{{ $currentwater->Cost }}" placeholder="0.00" required autocomplete="Cost" autofocus>

                                                @error('Cost')
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
                                    <div class="card card-primary card-outline bg-warning" style="margin-bottom: 5%;text-align: center;">
                                      <div class="card-body">

                                        <div class="form-group row">
                                            <label for="Units" class="col-md-4 col-form-label text-md-right">{{ __('Units') }}</label>

                                            <div class="col-md-8">
                                                <input id="UPUnits" type="text" class="form-control @error('Units') is-invalid @enderror" name="Units" value="{{ $currentwater->Units }}" placeholder="0" readonly required autocomplete="Units" autofocus>

                                                @error('Units')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="Total" class="col-md-4 col-form-label text-md-right">{{ __('Total') }}</label>

                                            <div class="col-md-8">
                                                <input id="UPTotal" type="text" class="form-control @error('Total') is-invalid @enderror" name="Total" value="{{ $currentwater->Total }}" placeholder="0.00" readonly required autocomplete="Total" autofocus>

                                                @error('Total')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                          <div class="form-group row">
                                              <label for="Total_OS" class="col-md-4 col-form-label text-md-right">{{ __('Total_OS') }}</label>

                                              <div class="col-md-8">
                                                  <input id="UPTotal_OS" type="text" class="form-control @error('Total_OS') is-invalid @enderror" name="Total_OS" value="{{ $currentwater->Total_OS }}" placeholder="0.00" required autocomplete="Total_OS" autofocus>

                                                  @error('Total_OS')
                                                      <span class="invalid-feedback" role="alert">
                                                          <strong>{{ $message }}</strong>
                                                      </span>
                                                  @enderror
                                              </div>
                                          </div>

                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <button  class="btn btn-danger btn-small " name="submitplotbtn" id="submitplotbtn"  type="submit" >Update Waterbill for @if($watermonth!="")
                                              {{$watermonth}}
                                            @endif
                                            /@if($thistenant!="")
                                              {{$thistenant->Fname}}({{$thistenant->Status}})
                                            @endif</button>
                                            </div>
                                        </div>
                                        
                                      </div>
                                    </div>
                                </div>
                                
                            </div>
                            </form>
                            @empty
                            @endforelse

                            @endif
                            @endforelse
                            @endif
                            </div>
                          </div>
                        </div>
                        </div>
                      
                      </div>

                      </div>
                      <!-- /.card-body -->
                      </div>
                    </div>
               
                                
                   
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables  & Plugins -->
  
<script type="text/javascript">
$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
});

function confirmOperation(form,message,confirmmessage){
  $("#modalconfirmation-title").html(message);
  $("#modalconfirmation-body").html("Sure to. <br/> "+ confirmmessage+" ?");
  $("#modalconfirmation").modal('show');
  $('#ConfirmConfirmation').click(function(){
      $("#modalconfirmation").modal('hide');
      form.submit();
  });
  return false
}

function getselectedwaterbilltenants(){

  var selectedpenaltytenants=0,allselected=0;
  
  $('.selectedwaterbilltenants').each(function(){
    allselected=allselected+1;
    if($(this).is(":checked")){
      selectedpenaltytenants=selectedpenaltytenants+1;
    }
  })
  $('.selwaterbilltenants').html(selectedpenaltytenants+'/'+allselected);
 }
    function getselectedhousesforupdate(){

        var selectedpenaltytenants=0,allselected=0;
      
        $('.selectedhousesforupdate').each(function(){
            allselected=allselected+1;
            if($(this).is(":checked")){
              selectedpenaltytenants=selectedpenaltytenants+1;
            }
        })
        $('#selectedhousesforupdate').html(selectedpenaltytenants+'/'+allselected);
    }

    $(document).on('click','.waterselmemodiv',(function(e){
        var balidhouses=$(this).data("id1");
        var thisselhouses=document.getElementById(balidhouses);
        if (thisselhouses.checked===true) {
            this.style.backgroundColor='grey';
        }
        else{
            this.style.backgroundColor='#FFFFFF';
        }
        getselectedwaterbilltenants();
    }));
    $(document).on('click','.waterselmemodivsent',(function(e){
        var balidhouses=$(this).data("id1");
        var thisselhouses=document.getElementById(balidhouses);
        if (thisselhouses.checked===true) {
            this.style.backgroundColor='grey';
        }
        else{
            this.style.backgroundColor='#FFEB06';
        }
        getselectedwaterbilltenants();
    }));

  var origcurrent="",origcost="",origtotal="",origunits="",origprevious="";
  var origcurrentother="",origcostother="",origtotalother="",origunitsother="",origprevioousother="";
  var origccprevious=0,origcccurrent=0,origttaprevious=0,origttacurrent=0,origoscost=0;
  var ttaprevious=0,ttacurrent=0,ccprevious=0,cccurrent=0,oscost=0,percc=0,peros=0;

  $(document).on('keydown','#Current',function(){
         origcurrent=$('#Current').val();
      });
    $(document).on('keyup','#Current',function(){
      var thiscurrent=$('#Current').val();
      if(isNaN(thiscurrent)){
        alert("Enter Numbers Only");
        $(this).text(origcurrent);
      }
      else{
          var previous=$('#Previous').val();
          var cost=$('#Cost').val();
          var units=thiscurrent-previous;
          $('#Units').val(units);
          $('#Total').val(units*cost);
      }
    });

    $(document).on('keydown','#Previous',function(){
         origprevious=$('#Previous').val();
    });
    $(document).on('keyup','#Previous',function(){
      var thisprevious=$('#Previous').val();
      if(isNaN(thisprevious)){
        alert("Enter Numbers Only");
        $(this).text(origprevious);
      }
      else{
          var current=$('#Current').val();
          var cost=$('#Cost').val();
          var units=current-thisprevious;
          $('#Units').val(units);
          $('#Total').val(units*cost);
      }
    });
    //update total cost usingb this current cost
    $(document).on('keydown','#Cost',function(){
         origcost=$('#Cost').val();
      });
    $(document).on('keyup','#Cost',function(){
      var thiscost=$('#Cost').val();
      if(isNaN(thiscost)){
        alert("Enter Number Only");
        $(this).text(origcost);
      }
      else{
          var units=$('#Units').val();
          $('#Total').val(units*thiscost);
      }
    });

    $(document).on('keydown','#NPCurrent',function(){
         origcurrent=$('#NPCurrent').val();
      });
    $(document).on('keyup','#NPCurrent',function(){
      var thiscurrent=$('#NPCurrent').val();
      if(isNaN(thiscurrent)){
        alert("Enter Numbers Only");
        $(this).text(origcurrent);
      }
      else{
          var previous=$('#NPPrevious').val();
          var cost=$('#NPCost').val();
          var units=thiscurrent-previous;
          $('#NPUnits').val(units);
          $('#NPTotal').val(units*cost);
      }
    });

    $(document).on('keydown','#NPPrevious',function(){
         origprevious=$('#NPPrevious').val();
    });
    $(document).on('keyup','#NPPrevious',function(){
      var thisprevious=$('#NPPrevious').val();
      if(isNaN(thisprevious)){
        alert("Enter Numbers Only");
        $(this).text(origprevious);
      }
      else{
          var current=$('#NPCurrent').val();
          var cost=$('#NPCost').val();
          var units=current-thisprevious;
          $('#NPUnits').val(units);
          $('#NPTotal').val(units*cost);
      }
    });

    //update total cost usingb this current cost
    $(document).on('keydown','#NPCost',function(){
         origcost=$('#NPCost').val();
      });
    $(document).on('keyup','#NPCost',function(){
      var thiscost=$('#NPCost').val();
      if(isNaN(thiscost)){
        alert("Enter Number Only");
        $(this).text(origcost);
      }
      else{
          var units=$('#NPUnits').val();
          $('#NPTotal').val(units*thiscost);
      }
    });
    

      $(document).on('keydown','#UPCurrent',function(){
         origcurrent=$('#UPCurrent').val();
      });
    $(document).on('keyup','#UPCurrent',function(){
      var thiscurrent=$('#UPCurrent').val();
      if(isNaN(thiscurrent)){
        alert("Enter Numbers Only");
        $(this).text(origcurrent);
      }
      else{
          var previous=$('#UPPrevious').val();
          var cost=$('#UPCost').val();
          var units=thiscurrent-previous;
          $('#UPUnits').val(units);
          $('#UPTotal').val(units*cost);
      }
    });

    $(document).on('keydown','#UPPrevious',function(){
         origprevious=$('#UPPrevious').val();
    });
    $(document).on('keyup','#UPPrevious',function(){
      var thisprevious=$('#UPPrevious').val();
      if(isNaN(thisprevious)){
        alert("Enter Numbers Only");
        $(this).text(origprevious);
      }
      else{
          var current=$('#UPCurrent').val();
          var cost=$('#UPCost').val();
          var units=current-thisprevious;
          $('#UPUnits').val(units);
          $('#UPTotal').val(units*cost);
      }
    });

    //update total cost usingb this current cost
    $(document).on('keydown','#UPCost',function(){
         origcost=$('#UPCost').val();
      });
    $(document).on('keyup','#UPCost',function(){
      var thiscost=$('#UPCost').val();
      if(isNaN(thiscost)){
        alert("Enter Number Only");
        $(this).text(origcost);
      }
      else{
          var units=$('#UPUnits').val();
          $('#UPTotal').val(units*thiscost);
      }
    });

      
</script>
@endpush