@extends('layouts.adminheader')
@section('title','Send Message | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-6">
    <h5 class="m-0">Send Message to @if($thisproperty!="")
                            {{$thisproperty->Plotcode}}
                            @endif
                            @if($thismode!="")
                              /{{$thismode}}
                            @endif
                            /{{$watermonth}}
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
  <li class="breadcrumb-item active">Message(
                        @if($thisproperty!="")
                          {{$thisproperty->Plotcode}}
                          @endif
                        @if($thismode!="")
                            /{{$thismode}}
                          @endif)
                         /{{$watermonth}}</li>
</ol>
</div><!-- /.col -->
@endsection
@section('css')
    <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
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
                          @if($thisothers!="" && $thismode!="" && $watermonth!="")
                            <option value="/properties/send/message/Other/{{$thismode}}/{{$watermonth}}" selected>0. Other Message</option>
                            <option value="/properties/messages">Compose Message</option>
                          @elseif($thisothers!="" && $thismode!="" && $watermonth=="")
                            <option value="/properties/send/message/Other/{{$thismode}}/{{App\Http\Controllers\TenantController::getCurrentMonth()}}" selected>0. Other Message</option>
                            <option value="/properties/messages">Compose Message</option>
                          @elseif($thisothers!="" && $thismode=="" && $watermonth=="")
                            <option value="/properties/send/message/Other/Other Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}" selected>0. Other Message</option>
                            <option value="/properties/messages">Compose Message</option>
                          @elseif($thisothers=="" && $thisproperty=="")
                            <option value="/properties/messages" selected>Compose Message</option>
                            <option value="/properties/send/message/Other/Other Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">0. Other Message</option>
                          @else
                            <option value="/properties/send/message/Other/Other Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">0. Other Message</option>
                            <option value="/properties/messages">Compose Message</option>
                          @endif

                          @forelse($propertyinfo as $propertys)
                            @if($thisproperty!="" && $thismode!="" && $watermonth!="")
                              @if($thisproperty->id==$propertys->id)
                                <option value="/properties/send/messages/{{ $propertys->id }}/{{$thismode}}/{{$watermonth}}" selected>{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                              @else
                                <option value="/properties/send/messages/{{ $propertys->id }}/{{$thismode}}/{{$watermonth}}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                              @endif
                            @elseif($thisproperty!="" && $thismode!="" && $watermonth=="")
                              @if($thisproperty->id==$propertys->id)
                                <option value="/properties/send/messages/{{ $propertys->id }}/{{$thismode}}/{{App\Http\Controllers\TenantController::getCurrentMonth()}}" selected>{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                              @else
                                <option value="/properties/send/messages/{{ $propertys->id }}/{{$thismode}}/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                              @endif
                            @elseif($thisproperty!="" && $thismode=="" && $watermonth=="")
                              @if($thisproperty->id==$propertys->id)
                                <option value="/properties/send/messages/{{ $propertys->id }}/Single Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}" selected>{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                              @else
                                <option value="/properties/send/messages/{{ $propertys->id }}/Single Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                              @endif
                            @else
                              <option value="/properties/send/messages/{{$propertys->id}}/Single Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                          @endif
                          @empty
                            <option>No Tenant Found</option>
                          @endforelse
                        </select>
                      </div>

                    <div class="col-sm-4 col-6">
                      <select class="form-control select2" name="allproperties" onchange="location=this.value;" style="width: 100%;">
                        <option value="">Choose Mode</option>
                        @if($thisproperty!="")
                          @if($watermonth=="")
                            @if($thismode=="Single Water")
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}" selected>Send Single Water</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send All Water</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Choose Tenant</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Choose Rent</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send All Tenant</option>
                            @elseif($thismode=="All Water")
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Single Water</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}" selected>Send All Water</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Choose Tenant</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Choose Rent</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send All Tenant</option>
                            @elseif($thismode=="Choose Tenant")
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Single Water</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send All Water</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}" selected>Choose Tenant</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Choose Rent</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send All Tenant</option>
                            @elseif($thismode=="Choose Rent")
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Single Water</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send All Water</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Choose Tenant</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{App\Http\Controllers\TenantController::getCurrentMonth()}}" selected>Choose Rent</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send All Tenant</option>
                            @elseif($thismode=="Send All Tenant")
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Single Water</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send All Water</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Choose Tenant</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Choose Rent</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}" selected>Send All Tenant</option>
                            @else
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}" selected>Send Single Water</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send All Water</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Choose Tenant</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Choose Rent</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send All Tenant</option>
                            @endif
                          @else
                            @if($thismode=="Single Water")
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{$watermonth}}" selected>Send Single Water</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{$watermonth}}">Send All Water</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{$watermonth}}">Choose Tenant</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{$watermonth}}">Choose Rent</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{$watermonth}}">Send All Tenant</option>
                            @elseif($thismode=="All Water")
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{$watermonth}}">Send Single Water</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{$watermonth}}" selected>Send All Water</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{$watermonth}}">Choose Tenant</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{$watermonth}}">Choose Rent</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{$watermonth}}">Send All Tenant</option>
                            @elseif($thismode=="Choose Tenant")
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{$watermonth}}">Send Single Water</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{$watermonth}}">Send All Water</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{$watermonth}}" selected>Choose Tenant</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{$watermonth}}">Choose Rent</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{$watermonth}}">Send All Tenant</option>
                            @elseif($thismode=="Choose Rent")
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{$watermonth}}">Send Single Water</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{$watermonth}}">Send All Water</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{$watermonth}}">Choose Tenant</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{$watermonth}}" selected>Choose Rent</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{$watermonth}}">Send All Tenant</option>
                            @elseif($thismode=="Send All Tenant")
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{$watermonth}}">Send Single Water</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{$watermonth}}">Send All Water</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{$watermonth}}">Choose Tenant</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{$watermonth}}">Choose Rent</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{$watermonth}}" selected>Send All Tenant</option>
                            @else
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{$watermonth}}" selected>Send Single Water</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{$watermonth}}">Send All Water</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{$watermonth}}">Choose Tenant</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{$watermonth}}">Choose Rent</option>
                              <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{$watermonth}}">Send All Tenant</option>
                            @endif
                          @endif
                        @elseif($thisothers!="")
                          @if($watermonth=="")
                            @if($thismode=="Other Water")
                              <option value="/properties/send/message/Other/{{$thismode}}/{{App\Http\Controllers\TenantController::getCurrentMonth()}}" selected>Other Water</option>
                            @else
                              <option value="/properties/send/message/Other/{{$thismode}}/{{App\Http\Controllers\TenantController::getCurrentMonth()}}" selected>Other Notification</option>
                            @endif
                          @else
                            @if($thismode=="Other Water")
                              <option value="/properties/send/message/Other/{{$thismode}}/{{$watermonth}}" selected>Other Water</option>
                              <option value="/properties/send/message/Other/Other Notification/{{$watermonth}}">Other Notification</option>
                            @else
                              <option value="/properties/send/message/Other/Other Water/{{$watermonth}}" selected>Other Water</option>
                              <option value="/properties/send/message/Other/{{$thismode}}/{{$watermonth}}" selected>Other Notification</option>
                            @endif
                          @endif
                        @endif
                        
                      </select>
                    </div>

                    <div class="col-sm-4 col-6">
                      <select class="form-control select2" name="allproperties" onchange="location=this.value;" style="width: 100%;">
                        <option value="">Choose Month</option>
                        @if($thisproperty!="" && $thismode!="")
                          @if($monthinfo!="")
                            @forelse($monthinfo as $months)
                              @if($months->Month==$watermonth)
                                <option value="/properties/send/messages/{{ $thisproperty->id }}/{{$thismode}}/{{$months->Month}}" selected>{{App\Models\Property::getMonthDateAddWater($months->Month)}}</option>
                              @else
                                <option value="/properties/send/messages/{{ $thisproperty->id }}/{{$thismode}}/{{$months->Month}}">{{App\Models\Property::getMonthDateAddWater($months->Month)}}</option>
                              @endif
                            @empty

                            @endforelse
                          @endif
                        @elseif($thisothers!="" && $thismode!="")
                        @if($monthinfo!="")
                          @forelse($monthinfo as $months)
                            @if($months->Month==$watermonth)
                              <option value="/properties/send/message/Other/{{$thismode}}/{{$months->Month}}" selected>{{App\Models\Property::getMonthDateAddWater($months->Month)}}</option>
                            @else
                              <option value="/properties/send/message/Other/{{$thismode}}/{{$months->Month}}">{{App\Models\Property::getMonthDateAddWater($months->Month)}}</option>
                            @endif
                          @empty

                          @endforelse
                      @endif
                      @endif
                      </select>
                    </div>
                    </div>
                </div>

                <div class="card-body" style="padding-top: 2px;">
                    @if(\Session::has('success'))
                      <input type="hidden" name="successmsg" id="successmsg" value="{{ \Session::get('success') }}">
                    @endif
                    @if(\Session::has('error'))
                    <input type="hidden" name="errormsg" id="errormsg" value="{{ \Session::get('error') }}">
                    @endif
                    @if(\Session::has('dbError'))
                    <input type="hidden" name="dberrormsg" id="dberrormsg" value="{{ \Session::get('dbError') }}">
                    @endif
                    <div class="row">
                   
                    <div class="col-sm-12" style="padding: 2px;margin: 0px;">
                      @if($thismode!="")
                      @if($thismode=="Single Water")
                      <div class="card direct-chat direct-chat-primary" style="padding: 2px;margin: 5px;">
                      <div class="card-header bg-info" style="padding: 4px;">
                        
                        <span class="" style="font-size: 15px;">Send to 
                                    @if($thisproperty!="")
                                      {{$thisproperty->Plotname}}
                                    @endif
                                    /
                                    @if($thismode!="")
                                      {{$thismode}}
                                    @endif
                                    <i class="bg-light" style="width: 50px;font-size: 10px;padding: 2px;">Not sent</i>
                                    <i class="bg-warning" style="width: 50px;font-size: 10px;padding: 2px;">sent</i>
                                     /{{$watermonth}}
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
                        @if($waterbill!="")
                        @forelse($waterbill as $waters)
                        <div class="col-sm-6">
                        <div class="card-body bg-primary" style="padding: 5px;margin: 3px;">
                          <span class="text-light" style="font-size: 12px;"><b>{{ App\Http\Controllers\TenantController::TenantNames($waters->Tenant) }}, {{ App\Models\Property::getHouseName($waters->House) }} {{ App\Http\Controllers\TenantController::TenantPhone($waters->Tenant) }} </b> 
                            @if(App\Http\Controllers\TenantController::getSentDate($waters->House,$watermonth,$waters->Current)!="")
                              <i class="float-right">{{ App\Http\Controllers\TenantController::getSentDate($waters->House,$watermonth,$waters->Current) }}</i>
                            @else
                              <i class="float-right">Not sent</i>
                            @endif
                          </span>
                          <hr style="margin: 10px;">
                          <div class="card-body">
                            <div class="direct-chat-msg right">
                              <form role="form" class="form-horizontal" method="POST" action="/properties/send/messages/singlewater">
                                    @csrf
                                    <div class="col-sm-12">
                                        <div class="" style="text-align: center;">
                                          <div class="">
                                            <input type="hidden" name="pid" value="{{$thisproperty->id}}">
                                            <input type="hidden" name="id" value="{{$waters->House}}">
                                            <input type="hidden" name="phone" value="{{App\Http\Controllers\TenantController::TenantPhone($waters->Tenant)}}">
                                            <input type="hidden" name="month" value="{{$watermonth}}">
                                            <input type="hidden" name="message" value="WATER BILL: Greetings {{ App\Models\Property::getHouseName($waters->House) }} :{{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }};Previous:{{$waters->Previous}};Current:{{$waters->Current}};UnitCost Kshs.{{$waters->Cost}};Units:{{$waters->Units}};CC:{{$waters->Units*$waters->Cost}};Other:{{$waters->Total_OS}};Total Kshs.{{$waters->Total+$waters->Total_OS}}.Thank You">
                                            
                                          </div>
                                        </div>
                                    </div>
                                    @if(App\Http\Controllers\TenantController::getSentDate($waters->House,$watermonth,$waters->Current)!="")
                                      <button class="btn btn-warning float-right" style="padding: 2px;font-size: 12px;">Resend</button>
                                    @else
                                      <button class="btn btn-warning float-right" style="padding: 5px;">Send</button>
                                    @endif
                                    
                                </form>
                              
                              <!-- /.direct-chat-img -->
                              @if(App\Http\Controllers\TenantController::getSentDate($waters->House,$watermonth,$waters->Current)!="")
                              <div class="direct-chat-text bg-warning">
                                WATER BILL: Greetings {{ App\Models\Property::getHouseName($waters->House) }} :{{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }};Previous:{{$waters->Previous}};Current:{{$waters->Current}};UnitCost Kshs.{{$waters->Cost}};Units:{{$waters->Units}};CC:{{$waters->Units*$waters->Cost}};Other:{{$waters->Total_OS}};Total Kshs.{{$waters->Total+$waters->Total_OS}}.Thank You
                              </div>
                              @else
                                <div class="direct-chat-text bg-light">
                                WATER BILL: Greetings {{ App\Models\Property::getHouseName($waters->House) }} :{{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }};Previous:{{$waters->Previous}};Current:{{$waters->Current}};UnitCost Kshs.{{$waters->Cost}};Units:{{$waters->Units}};CC:{{$waters->Units*$waters->Cost}};Other:{{$waters->Total_OS}};Total Kshs.{{$waters->Total+$waters->Total_OS}}.Thank You
                              </div>
                              @endif
                              <!-- /.direct-chat-text -->
                            </div>
                          </div>
                        </div>
                        </div>
                      
                        @empty
                          <h4 class="text-info" style="padding:10px;margin-left:10px;">Messages Sent and Not Sent will be displayed here</h4>
                        @endforelse
                      @endif
                      </div>

                      </div>
                      <!-- /.card-body -->
                      </div>

                      @elseif($thismode=="All Water")
                      <div class="card direct-chat direct-chat-primary" style="padding: 2px;margin: 5px;">
                      <div class="card-header bg-info" style="padding: 4px;">
                        <span style="position:fixed;z-index:999999;color:red;font-size:20px;right:5%;">Selected Tenants(<i class="badge selwaterbilltenants" id="selectedwaterbilltenants" style="font-size:30px;">0</i>)</span>
                        <span class="" style="font-size: 15px;">Send to 
                                    @if($thisproperty!="")
                                      {{$thisproperty->Plotname}}
                                    @endif
                                    /
                                    @if($thismode!="")
                                      {{$thismode}}
                                    @endif
                                    <i class="bg-light" style="width: 50px;font-size: 10px;padding: 2px;">Not sent</i>
                                    <i class="bg-warning" style="width: 50px;font-size: 10px;padding: 2px;">sent</i>
                                     /{{$watermonth}}
                                  

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
                      <form role="form" class="form-horizontal" method="POST" action="/properties/send/messages/allwater">
                            @csrf
                       <div class="card-body">
                        <div class="row">
                        
                        @if($waterbill!="")
                        @forelse($waterbill as $waters)
                        <!-- all water send message -->
                        <div class="col-sm-6 col-12">
                        <div class="card-body bg-primary" style="padding: 5px;margin: 3px;">
                         
                          <span class="text-light" style="font-size: 12px;"><b>{{ App\Http\Controllers\TenantController::TenantNames($waters->Tenant) }}, {{ App\Models\Property::getHouseName($waters->House) }} {{ App\Http\Controllers\TenantController::TenantPhone($waters->Tenant) }} </b> 
                            @if(App\Http\Controllers\TenantController::getSentDate($waters->House,$watermonth,$waters->Current)!="")
                              <i class="float-right">{{ App\Http\Controllers\TenantController::getSentDate($waters->House,$watermonth,$waters->Current) }}</i>
                            @else
                              <i class="float-right">Not sent</i>
                            @endif
                          </span><hr style="margin: 10px;">
                          <div class="card-body">
                            <div class="direct-chat-msg right">
                                    <div class="col-sm-12">
                                        <div class="" style="text-align: center;">
                                          <div class="">
                                            <input type="hidden" name="pid" value="{{$thisproperty->id}}">
                                            <input type="hidden" name="month" value="{{$watermonth}}">
                                          </div>
                                        </div>
                                    </div>
                              
                              <!-- /.direct-chat-img -->
                              @if(App\Http\Controllers\TenantController::getSentDate($waters->House,$watermonth,$waters->Current)!="")
                              <div class="direct-chat-text waterselmemodivsent" style="padding:10px;margin:5px;background-color:#FFEB06;color:black;cursor:pointer;" data-id1="watermemo{{$waters->id}}">
                                  <label> <input type="checkbox" class="selectedwaterbilltenants" name="waterchoosen[]" id="watermemo{{$waters->id}}"  value="{{App\Http\Controllers\TenantController::TenantPhone($waters->Tenant)}}/{{$waters->House}}/WATER BILL: Greetings {{ App\Models\Property::getHouseName($waters->House) }} :{{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }};Previous:{{$waters->Previous}};Current:{{$waters->Current}};UnitCost Kshs.{{$waters->Cost}};Units:{{$waters->Units}};CC:{{$waters->Units*$waters->Cost}};Other:{{$waters->Total_OS}};Total Kshs.{{$waters->Total+$waters->Total_OS}}.Thank You"> WATER BILL: Greetings {{ App\Models\Property::getHouseName($waters->House) }} :{{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }};Previous:{{$waters->Previous}};Current:{{$waters->Current}};UnitCost Kshs.{{$waters->Cost}};Units:{{$waters->Units}};CC:{{$waters->Units*$waters->Cost}};Other:{{$waters->Total_OS}};Total Kshs.{{$waters->Total+$waters->Total_OS}}.Thank You</label>
                              </div>
                              @else
                              <div class="direct-chat-text waterselmemodiv" style="padding:10px;margin:5px;background-color:#FFFFFF;color:black;cursor:pointer;" data-id1="watermemo{{$waters->id}}">
                                  <label> <input type="checkbox" class="selectedwaterbilltenants" name="waterchoosen[]" id="watermemo{{$waters->id}}"  value="{{App\Http\Controllers\TenantController::TenantPhone($waters->Tenant)}}/{{$waters->House}}/WATER BILL: Greetings {{ App\Models\Property::getHouseName($waters->House) }} :{{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }};Previous:{{$waters->Previous}};Current:{{$waters->Current}};UnitCost Kshs.{{$waters->Cost}};Units:{{$waters->Units}};CC:{{$waters->Units*$waters->Cost}};Other:{{$waters->Total_OS}};Total Kshs.{{$waters->Total+$waters->Total_OS}}.Thank You"> WATER BILL: Greetings {{ App\Models\Property::getHouseName($waters->House) }} :{{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }};Previous:{{$waters->Previous}};Current:{{$waters->Current}};UnitCost Kshs.{{$waters->Cost}};Units:{{$waters->Units}};CC:{{$waters->Units*$waters->Cost}};Other:{{$waters->Total_OS}};Total Kshs.{{$waters->Total+$waters->Total_OS}}.Thank You</label>
                              </div>
                              @endif
                              <!-- /.direct-chat-text -->
                            </div>
                          </div>
                          
                        </div>

                       
                        </div>

                        @empty
                          <h4 class="text-info" style="padding:10px;margin-left:10px;">Messages Sent and Not Sent will be displayed here</h4>
                        @endforelse
                      @endif
                      </div>

                      </div>
                      @if($thisproperty->Waterbill=="Monthly")
                      @forelse($waterbill as $watersbil)
                      <div class="modal-footer justify-content-between bg-primary" style="padding: 3px;">
                        <div style="color: white;">
                          <span style="color:red;font-size:20px;">Selected Tenants(<i class="badge selwaterbilltenants" id="selectedwaterbilltenants" style="font-size:30px;">0</i>)</span>
                          <button class="btn btn-warning float-right" style="padding: 5px;">Send Selected Messages</button>
                        </div>
                      </div>
                      @break(($loop->index)==0)

                      @empty

                      @endforelse
                      @endif
                    </form>
                      </div>

                    <!-- start other water  -->
                    @elseif($thismode=="Other Water")
                      <div class="card direct-chat direct-chat-primary" style="padding: 2px;margin: 5px;">
                      <div class="card-header bg-info" style="padding: 4px;">
                        
                        <span class="" style="font-size: 15px;">Send to 
                                    @if($thisproperty!="")
                                      {{$thisproperty->Plotname}}
                                    @endif
                                    @if($thisothers!="")
                                      {{$thisothers}}
                                    @endif
                                    @if($thismode!="")
                                      /{{$thismode}}
                                    @endif
                                    <i class="bg-light" style="width: 50px;font-size: 10px;padding: 2px;">Not sent</i>
                                    <i class="bg-warning" style="width: 50px;font-size: 10px;padding: 2px;">sent</i>
                                     /{{$watermonth}}
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
                        @if($waterbill!="")
                        @forelse($waterbill as $waters)
                        <div class="col-sm-6">
                        <div class="card-body bg-primary" style="padding: 5px;margin: 3px;">
                          <span class="text-light" style="font-size: 12px;"><b>{{ App\Http\Controllers\TenantController::TenantNames($waters->Tenant) }}, {{ App\Http\Controllers\TenantController::TenantPhone($waters->Tenant) }} </b> 
                            @if(App\Http\Controllers\TenantController::getSentDateOther($waters->Tenant,$watermonth)!="")
                              <i class="float-right">{{ App\Http\Controllers\TenantController::getSentDateOther($waters->House,$watermonth) }}</i>
                            @else
                              <i class="float-right">Not sent</i>
                            @endif
                          </span><hr style="margin: 10px;">
                          <div class="card-body">
                            <div class="direct-chat-msg right">
                              <form role="form" class="form-horizontal" method="POST" action="/properties/send/messages/others/singlewater">
                                    @csrf
                                    <div class="col-sm-12">
                                        <div class="" style="text-align: center;">
                                          <div class="">
                                            <input type="hidden" name="id" value="{{$waters->Tenant}}">
                                            <input type="hidden" name="phone" value="{{App\Http\Controllers\TenantController::TenantPhone($waters->Tenant)}}">
                                            <input type="hidden" name="month" value="{{$watermonth}}">
                                            <input type="hidden" name="message" value="WATER BILL: Greetings {{ App\Http\Controllers\TenantController::TenantNames($waters->Tenant) }} :{{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }};Previous:{{$waters->Previous}};Current:{{$waters->Current}};UnitCost Kshs.{{$waters->Cost}};Units:{{$waters->Units}};CC:{{$waters->Units*$waters->Cost}};Other:{{$waters->Total_OS}};Total Kshs.{{$waters->Total+$waters->Total_OS}}.Thank You">
                                            
                                          </div>
                                        </div>
                                    </div>
                                    @if(App\Http\Controllers\TenantController::getSentDateOther($waters->Tenant,$watermonth)!="")
                                      <button class="btn btn-warning float-right" style="padding: 2px;font-size: 12px;">Resend</button>
                                    @else
                                      <button class="btn btn-warning float-right" style="padding: 5px;">Send</button>
                                    @endif
                                    
                                </form>
                              
                              <!-- /.direct-chat-img -->
                              @if(App\Http\Controllers\TenantController::getSentDateOther($waters->Tenant,$watermonth)!="")
                              <div class="direct-chat-text bg-warning">
                                WATER BILL: Greetings {{ App\Models\Property::getHouseName($waters->Tenant) }} :{{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }};Previous:{{$waters->Previous}};Current:{{$waters->Current}};UnitCost Kshs.{{$waters->Cost}};Units:{{$waters->Units}};CC:{{$waters->Units*$waters->Cost}};Other:{{$waters->Total_OS}};Total Kshs.{{$waters->Total+$waters->Total_OS}}.Thank You
                              </div>
                              @else
                                <div class="direct-chat-text bg-light">
                                WATER BILL: Greetings {{ App\Models\Property::getHouseName($waters->Tenant) }} :{{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }};Previous:{{$waters->Previous}};Current:{{$waters->Current}};UnitCost Kshs.{{$waters->Cost}};Units:{{$waters->Units}};CC:{{$waters->Units*$waters->Cost}};Other:{{$waters->Total_OS}};Total Kshs.{{$waters->Total+$waters->Total_OS}}.Thank You
                              </div>
                              @endif
                              <!-- /.direct-chat-text -->
                            </div>
                          </div>
                        </div>
                        </div>
                      
                        @empty
                          <h4 class="text-info" style="padding:10px;margin-left:10px;">Messages Sent and Not Sent will be displayed here</h4>
                        @endforelse
                      @endif
                      </div>

                      </div>
                      <!-- /.card-body -->
                      </div>
                      <!-- end messages for other -->

                      <!-- start other notification -->
                      @elseif($thismode=="Other Notification")
                      <div class="card direct-chat direct-chat-primary" style="padding: 2px;margin: 5px;">
                      <div class="card-header bg-info" style="padding: 4px;">
                        
                        <span class="" style="font-size: 15px;">Send to 
                                    @if($thisproperty!="")
                                      {{$thisproperty->Plotname}}
                                    @endif
                                    @if($thisothers!="")
                                      {{$thisothers}}
                                    @endif
                                    @if($thismode!="")
                                      /{{$thismode}}
                                    @endif
                                    <i class="bg-light" style="width: 50px;font-size: 10px;padding: 2px;">Not sent</i>
                                    <i class="bg-warning" style="width: 50px;font-size: 10px;padding: 2px;">sent</i>
                                     /{{$watermonth}}
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
                        @if($waterbill!="")
                        @forelse($waterbill as $waters)
                        <div class="col-sm-6">
                        <div class="card-body bg-primary" style="padding: 5px;margin: 3px;">
                          <span class="text-light" style="font-size: 12px;"><b>{{ App\Http\Controllers\TenantController::TenantNames($waters->Tenant) }}, {{ App\Http\Controllers\TenantController::TenantPhone($waters->Tenant) }} </b> 
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#tenant{{ $waters->Tenant }}" style="padding: 2px;display: inline;font-size: 12px;color: white;">
                               <i class="fa fa-dollar"> </i> Add Payment
                              </button>
                            @if(App\Http\Controllers\TenantController::getSentDateOther($waters->Tenant,$watermonth)!="")
                              <i class="float-right">{{ $waters->Status }}</i>
                            @else
                              <i class="float-right">Not sent</i>
                            @endif
                          </span>
                          <div class="card-body">
                            <div class="direct-chat-msg right">
                              <form role="form" class="form-horizontal" method="POST" action="/properties/send/messages/others/notification">
                                    @csrf
                                    <div class="col-sm-12">
                                        <div class="" style="text-align: center;">
                                          <div class="">
                                            <input type="hidden" name="id" value="{{$waters->Tenant}}">
                                            <input type="hidden" name="wid" value="{{$waters->id}}">
                                            <input type="hidden" name="phone" value="{{App\Http\Controllers\TenantController::TenantPhone($waters->Tenant)}}">
                                            <input type="hidden" name="month" value="{{$watermonth}}">
                                            @if(($waters->Waterbill+$waters->Arrears)-($waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded+$waters->Excess)>0)
                                              <input type="hidden" name="message" value="WATERBILL RECEIPT: Greetings {{ App\Http\Controllers\TenantController::TenantFNames($waters->Tenant) }}, {{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }} bill. Total {{$waters->Waterbill}}, Received Ksh.{{$waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded}}.Balance Kshs.{{($waters->Waterbill+$waters->Arrears)-($waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded+$waters->Excess)}}. Thank You">
                                            @elseif(($waters->Waterbill+$waters->Arrears)-($waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded+$waters->Excess)<0)
                                              <input type="hidden" name="message" value="WATERBILL RECEIPT: Greetings {{ App\Http\Controllers\TenantController::TenantFNames($waters->Tenant) }}, {{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }} bill. Total {{$waters->Waterbill}}, Received Ksh.{{$waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded}}.Overpayment Kshs.{{abs(($waters->Waterbill+$waters->Arrears)-($waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded+$waters->Excess))}}.Thank You">
                                            @else
                                              <input type="hidden" name="message" value="WATERBILL RECEIPT: Greetings {{ App\Http\Controllers\TenantController::TenantFNames($waters->Tenant) }}, {{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }} bill. Total {{$waters->Waterbill}}, Received Ksh.{{$waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded}}.Thank You">
                                            @endif
                                            
                                          </div>
                                        </div>
                                    </div>
                                    @if(($waters->Status)=="Sent")
                                      <button class="btn btn-warning float-right" style="padding: 2px;font-size: 12px;">Resend</button>
                                    @else
                                      <button class="btn btn-warning float-right" style="padding: 5px;">Send</button>
                                    @endif
                                    
                                </form>
                              
                              <!-- /.direct-chat-img -->
                              @if(($waters->Status)=="Sent")
                              <div class="direct-chat-text bg-warning">
                                WATERBILL RECEIPT: Greetings {{ App\Http\Controllers\TenantController::TenantFNames($waters->Tenant) }}, {{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }} bill. Total {{$waters->Waterbill}}, Received:Ksh.{{$waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded}}. 
                                @if(($waters->Waterbill+$waters->Arrears)-($waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded+$waters->Excess)>0)
                                  Balance Kshs.{{($waters->Waterbill+$waters->Arrears)-($waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded+$waters->Excess)}}.
                                @elseif(($waters->Waterbill+$waters->Arrears)-($waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded+$waters->Excess)<0)
                                  Overpayment Kshs.{{abs(($waters->Waterbill+$waters->Arrears)-($waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded+$waters->Excess))}}.
                                @endif
                                Thank You
                              </div>
                              @else
                                
                              <div class="direct-chat-text bg-light">
                                WATERBILL RECEIPT: Greetings {{ App\Http\Controllers\TenantController::TenantFNames($waters->Tenant) }}, {{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }} bill. Total {{$waters->Waterbill}}, Received:Ksh.{{$waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded}}. 
                                @if(($waters->Waterbill+$waters->Arrears)-($waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded+$waters->Excess)>0)
                                  Balance Kshs.{{($waters->Waterbill+$waters->Arrears)-($waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded+$waters->Excess)}}.
                                @elseif(($waters->Waterbill+$waters->Arrears)-($waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded+$waters->Excess)<0)
                                  Overpayment Kshs.{{abs(($waters->Waterbill+$waters->Arrears)-($waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded+$waters->Excess))}}.
                                @endif
                                Thank You
                              </div>
                              @endif
                              <!-- /.direct-chat-text -->
                            </div>
                          </div>
                        </div>
                        </div>

                        <div class="modal fade" id="tenant{{ $waters->Tenant }}">
                          <div class="modal-dialog">
                            <div class="modal-content bg-info">
                              <div class="modal-header">
                                <h6 class="modal-title">Water bill Payment for {{ App\Http\Controllers\TenantController::TenantNames($waters->Tenant) }}, {{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }} </h6>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body bg-light">
                                <form role="form" class="form-horizontal" method="POST" action="/properties/save/waterbill/otherpayment">
                                @csrf
                                  <div class="row">
                                  <input type="hidden" name="wid" value="{{$waters->id}}">
                                  <input type="hidden" name="id" value="{{$waters->Tenant}}">
                                  <input type="hidden" name="month" value="{{$watermonth}}">

                                  <div class="col-sm-6">
                                      <div class="card card-primary card-outline bg-info" style="margin-bottom: 5%;text-align: center;">
                                        <div class="card-body">
                                          <div class="form-group row">
                                              <label for="Waterbill" class="col-md-4 col-form-label text-md-right">{{ __('Usage') }}</label>

                                              <div class="col-md-8">
                                                  <input id="Waterbill" type="text" class="form-control @error('Waterbill') is-invalid @enderror" name="Waterbill" value="{{$waters->Waterbill}}" placeholder="Waterbill" required readonly autocomplete="Waterbill" autofocus>

                                                  @error('Waterbill')
                                                      <span class="invalid-feedback" role="alert">
                                                          <strong>{{ $message }}</strong>
                                                      </span>
                                                  @enderror
                                              </div>
                                          </div>

                                          <div class="form-group row">
                                              <label for="Arrears" class="col-md-4 col-form-label text-md-right">{{ __('Arrears') }}</label>

                                              <div class="col-md-8">
                                                  <input id="Arrears" type="text" class="form-control @error('Arrears') is-invalid @enderror" name="Arrears" value="{{$waters->Arrears}}" placeholder="0.00" required autocomplete="Arrears" autofocus>

                                                  @error('Arrears')
                                                      <span class="invalid-feedback" role="alert">
                                                          <strong>{{ $message }}</strong>
                                                      </span>
                                                  @enderror
                                              </div>
                                          </div>

                                          <div class="form-group row">
                                              <label for="Excess" class="col-md-4 col-form-label text-md-right">{{ __('Excess') }}</label>

                                              <div class="col-md-8">
                                                  <input id="Excess" type="text" class="form-control @error('Excess') is-invalid @enderror" name="Excess" value="{{$waters->Excess}}" placeholder="0.00" required autocomplete="Excess" autofocus>

                                                  @error('Excess')
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
                                              <label for="PaidUploaded" class="col-md-4 col-form-label text-md-right">{{ __('Paid') }}</label>

                                              <div class="col-md-8">
                                                  <input id="PaidUploaded" type="text" class="form-control @error('PaidUploaded') is-invalid @enderror" name="PaidUploaded" value="{{$waters->PaidUploaded}}" placeholder="0" required autocomplete="PaidUploaded" autofocus>

                                                  @error('PaidUploaded')
                                                      <span class="invalid-feedback" role="alert">
                                                          <strong>{{ $message }}</strong>
                                                      </span>
                                                  @enderror
                                              </div>
                                          </div>

                                          <div class="form-group row">
                                              <div class="col-sm-12">
                                                  <button  class="btn btn-success btn-small" name="submitplotbtn" id="submitplotbtn"  type="submit" >Save Payment for @if($watermonth!="")
                                                {{$watermonth}}
                                              @endif
                                              /{{ App\Http\Controllers\TenantController::TenantNames($waters->Tenant) }}</button>
                                              </div>
                                          </div>

                                          
                                        </div>
                                      </div>
                                  </div>
                                  
                                </div>
                              </form>
                              </div>
                              <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                              </div>
                            </div>
                            <!-- /.modal-content -->
                          </div>
                          <!-- /.modal-dialog -->
                        </div>
                      
                        @empty
                          <h4 class="text-info" style="padding:10px;margin-left:10px;">Messages Sent and Not Sent will be displayed here</h4>
                        @endforelse
                      @endif
                      </div>

                      </div>
                      <!-- /.card-body -->
                      </div>
                      <!-- end other nottification -->

                      @elseif($thismode=="Choose Tenant")
                      <div class="card direct-chat direct-chat-primary" style="padding: 2px;margin: 5px;">
                      <div class="card-header bg-info" style="padding: 4px;">
                        <span style="position:fixed;z-index:999999;color:red;font-size:20px;right:5%;">Selected Tenants(<i class="badge selwaterbilltenants" id="selectedwaterbilltenants" style="font-size:30px;">0</i>)</span>
                        <span class="" style="font-size: 15px;">Send to 
                            @if($thisproperty!="")
                              {{$thisproperty->Plotname}}
                            @endif
                            /
                            @if($thismode!="")
                              {{$thismode}}
                            @endif
                            <i class="bg-light" style="width: 50px;font-size: 10px;padding: 2px;">Not sent</i>
                            <i class="bg-warning" style="width: 50px;font-size: 10px;padding: 2px;">sent</i>
                             /{{$watermonth}}
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
                      <form role="form" class="form-horizontal" method="POST" action="/properties/send/messages/choosetenant">
                        @csrf
                       <div class="card-body">
                        <div class="row">
                        
                        @if($waterbill!="")
                        @forelse($waterbill as $waters)
                        <!-- all water send message -->
                        <div class="col-sm-6 col-12">
                        <div class="card-body bg-primary" style="padding: 5px;margin: 3px;">
                         
                          <div class="card-body">
                            <div class="direct-chat-msg right">
                              <div class="col-sm-12">
                                  <div class="" style="text-align: center;">
                                    <div class="">
                                      <input type="hidden" name="pid" value="{{$thisproperty->id}}">
                                      <input type="hidden" name="month" value="{{$watermonth}}">
                                    </div>
                                  </div>
                              </div>
                              
                              <!-- /.direct-chat-img -->
                              <div class="direct-chat-text waterselmemodiv" style="padding:10px;margin:5px;background-color:#FFFFFF;color:black;cursor:pointer;" data-id1="watermemo{{$waters->id}}">
                                  <label> <input type="checkbox" class="selectedwaterbilltenants" name="waterchoosen[]" id="watermemo{{$waters->id}}"  value="+254{{App\Http\Controllers\TenantController::TenantPhone($waters->Tenant)}}"> <b>House: {{ App\Models\Property::getHouseName($waters->House) }}, Name: {{ App\Http\Controllers\TenantController::TenantNames($waters->Tenant) }},Phone: {{ App\Http\Controllers\TenantController::TenantPhone($waters->Tenant) }} </b> </label>
                              </div>
                              
                              <!-- /.direct-chat-text -->
                            </div>
                          </div>
                          
                        </div>

                       
                        </div>

                        @empty
                          <h4 class="text-info" style="padding:10px;margin-left:10px;">Messages Sent and Not Sent will be displayed here</h4>
                        @endforelse
                      @endif
                      </div>

                      </div>
                      <div class="modal-footer justify-content-between bg-primary" style="padding: 3px;">
                        <div style="color: white;">
                          <div class="">
                            <div class="col-lg-12">
                              <textarea  id="Message" type="text" class="form-control @error('Message') is-invalid @enderror" name="Message" placeholder="Compose Message to Send to Selected Tenants" required autocomplete="Message" autofocus cols="150">{{ old('Message') }}</textarea>
                              <span id="charactercountsinglewater" name="charactercountsinglewater" class="badge">Characters: 0, 1 Message(s)<span>
                                @error('Message')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                          </div>
                          <button class="btn btn-warning float-right" style="padding: 5px;">Send Selected Messages</button>
                        </div>

                          
                      </div>
                    </form>
                      </div>
<!-- end choose tenants -->
                      @endif
                      @else

                      <!-- starting compose message -->

                      <div class="modal-body bg-light">
                        <form role="form" class="form-horizontal" method="post" action="/properties/send/message">
                        @csrf
                          <div class="row">
                          
                          <div class="col-sm-12">
                              <div class="card card-primary card-outline bg-info" style="margin-bottom: 5%;text-align: center;">
                                <div class="card-body">

                                  <div class="form-group row">
                                      <label for="Phone" class="col-md-2 col-form-label text-md-right">{{ __('Phone') }}</label>

                                      <div class="col-md-10">
                                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="+2547xxxxxxxx,+2547xxxxxxx1,+2547xxxxxxx2" required autocomplete="phone" autofocus>

                                          @error('Phone')
                                              <span class="invalid-feedback" role="alert">
                                                  <strong>{{ $message }}</strong>
                                              </span>
                                          @enderror
                                      </div>
                                    </div>
                                    <div class="form-group row">
                                      <label for="Message" class="col-md-2 col-form-label text-md-right">{{ __('Message') }}</label>

                                      <div class="col-md-10">
                                        <textarea  id="Message" type="text" class="form-control @error('Message') is-invalid @enderror" name="Message" placeholder="Compose Message Here" required autocomplete="Message" autofocus>{{ old('Message') }}</textarea>
                                        <span id="charactercountsinglewater" name="charactercountsinglewater" class="badge">Characters: 0, 1 Message(s)<span>
                                          @error('Message')
                                              <span class="invalid-feedback" role="alert">
                                                  <strong>{{ $message }}</strong>
                                              </span>
                                          @enderror
                                      </div>
                                  </div>

                                  <div class="form-group row">
                                      <div class="col-sm-12">
                                          <button  class="btn btn-success btn-small" name="submitplotbtn" id="submitplotbtn"  type="submit" >Send Message</button>
                                      </div>
                                  </div>

                                  
                                </div>
                              </div>
                          </div>
                          
                      </div>
                      </form>
                      </div>
                      @endif
                    </div>
               
                                
                   
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Toastr -->
<script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
<script type="text/javascript">
$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    var successmsg=$('#successmsg').val();
    var errormsg=$('#errormsg').val();
    var dberrormsg=$('#dberrormsg').val();
  

    if (successmsg) {
      toastr.success(successmsg)
    }
    if (dberrormsg) {
      toastr.error(dberrormsg)
    }
    if (errormsg) {
         toastr.warning(errormsg)
    }

});

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
    //CALCULATE MESSAGE CHARACTERS ON CHANGING VALUE
    $(document).on('change','#Message',function(){
      calcMSGCharactersSingle();
    });

    //CALCULATE MESSAGE CHARACTERS ON TYPING VALUE
    $(document).on('keyup','#Message',function(){
      calcMSGCharactersSingle();
    });

    function calcMSGCharactersSingle(){
      var data =$('#Message').val();
      var counttxt=data.length;
      if (counttxt>160) {
        var msgs=Math.floor(counttxt/160)+1;
        $('#charactercountsinglewater').html("Characters: "+counttxt+", "+msgs+" Message(s)");
      }
      else{
        var msgs=1;
        $('#charactercountsinglewater').html("Characters: "+counttxt+", "+msgs+" Message(s)");
      }
    }
</script>
@endpush