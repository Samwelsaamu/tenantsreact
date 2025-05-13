@extends('layouts.adminheader')
@section('title','Add Tenant House | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-4">
    <h5 class="m-0">{{ $thistenant->Fname }} {{ $thistenant->Oname }} to @if($thishouse!="")
                          {{ $thishouse->Housename }}
                          @endif</h5>
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
  <li class="breadcrumb-item active">{{ $thistenant->Fname }} {{ $thistenant->Oname }} to @if($thishouse!="")
                          {{ $thishouse->Housename }}
                          @endif</li>
</ol>
</div><!-- /.col -->
@endsection
@section('content')
<div class="" style="">
    <div class="row justify-content-center" style="">
        <div class="col-md-12" style="">
            <div class="card" style="border: none;">
                <div class="card-header" style="padding-top: 10px;">
                    <h4 style="text-align: center;"><button class="btn btn-success btn-small" style="padding: 2px;font-size: 13px;"><i class="fa fa-plus"></i> <a href="/properties/newtenant" style="color: white;">Add New</a></button>
                        Add House to  {{ $thistenant->Fname }} {{ $thistenant->Oname }}:  @if($thishouse!="")
                          <b>{{ $thishouse->Housename }}</b>
                          @endif
                    </h4>
                    <div class="form-group">
                      <select class="form-control select2" name="alltenants" onchange="location=this.value;" style="width: 100%;">
                        <option value="">Search Tenant</option>
                        @forelse($tenantsinfo as $tenants)
                        @if($thistenant->id==$tenants->id)
                          @if($tenants->Status=="New" || $tenants->Status=="Vacated")
                            <option value="/properties/Assign/Tenant/{{$tenants->id}}" selected>{{ $loop->index+1 }}. {{ $tenants->Fname }} {{ $tenants->Oname }} ({{ $tenants->Status }}) ....Assign</option>
                          @else
                            <option value="/properties/Add/House/Tenant/{{$tenants->id}}" selected>{{ $loop->index+1 }}. {{ $tenants->Fname }} {{ $tenants->Oname }} ({{ $tenants->Status }}) </option>
                          @endif
                        @else
                          @if($tenants->Status=="New" || $tenants->Status=="Vacated")
                            <option value="/properties/Assign/Tenant/{{$tenants->id}}">{{ $loop->index+1 }}. {{ $tenants->Fname }} {{ $tenants->Oname }} ({{ $tenants->Status }}) ....Assign</option>
                          @else
                            <option value="/properties/Add/House/Tenant/{{$tenants->id}}">{{ $loop->index+1 }}. {{ $tenants->Fname }} {{ $tenants->Oname }} ({{ $tenants->Status }}) </option>
                          @endif
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
                    <div class="card direct-chat direct-chat-primary col-lg-5 col-sm-5 col-xs-5" style="margin: 5px;padding: 2px;">
                      <div class="card-header" style="padding: 4px;">
                        <span class="" style="font-size: 12px;"> {{ $thistenant->Fname }} {{ $thistenant->Oname }}</span>

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
                              <a href="/properties/Agreement/Tenant/{{$thistenant->id}}" class="btn btn-success btn-sm " style="padding: 3px;font-size: 12px;margin-right: 3px;color: white;" title="Tenant Agreement"><span class="fa fa-thumbs-up fa-1x"></span></a>
                              <a href="/properties/Assign/Tenant/{{$thistenant->id}}" class="btn btn-success btn-sm " style="padding: 3px;font-size: 12px;margin-right: 3px;color: white;"><i style="color: white;" class="fa fa-plus"></i> Assign</a>
                            @elseif($thistenant->Status=="Assigned" || $thistenant->Status=="Reassigned" || $thistenant->Status=="Transferred")
                              <a href="/properties/Agreement/Tenant/{{$thistenant->id}}" class="btn btn-success btn-sm " style="padding: 3px;font-size: 12px;margin-right: 3px;color: white;" title="Tenant Agreement"><span class="fa fa-thumbs-up fa-1x"></span></a>
                              {{ App\Http\Controllers\TenantController::tenantHousesTransfer($thistenant->id) }}
                              
                            @endif
                            </div>
                          </div>
                          </div>
                                        
                      </div>
                      <!-- /.card-body -->
                      
                    </div>


                    <!-- assign tenant info -->

                    <div class="card direct-chat direct-chat-primary col-lg-6 col-sm-6 col-xs-6" style="margin: 5px;padding: 2px;">
                      <div class="card-header" style="padding: 4px;">
                        <span class="" style="font-size: 12px;">Add <b>{{ $thistenant->Fname }} {{ $thistenant->Oname }}</b> House:
                          @if($thishouse!="")
                          <b>{{ $thishouse->Housename }}</b>
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
                        <div class="form-group">
                          <select class="form-control select2" name="alltenants" onchange="location=this.value;" style="width: 100%;">
                            <option value="">Search Vacant House</option>
                            @forelse($houseinfo as $houses)
                            @if($thishouse!="")
                              @if($thishouse->id==$houses->id)
                                <option value="/properties/Add/House/Tenant/{{$houses->id}}/{{$thistenant->id}}" selected>{{ $loop->index+1 }}. {{ $houses->Housename }} ({{ $houses->Status }}) </option>
                              @else
                                <option value="/properties/Add/House/Tenant/{{$houses->id}}/{{$thistenant->id}}">{{ $loop->index+1 }}. {{ $houses->Housename }} ({{ $houses->Status }}) </option>
                              @endif
                            @else
                              <option value="/properties/Add/House/Tenant/{{$houses->id}}/{{$thistenant->id}}">{{ $loop->index+1 }}. {{ $houses->Housename }} ({{ $houses->Status }}) </option>
                            @endif
                            @empty
                              <option>No House Vacant Found</option>
                            @endforelse
                          </select>
                        </div>

                        @if($thishouse=="")
                            <div class="text-danger"> No House Selected</div>
                        @else
                        <div class="card-body row">
                          
                            <div class="col-sm-6 bg-info">
                              <dl class="row">
                                <dt class="col-sm-7">Housename</dt>
                                <dd class="col-sm-5">{{ $thishouse->Housename }}</dd>
                                <dt class="col-sm-7">R and G</dt>
                                <dd class="col-sm-5">{{ $thishouse->Rent+$thishouse->Garbage }}</dd>
                                <dt class="col-sm-7">Deposit</dt>
                                <dd class="col-sm-5">{{ $thishouse->Deposit+$thishouse->Water+$thishouse->Kplc }}</dd>
                                <dt class="col-sm-7">Lease</dt>
                                <dd class="col-sm-5">{{ $thishouse->Lease }} </dd>
                                <dt class="col-sm-7">Existing</dt>
                                <dd class="col-sm-5">{{ $thishouse->Rent+$thishouse->Garbage }} </dd>
                                <dt class="col-sm-7">New</dt>
                                <dd class="col-sm-5">{{ $thishouse->Rent+$thishouse->Garbage+$thishouse->Deposit+$thishouse->Water+$thishouse->Kplc+$thishouse->Lease }} </dd>
                              </dl>
                            </div>
                            <div class="col-sm-6">
                              @if($thistenant->Status=="Assigned" || $thistenant->Status=="Reassigned" || $thistenant->Status=="Transferred")
                                <form role="form" class="form-horizontal" method="POST" action="/properties/savetenant/add/house">
                                    <div class="row">
                                    @csrf
                                    <div class="col-sm-12">
                                        <div class="card card-primary card-outline bg-info" style="text-align: center;">
                                          <div class="card-body">
                                            <input type="hidden" name="hid" value="{{ $thishouse->id }}">
                                            <input type="hidden" name="tid" value="{{ $thistenant->id }}">
                                            <input type="hidden" name="Rent" value="{{ $thishouse->Rent }}">
                                            <input type="hidden" name="Garbage" value="{{ $thishouse->Garbage }}">
                                            <input type="hidden" name="KPLC" value="{{ $thishouse->Kplc }}">
                                            <input type="hidden" name="HseDeposit" value="{{ $thishouse->Deposit }}">
                                            <input type="hidden" name="Water" value="{{ $thishouse->Water }}">
                                            <input type="hidden" name="Lease" value="{{ $thishouse->Lease }}">
                                            <div class="form-group row">
                                                <label for="DateAssigned" class="col-md-4 col-form-label text-md-right">{{ __('Assign') }}</label>

                                                <div class="col-md-8">
                                                    <input id="DateAssigned" type="date" class="form-control @error('DateAssigned') is-invalid @enderror" name="DateAssigned" value="{{ old('DateAssigned') }}" placeholder="huduma number" required autocomplete="DateAssigned" autofocus>

                                                    @error('DateAssigned')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="DateAssigned" class="col-md-4 col-form-label text-md-right">{{ __('Nature') }}</label>

                                                <div class="col-md-8">
                                                     <label>
                                                        <input type="radio" name="Nature" style="margin-right: 8px;" value="Existing" required="required"> Existing
                                                      </label>
                                                    <label>
                                                      <input type="radio" name="Nature" style="margin-right: 8px;" value="New" required="required"> New
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <button  class="btn btn-success btn-small btn-block" name="submitplotbtn" id="submitplotbtn"  type="submit" >Assign {{ $thistenant->Fname }} {{ $thistenant->Oname }} to {{ $thishouse->Housename }}</button>
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