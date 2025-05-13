@extends('layouts.adminheader')
@section('title','All Tenants | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-6">
    <h1 class="m-0">All Tenants</h1>
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
  <li class="breadcrumb-item"><a href="/properties/tenants">Tenants (@forelse($tenantsinfo as $tenants)
                        {{$loop->count}}
                            @break
                        @empty
                            0
                        @endforelse
                        )</a></li>
  <li class="breadcrumb-item active">All Tenants</li>
</ol>
</div><!-- /.col -->
@endsection
@section('css')
    <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('public/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
@section('content')
<div class="" style="">
    <div class="row justify-content-center" style="">
        <div class="col-md-12" style="">
            <div class="card" style="border: none;">
                <div class="card-header" style="padding-top: 10px;">
                  <div class="row" style="padding: 0px;margin: 0px;">

                      <div class="col-sm-4 col-6">
                        <select class="form-control select2" name="alltenants" onchange="location=this.value;" style="width: 100%;">
                          <option value="">Search Tenant</option>
                          @forelse($tenantsinfo as $tenants)
                          <option value="/properties/search/tenants/{{$tenants->id}}">{{ $loop->index+1 }}. {{ $tenants->Fname }} {{ $tenants->Oname }} ({{ $tenants->Status }})</option>
                          @empty
                            <option>No Tenant Found</option>
                          @endforelse
                        </select>
                      </div>
                      <div class="col-sm-3 col-6">
                        <select class="form-control select2" name="allproperties" onchange="location=this.value;" style="width: 100%;">
                          <option value="">Search Houses in Property</option>
                          <option value="/properties/tenants/Actions/Vacated">->Vacated Tenants</option>
                          <option value="/properties/tenants/Actions/New">->Not Assigned Tenants</option>
                          <option value="/properties/tenants/Actions/Reassigned">->Reassigned Tenants</option>
                          <option value="/properties/tenants/Actions/Assigned">->Assigned Tenants</option>
                          @forelse($propertyinfo as $propertys)
                                <option value="/properties/tenants/property/{{$propertys->id}}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                          @empty
                            <option>No Property Found</option>
                          @endforelse
                        </select>
                      </div>
                      <div class="col-sm-3 col-6">
                        <select class="form-control select2" name="allhouses" onchange="location=this.value;" style="width: 100%;">
                          <option value="">Search Tenants in House</option>
                            @forelse($propertyhouses as $dropphouses)
                                <option value="/properties/tenants/houses/{{$dropphouses->id}}">{{ $loop->index+1 }}. {{ $dropphouses->Housename }} ({{ $dropphouses->Status }})</option>
                            @empty
                              <option>No Tenant Found</option>
                            @endforelse
                      </select>
                      </div>
                      <div class="col-sm-2 col-6">
                          <h6 style="text-align: center;"><button class="btn btn-success btn-small" style="padding: 2px;font-size: 13px;"><i class="fa fa-plus"></i> <a href="/properties/newtenant" style="color: white;">Add New</a></button>
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
                    <div class="">
                      <table id="example1" class="table table-bordered table-striped">
                      <thead>
                      <tr style="background-color: #77B5ED;">
                        <th>Sno</th>
                        <th>Tenant Name</th>
                        <th>Status</th>
                        <th>All</th>
                        <td>House(s)</td>
                        <th>Actions</th>
                      </tr>
                      </thead>
                      <tbody>


                    @forelse($alltenantsinfo as $alltenants)
                    <tr>
                      <td>{{ $loop->index+1 }} 
                            <div class=" user-panel image" style="display: inline;">
                              @if($alltenants['Gender']=="Male")
                              <img class="profile-user-img img-fluid img-circle"
                                   src="/assets/img/avatar.png"
                                   alt="User profile picture" width="20%">
                              @else
                              <img class="profile-user-img img-fluid img-circle"
                                   src="/assets/img/avatar3.png"
                                   alt="User profile picture">
                              @endif
                            </div>
                        </td>
                      <td>{{ $alltenants['Fname'] }} {{ $alltenants['Oname'] }}</td>
                      <td>{{ $alltenants['Status'] }}</td>
                      <td>{{ App\Http\Controllers\TenantController::tenantHousesAssigned($alltenants['id']) }}</td>
                      <td>{{ App\Http\Controllers\TenantController::tenantHouses($alltenants['id']) }}
                          {{ App\Http\Controllers\TenantController::tenantHousesActions($alltenants['id']) }} 
                      </td>
                      <td>
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#tenant{{ $alltenants['id'] }}" style="padding: 2px;display: inline;font-size: 12px;color: white;">
                               <i class="fa fa-info-circle"></i>
                          </button>
                          <a href="{{ route('tenant.edit', $alltenants['id'])}}" class="btn btn-primary btn-sm" style="padding: 2px;font-size: 12px;"><i class="fa fa-edit"></i></a>
                          <form action="{{ route('tenant.destroy', $alltenants['id'])}}" method="post" class="form-horizontal" style="display: inline;">
                              @csrf
                              @method('DELETE')
                              <button class="btn btn-danger btn-sm" type="submit" style="padding: 2px;font-size: 12px;display: inline;"><i class="fa fa-trash"></i></button>
                          </form>
                          @if($alltenants['Status']=="New")
                          <a href="/properties/Assign/Tenant/{{$alltenants['id']}}" class="btn btn-success btn-sm " style="padding: 3px;font-size: 12px;margin-right: 3px;color: white;"><i style="color: white;" class="fa fa-plus"></i> Assign</a>
                          @elseif($alltenants['Status']=="Vacated")
                            <a href="/properties/Add/House/Tenant/{{$alltenants['id']}}" class="btn btn-info btn-sm " style="padding: 3px;font-size: 12px;margin-right: 3px;color: white;" title="Add House"><span class="fa fa-plus fa-1x"></span></a>
                            <a href="/properties/Agreement/Tenant/{{$alltenants['id']}}" class="btn btn-success btn-sm " style="padding: 3px;font-size: 12px;margin-right: 3px;color: white;" title="Tenant Agreement"><span class="fa fa-thumbs-up fa-1x"></span></a>
                          @elseif($alltenants['Status']=="Assigned" || $alltenants['Status']=="Reassigned" || $alltenants['Status']=="Transferred")
                            <a href="/properties/Agreement/Tenant/{{$alltenants['id']}}" class="btn btn-success btn-sm " style="padding: 3px;font-size: 12px;margin-right: 3px;color: white;" title="Tenant Agreement"><span class="fa fa-thumbs-up fa-1x"></span></a>
                            <a href="/properties/Add/House/Tenant/{{$alltenants['id']}}" class="btn btn-info btn-sm " style="padding: 3px;font-size: 12px;margin-right: 3px;color: white;" title="Add House"><span class="fa fa-plus fa-1x"></span></a>
                            {{ App\Http\Controllers\TenantController::tenantHousesTransfer($alltenants['id']) }}
                          @elseif($alltenants['Status']=="Other")
                            <a href="/properties/Other/Info/{{$alltenants['id']}}" class="btn btn-danger btn-sm " style="padding: 3px;font-size: 9px;margin-right: 2px;" title="Vacate"><span class="fa fa-times-circle fa-1x"></span> View Info </a>
                          @endif
                      </td>
                    </tr>
                    
                   <!--/.direct-chat -->
                      <div class="modal fade" id="tenant{{ $alltenants['id'] }}">
                        <div class="modal-dialog">
                          <div class="modal-content bg-info">
                            <div class="modal-header">
                              <h4 class="modal-title">Tenant: {{ $alltenants['Fname'] }} {{ $alltenants['Oname'] }}</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body bg-light">
                              <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                  <b>Idno</b> <a class="float-right">{{ $alltenants['IDno'] }}</a>
                                </li>
                                <li class="list-group-item">
                                  <b>Phone</b> <a class="float-right">{{ $alltenants['Phone'] }}</a>
                                </li>
                                <li class="list-group-item">
                                  <b>Email</b> <a class="float-right">{{ $alltenants['Email'] }}</a>
                                </li>
                                <li class="list-group-item">
                                  <b>Gender</b> <a class="float-right">{{ $alltenants['Gender'] }}</a>
                                </li>
                                <li class="list-group-item">
                                  <b>Created At</b> <a class="float-right">{{ $alltenants['created_at'] }}</a>
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
                            <td colspan="10">No Tenant(s) Found</td>
                        </tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr style="background-color: #77B5ED;">
                      <th>Sno</th>
                      <th>Tenant Name</th>
                      <th>Status</th>
                      <th>All</th>
                      <td>House(s)</td>
                      <th>Actions</th>
                    </tr>
                    </tfoot>
                  </table>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables  & Plugins -->
  <script src="{{ asset('public/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('public/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('public/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('public/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('public/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
  <script src="{{ asset('public/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('public/assets/plugins/jszip/jszip.min.js') }}"></script>
  <script src="{{ asset('public/assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
  <script src="{{ asset('public/assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
  <script src="{{ asset('public/assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
  <script src="{{ asset('public/assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
  <script src="{{ asset('public/assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script type="text/javascript">


$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    $("#example1").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,"ordering":false
    });
    
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