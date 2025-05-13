@extends('layouts.adminheader')
@section('title','Update Payment | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-6">
    <h5 class="m-0">Update Payment to @if($thisproperty!="")
                            {{$thisproperty->Plotcode}}
                            @endif
                            /{{$watermonth}})
    </h5>
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
  <li class="breadcrumb-item active">Waterbill(
                                  @if($thisproperty!="")
                                    {{$thisproperty->Plotcode}}
                                    @endif
                                   /{{$watermonth}})</li>
</ol>
</div><!-- /.col -->
@endsection
@section('content')
<div class="" style="">
    <div class="row justify-content-center" style="">
        <div class="col-md-12" style="">
            <div class="card" style="border: none;">
                <div class="card-header" style="padding-top: 10px;padding: 2px;">
                    
                    <div class="row" style="padding: 0px;margin: 0px;">
                      <div class="col-sm-3">
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


                    <div class="col-sm-3">
                      <select class="form-control select2" name="allproperties" onchange="location=this.value;" style="width: 100%;">
                        <option value="">Choose Month</option>
                          @if($thisproperty!="" && $watermonth!="")
                            {{App\Http\Controllers\TenantController::getUpdateBillsMonths($thisproperty->id,$watermonth)}}
                          @elseif($thisproperty!="" && $watermonth=="")
                            {{App\Http\Controllers\TenantController::getUpdateBillsMonths($thisproperty->id,App\Http\Controllers\TenantController::getAddWaterMonthsThis())}}
                          @endif
                      </select>
                    </div>

                    <div class="col-sm-6" style="padding: 0px;margin: 0px;">
                      @if($thisproperty!="" && $watermonth!="")
                      <form role="form" class="form-horizontal" action="/properties/update/bills/save" method="post" enctype="multipart/form-data">
                         @csrf
                          <input type="hidden" name="month" id="month" value="{{$watermonth}}">
                          <input type="hidden" name="id" id="id" value="{{$thisproperty->id}}">
                          <div class="row" style="padding: 0px;margin: 0px;">
                            <div class="col-sm-4" style="padding: 5px;margin: 0px;">
                              <button  class="btn btn-info btn-small " name="submitplotbtn" type="submit" >Click to Update Bills</button>
                            </div>

                          </div>
                          
                      </form>
                      @endif
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
                        
                        <span class="" style="font-size: 15px;">Update Payment for / 
                                    @if($thisproperty!="")
                                      {{$thisproperty->Plotname}}
                                    @endif
                                    /
                                    @if($watermonth!="")
                                      {{$watermonth}}
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

                              <form role="form" class="form-horizontal" method="POST" action="/properties/save/waterbill/upload">
                                @csrf
                                <div class="row">
                                <div class="col-sm-12">
                                  <table border="1" class="table table-hover" id="example"><thead>
                                    <tr style="color:white;background-color:#77B5ED;">
                                        <th>Sno</th>
                                        <th>House</th>
                                        <th>Tenant</th>
                                        <th>Excess</th>
                                        <th>Arrears</th>
                                        <th>Rent</th>
                                        <th>Garbage</th>
                                        <th>Waterbill</th>
                                        <th>Total</th>
                                      </tr></thead><tbody>
                                      @if($thisproperty!="" && $watermonth!="" && $output!="")
                                      <input type="hidden" name="pid" value="{{$thisproperty->id}}">
                                      <input type="hidden" name="month" value="{{$watermonth}}">
                                      <span style="position:fixed;z-index:999999;color:red;font-size:20px;right:5%;">Selected Waterbill(<i class="badge selwaterbilltenants" id="selectedwaterbilltenants" style="font-size:30px;">0</i>)</span>
                                        @foreach($output as $waterpreview)
                                          @if($waterpreview['waterid']=="")
                                            @if($waterpreview['tid']!="Vacated")
                                              <tr class="unwaterbillvaluesdiv" style="padding:5px;margin:2px;background-color:#FFFFFF;" data-id1="unstatement{{$loop->index+1}}">
                                                <td><label class="col-lg-12" style="font-size:12px;"><input type="checkbox" class="selectedwaterbilltenants" name="waterbillvalues[]" id="unstatement{{$loop->index+1}}" value="{{$waterpreview['id']}}?{{$waterpreview['housename']}}?{{$waterpreview['tid']}}?{{$waterpreview['tenantname']}}?{{$waterpreview['previous']}}?{{$waterpreview['current']}}?{{$waterpreview['cost']}}?{{$waterpreview['units']}}?{{$waterpreview['total']}}?"> {{$loop->index+1}}</label></td>
                                                <td>{{$waterpreview['housename']}}</td>
                                                <td>{{$waterpreview['tenantname']}}</td>
                                                <td>{{$waterpreview['month']}}</td>
                                                  <td>{{$waterpreview['previous']}}</td>
                                                  <td>{{$waterpreview['current']}}</td>
                                                  <td>{{$waterpreview['cost']}}</td>
                                                  <td>{{$waterpreview['units']}}</td>
                                                  <td>{{$waterpreview['total']}}</td>
                                              </tr>
                                              @else
                                                <tr style="padding:0px;margin:2px;background-color:red;">
                                                  <td>{{$loop->index+1}}</td>
                                                  <td>{{$waterpreview['housename']}}</td>
                                                  <td>{{$waterpreview['tenantname']}}</td>
                                                  <td>{{$waterpreview['month']}}</td>
                                                    <td>{{$waterpreview['previous']}}</td>
                                                    <td>{{$waterpreview['current']}}</td>
                                                    <td>{{$waterpreview['cost']}}</td>
                                                    <td>{{$waterpreview['units']}}</td>
                                                    <td>{{$waterpreview['total']}}</td>
                                                </tr>
                                              @endif
                                          @else
                                            <tr style="padding:0px;margin:2px;background-color:#FFEB06;">
                                            <td>{{$loop->index+1}}</td>
                                            <td>{{$waterpreview['housename']}}</td>
                                            <td>{{$waterpreview['tenantname']}}</td>
                                            <td>{{$waterpreview['month']}}</td>
                                              <td>{{$waterpreview['previous']}}</td>
                                              <td>{{$waterpreview['current']}}</td>
                                              <td>{{$waterpreview['cost']}}</td>
                                              <td>{{$waterpreview['units']}}</td>
                                              <td>{{$waterpreview['total']}}</td>
                                          </tr>
                                          @endif
                                        @endforeach

                                      @else
                                        <h4>Choose Property to Update Rent and Garbage for selected Month</h4>

                                          @if($paymentbills!="")
                                            @foreach($paymentbills as $waterpreview)
                                                <tr style="padding:0px;margin:2px;background-color:#FFFFFF;font-size: 11px;">
                                                  <td>{{$loop->index+1}}</td>
                                                  <td>{{App\Models\Property::getHouseName($waterpreview['hid'])}}</td><td>{{App\Models\Property::checkCurrentTenantName($waterpreview['Tenant'])}}</td>
                                                  <td>{{$waterpreview['Excess']}}</td>
                                                  <td>{{$waterpreview['Arrears']}}</td>
                                                  <td>{{$waterpreview['Rent']}}</td>
                                                  <td>{{$waterpreview['Garbage']}}</td>
                                                  <td>{{$waterpreview['Waterbill']}}</td>
                                                  <td>{{App\Models\Property::PaymentTotals($waterpreview['Tenant'],$waterpreview['hid'],$watermonth)}}</td>
                                              </tr>
                                            @endforeach
                                          @else
                                            <h4>No Payment Update for this Month for this Month</h4>
                                          @endif
                                      @endif
                                    </tbody>
                                  </table>
                                </div>
                                @if($thisproperty!="" && $watermonth!="" && $output!="")
                                <div style="color: white;margin-left: 2%;">
                                  <span style="color:red;font-size:20px;">Selected Waterbill(<i class="badge selwaterbilltenants" id="selectedwaterbilltenants" style="font-size:30px;">0</i>)</span>
                                  <button class="btn btn-success float-right" style="padding: 5px;">Save Selected Waterbill</button>
                                </div>

                                @endif
                                </div>
                              </form>
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
<script type="text/javascript">
$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
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
      
</script>
@endpush