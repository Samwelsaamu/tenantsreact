@extends('layouts.adminheader')
@section('title','Upload Waterbill | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-6">
    <h5 class="m-0">Upload Waterbill to @if($thisproperty!="")
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
@section('css')
    <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
@section('content')
<div class="" style="">
    <div class="row justify-content-center" style="">
        <div class="col-md-12" style="">
           <!-- sadads -->
           <div class="row">
                <!-- start of order variables like uploads,delivery instrunctions and payments -->
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
                                        <option value="/properties/update/waterbill/{{ $propertys->id }}/{{$watermonth}}" selected>{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                                    @else
                                        <option value="/properties/update/waterbill/{{ $propertys->id }}/{{$watermonth}}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                                    @endif
                                    @elseif($thisproperty!="" && $watermonth=="")
                                    @if($thisproperty->id==$propertys->id)
                                        <option value="/properties/update/waterbill/{{ $propertys->id }}/{{App\Http\Controllers\TenantController::getAddWaterMonthsThis()}}" selected>{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                                    @else
                                        <option value="/properties/update/waterbill/{{ $propertys->id }}/{{App\Http\Controllers\TenantController::getAddWaterMonthsThis()}}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                                    @endif
                                    @else
                                    <option value="/properties/update/waterbill/{{$propertys->id}}/{{App\Http\Controllers\TenantController::getAddWaterMonthsThis()}}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
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
                                        {{App\Http\Controllers\TenantController::getUploadWaterMonths($thisproperty->id,$watermonth)}}
                                    @elseif($thisproperty!="" && $watermonth=="")
                                        {{App\Http\Controllers\TenantController::getUploadWaterMonths($thisproperty->id,App\Http\Controllers\TenantController::getAddWaterMonthsThis())}}
                                    @endif
                                </select>
                            </div>

                            <div class="col-12  m-1 p-0">
                                @if($thisproperty!="" && $watermonth!="")
                                    <form role="form" class="form-horizontal" action="/properties/update/waterbill/preview" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="month" id="month" value="{{$watermonth}}">
                                        <input type="hidden" name="pid" id="pid" value="{{$thisproperty->id}}">
                                        <input type="hidden" name="pcode" id="pcode" value="{{$thisproperty->Plotcode}}">
                                        <div class="row m-0 p-0">
                                            <div class="col-sm-8 m-0 p-1"> 
                                                <input type="file" name="waterbillfile" id="waterbillfile"   accept=".xls,.xlsx" required >
                                            </div>

                                            <div class="col-sm-4 m-0 p-1">
                                            <button  class="btn btn-outline-info btn-xs text-sm m-0 p-1" name="submitplotbtn" type="submit" >Preview Waterbill</button>
                                            </div>

                                        </div>
                                        
                                    </form>
                                @endif
                            </div>
                        </div>
                        <!-- end of choose property month and upload option-->
                      </div>
                    </div>

                   
                    
                  

                  </div>
                  <!-- end of order variables like uploads,delivery instrunctions and payments -->
                  
                  <div class="col-md-8">
                  <!-- start of update waterbill -->
                  <div class="card mb-2">
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
                            
                            <span class="" style="font-size: 15px;">Upload Waterbill for / 
                                  @if($thisproperty!="")
                                  {{$thisproperty->Plotname}}
                                  @endif
                                  /
                                  @if($watermonth!="")
                                  {{$watermonth}}
                                  @endif

                                  <i style="width: 50px;font-size: 10px;padding: 2px;background-color:#FFFFFF;color: black;">Not Saved</i>
                                  <i style="width: 50px;font-size: 10px;padding: 2px;background-color:#FFEB06;color: black;">Saved</i>
                                  <i style="width: 50px;font-size: 10px;padding: 2px;background-color:red;color: black;">Vacant</i>
                            </span>

                            <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body text-xs">
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
                                    <table id="example1" class="table table-bordered table-striped"><thead>
                                        <tr style="color:white;background-color:#77B5ED;">
                                            <th class="m-0">Sno</th>
                                            <th class="m-0">House</th>
                                            <th class="m-0">Tenant</th>
                                            <th class="m-0">Month</th>
                                            <th class="m-0">Previous</th>
                                            <th class="m-0">Current</th>
                                            <th class="m-0">Cost</th>
                                            <th class="m-0">Units</th>
                                            <th class="m-0">Total</th>
                                        </tr></thead><tbody>
                                        @if($thisproperty!="" && $watermonth!="" && $output!="")
                                        <input type="hidden" id="savepid" name="pid" value="{{$thisproperty->id}}">
                                        <input type="hidden" id="savemonth" name="month" value="{{$watermonth}}">
                                        <span style="position:fixed;z-index:999999;color:red;font-size:20px;right:5%;">Selected Waterbill(<i class="badge selwaterbilltenants" id="selectedwaterbilltenants" style="font-size:30px;">0</i>)</span>
                                            @foreach($output as $waterpreview)
                                            @if($waterpreview['waterid']=="")
                                                @if($waterpreview['tid']!="Vacated")
                                                  <tr class="unwaterbillvaluesdiv" style="padding:5px;margin:2px;background-color:#FFFFFF;" data-id1="unstatement{{$loop->index+1}}">
                                                      <td><label class="col-lg-12" style="font-size:12px;">
                                                        <input type="checkbox" class="selectedwaterbilltenants" name="waterbillvalues[]" id="unstatement{{$loop->index+1}}"
                                                        value="{{$waterpreview['id']}}?{{$waterpreview['housename']}}?{{$waterpreview['tid']}}?{{$waterpreview['tenantname']}}?{{$waterpreview['previous']}}?{{$waterpreview['current']}}?{{$waterpreview['cost']}}?{{$waterpreview['units']}}?{{$waterpreview['total']}}?"> {{$loop->index+1}}</label></td>
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
                                            @if($waterbill!="")
                                                @foreach($waterbill as $waterpreview)
                                                    <tr style="padding:0px;margin:2px;background-color:#FFFFFF;">
                                                    <td>{{$loop->index+1}}</td>
                                                    <td>{{App\Models\Property::getHouseName($waterpreview['hid'])}}</td><td>{{App\Models\Property::checkCurrentTenantName($waterpreview['tid'])}}</td>
                                                    <td>{{$waterpreview['Month']}}</td>
                                                    <td>{{$waterpreview['Previous']}}</td>
                                                    <td>{{$waterpreview['Current']}}</td>
                                                    <td>{{$waterpreview['Cost']}}</td>
                                                    <td>{{$waterpreview['Units']}}</td>
                                                    <td>{{$waterpreview['Total']+$waterpreview['Total_OS']}}</td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <h4>No Waterbill for this Month</h4>
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
                  <!-- end update waterbill -->
                  </div>
                </div>
            <!-- sdadadsadsa -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables  & Plugins -->
  <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script type="text/javascript">
$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
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