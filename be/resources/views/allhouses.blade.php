@extends('layouts.adminheader')
@section('title','All Houses | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-6">
    <h4 class="m-0">All Houses(
                        @forelse($housesinfo as $houses)
                            {{$loop->count}}
                            @break
                        @empty
                            0
                        @endforelse
                        )</h4>
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
  <li class="breadcrumb-item active">All Houses(
                        @forelse($housesinfo as $houses)
                            {{$loop->count}}
                            @break
                        @empty
                            0
                        @endforelse
                        )</li>
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
                        <select class="form-control select2" name="alltenants" onchange="location=this.value;" style="width: 100%;">
                          <option value="">Search Tenant</option>
                          @forelse($tenantsinfo as $tenants)
                          <option value="/properties/search/tenants/{{$tenants->id}}">{{ $loop->index+1 }}. {{ $tenants->Fname }} {{ $tenants->Oname }} ({{ $tenants->Status }})</option>
                          @empty
                            <option>No Tenant Found</option>
                          @endforelse
                        </select>
                      </div>
                      <div class="col-sm-3">
                        <select class="form-control select2" name="allproperties" onchange="location=this.value;" style="width: 100%;">
                          <option value="">Search Houses in Property</option>
                          @forelse($propertyinfo as $propertys)
                                <option value="/properties/houses/{{$propertys->id}}">{{ $loop->index+1 }}. {{ $propertys->Plotname }} ({{ $propertys->Plotcode }})</option>
                          @empty
                            <option>No Property Found</option>
                          @endforelse
                        </select>
                      </div>
                      <div class="col-sm-3">
                        <select class="form-control select2" name="allhouses" onchange="location=this.value;" style="width: 100%;">
                          <option value="">Search Tenants in House</option>
                            @forelse($housesinfo as $dropphouses)
                                <option value="/properties/tenants/{{$dropphouses->Plot}}/{{$dropphouses->id}}">{{ $loop->index+1 }}. {{ $dropphouses->Housename }} ({{ $dropphouses->Status }})</option>
                            @empty
                              <option>No House Found</option>
                            @endforelse
                      </select>
                      </div>
                      <div class="col-sm-2">
                          <h6 style="text-align: center;">
                                All Houses (
                                @forelse($housesinfo as $houses)
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

                <form role="form" name="seachedhousesform" id="seachedhousesform" class="form-horizontal" action="/properties/updatehouse" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label for="Housename" class="col-md-1 col-form-label text-md-right">Details</label>

                        <div class="col-md-3">
                            <select name="updatefield" id="updatefield" class="select form-control" required="required">
                                <option value="">Choose what to Update...</option>
                                <option value="Rent">Rent</option>
                                <option value="Deposit">House Deposit</option>
                                <option value="Kplc">Kplc Deposit</option>
                                <option value="Water">Water Deposit</option>
                                <option value="Lease">Lease Deposit</option>
                                <option value="Garbage">Garbage Deposit</option>
                                <option value="DueDay">DueDay</option>
                            </select>
                        </div>
                        <label for="Housename" class="col-md-1 col-form-label text-md-right">Value</label>

                        <div class="col-md-3">
                            <input type="text" name="updatevalue" id="updatevalue" placeholder="Enter Value to Update" class="form-control" required="required" >
                        </div>
                        <div class="col-sm-2">
                            <button  class="btn btn-warning btn-large" name="submitupdatebtn" id="submitupdatebtn"  type="submit" >Update Selected</button>
                        </div>
                        <div class="col-sm-2" style="padding: 0px;">
                             <span style="position:fixed;z-index:999999;color:red;font-size:20px;padding: 2px;margin: 2px;"> Selected(<i class="badge" id="selectedhousesforupdate" style="font-size:25px;">0</i>)</span>
                        </div>
                    </div>
                <div id="searchedhouses" class="well" style="overflow-x: auto;">
                    <!-- <div class="mailbox-controls">
                        <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
                        </button>
                        <div class="btn-group">
                          <button type="button" class="btn btn-default btn-sm">
                            <i class="far fa-trash-alt"></i>
                          </button>
                        </div>
                    </div> -->

                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr style="background-color: #77B5ED;">
                    <th>Sno</th>
                    <th>Property</th>
                    <th>Name</th>
                    <th>Rent</th>
                    <th>Deposit</th>
                    <th>Water</th>
                    <th>Lease</th>
                    <th>Garbage</th>
                    <th>Due</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                    @forelse($housesinfo as $houses)
                        <tr class="unstatementvaluesdiv" style="padding:5px;background-color:#FFFFFF;" data-id1="houseno{{ $loop->index+1 }}">
                        <td>
                            <label class="col-lg-12" style="font-size:12px;"><input type="checkbox"  name="houseno[]" id="houseno{{ $loop->index+1 }}" class="selectedhousesforupdate"  value="{{ $houses->id }}"> {{ $loop->index+1 }}</label>
                        </td>
                        </form>
                            <td>{{ App\Models\Property::getPropertyCode($houses->Plot) }}</td>
                            <td>{{ $houses->Housename }}</td>
                            <td>{{ $houses->Rent }}</td>
                            <td>{{ $houses->Deposit }}</td>
                            <td>{{ $houses->Water }}</td>
                            <td>{{ $houses->Lease }}</td>
                            <td>{{ $houses->Garbage }}</td>
                            <td>{{ $houses->DueDay }}</td>
                            <td>{{ $houses->Status }}</td>
                            <td>
                                <a href="/properties/tenants/{{$houses->Plot}}/{{$houses->id}}" class="btn btn-success btn-sm" style="padding: 2px;font-size: 10px;"><i class="badge">{{ App\Models\Property::getCountTenantsForHouses($houses->id) }}</i> Tenant(s)</a>
                                <a href="{{ route('house.edit', $houses->id)}}" class="btn btn-primary btn-sm" style="padding: 2px;font-size: 10px;"><i class="fa fa-edit"></i> </a>
                                <form action="{{ route('house.destroy', $houses->id)}}" method="post" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit" style="padding: 2px;font-size: 10px;"><i class="fa fa-trash"> Del</i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">No House(s) Found</td>
                        </tr>
                    @endforelse
                  </tbody>
                  <tfoot>
                  <tr style="background-color: #77B5ED;">
                    <th>Sno</th>
                    <th>Property</th>
                    <th>Name</th>
                    <th>Rent</th>
                    <th>Deposit</th>
                    <th>Water</th>
                    <th>Lease</th>
                    <th>Garbage</th>
                    <th>Due</th>
                    <th>Status</th>
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

    //Enable check and uncheck all functionality
    // $('.checkbox-toggle').click(function () {
    //   var clicks = $(this).data('clicks')
    //   if (clicks) {
    //     //Uncheck all checkboxes
    //     $('.mailbox-messages input[type=\'checkbox\']').prop('checked', false)
    //     $('.checkbox-toggle .far.fa-check-square').removeClass('fa-check-square').addClass('fa-square')
    //   } else {
    //     //Check all checkboxes
    //     $('.mailbox-messages input[type=\'checkbox\']').prop('checked', true)
    //     $('.checkbox-toggle .far.fa-square').removeClass('fa-square').addClass('fa-check-square')
    //   }
    //   $(this).data('clicks', !clicks)
    // })
    
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