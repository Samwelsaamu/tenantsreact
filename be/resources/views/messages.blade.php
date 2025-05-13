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
  <link rel="stylesheet" href="{{ asset('public/assets/plugins/toastr/toastr.min.css') }}">
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
        <a href="/properties/messages" class="nav-link active">
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
<div class="row justify-content-center m-0 p-0" style="">
  <div class="col-md-12 m-0 p-0" style="">
    <div class="row m-0 p-0">
      <!-- start of left side -->
      <div class="col-md-4 m-0 p-1">
        <div class="card mb-2">
          <div class="card-body m-1 p-1">
            <div class="row m-0 p-0">
              <div class="col-12 m-1 p-0">
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
              <div class="col-12 m-1 p-0">
                <select class="form-control select2" name="allproperties" onchange="location=this.value;" style="width: 100%;">
                  <option value="">Choose Mode</option>
                  @if($thisproperty!="")
                    @if($watermonth=="")
                      @if($thismode=="Single Water")
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}" selected>Send Single Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send All Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Completed Payment/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Completed Payment</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Summary Paid/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Summary Paid</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Choose Tenant</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Choose Rent</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send All Tenant</option>
                      @elseif($thismode=="All Water")
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Single Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}" selected>Send All Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Completed Payment/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Completed Payment</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Summary Paid/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Summary Paid</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Choose Tenant</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Choose Rent</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send All Tenant</option>
                      @elseif($thismode=="Choose Tenant")
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Single Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send All Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Completed Payment/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Completed Payment</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Summary Paid/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Summary Paid</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}" selected>Choose Tenant</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Choose Rent</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send All Tenant</option>
                      @elseif($thismode=="Choose Rent")
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Single Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send All Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Completed Payment/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Completed Payment</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Summary Paid/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Summary Paid</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Choose Tenant</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{App\Http\Controllers\TenantController::getCurrentMonth()}}" selected>Choose Rent</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send All Tenant</option>
                      @elseif($thismode=="Send All Tenant")
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Single Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send All Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Completed Payment/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Completed Payment</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Summary Paid/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Summary Paid</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Choose Tenant</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Choose Rent</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}" selected>Send All Tenant</option>
                      @elseif($thismode=="Completed Payment")
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Single Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send All Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Completed Payment/{{App\Http\Controllers\TenantController::getCurrentMonth()}}" selected>Send Completed Payment</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Summary Paid/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Summary Paid</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Choose Tenant</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Choose Rent</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send All Tenant</option>
                      @elseif($thismode=="Summary Paid")
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Single Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send All Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Completed Payment/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Completed Payment</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Summary Paid/{{App\Http\Controllers\TenantController::getCurrentMonth()}}" selected>Send Summary Paid</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Choose Tenant</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Choose Rent</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send All Tenant</option>
                      @else
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Single Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send All Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Completed Payment/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Completed Payment</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Summary Paid/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send Summary Paid</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Choose Tenant</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Choose Rent</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{App\Http\Controllers\TenantController::getCurrentMonth()}}">Send All Tenant</option>
                      @endif
                    @else
                      @if($thismode=="Single Water")
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{$watermonth}}" selected>Send Single Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{$watermonth}}">Send All Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Completed Payment/{{$watermonth}}">Send Completed Payment</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Summary Paid/{{$watermonth}}">Send Summary Paid</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{$watermonth}}">Choose Tenant</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{$watermonth}}">Choose Rent</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{$watermonth}}">Send All Tenant</option>
                      @elseif($thismode=="All Water")
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{$watermonth}}">Send Single Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{$watermonth}}" selected>Send All Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Completed Payment/{{$watermonth}}">Send Completed Payment</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Summary Paid/{{$watermonth}}">Send Summary Paid</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{$watermonth}}">Choose Tenant</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{$watermonth}}">Choose Rent</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{$watermonth}}">Send All Tenant</option>
                      @elseif($thismode=="Choose Tenant")
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{$watermonth}}">Send Single Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{$watermonth}}">Send All Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Completed Payment/{{$watermonth}}">Send Completed Payment</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Summary Paid/{{$watermonth}}">Send Summary Paid</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{$watermonth}}" selected>Choose Tenant</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{$watermonth}}">Choose Rent</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{$watermonth}}">Send All Tenant</option>
                      @elseif($thismode=="Choose Rent")
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{$watermonth}}">Send Single Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{$watermonth}}">Send All Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Completed Payment/{{$watermonth}}">Send Completed Payment</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Summary Paid/{{$watermonth}}">Send Summary Paid</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{$watermonth}}">Choose Tenant</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{$watermonth}}" selected>Choose Rent</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{$watermonth}}">Send All Tenant</option>
                      @elseif($thismode=="Send All Tenant")
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{$watermonth}}">Send Single Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{$watermonth}}">Send All Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Completed Payment/{{$watermonth}}">Send Completed Payment</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Summary Paid/{{$watermonth}}">Send Summary Paid</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{$watermonth}}">Choose Tenant</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{$watermonth}}">Choose Rent</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{$watermonth}}" selected>Send All Tenant</option>
                      @elseif($thismode=="Completed Payment")
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{$watermonth}}">Send Single Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{$watermonth}}">Send All Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Completed Payment/{{$watermonth}}" selected>Send Completed Payment</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Summary Paid/{{$watermonth}}">Send Summary Paid</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{$watermonth}}">Choose Tenant</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{$watermonth}}">Choose Rent</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{$watermonth}}">Send All Tenant</option>
                      @elseif($thismode=="Summary Paid")
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{$watermonth}}">Send Single Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{$watermonth}}">Send All Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Completed Payment/{{$watermonth}}">Send Completed Payment</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Summary Paid/{{$watermonth}}" selected>Send Summary Paid</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Tenant/{{$watermonth}}">Choose Tenant</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Choose Rent/{{$watermonth}}">Choose Rent</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Send All Tenant/{{$watermonth}}">Send All Tenant</option>
                      @else
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Single Water/{{$watermonth}}">Send Single Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/All Water/{{$watermonth}}">Send All Water</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Completed Payment/{{$watermonth}}">Send Completed Payment</option>
                        <option value="/properties/send/messages/{{ $thisproperty->id }}/Summary Paid/{{$watermonth}}">Send Summary Paid</option>
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

              <div class="col-12 m-1 p-0">
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
        </div>
      </div>


      <div class="col-md-8 m-0 p-1">
        <div class="card mb-2">
          <div class="card-body m-1 p-1">
            <div class="row m-0 p-0">
              <div class="col-12 m-0 p-0">
                  @if(\Session::has('success'))
                    <input type="hidden" name="successmsg" id="successmsg" value="{{ \Session::get('success') }}">
                  @endif
                  @if(\Session::has('error'))
                    <input type="hidden" name="errormsg" id="errormsg" value="{{ \Session::get('error') }}">
                  @endif
                  @if(\Session::has('dbError'))
                    <input type="hidden" name="dberrormsg" id="dberrormsg" value="{{ \Session::get('dbError') }}">
                  @endif

                  <div class="row m-0 p-0">
                    <div class="col-12 m-0 p-0">
                      @if($thismode!="")
                        @if($thismode=="Single Water")
                          <div class="card direct-chat direct-chat-primary m-0 p-0">
                            <div class="card-header bg-white m-0 p-2">
                              <span class="" style="font-size: 15px;">
                                Send to 
                                @if($thisproperty!="")
                                  {{$thisproperty->Plotname}}
                                @endif
                                  /
                                @if($thismode!="")
                                  {{$thismode}}
                                @endif
                                  <i class="bg-light m-1 p-1" style="border-radius:5px;">Not sent</i>
                                  <i class="bg-warning m-1 p-1" style="border-radius:5px;">sent</i>
                                  /{{$watermonth}}
                              </span>
                              <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                  <i class="fas fa-minus"></i>
                                </button>
                              </div>
                            </div>

                            <div class="card-body" style="overflow-x:auto;max-height: calc(100vh - 12rem);overflow-y:auto;">
                              <div class="row m-0 p-0">
                                @if($waterbill!="")
                                  @forelse($waterbill as $waters)
                                    <div class="col-12 m-0 p-0" style="border-radius:5px;">
                                      <div class="card-body bg-white m-1 elevation-3" style="border-radius:5px;">
                                        <form role="form" class="form-horizontal" method="POST" action="/properties/send/messages/singlewater" id="formmessagesavesendsinglewater" onsubmit="return confirmOperation(this,'Send Tenant Water Bill Message','Send Water Bill Message to {{ App\Http\Controllers\TenantController::TenantFNames($waters->Tenant) }}( {{ App\Models\Property::getHouseName($waters->House) }}) <br/> Phone : {{ App\Http\Controllers\TenantController::TenantPhone($waters->Tenant) }} for {{$watermonth}} ');">
                                          @csrf
                                          <div class="row m-0 p-0">
                                            <div class="col-12 m-0 p-0">
                                              <span class="text-dark" style="font-size: 10px;">
                                                <input type="hidden" name="pid" value="{{$thisproperty->id}}">
                                                <input type="hidden" name="id" value="{{$waters->House}}">
                                                <input type="hidden" name="phone" value="{{App\Http\Controllers\TenantController::TenantPhone($waters->Tenant)}}">
                                                <input type="hidden" name="month" value="{{$watermonth}}">
                                                <input type="hidden" name="message" value="WATER BILL: Greetings {{ App\Models\Property::getHouseName($waters->House) }} :{{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }};Previous:{{$waters->Previous}};Current:{{$waters->Current}};UnitCost Kshs.{{$waters->Cost}};Units:{{$waters->Units}};CC:{{$waters->Units*$waters->Cost}};Other:{{$waters->Total_OS}};Total Kshs.{{$waters->Total+$waters->Total_OS}}.Thank You">

                                                <b>{{$loop->index+1}}. {{ App\Http\Controllers\TenantController::TenantFNames($waters->Tenant) }}, {{ App\Models\Property::getHouseName($waters->House) }} {{ App\Http\Controllers\TenantController::TenantPhone($waters->Tenant) }} 
                                                  @if(App\Http\Controllers\TenantController::getSentDate($waters->House,$watermonth,$waters->Current)!="")
                                                    <button class="btn btn-outline-danger float-right text-xs m-1 p-0 pr-1 pl-1"><i class="fa fa-history"></i> Resend</button>
                                                  @else
                                                    <button class="btn btn-outline-info float-right text-xs m-1 p-0 pr-1 pl-1"><i class="fa fa-paper-plane"></i> Send</button>
                                                  @endif
                                                </b> 
                                                @if(App\Http\Controllers\TenantController::getSentDate($waters->House,$watermonth,$waters->Current)!="")
                                                  <i class="float-right m-1 p-0 pr-1 pl-1 text-muted">{{ App\Http\Controllers\TenantController::getSentDate($waters->House,$watermonth,$waters->Current) }}</i>
                                                @else
                                                  <i class="float-right m-1 p-0 pr-1 pl-1 text-muted">Not sent</i>
                                                @endif
                                              </span>
                                            </div>
                                          </div>
                                        </form>
                                        <!-- <hr style="margin: 10px;"> -->
                                        <div class="card-body">
                                          <div class="">
                                            <div class="row m-0 p-0">
                                              <div class="col-12 m-0 p-0">
                                                @if(App\Http\Controllers\TenantController::getSentDate($waters->House,$watermonth,$waters->Current)!="")
                                                  <div class="bg-warning p-1 text-xs" style="border-radius:5px;">
                                                    WATER BILL: Greetings {{ App\Models\Property::getHouseName($waters->House) }} :{{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }}; Previous:{{$waters->Previous}}; Current:{{$waters->Current}}; Cost Kshs.{{$waters->Cost}}; Units:{{$waters->Units}}; CC:{{$waters->Units*$waters->Cost}}; Other:{{$waters->Total_OS}}; Total Kshs.{{$waters->Total+$waters->Total_OS}}.
                                                  </div>
                                                @else
                                                  <div class="bg-light p-1 text-xs" style="border-radius:5px;">
                                                    WATER BILL: Greetings {{ App\Models\Property::getHouseName($waters->House) }} :{{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }}; Previous:{{$waters->Previous}}; Current:{{$waters->Current}}; Cost Kshs.{{$waters->Cost}}; Units:{{$waters->Units}}; CC:{{$waters->Units*$waters->Cost}}; Other:{{$waters->Total_OS}}; Total Kshs.{{$waters->Total+$waters->Total_OS}}.
                                                  </div>
                                                @endif
                                              </div>
                                            </div>
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
                          </div>
                          <!-- end of single water -->
                        @elseif($thismode=="All Water")
                          <div class="card direct-chat direct-chat-primary m-0 p-0">
                            <div class="card-header bg-white m-0 p-2">
                              <span class="" style="font-size: 15px;">
                              Send to 
                                @if($thisproperty!="")
                                  {{$thisproperty->Plotname}}
                                @endif
                                /
                                @if($thismode!="")
                                  {{$thismode}}
                                @endif
                                  <i class="bg-light m-1 p-1" style="border-radius:5px;">Not sent</i>
                                  <i class="bg-warning m-1 p-1" style="border-radius:5px;">sent</i>
                                /{{$watermonth}}
                              </span>
                              <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                  <i class="fas fa-minus"></i>
                                </button>
                              </div>
                              <span class="float-right" style="z-index:999999;color:red;font-size:15px;right:5%;">Selected(<i class="badge selwaterbilltenants" id="selectedwaterbilltenants" style="font-size:20px;">0</i>)</span>
                            </div>
                            <form role="form" class="form-horizontal" method="POST" action="/properties/send/messages/allwater" onsubmit="return confirmOperation(this,'Send Water Bill Messages for : @if($thisproperty!=''){{$thisproperty->Plotname}}@endif for {{$watermonth}}','Send Water Bill Message for Selected Tenants <br/> Property : @if($thisproperty!=''){{$thisproperty->Plotname}}@endif for {{$watermonth}} ');">
                              @csrf
                              <div class="card-body" style="overflow-x:auto;max-height: calc(100vh - 15rem);overflow-y:auto;">
                                <div class="row m-0 p-0">
                                  @if($waterbill!="")
                                    @forelse($waterbill as $waters)
                                    <!-- all water send message -->
                                    <div class="col-12 m-0 p-0" style="border-radius:5px;">
                                      <div class="card-body bg-white m-1 elevation-3" style="border-radius:5px;">
                                        <div class="row m-0 p-1">
                                          <div class="col-12 m-0 p-0">
                                            <span class="text-dark" style="font-size: 10px;">
                                              <b>
                                                {{$loop->index+1}}. {{ App\Http\Controllers\TenantController::TenantFNames($waters->Tenant) }}, {{ App\Models\Property::getHouseName($waters->House) }} {{ App\Http\Controllers\TenantController::TenantPhone($waters->Tenant) }} 
                                              </b> 
                                              @if(App\Http\Controllers\TenantController::getSentDate($waters->House,$watermonth,$waters->Current)!="")
                                                <i class="float-right m-1 p-0 pr-1 pl-1 text-muted">{{ App\Http\Controllers\TenantController::getSentDate($waters->House,$watermonth,$waters->Current) }}</i>
                                              @else
                                                <i class="float-right m-1 p-0 pr-1 pl-1 text-muted">Not sent</i>
                                              @endif
                                            </span>
                                          </div>
                                        </div>
                                        <div class="card-body">
                                          <div class="col-sm-12">
                                            <div class="" style="text-align: center;">
                                              <div class="">
                                                <input type="hidden" name="pid" value="{{$thisproperty->id}}">
                                                <input type="hidden" name="month" value="{{$watermonth}}">
                                              </div>
                                            </div>
                                          </div>
                                          
                                          <div class="row m-0 p-0">
                                            <div class="col-12 m-0 p-0">
                                              @if(App\Http\Controllers\TenantController::getSentDate($waters->House,$watermonth,$waters->Current)!="")
                                                <div class="p-1 text-xs waterselmemodivsent" style="background-color:#FFEB06;color:black;cursor:pointer;border-radius:5px;" data-id1="watermemo{{$waters->id}}">
                                                  <label class="p-0" style="cursor:pointer;color:black;font-weight:normal"> 
                                                    <input type="checkbox" class="selectedwaterbilltenants" name="waterchoosen[]" id="watermemo{{$waters->id}}"  value="{{App\Http\Controllers\TenantController::TenantPhone($waters->Tenant)}}/{{$waters->House}}/WATER BILL: Greetings {{ App\Models\Property::getHouseName($waters->House) }} :{{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }};Previous:{{$waters->Previous}};Current:{{$waters->Current}};UnitCost Kshs.{{$waters->Cost}};Units:{{$waters->Units}};CC:{{$waters->Units*$waters->Cost}};Other:{{$waters->Total_OS}};Total Kshs.{{$waters->Total+$waters->Total_OS}}.Thank You"> 
                                                    WATER BILL: Greetings {{ App\Models\Property::getHouseName($waters->House) }} :{{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }}; Prev:{{$waters->Previous}}; Cur:{{$waters->Current}}; Cost Kshs.{{$waters->Cost}}; Units:{{$waters->Units}}; CC:{{$waters->Units*$waters->Cost}}; Other:{{$waters->Total_OS}}; Total Kshs.{{$waters->Total+$waters->Total_OS}}.
                                                  </label>
                                                </div>
                                              @else
                                                <div class="p-1 text-xs waterselmemodiv" style="background-color:#f8f9fa;color:black;cursor:pointer;border-radius:5px;" data-id1="watermemo{{$waters->id}}">
                                                  <label class="p-0" style="cursor:pointer;color:black;font-weight:normal"> 
                                                    <input type="checkbox" class="selectedwaterbilltenants" name="waterchoosen[]" id="watermemo{{$waters->id}}"  value="{{App\Http\Controllers\TenantController::TenantPhone($waters->Tenant)}}/{{$waters->House}}/WATER BILL: Greetings {{ App\Models\Property::getHouseName($waters->House) }} :{{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }};Previous:{{$waters->Previous}};Current:{{$waters->Current}};Cost Kshs.{{$waters->Cost}};Units:{{$waters->Units}};CC:{{$waters->Units*$waters->Cost}};Other:{{$waters->Total_OS}};Total Kshs.{{$waters->Total+$waters->Total_OS}}.Thank You">
                                                    WATER BILL: Greetings {{ App\Models\Property::getHouseName($waters->House) }} :{{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }}; Prev:{{$waters->Previous}}; Cur:{{$waters->Current}}; Cost Kshs.{{$waters->Cost}}; Units:{{$waters->Units}}; CC:{{$waters->Units*$waters->Cost}}; Other:{{$waters->Total_OS}}; Total Kshs.{{$waters->Total+$waters->Total_OS}}.
                                                  </label>
                                                </div>
                                              @endif
                                            </div>
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
                                  <div class="modal-footer justify-content-center bg-light m-0 p-1" >
                                    <button class="btn btn-outline-info selectedtenantshsesbtn" style="padding: 5px;">
                                      Send Selected Messages (<i class="badge selwaterbilltenants" id="selectedwaterbilltenants" style="font-size:20px;">0</i>)
                                    </button>
                                  </div>
                                @break(($loop->index)==0)

                                @empty

                                @endforelse
                              @endif
                            </form>
                          </div>

                        <!-- start other water  -->
                        @elseif($thismode=="Other Water")
                          <div class="card direct-chat direct-chat-primary m-0 p-0">
                            <div class="card-header bg-white m-0 p-2">
                              <span class="" style="font-size: 15px;">
                                Send to 
                                @if($thisproperty!="")
                                  {{$thisproperty->Plotname}}
                                @endif
                                  /
                                @if($thismode!="")
                                  {{$thismode}}
                                @endif
                                  <i class="bg-light m-1 p-1" style="border-radius:5px;">Not sent</i>
                                  <i class="bg-warning m-1 p-1" style="border-radius:5px;">sent</i>
                                  /{{$watermonth}}
                              </span>
                              <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                  <i class="fas fa-minus"></i>
                                </button>
                              </div>
                            </div>

                            <div class="card-body" style="overflow-x:auto;max-height: calc(100vh - 12rem);overflow-y:auto;">
                              <div class="row m-0 p-0">
                                @if($waterbill!="")
                                  @forelse($waterbill as $waters)
                                    <div class="col-12 m-0 p-0" style="border-radius:5px;">
                                      <div class="card-body bg-white m-1 elevation-3" style="border-radius:5px;">
                                        <form role="form" class="form-horizontal" method="POST" action="/properties/send/messages/others/singlewater" onsubmit="return confirmOperation(this,'Send Tenant Water Bill Message','Send Water Bill Message to {{ App\Http\Controllers\TenantController::TenantFNames($waters->Tenant) }} <br/> Phone : {{ App\Http\Controllers\TenantController::TenantPhone($waters->Tenant) }} for {{$watermonth}} ');">
                                          @csrf
                                            <input type="hidden" name="id" value="{{$waters->Tenant}}">
                                            <input type="hidden" name="phone" value="{{App\Http\Controllers\TenantController::TenantPhone($waters->Tenant)}}">
                                            <input type="hidden" name="month" value="{{$watermonth}}">
                                            <input type="hidden" name="message" value="WATER BILL: Greetings {{ App\Http\Controllers\TenantController::TenantNames($waters->Tenant) }} :{{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }};Previous:{{$waters->Previous}};Current:{{$waters->Current}};UnitCost Kshs.{{$waters->Cost}};Units:{{$waters->Units}};CC:{{$waters->Units*$waters->Cost}};Other:{{$waters->Total_OS}};Total Kshs.{{$waters->Total+$waters->Total_OS}}.Thank You">
                                            <div class="row m-0 p-0">
                                              <span class="text-dark" style="font-size: 12px;">
                                                <b>{{$loop->index+1}}. {{ App\Http\Controllers\TenantController::TenantNames($waters->Tenant) }}, {{ App\Http\Controllers\TenantController::TenantPhone($waters->Tenant) }} 
                                                  @if(App\Http\Controllers\TenantController::getSentDateOther($waters->Tenant,$watermonth)!="")
                                                    <button class="btn btn-outline-danger m-1 p-0 pr-1 pl-1"><i class="fa fa-history"></i> Resend</button>
                                                  @else
                                                    <button class="btn btn-outline-info m-1 p-0 pr-1 pl-1"><i class="fa fa-paper-plane"></i> Send</button>
                                                  @endif
                                                </b> 
                                                @if(App\Http\Controllers\TenantController::getSentDateOther($waters->Tenant,$watermonth)!="")
                                                  <i class="text-right">{{ App\Http\Controllers\TenantController::getSentDate($waters->House,$watermonth,$waters->Current) }}</i>
                                                @else
                                                  <i class="text-right">Not sent</i>
                                                @endif
                                              </span>
                                            </div>
                                        </form>
                                        <!-- <hr style="margin: 10px;"> -->
                                        <div class="card-body">
                                          <div class="">
                                            <div class="row m-0 p-0">
                                              <div class="col-12 m-0 p-0">
                                                @if(App\Http\Controllers\TenantController::getSentDateOther($waters->Tenant,$watermonth)!="")
                                                  <div class="bg-warning p-1" style="border-radius:5px;">
                                                  WATER BILL: Greetings {{ App\Models\Property::getHouseName($waters->Tenant) }} :{{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }};Previous:{{$waters->Previous}};Current:{{$waters->Current}};UnitCost Kshs.{{$waters->Cost}};Units:{{$waters->Units}};CC:{{$waters->Units*$waters->Cost}};Other:{{$waters->Total_OS}};Total Kshs.{{$waters->Total+$waters->Total_OS}}.Thank You
                                                  </div>
                                                @else
                                                  <div class="bg-light p-1" style="border-radius:5px;">
                                                  WATER BILL: Greetings {{ App\Models\Property::getHouseName($waters->Tenant) }} :{{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }};Previous:{{$waters->Previous}};Current:{{$waters->Current}};UnitCost Kshs.{{$waters->Cost}};Units:{{$waters->Units}};CC:{{$waters->Units*$waters->Cost}};Other:{{$waters->Total_OS}};Total Kshs.{{$waters->Total+$waters->Total_OS}}.Thank You
                                                  </div>
                                                @endif
                                              </div>
                                            </div>
                                            
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
                          </div>
                          <!-- end messages for other -->

                        <!-- start other notification -->
                        @elseif($thismode=="Other Notification")
                          <div class="card direct-chat direct-chat-primary m-0 p-0">
                            <div class="card-header bg-white m-0 p-2">
                              <span class="" style="font-size: 15px;">
                              Send to 
                                @if($thisproperty!="")
                                  {{$thisproperty->Plotname}}
                                @endif
                                /
                                @if($thismode!="")
                                  {{$thismode}}
                                @endif
                                  <i class="bg-light m-1 p-1" style="border-radius:5px;">Not sent</i>
                                  <i class="bg-warning m-1 p-1" style="border-radius:5px;">sent</i>
                                /{{$watermonth}}
                              </span>
                              <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                  <i class="fas fa-minus"></i>
                                </button>
                              </div>
                            </div>
                            <div class="card-body" style="overflow-x:auto;max-height: calc(100vh - 12rem);overflow-y:auto;">
                              <div class="row m-0 p-0">
                                @if($waterbill!="")
                                  @forelse($waterbill as $waters)
                                    <div class="col-12 m-0 p-0" style="border-radius:5px;">
                                      <div class="card-body bg-white m-1 elevation-3" style="border-radius:5px;">
                                        <form role="form" class="form-horizontal" method="POST" action="/properties/send/messages/others/notification" onsubmit="return confirmOperation(this,'Send Tenant Water Bill Message','Send Water Bill Message to {{ App\Http\Controllers\TenantController::TenantFNames($waters->Tenant) }} <br/> Phone : {{ App\Http\Controllers\TenantController::TenantPhone($waters->Tenant) }} for {{$watermonth}} ');">
                                          @csrf
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

                                            <div class="row m-0 p-0">
                                              <span class="text-dark" style="font-size: 12px;">
                                                <b>{{$loop->index+1}}. {{ App\Http\Controllers\TenantController::TenantNames($waters->Tenant) }}, {{ App\Http\Controllers\TenantController::TenantPhone($waters->Tenant) }} 
                                                    <button type="button" class="btn btn-outline-success m-1 p-0 pr-1 pl-1" data-toggle="modal" data-target="#tenant{{ $waters->Tenant }}" style="">
                                                      <i class="fa fa-dollar"> </i> Add Payment
                                                    </button>
                                                  @if(App\Http\Controllers\TenantController::getSentDateOther($waters->Tenant,$watermonth)!="")
                                                    <button class="btn btn-outline-danger m-1 p-0 pr-1 pl-1"><i class="fa fa-history"></i> Resend</button>
                                                  @else
                                                    <button class="btn btn-outline-info m-1 p-0 pr-1 pl-1"><i class="fa fa-paper-plane"></i> Send</button>
                                                  @endif
                                                </b> 
                                                @if(App\Http\Controllers\TenantController::getSentDateOther($waters->Tenant,$watermonth)!="")
                                                  <i class="float-right">{{ $waters->Status }}</i>
                                                @else
                                                  <i class="float-right">Not sent</i>
                                                @endif
                                              </span>
                                            </div>
                                        </form>
                                        <!-- <hr style="margin: 10px;"> -->
                                        <div class="card-body">
                                          <div class="">
                                            <div class="row m-0 p-0">
                                              <div class="col-12 m-0 p-0">
                                                @if(($waters->Status)=="Sent")
                                                  <div class="bg-warning p-1" style="border-radius:5px;">
                                                    WATERBILL RECEIPT: Greetings {{ App\Http\Controllers\TenantController::TenantFNames($waters->Tenant) }}, {{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }} bill. Total {{$waters->Waterbill}}, Received:Ksh.{{$waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded}}. 
                                                    @if(($waters->Waterbill+$waters->Arrears)-($waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded+$waters->Excess)>0)
                                                      Balance Kshs.{{($waters->Waterbill+$waters->Arrears)-($waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded+$waters->Excess)}}.
                                                    @elseif(($waters->Waterbill+$waters->Arrears)-($waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded+$waters->Excess)<0)
                                                      Overpayment Kshs.{{abs(($waters->Waterbill+$waters->Arrears)-($waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded+$waters->Excess))}}.
                                                    @endif
                                                    Thank You
                                                  </div>
                                              
                                                @else
                                                  <div class="bg-light p-1" style="border-radius:5px;">
                                                    WATERBILL RECEIPT: Greetings {{ App\Http\Controllers\TenantController::TenantFNames($waters->Tenant) }}, {{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }} bill. Total {{$waters->Waterbill}}, Received:Ksh.{{$waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded}}. 
                                                    @if(($waters->Waterbill+$waters->Arrears)-($waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded+$waters->Excess)>0)
                                                      Balance Kshs.{{($waters->Waterbill+$waters->Arrears)-($waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded+$waters->Excess)}}.
                                                    @elseif(($waters->Waterbill+$waters->Arrears)-($waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded+$waters->Excess)<0)
                                                      Overpayment Kshs.{{abs(($waters->Waterbill+$waters->Arrears)-($waters->Equity+$waters->Cooperative+$waters->Others+$waters->PaidUploaded+$waters->Excess))}}.
                                                    @endif
                                                    Thank You
                                                  </div>
                                                @endif
                                              </div>
                                            </div>
                                            
                                          </div>
                                        </div>

                                      </div>
                                    </div>

                                    <div class="modal fade" id="tenant{{ $waters->Tenant }}">
                                      <div class="modal-dialog">
                                        <div class="modal-content bg-light">
                                          <div class="modal-header m-0 p-2">
                                            <h6 class="modal-title mx-auto">Water bill Payment for {{ App\Http\Controllers\TenantController::TenantNames($waters->Tenant) }}, {{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }} </h6>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                          <div class="modal-body bg-light m-0 p-0">
                                            <form role="form" class="form-horizontal" method="POST" action="/properties/save/waterbill/otherpayment">
                                            @csrf
                                              <div class="row m-0 p-0">
                                              <input type="hidden" name="wid" value="{{$waters->id}}">
                                              <input type="hidden" name="id" value="{{$waters->Tenant}}">
                                              <input type="hidden" name="month" value="{{$watermonth}}">

                                              <div class="col-12 m-0 p-1">
                                                  <div class="card card-primary card-outline bg-white" style="margin-bottom: 5%;text-align: center;">
                                                    <div class="card-body">
                                                      <div class="form-group row m-0 p-1">
                                                          <label for="Waterbill" class="col-md-4 m-0 p-1 col-form-label text-md-right">{{ __('Usage') }}</label>

                                                          <div class="col-md-8">
                                                              <input id="Waterbill" type="text" class="form-control @error('Waterbill') is-invalid @enderror" name="Waterbill" value="{{$waters->Waterbill}}" placeholder="Waterbill" required readonly autocomplete="Waterbill" autofocus>

                                                              @error('Waterbill')
                                                                  <span class="invalid-feedback" role="alert">
                                                                      <strong>{{ $message }}</strong>
                                                                  </span>
                                                              @enderror
                                                          </div>
                                                      </div>

                                                      <div class="form-group row m-0 p-1">
                                                          <label for="Arrears" class="col-md-4 m-0 p-1 col-form-label text-md-right">{{ __('Arrears') }}</label>

                                                          <div class="col-md-8">
                                                              <input id="Arrears" type="text" class="form-control @error('Arrears') is-invalid @enderror" name="Arrears" value="{{$waters->Arrears}}" placeholder="0.00" required autocomplete="Arrears" autofocus>

                                                              @error('Arrears')
                                                                  <span class="invalid-feedback" role="alert">
                                                                      <strong>{{ $message }}</strong>
                                                                  </span>
                                                              @enderror
                                                          </div>
                                                      </div>

                                                      <div class="form-group row m-0 p-1">
                                                          <label for="Excess" class="col-md-4 m-0 p-1 col-form-label text-md-right">{{ __('Excess') }}</label>

                                                          <div class="col-md-8">
                                                              <input id="Excess" type="text" class="form-control @error('Excess') is-invalid @enderror" name="Excess" value="{{$waters->Excess}}" placeholder="0.00" required autocomplete="Excess" autofocus>

                                                              @error('Excess')
                                                                  <span class="invalid-feedback" role="alert">
                                                                      <strong>{{ $message }}</strong>
                                                                  </span>
                                                              @enderror
                                                          </div>
                                                      </div>

                                                      <div class="form-group row m-0 p-1">
                                                          <label for="PaidUploaded" class="col-md-4 m-0 p-1 col-form-label text-md-right">{{ __('Paid') }}</label>

                                                          <div class="col-md-8">
                                                              <input id="PaidUploaded" type="text" class="form-control @error('PaidUploaded') is-invalid @enderror" name="PaidUploaded" value="{{$waters->PaidUploaded}}" placeholder="0" required autocomplete="PaidUploaded" autofocus>

                                                              @error('PaidUploaded')
                                                                  <span class="invalid-feedback" role="alert">
                                                                      <strong>{{ $message }}</strong>
                                                                  </span>
                                                              @enderror
                                                          </div>
                                                      </div>

                                                      
                                                    </div>
                                                    <div class="card-footer justify-content-center m-0 p-2">
                                                      <div class="">
                                                        <button type="button" class="btn btn-outline-danger float-right" data-dismiss="modal">Close</button>
                                                        <button  class="btn btn-outline-success btn-small" name="submitplotbtn" id="submitplotbtn"  type="submit" >Save Payment for @if($watermonth!="")
                                                            {{$watermonth}}
                                                          @endif
                                                          /{{ App\Http\Controllers\TenantController::TenantNames($waters->Tenant) }}
                                                        </button>
                                                      </div>
                                                    </div>

                                                  </div>
                                              </div>

                                              
                                              
                                            </div>
                                          </form>
                                          </div>
                                          <!-- <div class="modal-footer justify-content-center m-0 p-2">
                                            <button type="button" class="btn btn-outline-danger float-right" data-dismiss="modal">Close</button>
                                          </div> -->
                                        </div>
                                      </div>
                                    </div>
                                    <!-- end of modal -->

                                  @empty
                                    <h4 class="text-info" style="padding:10px;margin-left:10px;">Messages Sent and Not Sent will be displayed here</h4>
                                  @endforelse
                                @endif
                              </div>
                            </div>
                          </div>
                      
                      <!-- end other nottification -->

                        @elseif($thismode=="Choose Tenant")
                          <div class="card direct-chat direct-chat-primary m-0 p-0">
                            <div class="card-header bg-white m-0 p-2">
                              <span class="" style="font-size: 15px;">
                              Send to 
                                @if($thisproperty!="")
                                  {{$thisproperty->Plotname}}
                                @endif
                                /
                                @if($thismode!="")
                                  {{$thismode}}
                                @endif
                                  <i class="bg-light m-1 p-1" style="border-radius:5px;">Not sent</i>
                                  <i class="bg-warning m-1 p-1" style="border-radius:5px;">sent</i>
                                /{{$watermonth}}
                              </span>
                              <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                  <i class="fas fa-minus"></i>
                                </button>
                              </div>
                              <span class="float-right" style="z-index:999999;color:red;font-size:15px;right:5%;">
                                Selected(<i class="badge selwaterbilltenants" id="selectedwaterbilltenants" style="font-size:20px;">0</i>)
                              </span>
                            </div>
                            <form role="form" class="form-horizontal" method="POST" action="/properties/send/messages/choosetenant" onsubmit="return confirmOperation(this,'Send Bulk Messages for : @if($thisproperty!=''){{$thisproperty->Plotname}}@endif for {{$watermonth}}','Send Bulk Message for Selected Tenants <br/> Property : @if($thisproperty!=''){{$thisproperty->Plotname}}@endif ');">
                              @csrf
                              <div class="card-body" style="overflow-x:auto;max-height: calc(100vh - 15rem);overflow-y:auto;">
                                <div class="row m-0 p-0">
                                  @if($waterbill!="")
                                    @forelse($waterbill as $waters)
                                    <!-- all water send message -->
                                    <div class="col-12 m-0 p-0 pl-2" style="border-radius:5px;">
                                      <div class="card-body bg-white m-1 elevation-3" style="border-radius:5px;">
                                        <div class="row m-0 p-1">
                                         
                                        </div>
                                        <div class="card-body">
                                          <div class="col-12">
                                              <div class="" style="text-align: center;">
                                                <div class="">
                                                  <input type="hidden" name="pid" value="{{$thisproperty->id}}">
                                                  <input type="hidden" name="month" value="{{$watermonth}}">
                                                </div>
                                              </div>
                                          </div>
                                          
                                          <div class="row m-0 p-0">
                                            <div class="col-12 m-0 p-0">
                                                 <div class="p-1 text-xs waterselmemodiv" style="background-color:#f8f9fa;color:black;cursor:pointer;border-radius:5px;" data-id1="watermemo{{$waters->id}}">
                                                  <label class="p-0" style="cursor:pointer;color:black;font-weight:normal"> 
                                                    <input type="checkbox" class="selectedwaterbilltenants" name="waterchoosen[]" id="watermemo{{$waters->id}}"  value="+254{{App\Http\Controllers\TenantController::TenantPhone($waters->Tenant)}}"> 
                                                    <b>{{$loop->index+1}}. House: {{ App\Models\Property::getHouseName($waters->House) }}, Name: {{ App\Http\Controllers\TenantController::TenantFNames($waters->Tenant) }},Phone: {{ App\Http\Controllers\TenantController::TenantPhone($waters->Tenant) }} </b>
                                                  </label>
                                                </div>
                                                
                                            </div>
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
                                @forelse($waterbill as $watersbil)
                                  <div class="modal-footer justify-content-center bg-light m-0 p-1" >
                                    <div class="">
                                      <textarea  id="Message" type="text" class="form-control @error('Message') is-invalid @enderror" name="Message" placeholder="Compose Message to Send to Selected Tenants" required autocomplete="Message" autofocus cols="150">{{ old('Message') }}</textarea>
                                      <span id="charactercountsinglewater" name="charactercountsinglewater" class="badge">Characters: 0, 1 Message(s)<span>
                                    </div>
                                    <button class="btn btn-outline-info selectedtenantshsesbtn" style="padding: 5px;">
                                      Send To Selected Tenants (<i class="badge selwaterbilltenants" id="selectedwaterbilltenants" style="font-size:20px;">0</i>)
                                    </button>
                                  </div>
                                @break(($loop->index)==0)

                                @empty

                                @endforelse
                            </form>
                          </div>
                          <!-- end choose tenants -->

                        @elseif($thismode=="Completed Payment")
                          <div class="card direct-chat direct-chat-primary m-0 p-0">
                            <div class="card-header bg-white m-0 p-2">
                              <span class="" style="font-size: 15px;">
                              Send to 
                                @if($thisproperty!="")
                                  {{$thisproperty->Plotname}}
                                @endif
                                /
                                @if($thismode!="")
                                  {{$thismode}}
                                @endif
                                  <i class="bg-light m-1 p-1" style="border-radius:5px;">Not sent</i>
                                  <i class="bg-warning m-1 p-1" style="border-radius:5px;">sent</i>
                                /{{$watermonth}}
                              </span>
                              
                              <span class="float-right" style="z-index:999999;color:red;font-size:15px;right:5%;">Selected(<i class="badge selwaterbilltenants" id="selectedwaterbilltenants" style="font-size:20px;">0</i>)</span>
                            </div>
                            <form role="form" class="form-horizontal submitsendcompletedpaid" method="POST" name="submitsendcompletedpaid" id="submitsendcompletedpaid" >
                              @csrf
                              <div class="card-body" style="overflow-x:auto;max-height: calc(100vh - 15rem);overflow-y:auto;">
                              @if($thisproperty!="" && $thismode!="" && $watermonth!="")
                              <input type="hidden" id="savepaymentpid" name="savepaymentpid" value="{{$thisproperty->id}}">
                              <input type="hidden" id="savepaymentmonth" name="savepaymentmonth" value="{{$watermonth}}">
                              <input type="hidden" id="savepaymentmode" name="savepaymentmode" value="{{$thismode}}">
                              @endif
                                <div class="row m-0 p-0" id="loadpaymentsresults">
                                  <h4 class="text-danger mx-auto">Please Wait ... <img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading..."></h4>
                                </div>
                              </div>

                              <div class="modal-footer justify-content-center bg-light m-0 p-1" >
                                <div class="col-12 m-0 p-0" id="savecompletedpaidload"></div>
                                <button class="btn btn-outline-info selectedtenantshsesbtn" style="padding: 5px;">
                                  Send To Selected Tenants (<i class="badge selwaterbilltenants" id="selectedwaterbilltenants" style="font-size:20px;">0</i>)
                                </button>
                              </div>
                            </form>
                          </div>
                      
                        <!-- Summary Paid -->

                        @elseif($thismode=="Summary Paid")
                          <div class="card direct-chat direct-chat-primary m-0 p-0">
                            <div class="card-header bg-white m-0 p-2">
                              <span class="" style="font-size: 15px;">
                              Send to 
                                @if($thisproperty!="")
                                  {{$thisproperty->Plotname}}
                                @endif
                                /
                                @if($thismode!="")
                                  {{$thismode}}
                                @endif
                                  <i class="bg-light m-1 p-1" style="border-radius:5px;">Not sent</i>
                                  <i class="bg-warning m-1 p-1" style="border-radius:5px;">sent</i>
                                /{{$watermonth}}
                              </span>
                              
                              <span class="float-right" style="z-index:999999;color:red;font-size:15px;right:5%;">Selected(<i class="badge selwaterbilltenants" id="selectedwaterbilltenants" style="font-size:20px;">0</i>)</span>
                            </div>
                            
                            <form role="form" class="form-horizontal submitsendsummarypaid" method="POST" name="submitsendsummarypaid" id="submitsendsummarypaid">
                              @csrf
                              <div class="card-body" style="overflow-x:auto;max-height: calc(100vh - 15rem);overflow-y:auto;">
                              @if($thisproperty!="" && $thismode!="" && $watermonth!="")
                              <input type="hidden" id="savepaymentpid" name="savepaymentpid" value="{{$thisproperty->id}}">
                              <input type="hidden" id="savepaymentmonth" name="savepaymentmonth" value="{{$watermonth}}">
                              <input type="hidden" id="savepaymentmode" name="savepaymentmode" value="{{$thismode}}">
                              @endif
                                <div class="row m-0 p-0" id="loadpaymentsresults">
                                  <h4 class="text-danger mx-auto">Please Wait ... <img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading..."></h4>
                                </div>
                              </div>

                              <div class="modal-footer justify-content-center bg-light m-0 p-1" >
                                <div class="col-12 m-0 p-0" id="savesummarypaidload"></div>
                                <button class="btn btn-outline-info selectedtenantshsesbtn" style="padding: 5px;">
                                  Send To Selected Tenants (<i class="badge selwaterbilltenants" id="selectedwaterbilltenants" style="font-size:20px;">0</i>)
                                </button>
                              </div>
                            </form>
                          </div>
                      
                        <!-- Summary Paid -->

                        @endif
                      <!-- end of this mode -->
                      @else
                        <!-- starting compose message -->

                        <div class="">
                          <form role="form" class="form-horizontal" method="post" action="/properties/send/message" onsubmit="return confirmOperation(this,'Send Message to Phone Numbers','Send Message for Phone Numbers ');">
                            @csrf
                            <div class="row">
                              <div class="col-sm-12">
                                <div class="card card-primary card-outline" style="margin-bottom: 5%;text-align: center;">
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

            <!-- modal here -->
            <div class="modal fade" id="paymentupdate" style="">
              <div class="modal-dialog" style="">
                <div class="modal-content bg-light" style="">
                  <div class="modal-header bg-info m-0 p-2">
                    <h6 class="modal-title mx-auto modal-title-bills">Payment Summary for <span class="paymenttenantname"></span> / <i class="paymenthousename"></i>
                                      (<span class="paymentwatermonth"></span>)</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body bg-light m-0 p-0" style="">
                    
                      <div class="viewhousepaymentspanelres">
                        <form role="form" class="form-horizontal submitupdatebillspayments" method="POST" name="submitupdatebillspayments" id="submitupdatebillspayments">
                          @csrf
                          <div class="row m-0 p-0">
                            <input type="hidden" name="paymentpid" id="paymentpid">
                            <input type="hidden" name="paymenttid" id="paymenttid">
                            <input type="hidden" name="paymenthid" id="paymenthid">
                            <input type="hidden" name="paymentmonth" id="paymentmonth">

                            <div class="col-12 m-0 p-0">
                              <div class="card card-primary bg-light card-outline m-0 p-0">
                                <div class="card-body m-0 mt-2 p-0">
                                  <h6 class="text-center text-bold">House <span class="paymenthousename"> Info </span> </h6>
                                  <div class="bg-light text-black text-xs m-1 p-1 viewhousepaymentspanelothers">
                                    
                                  </div>
                                  <p class="text-center text-danger text-xs">Excess, Arrears, Billed and Paid Values should be updated</p>
                                  <div class="form-group row m-0 p-1">
                                    <label class="col-md-3 m-0 p-0 col-form-label">{{ __('Update') }} <sup class="text-danger text-xs">*</sup></label>
                                    <div class="col-md-9 m-0 p-0">
                                      <label class="text-xs" style="cursor:pointer;">
                                        <input type="radio" name="UpdateType" value="Excess" required> Excess 
                                      </label>
                                      <label class="text-xs" style="cursor:pointer;">
                                        <input type="radio" name="UpdateType" value="Arrears" required> Arrears
                                      </label>
                                      <label class="text-xs" style="cursor:pointer;">
                                        <input type="radio" name="UpdateType" value="Penalty" required> Penalty
                                      </label>
                                      <label class="text-xs" style="cursor:pointer;">
                                        <input type="radio" name="UpdateType" value="Paid" required> Paid
                                      </label>
                                    </div>
                                  </div>

                                  <div class="form-group row m-0 p-1">
                                    <label for="paymentpaid" class="col-md-3 m-0 p-0 col-form-label">{{ __('Amount') }} <sup class="text-danger text-xs">*</sup></label>
                                    <div class="col-md-9 m-0 p-0">
                                        <input type="text" class="form-control" id="paymentpaid" name="paymentpaid"  placeholder="Update Amount" required autocomplete="paymentpaid" autofocus>
                                    </div>
                                  </div>

                                  <div class="form-group row m-0 p-1">
                                    <label for="paymentdate" class="col-md-3 m-0 p-0 col-form-label">{{ __('Date') }} </label>
                                    <div class="col-md-9 m-0 p-0">
                                        <input type="date" class="form-control" id="paymentdate" name="paymentdate"  placeholder="Date Done" autocomplete="paymentdate" autofocus>
                                    </div>
                                  </div>

                                  <div class="form-group row m-0 p-1">
                                    <div class="col-12 m-0 p-0" id="saveupdatepaymentload">

                                    </div>
                                  </div>
                                </div>
                                <div class="card-footer justify-content-center m-0 p-2">
                                  <div class="">
                                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                                    <button  class="btn btn-outline-success btn-small float-right" name="submitplotbtn" id="submitplotbtn"  type="submit" >
                                      Submit Changes for <span class="paymenthousename"></span>
                                      (<span class="paymentwatermonth"></span>)
                                    </button>
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
            <!-- end of modal -->
            
          </div>
        </div>
      </div>


    </div>
  </div>
</div>
        


@endsection

@push('scripts')
<!-- Toastr -->
<!-- <script src="{{ asset('public/assets/plugins/toastr/toastr.min.js') }}"></script> -->
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
      $(document).Toasts('create', {
          title: 'Success in Submit',
          class: 'bg-success',
          position: 'bottomLeft',
          body: successmsg
      })
    }
    if (dberrormsg) {
      $(document).Toasts('create', {
          title: 'Error in Saving',
          class: 'bg-danger',
          position: 'bottomLeft',
          body: dberrormsg
      })
    }
    if (errormsg) {
      $(document).Toasts('create', {
          title: 'Error in Submiting',
          class: 'bg-warning',
          position: 'bottomLeft',
          body: errormsg
      })
    }
    paymentbill_results();
    getselectedwaterbilltenants();
    getselectedhousesforupdate();

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
  if(selectedpenaltytenants===0){
    $('.selectedtenantshsesbtn').hide();
  }
  else{
    $('.selectedtenantshsesbtn').show();
  }
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
    if(selectedpenaltytenants===0){
      $('.selectedtenantshsesbtn').hide();
    }
    else{
      $('.selectedtenantshsesbtn').show();
    }
}

$(document).on('click','.waterselmemodiv',(function(e){
    var balidhouses=$(this).data("id1");
    var thisselhouses=document.getElementById(balidhouses);
    if (thisselhouses.checked===true) {
        this.style.backgroundColor='grey';
    }
    else{
        this.style.backgroundColor='#f8f9fa';
    }
    getselectedwaterbilltenants();
}));


$(document).on('click','.paymentselmemodiv',(function(e){
    var balidhouses=$(this).data("id1");
    var thisselhouses=document.getElementById(balidhouses);
    if (thisselhouses.checked===true) {
        this.style.backgroundColor='grey';
    }
    else{
        this.style.backgroundColor='#f8f9fa';
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

  $("#submitupdatebillspayments").on('submit',(function(e){
  e.preventDefault();
  if (confirm("Do You Want to Add Paid Amount or Update Selected Value.")) {
    $('#saveupdatepaymentload').html('... <img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
    $.ajax({
      url:'/properties/save/update/bills/submitupdate',
      type:"POST",
      data:new FormData(document.getElementById('submitupdatebillspayments')),
      processData:false,
      contentType:false,
      cache:false,
      success:function(data){
        if(data['error']){
            $(document).Toasts('create', {
                title: 'Error in Saving',
                class: 'bg-warning',
                position: 'bottomLeft',
                body: data['error']
            })
            $('#saveupdatepaymentload').html(data['error']);
        }
        else{
            $(document).Toasts('create', {
                title: 'Updating Payment',
                class: 'bg-success',
                position: 'bottomLeft',
                body: data['success']
            })
            $('#saveupdatepaymentload').html(data['success']);
            paymentbill_results();
        }
      },
      error: function(xhr, status, error){
        var errorMessage = xhr.status + ': ' + xhr.statusText
        if (errorMessage=="0: error") {
            errorMessage="Internet Connection Interrupted.";
        }
        else if(errorMessage=="404: Not Found"){
            errorMessage="Could Not Process Data."
        }
        else if(errorMessage=="500: Internal Server Error"){
            errorMessage="Failed due to Some Technical Error In a Server."
        }
        
        $(document).Toasts('create', {
            title: 'Updating Payments',
            class: 'bg-warning',
            position: 'bottomLeft',
            body: errorMessage
        })
        $('#saveupdatepaymentload').html('<h6 class="text-center text-danger">Oops!! Sorry - ' + errorMessage+'</h6>');
        
      }
    });    
  }
  }));

  function paymentbill_results(){
    let savepaymentpid=document.getElementById('savepaymentpid').value;
    let savepaymentmonth=document.getElementById('savepaymentmonth').value;
    let savepaymentmode=document.getElementById('savepaymentmode').value;
    if(savepaymentmode=="Completed Payment"){

    }
    else if(savepaymentmode=="Summary Paid"){

    }
    else{
      $('#loadpaymentsresults').html('<h4 class="text-muted mx-auto">Please Choose a Valid Message Mode</h4>');
      return
    }
    $.ajax({
      url:"/properties/send/messages/load/"+savepaymentpid+"/"+savepaymentmode+"/"+savepaymentmonth,
      method:"GET",
      success:function(data){
          var thisproperty=data['thisproperty'];
          var watermonth=data['watermonth'];
          var paymentbills=data['waterbill'];
          let alreadysaved="";
          $('#loadpaymentsresults').html('');
          if(paymentbills!=""){
            for (var i = 0; i < paymentbills.length; i++) {
              let SentDatePayment='';
              let balance=0;
              let inputvalue='';
              if(paymentbills[i]['SentDatePayment']){
                SentDatePayment='<i class="float-right m-1 p-0 pr-1 pl-1 text-muted">'+paymentbills[i]['SentDatePayment']+'</i>';
              }
              else{
                SentDatePayment='<i class="float-right m-1 p-0 pr-1 pl-1 text-muted">Not sent</i>';
              }

              if(savepaymentmode=="Summary Paid"){
                if(paymentbills[i]['Balance']>0){
                  balance='Balance Kshs.'+paymentbills[i]['Balance']+'.';
                }
                else if(paymentbills[i]['Balance']<0){
                  balance='Overpayment Kshs.'+Math.abs(paymentbills[i]['Balance'])+'.';
                }
                else if(paymentbills[i]['Balance']==0){
                  balance='You have No Arrears';
                }

                if((paymentbills[i]['Balance'])>0){
                  inputvalue='<input type="checkbox" class="selectedwaterbilltenants" name="waterchoosen[]" id="watermemo'+paymentbills[i]['id']+'" value="'+paymentbills[i]['TenantPhones']+'/'+paymentbills[i]['tid']+'/'+paymentbills[i]['hid']+'/Rent RECEIPT: Greetings '+paymentbills[i]['Tenantfname']+' of '+paymentbills[i]['Housename']+'  '+paymentbills[i]['MonthWaterDate']+' Bill. Total Kshs '+paymentbills[i]['TotalUsed']+'  Received Ksh.'+paymentbills[i]['TotalPaid']+'.Balance Kshs.'+paymentbills[i]['Balance']+'. Thank You">';
                }
                else if((paymentbills[i]['Balance'])<0){
                  inputvalue='<input type="checkbox" class="selectedwaterbilltenants" name="waterchoosen[]" id="watermemo'+paymentbills[i]['id']+'" value="'+paymentbills[i]['TenantPhones']+'/'+paymentbills[i]['tid']+'/'+paymentbills[i]['hid']+'/Rent RECEIPT: Greetings '+paymentbills[i]['Tenantfname']+' of '+paymentbills[i]['Housename']+'  '+paymentbills[i]['MonthWaterDate']+' Bill. Total Kshs '+paymentbills[i]['TotalUsed']+'  Received Ksh.'+paymentbills[i]['TotalPaid']+'.Overpayment Kshs.'+Math.abs(paymentbills[i]['Balance'])+'.Thank You">';
                }
                else{
                  inputvalue='<input type="checkbox" class="selectedwaterbilltenants" name="waterchoosen[]" id="watermemo'+paymentbills[i]['id']+'" value="'+paymentbills[i]['TenantPhones']+'/'+paymentbills[i]['tid']+'/'+paymentbills[i]['hid']+'/Rent RECEIPT: Greetings '+paymentbills[i]['Tenantfname']+' of '+paymentbills[i]['Housename']+'  '+paymentbills[i]['MonthWaterDate']+' Bill. Total Kshs '+paymentbills[i]['TotalUsed']+'  Received Ksh.'+paymentbills[i]['TotalPaid']+'. You have No Arrears for this Month. Thank You">';
                }
                
                alreadysaved='<div class="col-12 m-0 p-1 pl-2" style="border-radius:5px;">'+
                    '<div class="card-body bg-white m-1 elevation-3" style="border-radius:5px;">'+
                      '<div class="row m-0 p-0">'+
                        '<div class="col-12 m-0 p-0">'+
                          '<span class="text-dark" style="font-size: 12px;">'+
                            '<b>'+(i+1)+'. '+paymentbills[i]['Tenantfname']+', '+paymentbills[i]['Housename']+' '+paymentbills[i]['TenantPhones']+' '+
                                '<button type="button" class="btn btn-outline-success text-xs m-1 p-0 pr-1 pl-1 paymentupdatebtn" '+
                                  'data-id0="'+data['thisproperty'].id+'" data-id1="'+paymentbills[i]['tid']+'" data-id2="'+paymentbills[i]['hid']+'" data-id3="'+data['watermonth']+'" '+
                                  'data-id4="'+paymentbills[i]['Rent']+'" data-id5="'+paymentbills[i]['Garbage']+'" data-id6="'+paymentbills[i]['Waterbill']+'"'+
                                  'data-id7="'+paymentbills[i]['Excess']+'" data-id8="'+paymentbills[i]['Arrears']+'" data-id9="'+paymentbills[i]['TotalUsed']+'"'+
                                  'data-id10="'+paymentbills[i]['TotalPaid']+'" data-id11="'+paymentbills[i]['Balance']+'" data-id12="'+paymentbills[i]['HseDeposit']+'"'+
                                  'data-id13="'+paymentbills[i]['Water']+'" data-id14="'+paymentbills[i]['KPLC']+'" data-id15="'+paymentbills[i]['Lease']+'"'+
                                  'data-id16="'+paymentbills[i]['Tenantname']+'" data-id17="'+paymentbills[i]['Tenantfname']+'" data-id18="'+paymentbills[i]['MonthWaterDate']+'"'+
                                  'data-id19="'+paymentbills[i]['Housename']+'"'+
                                  '>'+
                                  '<i class="fa fa-edit text-xs"> </i> Bills'+
                                '</button>'+
                            '</b> '+SentDatePayment+
                          '</span>'+
                        '</div>'+
                      '</div>'+
                      '<div class="card-body">'+
                        '<div class="col-sm-12">'+
                            '<div class="" style="text-align: center;">'+
                              '<div class="">'+
                                '<input type="hidden" name="pid" value="'+data['thisproperty'].id+'">'+
                                '<input type="hidden" name="month" value="'+data['watermonth']+'">'+
                              '</div>'+
                            '</div>'+
                        '</div>'+
                        
                        '<div class="row m-0 p-0">'+
                          '<div class="col-12 m-0 p-0">'+
                            
                              '<div class="p-1 paymentselmemodiv text-xs " style="background-color:#f8f9fa;color:black;cursor:pointer;border-radius:5px;" data-id1="watermemo'+paymentbills[i]['id']+'">'+
                                '<label class="p-0" style="cursor:pointer;color:black;font-weight:normal">'+inputvalue+
                                
                                ' Rent RECEIPT: Greetings '+paymentbills[i]['Tenantfname']+' of '+paymentbills[i]['Housename']+', '+paymentbills[i]['MonthWaterDate']+' Bill. Total '+paymentbills[i]['TotalUsed']+', Received Ksh.'+paymentbills[i]['TotalPaid']+'. '+balance+
                            
                                '</label>'+
                              '</div>'+
                              
                          '</div>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</div>';
              }
              else if(savepaymentmode=="Completed Payment"){
                if(paymentbills[i]['Balance']>0){
                  balance='Balance Kshs.'+paymentbills[i]['Balance']+'.';
                }
                else if(paymentbills[i]['Balance']<0){
                  balance='Overpayment Kshs.'+Math.abs(paymentbills[i]['Balance'])+'.';
                }
                else if(paymentbills[i]['Balance']==0){
                  balance='You have No Arrears.';
                }

                if((paymentbills[i]['Balance'])>0){
                  
                }
                else if((paymentbills[i]['Balance'])<0){
                  
                  inputvalue='<input type="checkbox" class="selectedwaterbilltenants" name="waterchoosen[]" id="watermemo'+paymentbills[i]['id']+'" value="'+paymentbills[i]['TenantPhones']+'/'+paymentbills[i]['tid']+'/'+paymentbills[i]['hid']+'/Rent RECEIPT: Greetings '+paymentbills[i]['Tenantfname']+' of '+paymentbills[i]['Housename']+' '+paymentbills[i]['MonthWaterDate']+' Bill. Total Kshs '+paymentbills[i]['TotalUsed']+' Received Ksh.'+paymentbills[i]['TotalPaid']+'.Overpayment Kshs.'+Math.abs(paymentbills[i]['Balance'])+'.Thank You">';
                }
                else{
                  inputvalue='<input type="checkbox" class="selectedwaterbilltenants" name="waterchoosen[]" id="watermemo'+paymentbills[i]['id']+'" value="'+paymentbills[i]['TenantPhones']+'/'+paymentbills[i]['tid']+'/'+paymentbills[i]['hid']+'/Rent RECEIPT: Greetings '+paymentbills[i]['Tenantfname']+' of '+paymentbills[i]['Housename']+' '+paymentbills[i]['MonthWaterDate']+' Bill. Total Kshs '+paymentbills[i]['TotalUsed']+' Received Ksh.'+paymentbills[i]['TotalPaid']+'. You have No Arrears for this Month. Thank You">';
                }
                
                alreadysaved='<div class="col-12 m-0 p-1 pl-2" style="border-radius:5px;">'+
                    '<div class="card-body bg-white m-1 elevation-3" style="border-radius:5px;">'+
                      '<div class="row m-0 p-0">'+
                        '<div class="col-12 m-0 p-0">'+
                          '<span class="text-dark" style="font-size: 12px;">'+
                            '<b>'+(i+1)+'. '+paymentbills[i]['Tenantfname']+', '+paymentbills[i]['Housename']+' '+paymentbills[i]['TenantPhones']+' '+
                                '<button type="button" class="btn btn-outline-success text-xs m-1 p-0 pr-1 pl-1 paymentupdatebtn" '+
                                  'data-id0="'+data['thisproperty'].id+'" data-id1="'+paymentbills[i]['tid']+'" data-id2="'+paymentbills[i]['hid']+'" data-id3="'+data['watermonth']+'" '+
                                  'data-id4="'+paymentbills[i]['Rent']+'" data-id5="'+paymentbills[i]['Garbage']+'" data-id6="'+paymentbills[i]['Waterbill']+'"'+
                                  'data-id7="'+paymentbills[i]['Excess']+'" data-id8="'+paymentbills[i]['Arrears']+'" data-id9="'+paymentbills[i]['TotalUsed']+'"'+
                                  'data-id10="'+paymentbills[i]['TotalPaid']+'" data-id11="'+paymentbills[i]['Balance']+'" data-id12="'+paymentbills[i]['HseDeposit']+'"'+
                                  'data-id13="'+paymentbills[i]['Water']+'" data-id14="'+paymentbills[i]['KPLC']+'" data-id15="'+paymentbills[i]['Lease']+'"'+
                                  'data-id16="'+paymentbills[i]['Tenantname']+'" data-id17="'+paymentbills[i]['Tenantfname']+'" data-id18="'+paymentbills[i]['MonthWaterDate']+'"'+
                                  'data-id19="'+paymentbills[i]['Housename']+'"'+
                                  '>'+
                                  '<i class="fa fa-edit text-xs"> </i> Bills'+
                                '</button>'+
                            '</b> '+SentDatePayment+
                          '</span>'+
                        '</div>'+
                      '</div>'+
                      '<div class="card-body">'+
                        '<div class="col-sm-12">'+
                            '<div class="" style="text-align: center;">'+
                              '<div class="">'+
                                '<input type="hidden" name="pid" value="'+data['thisproperty'].id+'">'+
                                '<input type="hidden" name="month" value="'+data['watermonth']+'">'+
                              '</div>'+
                            '</div>'+
                        '</div>'+
                        
                        '<div class="row m-0 p-0">'+
                          '<div class="col-12 m-0 p-0">';
                        if((paymentbills[i]['Balance'])>0){
                          alreadysaved=alreadysaved+'<div class="p-1 text-xs" style="background-color:#ffffff;color:white;cursor:pointer;border-radius:5px;" data-id1="watermemo'+paymentbills[i]['id']+'">'+
                            '<label class="p-0" style="cursor:pointer;color:black;font-weight:normal">'+inputvalue+
                            
                            ' Rent RECEIPT: Greetings '+paymentbills[i]['Tenantfname']+' of '+paymentbills[i]['Housename']+', '+paymentbills[i]['MonthWaterDate']+' Bill. Total '+paymentbills[i]['TotalUsed']+', Received Ksh.'+paymentbills[i]['TotalPaid']+'. '+balance+
                        
                            '</label>'+
                          '</div>'
                        }
                        else{
                          alreadysaved=alreadysaved+'<div class="p-1 paymentselmemodiv text-xs" style="background-color:#f8f9fa;color:black;cursor:pointer;border-radius:5px;" data-id1="watermemo'+paymentbills[i]['id']+'">'+
                            '<label class="p-0" style="cursor:pointer;color:black;font-weight:normal">'+inputvalue+
                            
                            ' Rent RECEIPT: Greetings '+paymentbills[i]['Tenantfname']+' of '+paymentbills[i]['Housename']+', '+paymentbills[i]['MonthWaterDate']+' Bill. Total '+paymentbills[i]['TotalUsed']+', Received Ksh.'+paymentbills[i]['TotalPaid']+'. '+balance+
                        
                            '</label>'+
                          '</div>'
                        }
                              
                        alreadysaved=alreadysaved+'</div>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</div>';
              }
              $('#loadpaymentsresults').append(alreadysaved);
            }
          }
          else{
            $('#loadpaymentsresults').html('<h4 class="text-info" style="padding:10px;margin-left:10px;">Messages Sent and Not Sent will be displayed here</h4>');
          }
            //end of payments loop
      },
      error: function(xhr, status, error){
        var errorMessage = xhr.status + ': ' + xhr.statusText
        if (errorMessage=="0: error") {
            errorMessage="Internet Connection Interrupted.";
        }
        else if(errorMessage=="404: Not Found"){
            errorMessage="Intended Server Resource Not found."
        }
        else if(errorMessage=="500: Internal Server Error"){
            errorMessage="Failed due to Some Technical Error In a Server."
        }
        
        $(document).Toasts('create', {
            title: 'Loading Payment',
            class: 'bg-warning',
            position: 'bottomLeft',
            body: errorMessage
        })
      }
    });
  }

  $(document).on('click', '.paymentupdatebtn', function(e){
    e.preventDefault();
    $('#paymentupdate').modal('show');
    $('#saveupdatepaymentload').html('');
    let paymentpid    =$(this).data("id0");
    let paymenttid    =$(this).data("id1");
    let paymenthid    =$(this).data("id2");
    let paymentmonth  =$(this).data("id3");
    let Rent          =$(this).data("id4");
    let Garbage       =$(this).data("id5");
    let Waterbill     =$(this).data("id6");
    let Excess        =$(this).data("id7");
    let Arrears       =$(this).data("id8");
    let TotalUsed     =$(this).data("id9");
    let TotalPaid     =$(this).data("id10");
    let Balance       =$(this).data("id11");
    let HseDeposit    =$(this).data("id12");
    let Water         =$(this).data("id13");
    let KPLC          =$(this).data("id14");
    let Lease         =$(this).data("id15");
    let Tenantname    =$(this).data("id16");
    let Tenantfname   =$(this).data("id17");
    let MonthWaterDate  =$(this).data("id18");
    let Housename  =$(this).data("id19");

    document.getElementById('paymentpid').value=paymentpid;
    document.getElementById('paymenttid').value=paymenttid;
    document.getElementById('paymenthid').value=paymenthid;
    document.getElementById('paymentmonth').value=paymentmonth;

    $('.paymenthousename').html(Housename);
    $('.paymentwatermonth').html(MonthWaterDate);
    $('.paymenttenantname').html(Tenantfname);
    $('#paymentpaid').val('');
    $('#paymentdate').val('');
    
    let housedetails='';
    let others=HseDeposit + Water + KPLC + Lease;
    housedetails=(
      '<div class="row m-0 mb-1 bg-white p-0 elevation-2">'+
        '<div class="col-4 m-0 p-1">'+
        '<span class="m-0 p-1">Rent: <i class="text-xs text-info">'+
          new Number(Rent).toFixed(2)+'</i></span>'+
        '</div>'+
        '<div class="col-4 m-0 p-1">'+
        '<span class="m-0 p-1">Garbage: <i class="text-xs text-info">'+
          new Number(Garbage).toFixed(2)+'</i></span>'+
        '</div>'+
        '<div class="col-4 m-0 p-1">'+
        '<span class="m-0 p-1">Waterbill: <i class="text-xs text-info">'+
          new Number(Waterbill).toFixed(2)+'</i></span>'+
        '</div>'+
      '</div>'+
      '<div class="row m-0 mb-1 bg-white p-0 elevation-2">'+
        '<div class="col-4 m-0 p-1">'+
        '<span class="m-0 p-1">Excess: <i class="text-xs text-info">'+
          new Number(Excess).toFixed(2)+'</i></span>'+
        '</div>'+
        '<div class="col-4 m-0 p-1">'+
        '<span class="m-0 p-1">Arrears: <i class="text-xs text-info">'+
          new Number(Arrears).toFixed(2)+'</i></span>'+
        '</div>'+
        '<div class="col-4 m-0 p-1">'+
        '<span class="m-0 p-1">Others: <i class="text-xs text-info">'+
          new Number(others).toFixed(2)+'</i></span>'+
        '</div>'+
      '</div>'+
      '<div class="row m-0 mb-1 bg-white p-0 elevation-2">'+
        '<div class="col-4 m-0 p-1">'+
        '<span class="m-0 p-1">Billed: <i class="text-xs text-info">'+
          new Number(TotalUsed).toFixed(2)+'</i></span>'+
        '</div>'+
        '<div class="col-4 m-0 p-1">'+
        '<span class="m-0 p-1">Paid: <i class="text-xs text-info">'+
          new Number(TotalPaid).toFixed(2)+'</i></span>'+
        '</div>'+
        '<div class="col-4 m-0 p-1">'+
        '<span class="m-0 p-1">Bal: <i class="text-xs text-info">'+
          new Number(Balance).toFixed(2)+'</i></span>'+
        '</div>'+
      '</div>'+
      '<div class="row m-0 mb-1 bg-white p-0 elevation-2">'+
        '<div class="col-12 m-0 p-0">'+
        '<span class="m-0 p-1">Payments: <i class="text-xs text-info">'+
          new Number(TotalUsed).toFixed(2)+'</i></span>'+
        '</div>'+
      '</div>'+
      '</div>'
    );
    $('.viewhousepaymentspanelothers').html(housedetails);
    
  });


  $("#submitsendsummarypaid").on('submit',(function(e){
    e.preventDefault();
    if (confirm("Do You Want to Send Selected Payment Summary Messages")) {
        
        $('#savesummarypaidload').html('<h4 class="text-center text-lime">Sending... <img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading..."></h4>');
        $.ajax({
            url:'/properties/send/messages/summarypayments',
            type:"POST",
            data:new FormData(document.getElementById('submitsendsummarypaid')),
            processData:false,
            contentType:false,
            cache:false,
            success:function(data){
                if(data['error']){
                    $(document).Toasts('create', {
                        title: 'Sending Payment Messages',
                        class: 'bg-warning',
                        position: 'bottomLeft',
                        body: data['error']
                    })
                    
                    $('#savesummarypaidload').html(data['error']);
                }
                else{
                    $(document).Toasts('create', {
                        title: 'Sending Payment Messages',
                        class: 'bg-success',
                        position: 'bottomLeft',
                        body: data['success']
                    })
                    
                    $('#savesummarypaidload').html(data['success']);
                    paymentbill_results();
                }
            },
            error: function(xhr, status, error){
                var errorMessage = xhr.status + ': ' + xhr.statusText
                if (errorMessage=="0: error") {
                    errorMessage="Internet Connection Interrupted.";
                }
                else if(errorMessage=="404: Not Found"){
                    errorMessage="Intended Server Resource Not found."
                }
                else if(errorMessage=="500: Internal Server Error"){
                    errorMessage="Failed due to Some Technical Error In a Server."
                }
                
                $(document).Toasts('create', {
                    title: 'Sending Error',
                    class: 'bg-warning',
                    position: 'bottomLeft',
                    body: errorMessage
                })
                $('#savesummarypaidload').html('<h6 class="text-center text-danger">Oops!! Sorry - ' + errorMessage+'</h6>');
                
            }
        });    
    };
  
  }));

  $("#submitsendcompletedpaid").on('submit',(function(e){
    e.preventDefault();
    if (confirm("Do You Want to Send Selected Completed Paid Messages.")) {
        $('#savecompletedpaidload').html('<h4 class="text-center text-lime">Sending... <img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading..."></h4>');
        $.ajax({
            url:'/properties/send/messages/completedpayments',
            type:"POST",
            data:new FormData(document.getElementById('submitsendcompletedpaid')),
            processData:false,
            contentType:false,
            cache:false,
            success:function(data){
                if(data['error']){
                    $(document).Toasts('create', {
                        title: 'Sending Payment Messages',
                        class: 'bg-warning',
                        position: 'bottomLeft',
                        body: data['error']
                    })
                    
                    $('#savecompletedpaidload').html(data['error']);
                }
                else{
                    $(document).Toasts('create', {
                        title: 'Sending Payment Messages',
                        class: 'bg-success',
                        position: 'bottomLeft',
                        body: data['success']
                    })
                    
                    $('#savecompletedpaidload').html(data['success']);
                    paymentbill_results();
                }
            },
            error: function(xhr, status, error){
                var errorMessage = xhr.status + ': ' + xhr.statusText
                if (errorMessage=="0: error") {
                    errorMessage="Internet Connection Interrupted.";
                }
                else if(errorMessage=="404: Not Found"){
                    errorMessage="Intended Server Resource Not found."
                }
                else if(errorMessage=="500: Internal Server Error"){
                    errorMessage="Failed due to Some Technical Error In a Server."
                }
                
                $(document).Toasts('create', {
                    title: 'Sending Error',
                    class: 'bg-warning',
                    position: 'bottomLeft',
                    body: errorMessage
                })
                $('#savecompletedpaidload').html('<h6 class="text-center text-danger">Oops!! Sorry - ' + errorMessage+'</h6>');
                
            }
        });    
    };
  }));
  // WATER BILL: Greetings D4-3 :2022 May;Prus:378;Cut:388;Cost Kshs.150.00;Units:10.00;CC:1500;Other:0.00;Total Kshs.1500.Thank You
  // update water_messages set Message= REPLACE(Message,'Prus','Previous')
  // update water_messages set Message= REPLACE(Message,'Cut','Current')

</script>
@endpush