@extends('layouts.adminheader')
@section('title','Update Waterbill | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-6">
    <h5 class="m-0">Update Waterbill to @if($thisproperty!="")
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
  <!-- dropzonejs -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/dropzone/basic.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/dropzone/dropzone.css') }}">
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
        <a href="/properties/update/waterbill" class="nav-link active">
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
            <!-- sadads -->
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
                                        {{App\Http\Controllers\TenantController::getUpdateWaterMonths($thisproperty->id,$watermonth)}}
                                    @elseif($thisproperty!="" && $watermonth=="")
                                        {{App\Http\Controllers\TenantController::getUpdateWaterMonths($thisproperty->id,App\Http\Controllers\TenantController::getAddWaterMonthsThis())}}
                                    @endif
                                </select>
                            </div>

                            <div class="col-12  m-1 p-0">
                                @if($thisproperty!="" && $watermonth!="")
                                    <div class="" style="">
                                    <form role="form" class="form-horizontal" method="POST" action="/properties/update/waterbill/preview" enctype="multipart/form-data" style="">
                                        @csrf
                                        <div class="row m-0 p-0" style="">
                                        <div class="col-12 m-0 p-0" style="">
                                            <form></form>
                                            <form method="post" action="/properties/update/waterbill/preview" class="dropzone" id="dropzoneForm" style="border: 4px dotted blue;">
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

                            <div class="col-12 text-center m-1 p-0">
                              @if($thisproperty!="" && $watermonth!="")
                                <a target="_blank" href="/properties/download/Reports/Waterbill/{{$thisproperty->id}}/{{$watermonth}}" class="btn btn-info p-1"><i class="fa fa-download"></i></a>
                                <a target="_blank" href="/properties/download/Reports/Waterbill/{{$thisproperty->id}}/Now" class="btn btn-primary p-1"><i class="fa fa-download"></i> {{date('Y')}}</a>
                                <a target="_blank" href="/properties/download/Reports/Waterbill/{{$thisproperty->id}}/Previous" class="btn btn-primary p-1"><i class="fa fa-download"></i> {{date('Y')-1}}</a>
                              @endif
                            </div>

                            <div class="col-12 text-center mx-auto">
                              <span class="text-center m-1">
                              @if($watermonth!="")
                                <a target="_blank" href="/properties/download/Reports/Waterbill/All/{{$watermonth}}" class="btn btn-outline-success p-1 mt-1"><i class="fa fa-download"></i> All Properties ({{$watermonth}}) </a>
                              @endif
                             
                              </span>
                            </div>

                            

                        </div>
                        <!-- end of choose property month and upload option-->
                      </div>
                    </div>

                </div>
                    <!-- end of order variables like uploads,delivery instrunctions and payments -->
                  
                <div class="col-md-8">
                    <!-- start of update waterbill -->
                    <div class="">
                        <div class="" style="">
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
                        
                                <div class="col-sm-12" style="">
                                
                                    <div class="card direct-chat direct-chat-primary" style="padding: 2px;margin: 5px;">
                                        <div class="card-header bg-white" style="padding: 4px;">
                                            
                                            <span class="mx-auto" style="font-size: 15px;">
                                                Upload Waterbill for / 
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
                                            </div>
                                            <div class="float-right">
                                            <span class="bg-white text-black p-1 text-xs" style="width: 50px;font-size: 10px;padding: 3px;background-color:#FFFFFF;color: black;">Not Saved</span>
                                            <span class="bg-warning text-black p-1 text-xs" style="width: 50px;font-size: 10px;padding: 3px;background-color:#FFEB06;color: black;">Saved</span>
                                            <span class="bg-danger text-white p-1 text-xs" style="width: 50px;font-size: 10px;padding: 3px;background-color:red;color: black;">Vacant</span>
                                            <span class="bg-success text-white p-1 text-xs" style="width: 50px;font-size: 10px;padding: 2px;background-color:green;color: white;">Changed Value</span>
                                            <span class="bg-purple text-white p-1 text-xs" style="width: 50px;font-size: 10px;padding: 2px;background-color:green;color: white;">Previous Match</span>
                                            </div>
                                            <span class="ml-2 mr-2 text-lg " style="color:red;">(<i class="fa fa-check-square text-primary text-xs"></i><i class="badge selwaterbilltenants" id="selectedwaterbilltenants">0</i>)</span>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body text-xs">
                                            <div class="row">
                                            <div class="col-sm-12">
                                            <div class="card-body bg-white" style="padding: 5px;margin: 3px;">
                                            <div class="card-body">
                                                <div class="direct-chat-msg right">

                                                <form role="form" class="form-horizontal submitupdateuploadwaterbill" method="POST" name="saveuploadwaterbill"
                                                action="/properties/save/waterbill/uploadupdate" id="submitupdateuploadwaterbill">
                                                    @csrf
                                                    @if($thisproperty!="" && $watermonth!="")
                                                    <input type="hidden" id="savepid" name="savepid" value="{{$thisproperty->id}}">
                                                    <input type="hidden" id="savemonth" name="savemonth" value="{{$watermonth}}">
                                                    @endif
                                                    <div class="row">
                                                    <div class="col-sm-12" style="overflow-x:auto;max-height:420px;overflow-y:auto;">
                                                    <table id="example1" class="table table-bordered table-striped text-xs" style="">
                                                        <thead style="">
                                                        <tr style="color:white;color:#77B5ED;">
                                                            <th class="m-0 p-1">Sno</th>
                                                            <th class="m-0 p-1">House</th>
                                                            <th class="m-0 p-1">Tenant Name</th>
                                                            <th class="m-0 p-1">Month</th>
                                                            <th class="m-0 p-1">Prev</th>
                                                            <th class="m-0 p-1">Cur</th>
                                                            <th class="m-0 p-1">Cost</th>
                                                            <th class="m-0 p-1">All</th>
                                                            <th class="m-0 p-1">Total(<i class="text-xs">Kshs</i>)</th>
                                                            <th class="m-0 p-1 bg-success text-white">Change</th>
                                                            <th class="m-0 p-1 bg-purple text-white">Previous</th>
                                                        </tr></thead>
                                                        <tbody style="overflow-x:auto;" id="updatedwaterbill">
                                                        
                                                        </tbody>
                                                        
                                                        
                                                    </table>
                                                    </div>
                                                    
                                                    </div>
                                                    <div id="saveupdatewaterbillload"></div>
                                                    <button class="btn btn-success float-right" id="saveupdatewaterbill" style="padding: 5px;display:none;">Save Selected Waterbill</button>
                                                </form>
                                                </div>
                                            </div>
                                            </div>
                                            </div>
                                        
                                        </div>

                                    </div>
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
  <!-- dropzone -->
<script src="{{ asset('assets/plugins/dropzone/dropzone.js') }}"></script>
<script type="text/javascript">
$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

  

    waterbill_functs();
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

$(document).on('click','.unwaterbillvaluesupdatediv',(function(e){
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
            title: 'Uploading Waterbill Error',
            class: 'bg-warning',
            position: 'bottomLeft',
            subtitle: 'Error',
            body: response['error']
        })
    }
    else{
        $(document).Toasts('create', {
            title: 'Previewing Waterbill',
            class: 'bg-success',
            position: 'bottomLeft',
            subtitle:response['thisproperty']['Plotname'],
            body: response['success']
        })
        //first get all data for waterbill
        var thisproperty=response['thisproperty'];
        var watermonth=response['watermonth'];
        var waterbill=response['output'];

        $('#updatedwaterbill').html('');
        let alreadysaved="";
        let validwaterbill="";
        let vacatedhousebills="";
        let savepiddoc=document.getElementById('savepid');
        let savemonthdoc=document.getElementById('savemonth');
        savepiddoc.value=response['thisproperty'].id;
        savemonthdoc.value=watermonth;
        //loop through waterbill
        for (var i = 0; i < waterbill.length; i++) {
            //check if waterbill is loaded from Saved Information
            if(waterbill[i]['waterid']==null){
                //loaded from preview file
                if(waterbill[i]['tid']!="Vacated"){
                    if(waterbill[i]['prevmatches']=='Ok'){
                    validwaterbill='<tr class="unwaterbillvaluesdiv m-0 p-0" style="background-color:#FFFFFF;" data-id1="unstatement'+(i+1)+'">'+
                                    '<td class="m-0 p-2"><label class="col-lg-12 m-0 p-0" style="font-size:12px;">'+
                                        '<input type="checkbox" class="selectedwaterbilltenants" name="waterbillvalues[]"'+
                                        'id="unstatement'+(i+1)+'"'+
                                        'value="'+waterbill[i]['id']+'?'+waterbill[i]['housename']+'?'+waterbill[i]['tid']+'?'+waterbill[i]['tenantname']+'?'+waterbill[i]['previous']+'?'+waterbill[i]['current']+'?'+waterbill[i]['cost']+'?'+waterbill[i]['units']+'?'+waterbill[i]['total']+'?"> '+(i+1)+'</label></td>'+
                                    '<td class="m-0 p-2">'+waterbill[i]['housename']+'</td>'+
                                    '<td class="m-0 p-2">'+waterbill[i]['tenantname']+'</td>'+
                                    '<td class="m-0 p-2">'+waterbill[i]['month']+'</td>'+
                                    '<td class="m-0 p-2">'+waterbill[i]['previous']+'</td>'+
                                    '<td class="m-0 p-2">'+waterbill[i]['current']+'</td>'+
                                    '<td class="m-0 p-2">'+waterbill[i]['cost']+'</td>'+
                                    '<td class="m-0 p-2">'+waterbill[i]['units']+'</td>'+
                                    '<td class="m-0 p-2">'+waterbill[i]['total']+'</td>'+
                                    '<td class="m-0 p-2 bg-success text-white">No</td>'+
                                    '<td class="m-0 p-2 bg-purple text-white">'+waterbill[i]['prevmatches']+'</td>'+
                                '</tr>';
                    }
                    else{
                        validwaterbill='<tr class="unwaterbillvaluesdiv m-0 p-0" style="background-color:#FFFFFF;" data-id1="unstatementupdate'+(i+1)+'">'+
                                        '<td class="m-0 p-2">'+(i+1)+'</td>'+
                                        '<td class="m-0 p-2">'+waterbill[i]['housename']+'</td>'+
                                        '<td class="m-0 p-2">'+waterbill[i]['tenantname']+'</td>'+
                                        '<td class="m-0 p-2">'+waterbill[i]['month']+'</td>'+
                                        '<td class="m-0 p-2">'+waterbill[i]['previous']+'</td>'+
                                        '<td class="m-0 p-2">'+waterbill[i]['current']+'</td>'+
                                        '<td class="m-0 p-2">'+waterbill[i]['cost']+'</td>'+
                                        '<td class="m-0 p-2">'+waterbill[i]['units']+'</td>'+
                                        '<td class="m-0 p-2">'+waterbill[i]['total']+'</td>'+
                                        '<td class="m-0 p-2 bg-success text-white">No</td>'+
                                        '<td class="m-0 p-2 bg-purple text-white">'+waterbill[i]['prevmatches']+'</td>'+
                                    '</tr>';
                    }
                    $('#updatedwaterbill').append(validwaterbill);
                }
                else{
                    vacatedhousebills='<tr class="m-0 p-0" style="background-color:red;">'+
                                    '<td class="m-0 p-2">'+(i+1)+'</td>'+
                                    '<td class="m-0 p-2">'+waterbill[i]['housename']+'</td>'+
                                    '<td class="m-0 p-2">'+waterbill[i]['tenantname']+'</td>'+
                                    '<td class="m-0 p-2">'+waterbill[i]['month']+'</td>'+
                                    '<td class="m-0 p-2">'+waterbill[i]['previous']+'</td>'+
                                    '<td class="m-0 p-2">'+waterbill[i]['current']+'</td>'+
                                    '<td class="m-0 p-2">'+waterbill[i]['cost']+'</td>'+
                                    '<td class="m-0 p-2">'+waterbill[i]['units']+'</td>'+
                                    '<td class="m-0 p-2">'+waterbill[i]['total']+'</td>'+
                                    '<td class="m-0 p-2 bg-success text-white">None</td>'+
                                    '<td class="m-0 p-2 bg-purple text-white">None</td>'+
                                '</tr>';
                    $('#updatedwaterbill').append(vacatedhousebills);
                }
            }
            else{
                // loaded from database
                // prevmatches
                // let prevmatches=(waterbill[i]['prevmatches']=='No Change')?waterbill[i]['prevmatches']+':'+waterbill[i]['saved_current']:waterbill[i]['saved'];
                let saved=(waterbill[i]['saved']=='Saved')?waterbill[i]['saved_previous']+':'+waterbill[i]['saved_current']:waterbill[i]['saved'];
                alreadysaved='<tr class="unwaterbillvaluesupdatediv m-0 p-0" style="background-color:#FFEB06;" data-id1="unstatementupdate'+(i+1)+'">'+
                                '<td class="m-0 p-2"><label class="col-lg-12 m-0 p-0" style="font-size:12px;">'+
                                    '<input type="checkbox" class="selectedwaterbilltenants" name="waterbillvaluesupdate[]"'+
                                    'id="unstatementupdate'+(i+1)+'"'+
                                    'value="'+waterbill[i]['id']+'?'+waterbill[i]['housename']+'?'+waterbill[i]['tid']+'?'+waterbill[i]['tenantname']+'?'+waterbill[i]['previous']+'?'+waterbill[i]['current']+'?'+waterbill[i]['cost']+'?'+waterbill[i]['units']+'?'+waterbill[i]['total']+'?'+waterbill[i]['waterid']+'?"> '+(i+1)+'</label></td>'+
                                '<td class="m-0 p-2">'+waterbill[i]['housename']+'</td>'+
                                '<td class="m-0 p-2">'+waterbill[i]['tenantname']+'</td>'+
                                '<td class="m-0 p-2">'+waterbill[i]['month']+'</td>'+
                                '<td class="m-0 p-2">'+waterbill[i]['previous']+'</td>'+
                                '<td class="m-0 p-2">'+waterbill[i]['current']+'</td>'+
                                '<td class="m-0 p-2">'+waterbill[i]['cost']+'</td>'+
                                '<td class="m-0 p-2">'+waterbill[i]['units']+'</td>'+
                                '<td class="m-0 p-2">'+waterbill[i]['total']+'</td>'+
                                '<td class="m-0 p-2 bg-success text-white">'+saved+'</td>'+
                                '<td class="m-0 p-2 bg-purple text-white">'+waterbill[i]['prevmatches']+'</td>'+
                            '</tr>';
                $('#updatedwaterbill').append(alreadysaved);
            }
        }
        
        $('#saveupdatewaterbill').show();
        //end of waterbill loop
    }
    return true;
  },
  error: function(file, response)
  {
    $(document).Toasts('create', {
      title: 'Uploading Files',
      class: 'bg-warning',
      position: 'bottomLeft',
      body: response['error']
    })
    return false;
  }
  
};

$("#submitupdateuploadwaterbill").on('submit',(function(e){
    e.preventDefault();
    let savepiddoc=document.getElementById('savepid');
    let savemonthdoc=document.getElementById('savemonth');

    let savepid=savepiddoc.value;
    let savemonth=savemonthdoc.value;
    if (confirm("Do You Want to Save Selected Waterbill")) {
        $('#saveupdatewaterbill').hide();
        $('#saveupdatewaterbillload').html('<h4 class="text-center text-lime">Submitting... <img src="{{ asset('assets/img/spinner.gif') }}" class="img-circle" alt="loading..."></h4>');
        $.ajax({
            url:'/properties/save/waterbill/uploadupdate',
            type:"POST",
            data:new FormData(document.getElementById('submitupdateuploadwaterbill')),
            processData:false,
            contentType:false,
            cache:false,
            success:function(data){
                if(data['error']){
                    $(document).Toasts('create', {
                        title: 'Uploading Waterbill Error',
                        class: 'bg-warning',
                        position: 'bottomLeft',
                        subtitle: 'Some Error',
                        body: data['error']
                    })
                    $('#saveupdatewaterbill').show();
                    $('#saveupdatewaterbillload').html(data['error']);
                }
                else{
                    $(document).Toasts('create', {
                        title: 'Saving Waterbill',
                        class: 'bg-success',
                        position: 'bottomLeft',
                        subtitle:'Saved',
                        body: data['success']
                    })
                    $('#saveupdatewaterbill').hide();
                    $('#saveupdatewaterbillload').html(data['success']);
                    waterbill_functs();
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
                    title: 'Saving Waterbill',
                    class: 'bg-warning',
                    position: 'bottomLeft',
                    subtitle:'Error',
                    body: errorMessage
                })
                $('#saveupdatewaterbillload').html('<h6 class="text-center text-danger">Oops!! Sorry - ' + errorMessage+'</h6>');
                $('#saveupdatewaterbill').show();
            }
        });    
    };
  
}));

function waterbill_functs(){
  waterbill_data();
}

function waterbill_data(){
    let savepiddoc=document.getElementById('savepid');
    let savemonthdoc=document.getElementById('savemonth');
    let propertyid=savepiddoc.value;
    let watermonth=savemonthdoc.value;
  $.ajax({
    url:"/properties/updateload/waterbill/"+propertyid+"/"+watermonth,
      method:"GET",
      success:function(data){
        
            // $(document).Toasts('create', {
            //     title: 'Loading Waterbill',
            //     class: 'bg-success',
            //     position: 'bottomLeft',
            //     subtitle:data['thisproperty']['Plotname'],
            //     body: data['success']
            // })
            //first get all data for waterbill
            var thisproperty=data['thisproperty'];
            var watermonth=data['watermonth'];
            var waterbill=data['output'];
            $('#updatedwaterbill').html('');
            let alreadysaved="";
            let validwaterbill="";
            let vacatedhousebills="";
            let savepiddoc=document.getElementById('savepid');
            let savemonthdoc=document.getElementById('savemonth');
            savepiddoc.value=data['thisproperty'].id;
            savemonthdoc.value=watermonth;
            //loop through waterbill
            for (var i = 0; i < waterbill.length; i++) {
                //check if waterbill is loaded from Saved Information
                if(waterbill[i]['waterid']==null){
                    //loaded from preview file
                    if(waterbill[i]['tid']!="Vacated"){
                        validwaterbill='<tr class="unwaterbillvaluesdiv m-0 p-0" style="background-color:#FFFFFF;" data-id1="unstatement'+(i+1)+'">'+
                                        '<td class="m-0 p-2"><label class="col-lg-12 m-0 p-0" style="font-size:12px;">'+
                                            '<input type="checkbox" class="selectedwaterbilltenants" name="waterbillvalues[]"'+
                                            'id="unstatement'+(i+1)+'"'+
                                            'value="'+waterbill[i]['id']+'?'+waterbill[i]['housename']+'?'+waterbill[i]['tid']+'?'+waterbill[i]['tenantname']+'?'+waterbill[i]['previous']+'?'+waterbill[i]['current']+'?'+waterbill[i]['cost']+'?'+waterbill[i]['units']+'?'+waterbill[i]['total']+'?"> '+(i+1)+'</label></td>'+
                                        '<td class="m-0 p-2">'+waterbill[i]['housename']+'</td>'+
                                        '<td class="m-0 p-2">'+waterbill[i]['tenantname']+'</td>'+
                                        '<td class="m-0 p-2">'+waterbill[i]['month']+'</td>'+
                                        '<td class="m-0 p-2">'+waterbill[i]['previous']+'</td>'+
                                        '<td class="m-0 p-2">'+waterbill[i]['current']+'</td>'+
                                        '<td class="m-0 p-2">'+waterbill[i]['cost']+'</td>'+
                                        '<td class="m-0 p-2">'+waterbill[i]['units']+'</td>'+
                                        '<td class="m-0 p-2">'+waterbill[i]['total']+'</td>'+
                                        '<td class="m-0 p-2 bg-success text-white">No</td>'+
                                        '<td class="m-0 p-2 bg-purple text-white">OK</td>'+
                                    '</tr>';
                        $('#updatedwaterbill').append(validwaterbill);
                    }
                    else{
                        vacatedhousebills='<tr class="m-0 p-0" style="background-color:red;">'+
                                        '<td class="m-0 p-2">'+(i+1)+'</td>'+
                                        '<td class="m-0 p-2">'+waterbill[i]['housename']+'</td>'+
                                        '<td class="m-0 p-2">'+waterbill[i]['tenantname']+'</td>'+
                                        '<td class="m-0 p-2">'+waterbill[i]['month']+'</td>'+
                                        '<td class="m-0 p-2">'+waterbill[i]['previous']+'</td>'+
                                        '<td class="m-0 p-2">'+waterbill[i]['current']+'</td>'+
                                        '<td class="m-0 p-2">'+waterbill[i]['cost']+'</td>'+
                                        '<td class="m-0 p-2">'+waterbill[i]['units']+'</td>'+
                                        '<td class="m-0 p-2">'+waterbill[i]['total']+'</td>'+
                                        '<td class="m-0 p-2 bg-success text-white">No</td>'+
                                        '<td class="m-0 p-2 bg-purple text-white">OK</td>'+
                                    '</tr>';
                        $('#updatedwaterbill').append(vacatedhousebills);
                    }
                }
                else{
                    // loaded from database
                    alreadysaved='<tr style="padding:0px;margin:2px;background-color:#FFFFFF;">'+
                                '<td class="m-0 p-2">'+(i+1)+'</td>'+
                                '<td class="m-0 p-2">'+waterbill[i]['housename']+'</td>'+
                                '<td class="m-0 p-2">'+waterbill[i]['tenantname']+'</td>'+
                                '<td class="m-0 p-2">'+waterbill[i]['month']+'</td>'+
                                '<td class="m-0 p-2">'+waterbill[i]['previous']+'</td>'+
                                '<td class="m-0 p-2">'+waterbill[i]['current']+'</td>'+
                                '<td class="m-0 p-2">'+waterbill[i]['cost']+'</td>'+
                                '<td class="m-0 p-2">'+waterbill[i]['units']+'</td>'+
                                '<td class="m-0 p-2">'+waterbill[i]['total']+'</td>'+
                                '<td class="m-0 p-2 bg-success text-white">No</td>'+
                                '<td class="m-0 p-2 bg-purple text-white">OK</td>'+
                            '</tr>';
                    $('#updatedwaterbill').append(alreadysaved);
                }
            }
            
            //end of waterbill loop
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
            title: 'Loading Waterbill',
            class: 'bg-warning',
            position: 'bottomLeft',
            subtitle:'Error',
            body: errorMessage
        })
    }
  });
}
      
</script>
@endpush