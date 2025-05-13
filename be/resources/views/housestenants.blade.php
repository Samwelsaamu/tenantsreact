@extends('layouts.adminheader')
@section('title','Houses Tenants | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-6">
    <h4 class="m-0">{{ $properties->Plotname }}({{ $housesinfo->Housename }} Tenants) </h4>
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
     <li class="breadcrumb-item"><a href="/properties/houses/{{$properties->id}}">Houses (@forelse($allhousesinfo as $allhouses)
                            {{$loop->count}}
                            @break
                        @empty
                            0
                        @endforelse
                        )</a></li>
  <li class="breadcrumb-item active">{{ $housesinfo->Housename }} Tenants</li>
</ol>
</div><!-- /.col -->
@endsection
@section('content')
<div class="" style="">
    <div class="row justify-content-center" style="">
        <div class="col-md-12" style="">
            <div class="card" style="border: none;">
                
                <div class="card-header" style="padding-top: 10px;">
                    
                  <div class="row" style="padding: 0px;margin: 0px;">
                      <div class="col-sm-3">
                        <select class="form-control select2" name="allproperties" onchange="location=this.value;" style="width: 100%;">
                          <option value="">Select Property</option>
                          @forelse($propertyinfo as $propertys)
                            @if($properties->id==$propertys->id)
                             <option value="/properties/houses/{{$propertys->id}}" selected="">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                            @else
                                <option value="/properties/houses/{{$propertys->id}}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                            @endif
                          @empty
                            <option>No Property Found</option>
                          @endforelse
                        </select>
                      </div>
                      <div class="col-sm-3">
                        <select class="form-control select2" name="allhouses" onchange="location=this.value;" style="width: 100%;">
                          <option value="">Search Tenant</option>
                          @forelse($propertyhouses as $dropphouses)
                            @if($dropphouses->id==$housesinfo->id)
                              <option value="/properties/tenants/{{$dropphouses->Plot}}/{{$dropphouses->id}}" selected>{{ $loop->index+1 }}. {{ $dropphouses->Housename }} ({{ $dropphouses->Status }})</option>
                            @else
                              <option value="/properties/tenants/{{$dropphouses->Plot}}/{{$dropphouses->id}}">{{ $loop->index+1 }}. {{ $dropphouses->Housename }} ({{ $dropphouses->Status }})</option>
                            @endif
                          @empty
                            <option>No Tenant Found</option>
                          @endforelse
                      </select>
                      </div>
                      <div class="col-sm-6">
                          <h6 style="text-align: center;"><button class="btn btn-success btn-small" style="padding: 2px;font-size: 13px;"><i class="fa fa-plus"></i> <a href="/properties/newtenant" style="color: white;">New Tenant</a></button>
                            <button class="btn btn-success btn-small" style="padding: 2px;font-size: 13px;"><i class="fa fa-plus"></i> <a href="/properties/newhouse/{{$properties->id}}" style="color: white;">New House</a></button>
                            Tenants for {{ $properties->Plotname }}: {{ $housesinfo->Housename }}
                          </h6>
                      </div>
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
                    @forelse($agreementinfo as $agreements)
                                <!-- DIRECT CHAT -->
                    @break(($loop->index+1)==100)
                    <div class="col-sm-4" style="padding: 2px;margin: 0px;">
                      <div class="card direct-chat direct-chat-primary" style="padding: 2px;margin: 5px;">
                      <div class="card-header bg-info" style="padding: 4px;">
                        {{ $loop->index+1 }}.
                        <span class="" style="font-size: 12px;"> {{ App\Http\Controllers\TenantController::tenantNames($agreements->Tenant) }}</span>

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
                      <div class="card-body bg-light">
                       <div class="card-body box-profile">
                         <div class="text-center">
                              @if($agreements->Gender=="Male")
                              <img class="profile-user-img img-fluid img-circle"
                                   src="/assets/img/avatar.png"
                                   alt="User profile picture">
                              @else
                              <img class="profile-user-img img-fluid img-circle"
                                   src="/assets/img/avatar3.png"
                                   alt="User profile picture">
                              @endif
                            </div>

                            <h6 class="profile-username text-center" style="font-size: 15px;">{{ App\Http\Controllers\TenantController::tenantFNames($agreements->Tenant) }}({{ App\Http\Controllers\TenantController::tenantStatus($agreements->Tenant) }} ({{ App\Http\Controllers\TenantController::tenantHousesAssigned($agreements->Tenant) }})) <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#tenant{{ $agreements->Tenant }}" style="padding: 2px;display: inline;font-size: 12px;color: white;">
                              <i class="fa fa-info-circle"></i>
                              </button></h6>
                            <div class="text-center">
                            <p class="text-muted text-center" style="padding: 0px;margin:0px;display: inline;">
                              
                              
                              {{ App\Http\Controllers\TenantController::tenantHouses($agreements->Tenant) }}
                            </p>
                            <p class="text-muted text-center" style="padding: 0px;margin:0px;display: inline;">
                             {{ App\Http\Controllers\TenantController::tenantHousesActions($agreements->Tenant) }}
                            </p>
                          </div>

                          
                          </div>
                                        
                      </div>

                       <div class="modal-footer justify-content-between bg-primary" style="padding: 3px;">
                        <div style="color: white;">
                          <a href="{{ route('tenant.edit', $agreements->Tenant)}}" class="btn btn-primary btn-sm" style="padding: 4px;font-size: 12px;"><i class="fa fa-edit"></i></a>
                          <form action="{{ route('tenant.destroy', $agreements->Tenant)}}" method="post" class="form-horizontal" style="display: inline;">
                              @csrf
                              @method('DELETE')
                              <button class="btn btn-danger btn-sm" type="submit" style="padding: 4px;font-size: 12px;display: inline;"><i class="fa fa-trash"></i></button>
                          </form>
                        @if(App\Http\Controllers\TenantController::tenantStatus($agreements->Tenant)=="New")
                          <a href="/properties/Assign/Tenant/{{$agreements->Tenant}}" class="btn btn-success btn-sm " style="padding: 3px;font-size: 12px;margin-right: 3px;color: white;"><i style="color: white;" class="fa fa-plus"></i> Assign</a>
                        @elseif(App\Http\Controllers\TenantController::tenantStatus($agreements->Tenant)=="Vacated")
                          <a href="/properties/Add/House/Tenant/{{$agreements->Tenant}}" class="btn btn-info btn-sm " style="padding: 3px;font-size: 12px;margin-right: 3px;color: white;" title="Add House"><span class="fa fa-plus fa-1x"></span></a>
                          <a href="/properties/Agreement/Tenant/{{$agreements->Tenant}}" class="btn btn-success btn-sm " style="padding: 3px;font-size: 12px;margin-right: 3px;color: white;" title="Tenant Agreement"><span class="fa fa-thumbs-up fa-1x"></span></a>
                        @elseif(App\Http\Controllers\TenantController::tenantStatus($agreements->Tenant)=="Assigned" || App\Http\Controllers\TenantController::tenantStatus($agreements->Tenant)=="Reassigned" || App\Http\Controllers\TenantController::tenantStatus($agreements->Tenant)=="Transferred")
                          <a href="/properties/Agreement/Tenant/{{$agreements->Tenant}}" class="btn btn-success btn-sm " style="padding: 3px;font-size: 12px;margin-right: 3px;color: white;" title="Tenant Agreement"><span class="fa fa-thumbs-up fa-1x"></span></a>
                          <a href="/properties/Add/House/Tenant/{{$agreements->Tenant}}" class="btn btn-info btn-sm " style="padding: 3px;font-size: 12px;margin-right: 3px;color: white;" title="Add House"><span class="fa fa-plus fa-1x"></span></a>
                          {{ App\Http\Controllers\TenantController::tenantHousesTransfer($agreements->Tenant) }}
                        @endif
                        </div>
                      </div>
                      <!-- /.card-body -->
                      </div>
                    </div>
                   <!--/.direct-chat -->
                      <div class="modal fade" id="tenant{{ $agreements->Tenant }}">
                        <div class="modal-dialog">
                          <div class="modal-content bg-info">
                            <div class="modal-header">
                              <h4 class="modal-title">Tenant: {{ App\Http\Controllers\TenantController::tenantNames($agreements->Tenant) }}</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body bg-light">
                              <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                  <b>Idno</b> <a class="float-right">{{ App\Http\Controllers\TenantController::tenantIdno($agreements->Tenant) }}</a>
                                </li>
                                <li class="list-group-item">
                                  <b>Phone</b> <a class="float-right">{{ $agreements->Phone }}</a>
                                </li>
                                <li class="list-group-item">
                                  <b>Email</b> <a class="float-right">{{ App\Http\Controllers\TenantController::tenantEmail($agreements->Tenant) }}</a>
                                </li>
                                <li class="list-group-item">
                                  <b>Gender</b> <a class="float-right">{{ App\Http\Controllers\TenantController::tenantGender($agreements->Tenant) }}</a>
                                </li>
                                <li class="list-group-item">
                                  <b>Created At</b> <a class="float-right">{{ App\Http\Controllers\TenantController::tenantCreated($agreements->Tenant) }}</a>
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
                                
                    @empty
                        <tr>
                            <td colspan="10">No Tenant(s) Found in {{ $properties->Plotname }}: {{ $housesinfo->Housename }}</td>
                        </tr>
                    @endforelse
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