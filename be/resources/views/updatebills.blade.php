@extends('layouts.adminheader')
@section('title','Update Payment | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-6">
    <h5 class="m-0">
      Update Payment to @if($thisproperty!="")
      {{$thisproperty->Plotcode}}
      @endif
      /{{$watermonth}})
    </h5>
</div><!-- /.col -->
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="/properties">
    Properties (@forelse($propertyinfo as $property)
      {{$loop->count}}
          @break
      @empty
          0
      @endforelse
      )</a></li>
  <li class="breadcrumb-item active">
    Waterbill(
    @if($thisproperty!="")
      {{$thisproperty->Plotcode}}
      @endif
      /{{$watermonth}})</li>
</ol>
</div><!-- /.col -->
@endsection
@section('css')
  <!-- dropzonejs -->
  <link rel="stylesheet" href="{{ asset('public/assets/plugins/dropzone/basic.css') }}">
  <link rel="stylesheet" href="{{ asset('public/assets/plugins/dropzone/dropzone.css') }}">
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
        <a href="/properties/update/bills" class="nav-link active">
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
          <div class="row">
            <!-- start of left side -->
              <div class="col-md-4">
                <div class="card mb-2">
                  <div class="card-body m-1 p-1">
                    <!-- choose property month and upload option-->
                    <div class="row m-0 p-0">
                      <div class="col-12 m-1 p-0">
                        <select class="form-control select2" name="allproperties" onchange="location=this.value;" style="width: 100%;">
                          <option value="">Select Property</option>
                          @forelse($propertyinfo as $propertys)
                            @if($thisproperty!="" && $watermonth!="")
                              @if($thisproperty->id==$propertys->id)
                                <option value="/properties/update/bills/{{ $propertys->id }}/{{$watermonth}}" selected>{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                              @else
                                <option value="/properties/update/bills/{{ $propertys->id }}/{{$watermonth}}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                              @endif
                            @elseif($thisproperty!="" && $watermonth=="")
                              @if($thisproperty->id==$propertys->id)
                                <option value="/properties/update/bills/{{ $propertys->id }}/{{App\Http\Controllers\TenantController::getAddWaterMonthsThis()}}" selected>{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                              @else
                                <option value="/properties/update/bills/{{ $propertys->id }}/{{App\Http\Controllers\TenantController::getAddWaterMonthsThis()}}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                              @endif
                            @else
                              <option value="/properties/update/bills/{{$propertys->id}}/{{App\Http\Controllers\TenantController::getAddWaterMonthsThis()}}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                            @endif
                          @empty
                            <option>No Property Found</option>
                          @endforelse
                        </select>
                      </div>


                      <div class="col-12 m-1 p-0">
                          <select class="form-control select2" name="allproperties" onchange="location=this.value;" style="width: 100%;">
                            <option value="">Choose Month</option>
                            @if($thisproperty!="" && $watermonth!="")
                              {{App\Http\Controllers\TenantController::getUpdateBillsMonths($thisproperty->id,$watermonth)}}
                            @elseif($thisproperty!="" && $watermonth=="")
                              {{App\Http\Controllers\TenantController::getUpdateBillsMonths($thisproperty->id,App\Http\Controllers\TenantController::getAddWaterMonthsThis())}}
                            @endif
                          </select>
                      </div>

                      <div class="col-12  m-1 p-0">
                        @if($thisproperty!="" && $watermonth!="")
                          <div class="">
                            <form role="form" class="form-horizontal" method="POST" action="/properties/update/bills/preview" enctype="multipart/form-data" style="">
                              @csrf
                              <div class="row m-0 p-0" style="">
                                <div class="col-12 m-0 p-0" style="">
                                  <form></form>
                                  <form method="post" action="/properties/update/bills/preview" class="dropzone" id="dropzoneForm" style="border: 4px dotted blue;">
                                      @csrf
                                      <div class="form-group">
                                          <h6 class="text-center text-primary text-lg">{{$thisproperty->Plotcode}}({{$monthinfo}})</h6>
                                      </div>
                                      <input type="hidden" name="month" id="month" value="{{$watermonth}}">
                                      <input type="hidden" name="pid" id="pid" value="{{$thisproperty->id}}">
                                      <input type="hidden" name="pcode" id="pcode" value="{{$thisproperty->Plotcode}}">
                                  </form> 
                                </div>
                              </div>
                            </form>
                            
                          </div>
                        @endif
                      </div>

                      <!-- <div class="col-12 bg-light m-1 p-0">
                        @if($thisproperty!="" && $watermonth!="")
                        <form role="form" class="form-horizontal" action="/properties/update/bills/save" method="post" enctype="multipart/form-data">
                          @csrf
                            <input type="hidden" name="month" id="month" value="{{$watermonth}}">
                            <input type="hidden" name="id" id="id" value="{{$thisproperty->id}}">
                            <div class="row justify-content-center">
                              <div class="col-12 m-0 p-0 mx-auto">
                                <button type="button" class="btn btn-outline-primary text-xs">Upload Payments</button>
                              </div>
                            </div>
                        </form>
                        @endif
                      </div> -->


                      <div class="col-12 m-1 mt-2 p-0">
                        <div class="bg-warning text-black text-xs m-0 p-1 viewhousepaymentspanel" style="display:none;"></div>
                        <div class="viewhousepaymentspanelres"  style="display:none;">
                          <form role="form" class="form-horizontal" method="POST" name="submitupdatebillspayments" id="submitupdatebillspayments">
                            @csrf
                            <div class="row m-0 p-0">
                              <input type="hidden" name="paymentpid" id="paymentpid">
                              <input type="hidden" name="paymenttid" id="paymenttid">
                              <input type="hidden" name="paymenthid" id="paymenthid" >
                              <input type="hidden" name="paymentmonth" id="paymentmonth">

                              <div class="col-12 m-0 p-0">
                                <div class="card card-primary card-outline m-0 p-0">
                                  <div class="card-body bg-light m-0 mt-2 p-0">
                                    <h6 class="text-center text-bold">House <span id="paymenthousename"></span> Info </h6>
                                    <p class="text-center text-danger text-xs">Excess, Arrears, Billed and Paid Values should be updated</p>
                                    

                                    <div class="bg-light text-black text-xs m-1 p-1 viewhousepaymentspanelothers"></div>

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
                                      <label class="col-md-12 m-0 p-0 col-form-label">{{ __('Message Sent Status:') }}
                                        <span id="paymentdesc"></span>
                                      </label>
                                    </div>

                                    <div class="form-group row m-0 p-1">
                                      <div class="col-12 m-0 p-0" id="saveupdatepaymentload">

                                      </div>
                                      <div class="col-12 m-0 p-0">
                                        <button  class="btn btn-outline-success btn-xs float-right" name="submitplotbtn" id="submitplotbtn"  type="submit" >
                                          Submit <span id="tenanthouse"></span> Details
                                        </button>
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
              </div>
              <!-- end of left side -->
              <!-- start of rigth side  -->
              <div class="col-md-8">
                <!-- start of update waterbill -->
                <div class="row">
                  @if($thisproperty=="")
                    <div class="col-sm-12 col-12" style="padding: 2px;margin: 0px;">
                      <div class="card direct-chat direct-chat-primary" style="padding: 2px;margin: 3px;">
                          <h4>Please Choose Property Name</h4>
                      </div>
                    </div>
                  @else
                  @if($thisproperty!="")
                    <div class="col-sm-12 col-12" style="padding: 2px;margin: 0px;">
                      <div class="card direct-chat direct-chat-primary" style="padding: 2px;margin: 3px;">
                        <div class="card-header bg-info" style="padding: 4px;">
                          <span class="" style="font-size: 13px;"> Payment Details: {{$thisproperty->Plotname}}
                             (
                              @if($watermonth!="")
                                {{$watermonth}}
                              @endif)
                              @if($sno=0)@endif
                              <!-- <button class="btn btn-success btn-xs text-white m-0 p-0 pl-1 pr-1"><i class="fa fa-download"></i>
                                <a target="_blank" href="/properties/download/Reports/Payments/{{$thisproperty->id}}"  class="text-light">Excel</a>
                              </button>
                              <button class="btn btn-success btn-xs text-white m-0 p-0 pl-1 pr-1">
                                <a href="/properties/Download/Acknowledgement/{{$thisproperty->id}}/{{$watermonth}}" class="text-light" title="Generate PDF Payment Acknowledgement for All Houses in Property">Generate for All </a>
                              </button> -->
                              <i class="bg-light m-1 p-1" style="border-radius:5px;">No Changes</i>
                              <i class="bg-white m-1 p-1" style="border-radius:5px;">Not sent</i>
                              <i class="bg-warning m-1 p-1" style="border-radius:5px;">sent</i>
                              <i class="bg-danger m-1 p-1" style="border-radius:5px;">Vacant</i>
                               
                              </span>
                              
                              <span class="float-right" style="z-index:999999;color:red;font-size:15px;right:5%;">Selected(<i class="badge selwaterbilltenants" id="selectedwaterbilltenants" style="font-size:20px;">0</i>)</span>
                            </span>

                          <!-- <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                              <i class="fas fa-minus"></i>
                            </button>
                          </div> -->
                          
                        </div>
                        <form role="form" class="form-horizontal submitupdateuploadpayments" method="POST" name="saveuploadwaterbill"
                          action="/properties/save/payments/uploadupdate" id="submitupdateuploadpayments">
                          @csrf
                          <div class="card-body bg-light" >
                            <div class="row m-0 p-0">
                              <div class="col-sm-12" style="overflow-x:auto;max-height: calc(100vh - 12rem);overflow-y:auto;">
                              @if($thisproperty!="" && $watermonth!="")
                                <input type="hidden" id="savepid" name="savepid" value="{{$thisproperty->id}}">
                                <input type="hidden" id="savemonth" name="savemonth" value="{{$watermonth}}">
                              @endif
                                @if($paymentbills!="")
                                <table class="table table-bordered table-hover" style="font-size: 11px;">
                                  <thead>
                                    <tr style="color:white;color:#77B5ED;">
                                      <th class="m-0 p-1">#</th>
                                      <th class="m-0 p-1">House</th>
                                      <th class="m-0 p-1">Tenant</th>
                                      <th class="m-0 p-1">Excess</th>
                                      <th class="m-0 p-1">Arrears</th>
                                      <th class="m-0 p-1">Rent</th>
                                      <th class="m-0 p-1">Garbage</th>
                                      <th class="m-0 p-1">Waterbill</th>
                                      <th class="m-0 p-1">Others</th>
                                      <th class="m-0 p-1">Total</th>
                                      <th class="m-0 p-1">Paid</th>
                                      <th class="m-0 p-1">Bal</th>
                                      <th class="m-0 p-1">Sent</th>
                                    </tr>
                                  </thead>
                                  <tbody style="overflow-x:auto;" id="loadpaymentbills">
                                    <!-- @forelse($paymentbills as $waterpreview)
                                      <tr style="padding:0px;margin:2px;background-color:#FFFFFF;font-size: 10px;padding: 2px;font-size: 11px;">
                                        <td class="m-0 p-1">{{++$sno}}</td>
                                        <td class="m-0 p-1">{{$waterpreview['Housename']}}</td>
                                        <td class="m-0 p-1" style="text-transform:lowercase;">{{$waterpreview['Tenantname']}}</td>
                                        <td class="m-0 p-1">{{$waterpreview['Arrears']}}</td>
                                        <td class="m-0 p-1">{{$waterpreview['Excess']}}</td>
                                        <td class="m-0 p-1">{{$waterpreview['Rent']}}</td>
                                        <td class="m-0 p-1">{{$waterpreview['Garbage']}}</td>
                                        <td class="m-0 p-1">{{$waterpreview['Waterbill']}}</td>
                                        <td class="m-0 p-1">{{$waterpreview['HseDeposit'] + $waterpreview['Water'] + $waterpreview['KPLC'] + $waterpreview['Lease']}}</td>
                                        <td class="m-0 p-1">{{$waterpreview['TotalUsed']}}</td>
                                        <td class="m-0 p-1">{{$waterpreview['TotalPaid']}}</td>
                                        <td class="m-0 p-1">{{$waterpreview['Penalty']}}</td>
                                        <td class="m-0 p-1">{{$waterpreview['Balance']}}</td>
                                        <td class="m-0 p-0" style="padding:0px;margin:2px;font-size: 9px;">
                                          {{$waterpreview['MessageStatus']}}
                                          <div class="bg-info m-1 mb-0 p-1" style="font-size: 9px;border-radius:5px;" title="Notified as Paid">
                                            <i class="fa fa-envelope text-white"> Paid</i>
                                          </div>
                                          <div class="bg-success m-1 mb-0 p-1" style="font-size: 9px;border-radius:5px;" title="Message with Payment Details like Rent, Paid and Balance">
                                            <i class="fa fa-check"> Details</i>
                                          </div>
                                          <div class="bg-danger m-1 mb-0 p-1" style="font-size: 9px;border-radius:5px;" >
                                            <i class="text-dark">None</i>
                                          </div>
                                        </td>
                                      </tr>
                                    @empty
                                    @endforelse -->
                                  </tbody>
                                  
                                </table>
                                @else
                                  <h4>No Payments Found Here</h4>
                                @endif
                                  
                              </div>
                            </div>           
                          </div>
                          <div class="modal-footer justify-content-center bg-light m-0 p-1" >
                            <div class="col-12 m-0 p-0" id="saveuploadpaymentload"></div>
                            <button class="btn btn-outline-info selectedtenantshsesbtn" style="padding: 5px;">
                              Send To Selected Tenants (<i class="badge selwaterbilltenants" id="selectedwaterbilltenants" style="font-size:20px;">0</i>)
                            </button>
                          </div>
                        </form>

                      </div>
                    </div>
                    @endif
                  @endif
                </div>
            </div>
              <!-- end of right side -->
            </div>
           

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- dropzone -->
<script src="{{ asset('public/assets/plugins/dropzone/dropzone.js') }}"></script>
<script type="text/javascript">
  $(function () {
      //Initialize Select2 Elements
      $('.select2').select2()

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })
      paymentbill_data();
      getselectedwaterbilltenants();
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
    }

    $(document).on('click','.unwaterbillvaluesdiv',(function(e){
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

    $(document).on('click','.viewhousepaymentsuploadbtn',(function(e){
      $('.viewhousepaymentspanelupload').show();
      $('.viewhousepaymentspanel').hide();
      $('.viewhousepaymentspanelres').hide();
    }));

    function viewHousePayments(){
      let houseurl=document.getElementById('housespayments');
      let url=houseurl.value;
      
      $('#saveupdatepaymentload').html('');
      $('.viewhousepaymentspanelupload').hide();
      $('.viewhousepaymentspanel').hide();
      $('.viewhousepaymentspanelres').hide();
      $('.viewhousepaymentspanel').html('Please Wait... <img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
        $.ajax({
          headers:{
              'X-CSRF-TOKEN':$('meta[name="csrf-token"').attr('content')
            },
          type:'GET',
          url:url,
            success:function(response){
              if(response['error']){
                console.log(response['error'])
                $('.viewhousepaymentspanel').html('Error: - ' + response['error']);
                
              }
              else{
                var thisproperty=response['thisproperty'];
                var watermonth=response['watermonth'];
                var paymentbills=response['paymentbills'];
                
                let paymentpid=document.getElementById('paymentpid');
                let paymenttid=document.getElementById('paymenttid');
                let paymenthid=document.getElementById('paymenthid');
                let paymentmonth=document.getElementById('paymentmonth');
                paymentpid.value=paymentbills[0]['pid'];
                paymenttid.value=paymentbills[0]['tid'];
                paymenthid.value=paymentbills[0]['hid'];
                paymentmonth.value=paymentbills[0]['Month'];

                $('#tenanthouse').html(paymentbills[0]['Housename']);
                $('#paymenthousename').html(paymentbills[0]['Housename']);
                $('#paymentdesc').html(paymentbills[0]['MessageStatus']);
                
                let housedetails='';
                let others=new Number(paymentbills[0]['HseDeposit']).toFixed(2) + new Number(paymentbills[0]['Water']).toFixed(2) + new Number(paymentbills[0]['KPLC']).toFixed(2) + new Number(paymentbills[0]['Lease']).toFixed(2);
                housedetails=(
                '<div class="row m-0 mb-1 bg-white p-0">'+
                  '<div class="col-4 m-0 p-1">'+
                  '<span class="m-0 p-1">Rent: <i class="text-xs text-info">'+new Number(paymentbills[0]['Rent']).toFixed(2)+'</i></span>'+
                  '</div>'+
                  '<div class="col-4 m-0 p-1">'+
                  '<span class="m-0 p-1">Garbage: <i class="text-xs text-info">'+new Number(paymentbills[0]['Garbage']).toFixed(2)+'</i></span>'+
                  '</div>'+
                  '<div class="col-4 m-0 p-1">'+
                  '<span class="m-0 p-1">Waterbill: <i class="text-xs text-info">'+new Number(paymentbills[0]['Waterbill']).toFixed(2)+'</i></span>'+
                  '</div>'+
                '</div>'+
                '<div class="row m-0 mb-1 bg-white p-0">'+
                  '<div class="col-4 m-0 p-1">'+
                  '<span class="m-0 p-1">Excess: <i class="text-xs text-info">'+new Number(paymentbills[0]['Excess']).toFixed(2)+'</i></span>'+
                  '</div>'+
                  '<div class="col-4 m-0 p-1">'+
                  '<span class="m-0 p-1">Arrears: <i class="text-xs text-info">'+new Number(paymentbills[0]['Arrears']).toFixed(2)+'</i></span>'+
                  '</div>'+
                  '<div class="col-4 m-0 p-1">'+
                  '<span class="m-0 p-1">Others: <i class="text-xs text-info">'+new Number(paymentbills[i]['Others']).toFixed(2)+'</i></span>'+
                  '</div>'+
                '</div>'+
                '<div class="row m-0 mb-1 bg-white p-0">'+
                  '<div class="col-4 m-0 p-1">'+
                  '<span class="m-0 p-1">Billed: <i class="text-xs text-info">'+new Number(paymentbills[0]['TotalUsed']).toFixed(2)+'</i></span>'+
                  '</div>'+
                  '<div class="col-4 m-0 p-1">'+
                  '<span class="m-0 p-1">Paid: <i class="text-xs text-info">'+new Number(paymentbills[0]['TotalPaid']).toFixed(2)+'</i></span>'+
                  '</div>'+
                  '<div class="col-4 m-0 p-1">'+
                  '<span class="m-0 p-1">Bal: <i class="text-xs text-info">'+new Number(paymentbills[0]['Balance']).toFixed(2)+'</i></span>'+
                  '</div>'+
                '</div>'+
                '<div class="row m-0 mb-1 bg-white p-0">'+
                  '<div class="col-12 m-0 p-0">'+
                  '<span class="m-0 p-1">Payments: <i class="text-xs text-info">'+new Number(paymentbills[0]['TotalUsed']).toFixed(2)+'</i></span>'+
                  '</div>'+
                '</div>'+
                '</div>'
                );
                $('.viewhousepaymentspanelothers').html(housedetails);
                $('.viewhousepaymentspanel').html('');
                $('.viewhousepaymentspanel').hide();
                $('.viewhousepaymentspanelres').hide();
                $('.viewhousepaymentspanelres').show();
              }
            },
            error: function(xhr, status, error){
              var errorMessage = xhr.status + ': ' + xhr.statusText
              if (errorMessage=="0: error") {
                  errorMessage="Internet Connection Interrupted.";
              }
              else if(errorMessage=="404: Not Found"){
                  errorMessage="House Information Not found."
              }
              else if(errorMessage=="500: Internal Server Error"){
                  errorMessage="Technical Error."
              }
              $('.viewhousepaymentspanel').html('Error: - ' + errorMessage);
              $('.viewhousepaymentspanelres').hide();
            }
        }); 
    }

    function paymentbill_data(){
      let savepiddoc=document.getElementById('savepid');
      let savemonthdoc=document.getElementById('savemonth');
      let propertyid=savepiddoc.value;
      let watermonth=savemonthdoc.value;
      $.ajax({
        url:"/properties/update/loadbills/"+propertyid+"/"+watermonth,
        method:"GET",
        success:function(data){
              var thisproperty=data['thisproperty'];
              var watermonth=data['watermonth'];
              var paymentbills=data['paymentbills'];
              $('#loadpaymentbills').html('');
              let alreadysaved="";
              let validwaterbill="";
              let vacatedhousebills="";
              let savepiddoc=document.getElementById('savepid');
              let savemonthdoc=document.getElementById('savemonth');
              savepiddoc.value=data['thisproperty'].id;
              savemonthdoc.value=watermonth;
              //loop through payments
              for (var i = 0; i < paymentbills.length; i++) {
                // loaded from database
                let others=new Number(paymentbills[0]['HseDeposit']).toFixed(2) + new Number(paymentbills[0]['Water']).toFixed(2) + new Number(paymentbills[0]['KPLC']).toFixed(2) + new Number(paymentbills[0]['Lease']).toFixed(2);
                alreadysaved='<tr style="padding:0px;margin:2px;background-color:#FFFFFF;">'+
                            '<td class="m-0 p-1">'+(i+1)+'</td>'+
                            '<td class="m-0 p-1">'+paymentbills[i]['Housename']+'</td>'+
                            '<td class="m-0 p-1" style="text-transform:lowercase;">'+paymentbills[i]['Tenantname']+'</td>'+
                            '<td class="m-0 p-1">'+paymentbills[i]['Excess']+'</td>'+
                            '<td class="m-0 p-1">'+paymentbills[i]['Arrears']+'</td>'+
                            '<td class="m-0 p-1">'+paymentbills[i]['Rent']+'</td>'+
                            '<td class="m-0 p-1">'+paymentbills[i]['Garbage']+'</td>'+
                            '<td class="m-0 p-1">'+paymentbills[i]['Waterbill']+'</td>'+
                            '<td class="m-0 p-1">'+paymentbills[i]['Others']+'</td>'+
                            '<td class="m-0 p-1">'+paymentbills[i]['TotalUsed']+'</td>'+
                            '<td class="m-0 p-1">'+paymentbills[i]['TotalPaid']+'</td>'+
                            '<td class="m-0 p-1">'+paymentbills[i]['Balance']+'</td>'+
                            '<td class="m-0 p-0" style="padding:0px;margin:2px;font-size: 9px;">'+paymentbills[i]['MessageStatus']+'</td>'+
                        '</tr>';
                $('#loadpaymentbills').append(alreadysaved);
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
              subtitle:'Error',
              body: errorMessage
          })
        }
      });
  }

  $("#submitupdatebillspayments").on('submit',(function(e){
    e.preventDefault();
    let savepiddoc=document.getElementById('savepid');
    let savemonthdoc=document.getElementById('savemonth');
    let paymentpid=document.getElementById('paymentpid');
    let paymenttid=document.getElementById('paymenttid');
    let paymenthid=document.getElementById('paymenthid');
    let paymentmonth=document.getElementById('paymentmonth');
    let savepid=savepiddoc.value;
    let savemonth=savemonthdoc.value;
    
    // console.log(paymentmonth.value,paymentpid.value,paymenttid.value,paymenthid.value)
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
                  subtitle: 'Some Error',
                  body: data['error']
              })
              $('#saveupdatepaymentload').html(data['error']);
          }
          else{
              $(document).Toasts('create', {
                  title: 'Updating Payment',
                  class: 'bg-success',
                  position: 'bottomLeft',
                  subtitle:'Saved',
                  body: data['success']
              })
              $('#saveupdatepaymentload').html(data['success']);
              viewHousePayments();
              paymentbill_data();
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
              subtitle:'Error',
              body: errorMessage
          })
          $('#saveupdatepaymentload').html('<h6 class="text-center text-danger">Oops!! Sorry - ' + errorMessage+'</h6>');
         
        }
      });    
    }
  }));  

  Dropzone.options.dropzoneForm={
  autoProcessQueue:true,
  maxFilesize: 200,
  addRemoveLinks: true,
  timeout: 500000,
  acceptedFiles: ".xls,.xlsx",
  removedfile:function(file){
      var fileRef;
      return (fileRef=file.previewElement) !=null ? fileRef.parentNode.removeChild(file.previewElement) : void 0;
    
  },
  success: function(file, response)
  {
    if(response['error']){
        $(document).Toasts('create', {
            title: 'Uploading Payment Form',
            class: 'bg-warning',
            position: 'bottomLeft',
            body: response['error']
        })
    }
    else{
        $(document).Toasts('create', {
            title: 'Previewing Payments',
            class: 'bg-success',
            position: 'bottomLeft',
            subtitle:response['thisproperty']['Plotcode'],
            body: response['success']
        })
        //first get all data for waterbill
        var thisproperty=response['thisproperty'];
        var watermonth=response['watermonth'];
        var paymentbills=response['output'];

        $('#loadpaymentbills').html('');
        let alreadysaved="";
        let validwaterbill="";
        let vacatedhousebills="";
        //loop through waterbill
        for (var i = 0; i < paymentbills.length; i++) {
            //check if waterbill is loaded from Saved Information
            if(paymentbills[i]['paymentid']==null){
                //loaded from preview file
                if(paymentbills[i]['tid']!="Vacant"){
                  validwaterbill='<tr class="unwaterbillvaluesdiv m-0 p-0" style="background-color:#FFFFFF;" data-id1="unstatementupdate'+(i+1)+'">'+
                        '<td class="m-0 p-2"><label class="col-lg-12 m-0 p-0" style="font-size:12px;">'+
                          '<input type="checkbox" class="selectedwaterbilltenants" name="paymentvaluesupdate[]"'+
                              'id="unstatementupdate'+(i+1)+'"'+
                              'value="'+paymentbills[i]['hid']+'?'+paymentbills[i]['Housename']+
                              '?'+paymentbills[i]['tid']+'?'+paymentbills[i]['Tenantname']+'?'+paymentbills[i]['Excess']+
                              '?'+paymentbills[i]['Arrears']+'?'+paymentbills[i]['Rent']+'?'+paymentbills[i]['Garbage']+
                              '?'+paymentbills[i]['Waterbill']+'?'+paymentbills[i]['Others']+'?'+paymentbills[i]['paymentid']+
                              '?'+paymentbills[i]['TotalUsed']+'?'+paymentbills[i]['TotalPaid']+'?'+paymentbills[i]['Due']+
                              '?"> '+(i+1)+'</label></td>'+
                      '<td class="m-0 p-1">'+paymentbills[i]['Housename']+'</td>'+
                      '<td class="m-0 p-1" style="text-transform:lowercase;">'+paymentbills[i]['Tenantname']+'</td>'+
                      '<td class="m-0 p-1">'+paymentbills[i]['Excess']+'</td>'+
                      '<td class="m-0 p-1">'+paymentbills[i]['Arrears']+'</td>'+
                      '<td class="m-0 p-1">'+paymentbills[i]['Rent']+'</td>'+
                      '<td class="m-0 p-1">'+paymentbills[i]['Garbage']+'</td>'+
                      '<td class="m-0 p-1">'+paymentbills[i]['Waterbill']+'</td>'+
                      '<td class="m-0 p-1">'+paymentbills[i]['Others']+'</td>'+
                      '<td class="m-0 p-1">'+paymentbills[i]['TotalUsed']+'</td>'+
                      '<td class="m-0 p-1">'+paymentbills[i]['TotalPaid']+'</td>'+
                      '<td class="m-0 p-1">'+paymentbills[i]['Balance']+'</td>'+
                      '<td class="m-0 p-0" style="padding:0px;margin:2px;font-size: 9px;">'+paymentbills[i]['MessageStatus']+'</td>'+
                  '</tr>';
                  $('#loadpaymentbills').append(validwaterbill);
                }
                else{
                    vacatedhousebills='<tr class="m-0 p-0" style="background-color:red;">'+
                                    '<td class="m-0 p-2">'+(i+1)+'</td>'+
                                    '<td class="m-0 p-1">'+paymentbills[i]['Housename']+'</td>'+
                                    '<td class="m-0 p-1" style="text-transform:lowercase;">'+paymentbills[i]['Tenantname']+'</td>'+
                                    '<td class="m-0 p-1">'+paymentbills[i]['Excess']+'</td>'+
                                    '<td class="m-0 p-1">'+paymentbills[i]['Arrears']+'</td>'+
                                    '<td class="m-0 p-1">'+paymentbills[i]['Rent']+'</td>'+
                                    '<td class="m-0 p-1">'+paymentbills[i]['Garbage']+'</td>'+
                                    '<td class="m-0 p-1">'+paymentbills[i]['Waterbill']+'</td>'+
                                    '<td class="m-0 p-1">'+paymentbills[i]['Others']+'</td>'+
                                    '<td class="m-0 p-1">'+paymentbills[i]['TotalUsed']+'</td>'+
                                    '<td class="m-0 p-1">'+paymentbills[i]['TotalPaid']+'</td>'+
                                    '<td class="m-0 p-1">'+paymentbills[i]['Balance']+'</td>'+
                                    '<td class="m-0 p-0" style="padding:0px;margin:2px;font-size: 9px;">'+paymentbills[i]['MessageStatus']+'</td>'+
                                '</tr>';
                    $('#loadpaymentbills').append(vacatedhousebills);
                }
            }
            else{
                // loaded from database
                if(paymentbills[i]['paymentstatus']){
                  alreadysaved='<tr class="unwaterbillvaluesdiv m-0 p-0" style="background-color:#FFFFFF;" data-id1="unstatementupdate'+(i+1)+'">'+
                        '<td class="m-0 p-2"><label class="col-lg-12 m-0 p-0" style="font-size:12px;">'+
                          '<input type="checkbox" class="selectedwaterbilltenants" name="paymentvaluesupdate[]"'+
                              'id="unstatementupdate'+(i+1)+'"'+
                              'value="'+paymentbills[i]['hid']+'?'+paymentbills[i]['Housename']+
                              '?'+paymentbills[i]['tid']+'?'+paymentbills[i]['Tenantname']+'?'+paymentbills[i]['Excess']+
                              '?'+paymentbills[i]['Arrears']+'?'+paymentbills[i]['Rent']+'?'+paymentbills[i]['Garbage']+
                              '?'+paymentbills[i]['Waterbill']+'?'+paymentbills[i]['Others']+'?'+paymentbills[i]['paymentid']+
                              '?'+paymentbills[i]['TotalUsed']+'?'+paymentbills[i]['TotalPaid']+'?'+paymentbills[i]['Due']+
                              '?"> '+(i+1)+'</label></td>'+
                      '<td class="m-0 p-1">'+paymentbills[i]['Housename']+'</td>'+
                      '<td class="m-0 p-1" style="text-transform:lowercase;">'+paymentbills[i]['Tenantname']+'</td>'+
                      '<td class="m-0 p-1">'+paymentbills[i]['Excess']+'</td>'+
                      '<td class="m-0 p-1">'+paymentbills[i]['Arrears']+'</td>'+
                      '<td class="m-0 p-1">'+paymentbills[i]['Rent']+'</td>'+
                      '<td class="m-0 p-1">'+paymentbills[i]['Garbage']+'</td>'+
                      '<td class="m-0 p-1">'+paymentbills[i]['Waterbill']+'</td>'+
                      '<td class="m-0 p-1">'+paymentbills[i]['Others']+'</td>'+
                      '<td class="m-0 p-1">'+paymentbills[i]['TotalUsed']+'</td>'+
                      '<td class="m-0 p-1">'+paymentbills[i]['TotalPaid']+'</td>'+
                      '<td class="m-0 p-1">'+paymentbills[i]['Balance']+'</td>'+
                      '<td class="m-0 p-0" style="padding:0px;margin:2px;font-size: 9px;">'+paymentbills[i]['MessageStatus']+'</td>'+
                  '</tr>';
                }
                else{
                  alreadysaved='<tr class="m-0 p-0 bg-light" style="background-color:red;">'+
                          '<td class="m-0 p-2">'+(i+1)+'</td>'+
                          '<td class="m-0 p-1">'+paymentbills[i]['Housename']+'</td>'+
                          '<td class="m-0 p-1" style="text-transform:lowercase;">'+paymentbills[i]['Tenantname']+'</td>'+
                          '<td class="m-0 p-1">'+paymentbills[i]['Excess']+'</td>'+
                          '<td class="m-0 p-1">'+paymentbills[i]['Arrears']+'</td>'+
                          '<td class="m-0 p-1">'+paymentbills[i]['Rent']+'</td>'+
                          '<td class="m-0 p-1">'+paymentbills[i]['Garbage']+'</td>'+
                          '<td class="m-0 p-1">'+paymentbills[i]['Waterbill']+'</td>'+
                          '<td class="m-0 p-1">'+paymentbills[i]['Others']+'</td>'+
                          '<td class="m-0 p-1">'+paymentbills[i]['TotalUsed']+'</td>'+
                          '<td class="m-0 p-1">'+paymentbills[i]['TotalPaid']+'</td>'+
                          '<td class="m-0 p-1">'+paymentbills[i]['Balance']+'</td>'+
                          '<td class="m-0 p-0" style="padding:0px;margin:2px;font-size: 9px;">'+paymentbills[i]['MessageStatus']+'</td>'+
                      '</tr>';
                }
                
                $('#loadpaymentbills').append(alreadysaved);
            }
        }
        //end of waterbill loop
    }
    return true;
  },
  error: function(file, response)
  {
    $(document).Toasts('create', {
      title: 'Uploading Error',
      class: 'bg-danger',
      position: 'bottomLeft',
      body: response
    })
    return false;
  }
  
};

$("#submitupdateuploadpayments").on('submit',(function(e){
    e.preventDefault();
    let savepiddoc=document.getElementById('savepid');
    let savemonthdoc=document.getElementById('savemonth');

    let savepid=savepiddoc.value;
    let savemonth=savemonthdoc.value;
    if (confirm("Do You Want to Save Selected Payments")) {
        
        $('#saveuploadpaymentload').html('<h4 class="text-center text-lime">Submitting... <img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading..."></h4>');
        $.ajax({
            url:'/properties/save/payments/uploadupdate',
            type:"POST",
            data:new FormData(document.getElementById('submitupdateuploadpayments')),
            processData:false,
            contentType:false,
            cache:false,
            success:function(data){
                if(data['error']){
                    $(document).Toasts('create', {
                        title: 'Uploading Payment Form',
                        class: 'bg-warning',
                        position: 'bottomLeft',
                        body: data['error']
                    })
                    
                    $('#saveuploadpaymentload').html(data['error']);
                }
                else{
                    $(document).Toasts('create', {
                        title: 'Saving Payment Form',
                        class: 'bg-success',
                        position: 'bottomLeft',
                        body: data['success']
                    })
                    
                    $('#saveuploadpaymentload').html(data['success']);
                    paymentbill_data();
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
                    title: 'Saving Error',
                    class: 'bg-warning',
                    position: 'bottomLeft',
                    body: errorMessage
                })
                $('#saveuploadpaymentload').html('<h6 class="text-center text-danger">Oops!! Sorry - ' + errorMessage+'</h6>');
                
            }
        });    
    };
  
}));
      
</script>
@endpush