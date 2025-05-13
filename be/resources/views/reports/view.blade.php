@extends('layouts.adminheader')
@section('title','View Reports | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-6">
    <h1 class="m-0">View Reports @if($thistype!="")
                                /{{$thistype}}
                              @endif</h1>
</div><!-- /.col -->
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
      <li class="breadcrumb-item active">Reports @if($thistype!="")
                                /{{$thistype}}
                              @endif</li>
    </ol>
</div><!-- /.col -->
@endsection
@section('css')
    <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('public/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
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
        <a href="/properties/View/Reports" class="nav-link active">
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
                      <div class="col-12 col-sm-12">
                        <div class="card card-info card-tabs">
                          <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                              @if($thistype=="Waterbill")
                                <li class="nav-item">
                                  <a class="nav-link active" id="custom-tabs-one-home-tab"  href="/properties/View/Reports/Waterbill/{{App\Http\Controllers\TenantController::getCurrentMonthReport()}}" role="tab" aria-controls="custom-tabs-one-home" aria-selected="false">Waterbill</a>
                                </li>
                              @else
                                <li class="nav-item">
                                  <a class="nav-link" id="custom-tabs-one-home-tab"  href="/properties/View/Reports/Waterbill/{{App\Http\Controllers\TenantController::getCurrentMonthReport()}}" role="tab" aria-controls="custom-tabs-one-home" aria-selected="false">Waterbill</a>
                              </li>
                              @endif
                              @if($thistype=="Payments")
                                <li class="nav-item">
                                  <a class="nav-link active" id="custom-tabs-one-profile-tab" href="/properties/View/Reports/Payments/{{App\Http\Controllers\TenantController::getCurrentMonthReport()}}" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Payments</a>
                                </li>
                              @else
                                <li class="nav-item">
                                  <a class="nav-link" id="custom-tabs-one-profile-tab" href="/properties/View/Reports/Payments/{{App\Http\Controllers\TenantController::getCurrentMonthReport()}}" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Payments</a>
                                </li>
                              @endif
                              @if($thistype=="TenantsInfo")
                                <li class="nav-item">
                                  <a class="nav-link active" id="custom-tabs-one-messages-tab" href="/properties/View/Reports/TenantsInfo/{{App\Http\Controllers\TenantController::getCurrentMonthReport()}}" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">Tenants Info</a>
                                </li>
                              @else
                                <li class="nav-item">
                                  <a class="nav-link" id="custom-tabs-one-messages-tab" href="/properties/View/Reports/TenantsInfo/{{App\Http\Controllers\TenantController::getCurrentMonthReport()}}" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">Tenants Info</a>
                                  </li>
                              @endif
                              @if($thistype=="Messages")
                                <li class="nav-item">
                                  <a class="nav-link active" id="custom-tabs-one-settings-tab" href="/properties/View/Reports/Messages/{{App\Http\Controllers\TenantController::getCurrentMonthReport()}}" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="true">Messages</a>
                                </li>
                              @else
                                <li class="nav-item">
                                  <a class="nav-link" id="custom-tabs-one-settings-tab" href="/properties/View/Reports/Messages/{{App\Http\Controllers\TenantController::getCurrentMonthReport()}}" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="true">Messages</a>
                                </li>
                              @endif
                              <li class="nav-item">
                                <select class="form-control select2" name="allproperties" onchange="location=this.value;" style="">
                                  <option value="">Select Property</option>
                                  @forelse($propertyinfo as $propertys)
                                    @if($thistype!="" && $thisproperty!="" && $watermonth!="")
                                      @if($thisproperty->id==$propertys->id)
                                        <option value="/properties/View/Reports/{{$thistype}}/{{$propertys->id}}/{{$watermonth}}" selected>{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                                      @else
                                        <option value="/properties/View/Reports/{{$thistype}}/{{$propertys->id}}/{{$watermonth}}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                                      @endif
                                    @elseif($thistype!="" && $thisproperty!="" && $watermonth=="")
                                      @if($thisproperty->id==$propertys->id)
                                      <option value="/properties/View/Reports/{{$thistype}}/{{$propertys->id}}/{{App\Http\Controllers\TenantController::getCurrentMonthReport()}}" selected>{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                                      @else
                                        <option value="/properties/View/Reports/{{$thistype}}/{{$propertys->id}}/{{App\Http\Controllers\TenantController::getCurrentMonthReport()}}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                                      @endif
                                     @elseif($thistype!="" && $thisproperty=="" && $watermonth!="")
                                        <option value="/properties/View/Reports/{{$thistype}}/{{$propertys->id}}/{{$watermonth}}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                                    @else
                                      <option value="/properties/View/Reports/{{$thistype}}/{{$propertys->id}}/{{App\Http\Controllers\TenantController::getCurrentMonthReport()}}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                                    @endif
                                  @empty
                                    <option>No Property Found</option>
                                  @endforelse
                              </select>
                              </li>
                              <li class="nav-item">
                                <select class="form-control select2" name="allproperties" onchange="location=this.value;" style="">
                                      <option value="">Choose Month</option>
                                         @if($thisproperty!="")
                                          @forelse($monthinfo as $months)
                                            @if($months['Month']==$watermonth)
                                              <option value="/properties/View/Reports/{{$thistype}}/{{$thisproperty->id}}/{{$months['Month']}}" selected>{{$months['Monthname']}}</option>
                                            @else
                                              <option value="/properties/View/Reports/{{$thistype}}/{{$thisproperty->id}}/{{$months['Month']}}">{{$months['Monthname']}}</option>
                                            @endif
                                          @empty

                                          @endforelse
                                        @else
                                          @forelse($monthinfo as $months)
                                            @if($months['Month']==$watermonth)
                                              <option value="/properties/View/Reports/{{$thistype}}/All/{{$months['Month']}}" selected>{{$months['Monthname']}}</option>
                                            @else
                                              <option value="/properties/View/Reports/{{$thistype}}/All/{{$months['Month']}}">{{$months['Monthname']}}</option>
                                            @endif
                                          @empty

                                          @endforelse
                                        @endif
                                    </select>
                              </li>
                            </ul>
                          </div>
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
                            <div class="tab-content" id="custom-tabs-one-tabContent">
                              @if($thistype=="Waterbill")
                              <div class="tab-pane fade active show" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">@if($pno=0)@endif
                                <div class="row">
                                <div class="col-12 text-center mx-auto">
                                  <span class="text-center m-1">
                                    <a target="_blank" href="/properties/download/Reports/Waterbill/All/{{$watermonth}}" class="btn btn-outline-primary p-1 mt-1"><i class="fa fa-download"></i> All Properties ({{$watermonth}}) </a>
                                    
                                  </span>
                                </div>
                                
                                @if($thisproperty=="")
                                @forelse($propertyinfo as $propertys)
                                @if($propertys->Waterbill=="Monthly")
                                 <div class="col-sm-6 col-12" style="padding: 2px;margin: 0px;">
                                    <div class="card direct-chat direct-chat-primary" style="padding: 2px;margin: 3px;">
                                      <div class="card-header bg-success" style="padding: 4px;">
                                        <span class="" style="font-size: 13px;"> Waterbill: {{$propertys->Plotcode}} @if($sno=0)@endif
                                          <input type="hidden" name="pno" class="pno" value="{{$loop->count}}">
                                            <span class="bg-light" style="padding: 4px;margin: 3px;">
                                              <i class="text-secondary" style="width: 50px;font-size: 12px;padding: 2px;">Units:</i>
                                              <i class="text-primary" style="width: 80px;font-size: 12px;padding: 2px;">{{App\Http\Controllers\TenantController::getTotalUnits($propertys->id,$watermonth)}}</i>
                                            </span>
                                            <span class="bg-light" style="padding: 4px;margin: 3px;">
                                              <i class="text-secondary" style="width: 50px;font-size: 12px;padding: 2px;">Total:</i>
                                              <i class="text-primary" style="width: 80px;font-size: 12px;padding: 2px;">Kshs. {{App\Http\Controllers\TenantController::getTotalTotal($propertys->id,$watermonth)+App\Http\Controllers\TenantController::getTotalTotal_OS($propertys->id,$watermonth)}}</i>
                                            </span>
                                            <a target="_blank" href="/properties/download/Reports/Waterbill/{{$propertys->id}}/{{$watermonth}}" class="btn btn-info p-1"><i class="fa fa-download"></i></a>
                                            <a target="_blank" href="/properties/download/Reports/Waterbill/{{$propertys->id}}/Now" class="btn btn-primary p-1"><i class="fa fa-download"></i> {{date('Y')}}</a>
                                            <a target="_blank" href="/properties/download/Reports/Waterbill/{{$propertys->id}}/Previous" class="btn btn-primary p-1"><i class="fa fa-download"></i> {{date('Y')-1}}</a>
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
                                    <div class="card-body bg-light" style="">
                                       <div class="row">
                                          <div class="col-sm-12">
                                            
                                                @if($waterbill!="")
                                                <table id="property{{++$pno}}" class="table table-bordered table-striped"><thead>
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
                                                  @foreach($waterbill as $waterpreview)
                                                    @if($waterpreview['Plot']==$propertys->id)
                                                      <tr style="padding:0px;margin:2px;background-color:#FFFFFF;font-size: 11px;padding: 2px;">
                                                        <td>{{++$sno}}</td>
                                                        <td>{{App\Models\Property::getHouseName($waterpreview->House)}}</td>
                                                        <td>{{$waterpreview['Month']}}</td>
                                                        <td>{{$waterpreview['Previous']}}</td>
                                                        <td>{{$waterpreview['Current']}}</td>
                                                        <td>{{$waterpreview['Cost']}}</td>
                                                        <td>{{$waterpreview['Units']}}</td>
                                                        <td>{{$waterpreview['Total']+$waterpreview['Total_OS']}}</td>
                                                      </tr>
                                                    @endif
                                                  @endforeach
                                                  </tbody>
                                                  </table>
                                                @else
                                                  <h4>No Waterbill Recorded for this Month</h4>
                                                @endif
                                             
                                          </div>
                                          </div>
                                                        
                                    </div>

                                    
                                      <!-- /.card-body -->
                                    </div>
                                    </div>
                                    @endif
                                    <!-- end if property found -->
                                  @empty

                                  <!-- end if property not found -->
                                  @endforelse
                                  <!-- end when all properties has been looped -->

                                  @else
                                  @if($thisproperty!="" && $thisproperty->Waterbill=="Monthly")
                                   <div class="col-sm-12 col-12" style="padding: 2px;margin: 0px;">
                                      <div class="card direct-chat direct-chat-primary" style="padding: 2px;margin: 3px;">
                                        
                                        <div class="card-header bg-success" style="padding: 4px;">
                                          <span class="" style="font-size: 13px;"> Waterbill: {{$thisproperty->Plotname}} @if($sno=0)@endif
                                            <span class="bg-light" style="padding: 4px;margin: 3px;">
                                              <i class="text-secondary" style="width: 50px;font-size: 12px;padding: 2px;">Previous:</i>
                                              <i class="text-primary" style="width: 80px;font-size: 12px;padding: 2px;">{{App\Http\Controllers\TenantController::getTotalPrevious($thisproperty->id,$watermonth)}}</i>
                                            </span>
                                            <span class="bg-light" style="padding: 4px;margin: 3px;">
                                              <i class="text-secondary" style="width: 50px;font-size: 12px;padding: 2px;">Current:</i>
                                              <i class="text-primary" style="width: 80px;font-size: 12px;padding: 2px;">{{App\Http\Controllers\TenantController::getTotalCurrent($thisproperty->id,$watermonth)}}</i>
                                            </span>
                                            <span class="bg-light" style="padding: 4px;margin: 3px;">
                                              <i class="text-secondary" style="width: 50px;font-size: 12px;padding: 2px;">Units:</i>
                                              <i class="text-primary" style="width: 80px;font-size: 12px;padding: 2px;">{{App\Http\Controllers\TenantController::getTotalUnits($thisproperty->id,$watermonth)}}</i>
                                            </span>
                                            <span class="bg-light" style="padding: 4px;margin: 3px;">
                                              <i class="text-secondary" style="width: 50px;font-size: 12px;padding: 2px;">Total_OS:</i>
                                              <i class="text-primary" style="width: 80px;font-size: 12px;padding: 2px;">Kshs. {{App\Http\Controllers\TenantController::getTotalTotal_OS($thisproperty->id,$watermonth)}}</i>
                                            </span>
                                            <span class="bg-light" style="padding: 4px;margin: 3px;">
                                              <i class="text-secondary" style="width: 50px;font-size: 12px;padding: 2px;">Total:</i>
                                              <i class="text-primary" style="width: 80px;font-size: 12px;padding: 2px;">Kshs. {{App\Http\Controllers\TenantController::getTotalTotal($thisproperty->id,$watermonth)+App\Http\Controllers\TenantController::getTotalTotal_OS($thisproperty->id,$watermonth)}}</i>
                                            </span>
                                              <a target="_blank" href="/properties/download/Reports/Waterbill/{{$thisproperty->id}}/{{$watermonth}}" class="btn btn-info p-1"><i class="fa fa-download"></i></a>
                                              <a target="_blank" href="/properties/download/Reports/Waterbill/{{$thisproperty->id}}/Now" class="btn btn-primary p-1"><i class="fa fa-download"></i> {{date('Y')}}</a>
                                              <a target="_blank" href="/properties/download/Reports/Waterbill/{{$thisproperty->id}}/Previous" class="btn btn-primary p-1"><i class="fa fa-download"></i> {{date('Y')-1}}</a>
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
                                      <div class="card-body bg-light" style="overflow-y: auto;overflow-x: auto;">
                                         <div class="row">
                                            <div class="col-sm-12">
                                              
                                                  @if($waterbill!="")
                                                  <table id="example2" class="table table-bordered table-striped"><thead>
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
                                                    @forelse($waterbill as $waterpreview)
                                                      @if($waterpreview->Plot==$thisproperty->id)
                                                        <tr style="padding:0px;margin:2px;background-color:#FFFFFF;font-size: 11px;padding: 2px;">
                                                          <td>{{++$sno}}</td>
                                                          <td>{{App\Models\Property::getHouseName($waterpreview->House)}}</td>
                                                          <td>{{$waterpreview->Month}}</td>
                                                          <td>{{$waterpreview->Previous}}</td>
                                                          <td>{{$waterpreview->Current}}</td>
                                                          <td>{{$waterpreview->Cost}}</td>
                                                          <td>{{$waterpreview->Units}}</td>
                                                          <td>{{$waterpreview->Total+$waterpreview->Total_OS}}</td>
                                                        </tr>
                                                      @else
                                                      @endif
                                                    @empty
                                                      <h4>No Waterbill Recorded for this Month</h4>
                                                    @endforelse
                                                    </tbody>
                                                      
                                                    </table>
                                                  @else
                                                    <h4>No Waterbill Recorded for this Month</h4>
                                                  @endif
                                                
                                            </div>
                                            </div>
                                                          
                                      </div>

                                      <div class="modal-footer justify-content-between bg-primary" style="padding: 3px;">
                                          <div style="color: white;">
                                            Waterbill: {{$thisproperty->Plotcode}}
                                          </div>
                                      </div>
                                        <!-- /.card-body -->
                                      </div>
                                      </div>
                                    @endif
                                  @endif

                                  </div>
                                  <!-- end looping all properties -->
                              </div>
                              @endif
                              @if($thistype=="Payments")
                              <div class="tab-pane fade active show" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                                <div class="row">
                                @if($thisproperty=="")
                                  <div class="col-sm-12 col-12" style="padding: 2px;margin: 0px;">
                                    <div class="card direct-chat direct-chat-primary text-center" style="padding: 2px;margin: 3px;">
                                        <h4>Please Choose Property Name</h4>
                                        <span class="" style="font-size: 13px;">
                                          <a target="_blank" href="/properties/download/Reports/Payments" class="btn btn-info btn-sm p-1" style="padding: 2px;margin-right: 2px;"><i class="fa fa-download"></i>All Properties Payments in Excel</a>
                                          <a href="/properties/Download/Acknowledgement/{{$watermonth}}" class="btn btn-primary btn-sm p-1" style="padding: 2px;margin-right: 2px;" title="Download Payment Acknowledgement for All Houses in Property"><i class="fa fa-download"></i> Generate All Properties Acknowledgements</a>
                                        </span>
                                    </div>
                                  </div>

                                  @else
                                  @if($thisproperty!="")
                                   <div class="col-sm-12 col-12" style="padding: 2px;margin: 0px;">
                                      <div class="card direct-chat direct-chat-primary" style="padding: 2px;margin: 3px;">
                                        <div class="card-header bg-success" style="padding: 4px;">
                                          <span class="" style="font-size: 13px;"> Payment Details: {{$thisproperty->Plotname}} 
                                            @if($sno=0)@endif
                                              <a target="_blank" href="/properties/download/Reports/Payments/{{$thisproperty->id}}" class="btn btn-info btn-sm p-1" style="padding: 2px;margin-right: 2px;"><i class="fa fa-download"></i> Excel</a>
                                              <a href="/properties/Download/Acknowledgement/{{$thisproperty->id}}/{{$watermonth}}" class="btn btn-primary btn-sm p-1" style="padding: 2px;margin-right: 2px;" title="Download Payment Acknowledgement for All Houses in Property"><i class="fa fa-download"></i> Generate for All</a>
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
                                      <div class="card-body bg-light" style="overflow-y: auto;overflow-x: auto;">
                                         <div class="row">
                                            <div class="col-sm-12">
                                              
                                                  @if($payments!="")
                                                  <table id="example3" class="table table-bordered table-striped" style="font-size: 11px;"><thead>
                                                    <tr style="color:white;background-color:#77B5ED;">
                                                        <th>Sno</th>
                                                        <th>House</th>
                                                        <th>Tenant Name</th>
                                                        <th>Arrears</th>
                                                        <th>Excess</th>
                                                        <th>Rent</th>
                                                        <th>Garbage</th>
                                                        <th>Waterbill</th>
                                                        <th>Rent DT</th>
                                                        <th>Water DT</th>
                                                        <th>KPLC DT</th>
                                                        <th>Lease</th>
                                                        <th>Totals</th>
                                                        <th>Paid</th>
                                                        <th>Bal</th>
                                                        <th>Actions</th>
                                                      </tr></thead><tbody>
                                                    @forelse($payments as $waterpreview)
                                                        <tr style="padding:0px;margin:2px;background-color:#FFFFFF;font-size: 10px;padding: 2px;">
                                                          <td>{{++$sno}}</td>
                                                          <td>{{$waterpreview['Housename']}}</td>
                                                          <td>{{$waterpreview['Tenantname']}}</td>
                                                          <td>{{$waterpreview['Arrears']}}</td>
                                                          <td>{{$waterpreview['Excess']}}</td>
                                                          <td>{{$waterpreview['Rent']}}</td>
                                                          <td>{{$waterpreview['Garbage']}}</td>
                                                          <td>{{$waterpreview['Waterbill']}}</td>
                                                          <td>{{$waterpreview['HseDeposit']}}</td>
                                                          <td>{{$waterpreview['Water']}}</td>
                                                          <td>{{$waterpreview['KPLC']}}</td>
                                                          <td>{{$waterpreview['Lease']}}</td>
                                                          <td>{{$waterpreview['TotalUsed']}}</td>
                                                          <td>{{$waterpreview['TotalPaid']}}</td>
                                                          <td>{{$waterpreview['Balance']}}</td>
                                                          <td>
                                                            <a href="/properties/Download/Acknowledgement/{{$waterpreview['tid']}}/{{$waterpreview['id']}}/{{$watermonth}}" class="btn btn-warning btn-sm" style="padding: 2px;font-size: 9px;margin-right: 2px;" title="Download Payment Acknowledgement for {{$waterpreview['Housename']}}"><span class="fa fa-download fa-1x"></span> Generate</a>
                                                          </td>
                                                        </tr>
                                                    @empty
                                                    @endforelse
                                                    </tbody>
                                                      <tfooter>
                                                      <tr style="color:white;background-color:#77B5ED;">
                                                          <th>Sno</th>
                                                          <th>House</th>
                                                          <th>Tenant Name</th>
                                                          <th>Arrears</th>
                                                          <th>Excess</th>
                                                          <th>Rent</th>
                                                          <th>Garbage</th>
                                                          <th>Waterbill</th>
                                                          <th>Rent DT</th>
                                                          <th>Water DT</th>
                                                          <th>KPLC DT</th>
                                                          <th>Lease</th>
                                                          <th>Totals</th>
                                                          <th>Paid</th>
                                                          <th>Bal</th>
                                                          <th>Actions</th>
                                                        </tr></tfooter>
                                                    </table>
                                                  @else
                                                    <h4>No Payments Found Here</h4>
                                                  @endif
                                                
                                            </div>
                                            </div>
                                                          
                                      </div>

                                      <div class="modal-footer justify-content-between bg-primary" style="padding: 3px;">
                                          <div style="color: white;">
                                            Payments Details: {{$thisproperty->Plotcode}}
                                          </div>
                                      </div>
                                        <!-- /.card-body -->
                                      </div>
                                      </div>
                                    @endif
                                  @endif

                                  </div>
                                  <!-- end looping all properties --> 
                              </div>
                              @endif
                              @if($thistype=="TenantsInfo")
                              <div class="tab-pane fade active show" id="custom-tabs-one-messages" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">
                                 <div class="row">
                                @if($thisproperty=="")
                                @forelse($propertyinfo as $propertys)
                                 <div class="col-sm-6 col-12" style="padding: 2px;margin: 0px;">
                                    <div class="card direct-chat direct-chat-primary" style="padding: 2px;margin: 3px;">
                                      <div class="card-header bg-success" style="padding: 4px;">
                                        <span class="" style="font-size: 13px;"> Tenants Info: {{$propertys->Plotname}} 
                                          @if($sno=0)@endif
                                          <a target="_blank" href="/properties/download/Reports/TenantsInfo/{{$propertys->id}}" class="btn btn-info" style="padding:2px;margin-right:5px;margin-bottom:2px;"><i class="fa fa-download"></i> Excel</a>
                                          <a target="_blank" href="/properties/download/Reports/TenantsInfo" class="btn btn-primary" style="padding:2px;margin-right:5px;margin-bottom:2px;"><i class="fa fa-download"></i> All Properties</a>
                                         
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
                                    <div class="card-body bg-light" style="max-height: 400px;overflow-y: auto;overflow-x: auto;">
                                       <div class="row">
                                          <div class="col-sm-12">
                                            
                                                @if($tenantsinfo!="")
                                                <table id="example4" class="table table-bordered table-striped"><thead>
                                                  <tr style="color:white;background-color:#77B5ED;">
                                                      <th>Sno</th>
                                                      <th>House</th>
                                                      <th>Tenant Name</th>
                                                      <th>Phone</th>
                                                    </tr></thead><tbody>
                                                  @foreach($tenantsinfo as $waterpreview)
                                                    @if($waterpreview->Plot==$propertys->id)
                                                      <tr style="padding:0px;margin:2px;background-color:#FFFFFF;font-size: 11px;padding: 2px;">
                                                        <td>{{++$sno}}</td>
                                                        <td>{{$waterpreview->Housename}}</td>
                                                        <td>{{App\Http\Controllers\TenantController::TenantNames(App\Models\Property::checkCurrentTenant($waterpreview->id))}}</td>
                                                        <td>{{App\Http\Controllers\TenantController::TenantPhone(App\Models\Property::checkCurrentTenant($waterpreview->id))}}</td>
                                                      </tr>
                                                    @endif
                                                  @endforeach
                                                  </tbody>
                                                    <tfooter>
                                                    <tr style="color:white;background-color:#77B5ED;">
                                                        <th>Sno</th>
                                                        <th>House</th>
                                                        <th>Tenant Name</th>
                                                        <th>Phone</th>
                                                      </tr></tfooter>
                                                  </table>
                                                @else
                                                  <h4>No Tenants Found Here</h4>
                                                @endif
                                             
                                          </div>
                                          </div>
                                                        
                                    </div>

                                    <div class="modal-footer justify-content-between bg-primary" style="padding: 3px;">
                                        <div style="color: white;">
                                          Tenants Info: {{$propertys->Plotcode}}
                                        </div>
                                    </div>
                                      <!-- /.card-body -->
                                    </div>
                                    </div>
                                    <!-- end if property found -->
                                  @empty

                                  <!-- end if property not found -->
                                  @endforelse
                                  <!-- end when all properties has been looped -->

                                  @else
                                  @if($thisproperty!="")
                                   <div class="col-sm-12 col-12" style="padding: 2px;margin: 0px;">
                                      <div class="card direct-chat direct-chat-primary" style="padding: 2px;margin: 3px;">
                                        <div class="card-header bg-success" style="padding: 4px;">
                                          <span class="" style="font-size: 13px;"> Tenants Info: {{$thisproperty->Plotname}} 
                                            @if($sno=0)@endif
                                            <button><i class="fa fa-download"></i><a target="_blank" href="/properties/download/Reports/TenantsInfo/{{$thisproperty->id}}">Excel</a></button>
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
                                      <div class="card-body bg-light" style="overflow-y: auto;overflow-x: auto;">
                                         <div class="row">
                                            <div class="col-sm-12">
                                              
                                                  @if($tenantsinfo!="")
                                                  <table id="example5" class="table table-bordered table-striped"><thead>
                                                    <tr style="color:white;background-color:#77B5ED;">
                                                        <th>Sno</th>
                                                        <th>House</th>
                                                        <th>Tenant Name</th>
                                                        <th>Phone</th>
                                                        <th>Email</th>
                                                      </tr></thead><tbody>
                                                    @forelse($tenantsinfo as $waterpreview)
                                                        <tr style="padding:0px;margin:2px;background-color:#FFFFFF;font-size: 11px;padding: 2px;">
                                                          <td>{{++$sno}}</td>
                                                          <td>{{$waterpreview->Housename}}</td>
                                                          <td>{{App\Http\Controllers\TenantController::TenantNames(App\Models\Property::checkCurrentTenant($waterpreview->id))}}</td>
                                                          <td>{{App\Http\Controllers\TenantController::TenantPhone(App\Models\Property::checkCurrentTenant($waterpreview->id))}}</td>
                                                          <td>{{App\Http\Controllers\TenantController::TenantEmail(App\Models\Property::checkCurrentTenant($waterpreview->id))}}</td>
                                                        </tr>
                                                    @empty
                                                    @endforelse
                                                    </tbody>
                                                      <tfooter>
                                                      <tr style="color:white;background-color:#77B5ED;">
                                                          <th>Sno</th>
                                                          <th>House</th>
                                                          <th>Tenant Name</th>
                                                          <th>Phone</th>
                                                          <th>Email</th>
                                                        </tr></tfooter>
                                                    </table>
                                                  @else
                                                    <h4>No Tenants Found Here</h4>
                                                  @endif
                                                
                                            </div>
                                            </div>
                                                          
                                      </div>

                                      <div class="modal-footer justify-content-between bg-primary" style="padding: 3px;">
                                          <div style="color: white;">
                                            Tenants: {{$thisproperty->Plotcode}}
                                          </div>
                                      </div>
                                        <!-- /.card-body -->
                                      </div>
                                      </div>
                                    @endif
                                  @endif

                                  </div>
                                  <!-- end looping all properties --> 
                              </div>
                              @endif
                              @if($thistype=="Messages")
                              <div class="tab-pane fade active show" id="custom-tabs-one-settings" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
                                 Pellentesque vestibulum commodo nibh nec blandit. Maecenas neque magna, iaculis tempus turpis ac, ornare sodales tellus. Mauris eget blandit dolor. Quisque tincidunt venenatis vulputate. Morbi euismod molestie tristique. Vestibulum consectetur dolor a vestibulum pharetra. Donec interdum placerat urna nec pharetra. Etiam eget dapibus orci, eget aliquet urna. Nunc at consequat diam. Nunc et felis ut nisl commodo dignissim. In hac habitasse platea dictumst. Praesent imperdiet accumsan ex sit amet facilisis.
                              </div>
                              @endif
                            </div>
                          </div>
                          <!-- /.card -->
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
  <script src="{{ asset('public/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('public/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('public/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('public/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script type="text/javascript">
$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
    var pno=$('.pno').val();
    for (var i = 0; i <= pno; i++) {
      $("#property"+i).DataTable({
        "responsive": true, "lengthChange": true, "autoWidth": false,"ordering":true
      });
    }
    
    $("#example1").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,"ordering":true
    });
    $("#example2").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,"ordering":true
    });
    $("#example3").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,"ordering":true
    });
    $("#example4").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,"ordering":true
    });
    $("#example5").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,"ordering":true
    });
});
</script>
@endpush