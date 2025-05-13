@extends('layouts.adminheader')
@section('title','View Other Messages | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-6">
    <h5 class="m-0">View Other Messages to @if($thisproperty!="")
                            {{$thisproperty->Plotcode}}
                            @endif
                            
                                 /@if($thishouse!="")
                                      {{$thishouse->Housename}}
                                    @endif)
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
  <li class="breadcrumb-item active">Other(
                                  @if($thisproperty!="")
                                    {{$thisproperty->Plotcode}}
                                    @endif
                                   
                                 /@if($thishouse!="")
                                      {{$thishouse->Housename}}
                                    @endif)</li>
</ol>
</div><!-- /.col -->
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
        <a href="/properties/view/messages/others" class="nav-link active">
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
                      <div class="col-sm-4">
                        <select class="form-control select2" name="allproperties" onchange="location=this.value;" style="width: 100%;">
                          <option value="">Select Property</option>
                          @forelse($propertyinfo as $propertys)
                            @if($thisproperty!="")
                              @if($thisproperty->id==$propertys->id)
                                <option value="/properties/view/messages/others/{{ $propertys->id }}" selected>{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                              @else
                                <option value="/properties/view/messages/others/{{ $propertys->id }}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                              @endif
                            @elseif($thisproperty!="" && $watermonth=="")
                              @if($thisproperty->id==$propertys->id)
                                <option value="/properties/view/messages/others/{{ $propertys->id }}" selected>{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                              @else
                                <option value="/properties/view/messages/others/{{ $propertys->id }}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                              @endif
                            @else
                              <option value="/properties/view/messages/others/{{$propertys->id}}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                            @endif
                          @empty
                            <option>No Property Found</option>
                          @endforelse
                        </select>
                      </div>

                    <div class="col-sm-4">
                      <select class="form-control select2" name="allproperties" onchange="location=this.value;" style="width: 100%;">
                        <option value="">Choose House</option>
                            @forelse($housesinfo as $houses)
                              @if($thishouse!="")
                                @if($thishouse->id==$houses->id)
                                  <option value="/properties/view/messages/others/{{ $houses->Plot }}/{{$houses->id}}" selected>{{ $loop->index+1 }}. {{ $houses->Housename }} ({{ $houses->Status }})</option>
                                @else
                                  <option value="/properties/view/messages/others/{{ $houses->Plot }}/{{$houses->id}}">{{ $loop->index+1 }}. {{ $houses->Housename }} ({{ $houses->Status }})</option>
                                @endif
                              @else
                                <option value="/properties/view/messages/others/{{ $houses->Plot }}/{{$houses->id}}">{{ $loop->index+1 }}. {{ $houses->Housename }} ({{ $houses->Status }})</option>
                              @endif
                            @empty
                              <option value="">No House Found</option>
                            @endforelse
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
                        
                        <span class="" style="font-size: 15px;">View Other Messages / 
                                    @if($thisproperty!="")
                                      {{$thisproperty->Plotname}}
                                    @endif
                                    @if($thishouse!="")
                                      /{{$thishouse->Housename}}
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
                            <div class="row">
                              @if($waterbillmessage!="")
                                @forelse($waterbillmessage as $thiswatermessage)
                                  <div class="col-sm-6">
                                  <div class="card-body bg-primary" style="padding: 5px;margin: 3px;">
                                    <span class="text-light"><b>{{ App\Http\Controllers\TenantController::TenantNames($thiswatermessage['tid']) }}, {{ App\Models\Property::getHouseName($thiswatermessage['hid']) }} {{ App\Http\Controllers\TenantController::TenantPhone($thiswatermessage['tid']) }} </b> 
                                        <i class="float-right">{{ $thiswatermessage['created_at'] }}</i>
                                      
                                    </span>
                                    <div class="card-body">
                                      <div class="direct-chat-msg right">
                                        <form role="form" class="form-horizontal" method="POST" action="/properties/send/messages/singlewater">
                                              @csrf
                                              <div class="col-sm-12">
                                                  <div class="" style="text-align: center;">
                                                    <div class="">
                                                      <input type="hidden" name="pid" value="{{$thisproperty->id}}">
                                                      <input type="hidden" name="id" value="{{$thiswatermessage['hid']}}">
                                                      <input type="hidden" name="phone" value="{{App\Http\Controllers\TenantController::TenantPhone($thiswatermessage['tid'])}}">
                                                      <input type="hidden" name="message" value="{{$thiswatermessage['Message']}}">
                                                      <input type="hidden" name="resend" value="Resend">
                                                    </div>
                                                  </div>
                                              </div>
                                                <!-- <button class="btn btn-warning float-right" style="padding: 2px;font-size: 12px;">Resend</button> -->
                                          </form>
                                        
                                        <!-- /.direct-chat-img -->
                                        
                                          <div class="direct-chat-text bg-warning">
                                            {{$thiswatermessage['Message']}}
                                          </div>
                                        <!-- /.direct-chat-text -->
                                      </div>
                                    </div>
                                  </div>
                                  </div>
                                @empty
                                  <div class="col-md-12">
                                    <h4>No Water Messages sent for(
                                    @if($thisproperty!="")
                                      {{$thisproperty->Plotname}}
                                    @endif

                                    @if($thishouse!="")
                                      /{{$thishouse->Housename}}
                                    @endif)</h4>
                                  </div>
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
<script type="text/javascript">
$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
});

// $(document).on('change','.select2',(function(e){
//   $.ajax({
//       url:"/properties/search/tenants",
//       type:"POST",
//       data:{onlineusers},
//       dataType:"text",
//     success:function(data){
//       $('#appbalance').html(data);
//           $('#appbalancemin').html(data);
//     },
//     error: function(xhr, status, error){
//       var errorMessage = xhr.status + ': ' + xhr.statusText
//       if (errorMessage=="0: error") {
//         errorMessage="No Connection" 
//         }
//      $("#appbalance").html('Oops!! - ' + errorMessage);
//    }
//   });
// }));
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