@extends('layouts.adminheader')
@section('title','Properties | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-6">
    <h4 class="m-0">Properties Information ({{$propertycount}})</h4>
</div><!-- /.col -->
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
  <li class="breadcrumb-item"><a href="/newproperties">New</a></li>
  <li class="breadcrumb-item"><a href="/properties/tenants">Tenants ({{$tenantscount}})</a></li>
  <li class="breadcrumb-item"><a href="/properties/houses">Houses ({{$housescount}})</a></li>
  <li class="breadcrumb-item active">Properties ({{$propertycount}})</li>
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
            <div class="card" style="border: none;">
                
                <div class="card-header" style="padding-top: 10px;">
                    
                    <div class="row" style="padding: 0px;margin: 0px;">
                      <div class="col-sm-4">
                        <select class="form-control select2" name="allproperties" onchange="location=this.value;" style="width: 100%;">
                          <option value="">Select Property</option>
                          @forelse($propertyinfo as $propertys)
                             <option value="/properties/houses/{{$propertys->id}}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                          @empty
                            <option>No Property Found</option>
                          @endforelse
                        </select>
                      </div>
                      <div class="col-sm-8">
                            <h6 style="text-align: center;"><button class="btn btn-success btn-small" style="padding: 2px;font-size: 13px;"><i class="fa fa-plus"></i> <a href="/newproperties" style="color: white;">Add New Property</a></button>
                                Properties information (
                                @forelse($propertyinfo as $property)
                                    {{$loop->count}}
                                    @break
                                @empty
                                    0
                                @endforelse
                                )
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
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr style="color: #77B5ED;">
                    <th class="m-0 p-1">Sno</th>
                    <th class="m-0 p-1">Name</th>
                    <th class="m-0 p-1">Code</th>
                    <th class="m-0 p-1">Water</th>
                    <th class="m-0 p-1">Deposit</th>
                    <th class="m-0 p-1">WDeposit</th>
                    <th class="m-0 p-1">Others</th>
                    <th class="m-0 p-1">Garbage</th>
                    <th class="m-0 p-1">KPLCD</th>
                    <th class="m-0 p-1">Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                    @forelse($propertyinfo as $property)
                        <tr style="padding:0px;margin:2px;background-color:#FFFFFF;">
                            <td class="m-0 p-1">{{ $loop->index+1 }}</td>
                            <td class="m-0 p-1">{{ $property->Plotname }}</td>
                            <td class="m-0 p-1">{{ $property->Plotcode }}</td>
                            <td class="m-0 p-1">{{ $property->Waterbill }}</td>
                            <td class="m-0 p-1">{{ $property->Deposit }}</td>
                            <td class="m-0 p-1">{{ $property->Waterdeposit }}</td>
                            <td class="m-0 p-1">{{ $property->Outsourced }}</td>
                            <td class="m-0 p-1">{{ $property->Garbage }}</td>
                            <td class="m-0 p-1">{{ $property->Kplcdeposit }}</td>
                            <td class="m-0 p-1">

                                <a href="/properties/houses/{{$property->id}}" class="btn btn-success btn-sm" style="padding: 2px;font-size: 10px;"><i class="badge">{{ App\Models\Property::getCountHousesForProperty($property->id) }}</i> House</a>
                                 <a href="{{ route('plot.edit', $property->id)}}" class="btn btn-primary btn-sm" style="padding: 2px;font-size: 10px;"><i class="fa fa-edit"></i> Edit</a>
                                <form action="{{ route('plot.destroy', $property->id)}}" method="post" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit" style="padding: 2px;font-size: 10px;"><i class="fa fa-trash"> Del</i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11">No Property Found</td>
                        </tr>
                    @endforelse
                  </tbody>
                </table>

                </div>

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-info">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title" style="text-align: center;">Delete Confirmation</h4>
          
        </div>
        <div class="modal-body" id="modal-body">
          <p>One fine body&hellip;</p>
        </div>
        <div class="modal-footer justify-content-between bg-warning">
          <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-outline-dark" id="Confirm">Confirm and Delete</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
@endsection

@push('scripts')
<!-- DataTables  & Plugins -->
  <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
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
function confirmDelete(Plotname){
    $("#modal-body").html("Sure to Delete <b>"+ Plotname+" ?");
    $("#modal-info").modal('show');
    $('#Confirm').click(function(){
        $("#modal-info").modal('hide');
        // return true;
    });
    return false;
}
</script>

@endpush