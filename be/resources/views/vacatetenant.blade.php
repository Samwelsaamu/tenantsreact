@extends('layouts.adminheader')
@section('title','Vacate Tenant | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-4">
    <h6 class="m-0">Vacate: {{ $thistenant->Fname }} {{ $thistenant->Oname }} :{{ $thishouse->Housename }}</h6>
</div><!-- /.col -->
<div class="col-sm-8">
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
  <li class="breadcrumb-item"><a href="/properties">Properties (@forelse($propertyinfo as $property)
                        {{$loop->count}}
                            @break
                        @empty
                            0
                        @endforelse
                        )</a></li>
  <li class="breadcrumb-item"><a href="/properties/tenants">Tenants (@forelse($tenantsinfo as $tenants)
                            {{$loop->count}}
                            @break
                        @empty
                            0
                        @endforelse
                        )</a></li>
  <li class="breadcrumb-item active">Vacate: {{ $thishouse->Housename }}</li>
</ol>
</div><!-- /.col -->
@endsection
@section('content')
<div class="" style="">
    <div class="row justify-content-center" style="">
        <div class="col-md-12" style="">
            <div class="card" style="border: none;">
                <div class="card-header" style="padding-top: 10px;">
                    <h4 style="text-align: center;">
                        Vacate {{ $thistenant->Fname }} {{ $thistenant->Oname }} : {{ $thishouse->Housename }}
                    </h4>
                    <div class="form-group">
                      <select class="form-control select2" name="alltenants" onchange="location=this.value;" style="width: 100%;">
                        <option value="">Search Tenant</option>
                        @forelse($tenantsinfo as $tenants)
                        @if($thistenant->id==$tenants->id)
                          <option value="/properties/search/tenants/{{$tenants->id}}" selected>{{ $loop->index+1 }}. {{ $tenants->Fname }} {{ $tenants->Oname }} ({{ $tenants->Status }}) </option>
                        @else
                          <option value="/properties/search/tenants/{{$tenants->id}}">{{ $loop->index+1 }}. {{ $tenants->Fname }} {{ $tenants->Oname }} ({{ $tenants->Status }}) </option>
                        @endif
                        @empty
                          <option>No Tenant Found</option>
                        @endforelse
                      </select>
                    </div>
                </div>

                <div class="card-body" style="padding-top: 2px;">
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
                    <div class="row">
                    <!-- DIRECT CHAT -->
                    <div class="col-lg-5 col-sm-5 col-xs-5" style="margin: 0px;padding: 2px;">
                    <div class="card direct-chat direct-chat-primary ">
                      <div class="card-header" style="padding: 4px;">
                        <span class="" style="font-size: 12px;"> {{ $thistenant->Fname }} {{ $thistenant->Oname }} ({{ $thishouse->Housename }})</span>

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
                       <div class="card-body box-profile">
                         <div class="text-center">
                              @if($thistenant->Gender=="Male")
                              <img class="profile-user-img img-fluid img-circle"
                                   src="/assets/img/avatar.png"
                                   alt="User profile picture">
                              @else
                              <img class="profile-user-img img-fluid img-circle"
                                   src="/assets/img/avatar3.png"
                                   alt="User profile picture">
                              @endif
                            </div>

                            <h6 class="profile-username text-center">{{ $thistenant->Fname }} {{ $thistenant->Oname }}({{ $thistenant->Status }})</h6>
                            <div class="text-center">
                            <p class="text-muted text-center" style="padding: 0px;margin:0px;display: inline;"> 
                              <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#tenant{{ $thistenant->id }}" style="padding: 2px;display: inline;">
                              Info
                            </button>
                            <a href="{{ route('tenant.edit', $thistenant->id)}}" class="btn btn-primary btn-sm" style="padding: 2px;font-size: 10px;"><i class="fa fa-edit"></i> Edit</a>
                            <form action="{{ route('tenant.destroy', $thistenant->id)}}" method="post" class="form-horizontal" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit" style="padding: 2px;font-size: 10px;display: inline;"><i class="fa fa-trash"></i> Del</button>
                            </form>
                            {{ App\Http\Controllers\TenantController::tenantHouses($thistenant->id) }}
                          </p>
                          <p class="text-muted text-center" style="padding: 0px;margin:0px;display: inline;">
                            {{ App\Http\Controllers\TenantController::tenantHousesActions($thistenant->id) }}
                          </p>
                        </div>

                          <div class="modal-footer justify-content-between bg-primary" style="padding: 3px;">
                            <b>Actions</b> 
                            <div style="color: white;">
                        @if($thistenant->Status=="New")
                          <a href="/properties/Assign/Tenant/{{$thistenant->id}}" class="btn btn-success btn-sm " style="padding: 3px;font-size: 12px;margin-right: 3px;color: white;"><i style="color: white;" class="fa fa-plus"></i> Assign</a>
                        @elseif($thistenant->Status=="Vacated")
                          <a href="/properties/Add/House/Tenant/{{$thistenant->id}}" class="btn btn-info btn-sm " style="padding: 3px;font-size: 12px;margin-right: 3px;color: white;" title="Add House"><span class="fa fa-plus fa-1x"></span></a>
                          <a href="/properties/Agreement/Tenant/{{$thistenant->id}}" class="btn btn-success btn-sm " style="padding: 3px;font-size: 12px;margin-right: 3px;color: white;" title="Tenant Agreement"><span class="fa fa-thumbs-up fa-1x"></span></a>
                        @elseif($thistenant->Status=="Assigned" || $thistenant->Status=="Reassigned" || $thistenant->Status=="Transferred")
                          <a href="/properties/Agreement/Tenant/{{$thistenant->id}}" class="btn btn-success btn-sm " style="padding: 3px;font-size: 12px;margin-right: 3px;color: white;" title="Tenant Agreement"><span class="fa fa-thumbs-up fa-1x"></span></a>
                          <a href="/properties/Add/House/Tenant/{{$thistenant->id}}" class="btn btn-info btn-sm " style="padding: 3px;font-size: 12px;margin-right: 3px;color: white;" title="Add House"><span class="fa fa-plus fa-1x"></span></a>
                          {{ App\Http\Controllers\TenantController::tenantHousesTransfer($thistenant->id) }}
                        @endif
                        </div>
                          </div>
                          </div>
                                        
                      </div>
                      <!-- /.card-body -->
                      </div>
                  
                    </div>


                    <!-- assign tenant info -->
                    <div class="col-lg-7 col-sm-7 col-xs-7" style="margin: 0px;padding: 2px;">
                      <div class="card direct-chat direct-chat-primary ">
                        <div class="card-header" style="padding: 4px;">
                          <span class="" style="font-size: 15px;">Vacate: {{ $thishouse->Housename }}
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
                        @if($thishouse=="")
                            <div class="text-danger"> No House Selected</div>
                        @else
                        <div class="card-body">
                          <div class="row">
                            <div class="col-sm-5 bg-warning">
                              <dl class="row">
                                <dt class="col-sm-5">Phone</dt>
                                <dd class="col-sm-7">{{ $thisagreement->Phone }}</dd>
                                <dt class="col-sm-5">Total Used</dt>
                                <dd class="col-sm-7">{{ $TotalUsed }}</dd>
                                <dt class="col-sm-5">Total Paid</dt>
                                <dd class="col-sm-7">{{ $TotalPaid }}</dd>
                                <dt class="col-sm-5">Deposit</dt>
                                <dd class="col-sm-7">{{ $thisagreement->Deposit }}</dd>
                                <dt class="col-sm-5">Arrears</dt>
                                <dd class="col-sm-7">{{ $Balance }} </dd>
                                <dt class="col-sm-5">Refund</dt>
                                <dd class="col-sm-7">{{ App\Http\Controllers\TenantController::vacateRefund($Balance-$thisagreement->Deposit) }} </dd>
                                <dt class="col-sm-5">Assigned</dt>
                                <dd class="col-sm-7">{{ App\Http\Controllers\TenantController::dateToMonthName($thisagreement->DateAssigned) }} </dd>
                                
                              </dl>
                            </div>
                            <div class="col-sm-7">
                              @if($thistenant->Status=="Assigned" || $thistenant->Status=="Reassigned" || $thistenant->Status=="Transferred")
                                <form role="form" class="form-horizontal" method="POST" action="/properties/tenant/vacate">
                                    <div class="row">
                                    @csrf
                                    <div class="col-sm-12">
                                        <div class="card card-primary card-outline bg-warning" style="text-align: center;">
                                          <div class="card-body">
                                            <input type="hidden" name="hid" value="{{ $thishouse->id }}">
                                            <input type="hidden" name="tid" value="{{ $thistenant->id }}">

                                            <input type="hidden" name="aid" id="aid" value="{{ $thisagreement->id }}">
                                            <input type="hidden" name="Deposit" id="Deposit" value="{{ $thisagreement->Deposit }}">
                                            <input type="hidden" name="Arrears" id="Arrears" value="{{ $Balance }}">
                                            
                                            <div class="form-group row">
                                                <label for="Damages" class="col-md-5 col-form-label text-md-right">{{ __('Damages') }}</label>

                                                <div class="col-md-7">
                                                     <input id="Damages" type="text" class="form-control @error('Damages') is-invalid @enderror" name="Damages" placeholder="0.00" required autocomplete="Damages" autofocus>

                                                    @error('Damages')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            

                                            <div class="form-group row">
                                                <label for="Transaction" class="col-md-5 col-form-label text-md-right">{{ __('Transaction') }}</label>

                                                <div class="col-md-7">
                                                     <input id="Transaction" type="text" class="form-control @error('Transaction') is-invalid @enderror" name="Transaction" placeholder="0.00" required autocomplete="Transaction" autofocus>

                                                    @error('Transaction')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="Refund" class="col-md-5 col-form-label text-md-right">{{ __('Actual Refund') }}</label>

                                                <div class="col-md-7">
                                                     <input id="Refund" type="text" class="form-control @error('Refund') is-invalid @enderror" name="Refund" value="{{ App\Http\Controllers\TenantController::vacateRefund($Balance-$thisagreement->Deposit) }}" placeholder="0.00" required autocomplete="Refund" autofocus>

                                                    @error('Refund')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="DateVacated" class="col-md-5 col-form-label text-md-right">{{ __('Vacating') }}</label>

                                                <div class="col-md-7">
                                                    <input id="DateVacated" type="date" class="form-control @error('DateVacated') is-invalid @enderror" name="DateVacated" value="{{ old('DateVacated') }}" placeholder="huduma number" required autocomplete="DateVacated" autofocus>

                                                    @error('DateVacated')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <button  class="btn btn-danger btn-small btn-block" name="submitplotbtn" id="submitplotbtn"  type="submit" >Vacate {{ $thistenant->Fname }} {{ $thistenant->Oname }} from {{ $thishouse->Housename }}</button>
                                                </div>
                                            </div>

                                            
                                          </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                </form>
                                
                              @else
                                <h4 class="text-success"> Tenant is Not Assigned</h4>
                            @endif
                          </div>
                            </div>
                            
                          
                        </div>
                        @endif
                      </div>
                    </div>
                

                    
                   <!--/.direct-chat -->
                      <div class="modal fade" id="tenant{{ $thistenant->id }}">
                        <div class="modal-dialog">
                          <div class="modal-content bg-info">
                            <div class="modal-header">
                              <h4 class="modal-title">Tenant: {{ $thistenant->Fname }} {{ $thistenant->Oname }}</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body bg-light">
                              <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                  <b>Idno</b> <a class="float-right">{{ $thistenant->IDno }}</a>
                                </li>
                                <li class="list-group-item">
                                  <b>Phone</b> <a class="float-right">{{ $thistenant->Phone }}</a>
                                </li>
                                <li class="list-group-item">
                                  <b>Email</b> <a class="float-right">{{ $thistenant->Email }}</a>
                                </li>
                                <li class="list-group-item">
                                  <b>Gender</b> <a class="float-right">{{ $thistenant->Gender }}</a>
                                </li>
                                <li class="list-group-item">
                                  <b>Created At</b> <a class="float-right">{{ $thistenant->created_at }}</a>
                                </li>
                              </ul>
                            </div>
                            <div class="modal-footer justify-content-between">
                              <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                            </div>
                          </div>
                          <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                      </div>
                      <!-- /.modal -->
                                
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
    var origDamages="",origTransaction="";
      //enter current units and use them to find units and total
    $(document).on('keydown','#Damages',function(){
          //var sno=$(this).data("id1");
         origDamages=$('#Damages').val();
      });
    $(document).on('keyup','#Damages',function(){
      //var current=$(this).data("id1");
      var thisDamages=new Number($('#Damages').val());
      if(isNaN(thisDamages)){
        alert("Enter Number Only");
        $(this).val(origDamages);
      }
      else{
        var Deposit=new Number($('#Deposit').val());
        var Arrears=new Number($('#Arrears').val());
        var Transaction=new Number($('#Transaction').val());
        var total=new Number(Deposit-(thisDamages+Arrears+Transaction));
         $('#Refund').val(total);
      }
    });
    //update refund using this Transaction
     $(document).on('keydown','#Transaction',function(){
          //var sno=$(this).data("id1");
         origTransaction=$('#Transaction').val();
    });
    $(document).on('keyup','#Transaction',function(){
      //var current=$(this).data("id1");
      var thisTransaction=new Number($('#Transaction').val());
      if(isNaN(thisTransaction)){
        alert("Enter Number Only");
        $(this).val(origTransaction);
      }
      else{
        var Deposit=new Number($('#Deposit').val());
        var Arrears=new Number($('#Arrears').val());
        var Damages=new Number($('#Damages').val());
        var total=new Number(Deposit-(thisTransaction+Arrears+Damages));
        $('#Refund').val(total);
      }
    });

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
    $(document).on('click','.unstatementvaluesdiv',(function(e){
        var balidhouses=$(this).data("id1");
        var thisselhouses=document.getElementById(balidhouses);
        if (thisselhouses.checked===true) {
            this.style.backgroundColor='grey';
        }
        else{
            this.style.backgroundColor='#FFFFFF';
        }
        getselectedhousesforupdate();
    }));
</script>
@endpush