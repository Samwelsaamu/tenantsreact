@extends('layouts.adminheader')
@section('title','Properties | Wagitonga Agencies Limited')

@section('css')

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
        <a href="/properties/mgr/tenants" class="nav-link active">
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
        <div class="col-md-12 m-0 p-0" style="">
            <div class="mt-0" style="border: none;">
                

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
                <div class="row m-0 p-0">
                    <!-- start of left side -->
                  <div class="col-md-4 m-0">
                    <div class="m-0 mb-2 text-xs">
                      <div class="card-body m-0 p-0">
                        <!-- quick links for properties -->
                        <!-- <div class="card m-0 p-0 mb-2">
                          <div class="card-header bg-info m-0 p-0">
                              <h4 class="text-left text-sm  m-0 p-1">
                                Properties ({{$propertycount}})
                                <span class=" text-xs float-right m-0 p-0">
                                  <button type="button" class="btn bg-primary p-0 m-0 ml-1 pr-1 pl-1 btnplotmgrinfo"><i class="fas fa-info-circle"> View</i></button>
                                  <button type="button" class="btn bg-success p-0 m-0 ml-1 pr-1 pl-1 btnplotmgrnew"><i class="fas fa-plus-circle text-xs"> Property</i></button>
                                  <button type="button" class="btn bg-success p-0 m-0 ml-1 pr-1 pl-1 btnplotmgrnewtenant"><i class="fas fa-plus-circle text-xs"> Tenant</i></button>
                                </span>
                              </h4>
                          </div>
                          <div class="text-sm text-danger mx-auto propertiesmanageresload"></div>
                          <div class="card-body m-1 p-1 propertiesmanagesideproperty" style="overflow-x:auto;max-height: calc(50vh - 12rem);overflow-y:auto;">
                            
                          <p class="text-info text-center">Please Wait ... <img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading..."></p>
                            
                          </div>
                        </div> -->
                        <!-- end of quick links for properties -->

                        <!-- quick links for houses -->
                        <!-- <div class="card m-0 p-0 mb-2">
                          <div class="card-header bg-info m-0 p-0">
                              <h4 class="text-left text-sm  m-0 p-1">
                                Houses 
                                <span class=" text-xs float-right m-0 p-0">
                                  
                                  <input type="text">
                                  <button type="button" class="btn bg-success p-0 m-0 ml-1 pr-1 pl-1 text-right"><i class="fas fa-search text-xs"></i></button>
                                  
                                </span>
                              </h4>
                          </div>
                          <div class="text-sm text-danger mx-auto propertiesmanagehouseresload"></div>
                          <div class="card-body m-1 p-1 propertiesmanagesidehouse" style="overflow-x:auto;max-height: calc(50vh - 12rem);overflow-y:auto;">
                            
                          <p class="text-info text-center">Selected House Property will be Displayed Here</p>
                                 
                          </div>
                        </div> -->
                        <!-- end of quick links for houses -->

                        <!-- quick links for tenants -->
                        <div class="card m-0 p-0 mb-2">
                          <div class="card-header bg-info m-0 p-0">
                              <h4 class="text-left text-sm  m-0 p-1">
                                Tenants
                                <span class="text-xs float-right m-0 p-0">
                                  <div class="btn-group p-0 m-0 ml-1 pr-1 pl-1">
                                    <button type="button" class="btn btn-primary p-0 m-0 ml-1 pr-1 pl-1 dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                      <span>View</span>
                                    </button>
                                    <div class="dropdown-menu text-left row propertiesmanagedropdownproperty" role="menu" style="overflow-x:auto;max-height: calc(50vh - 6rem);overflow-y:auto;">
                                     <!-- load properties -->
                                    </div>
                                  </div>

                                  <input type="text">
                                  <button type="button" class="btn bg-success p-0 m-0 ml-1 pr-1 pl-1 text-right"><i class="fas fa-search text-xs"></i></button>
                                </span>
                              </h4>
                          </div>
                          <div class="text-sm text-danger mx-auto propertiesmanagetenantresload"></div>
                          <div class="card-body m-1 p-1 propertiesmanagesidetenant" style="overflow-x:auto;max-height: 100vh;overflow-y:auto;">
                            
                          <p class="text-info text-center">Selected Tenants will be Displayed Here</p>
                            
                          </div>
                        </div>
                        <!-- end of quick links for tenants -->
                        
                      </div>
                    </div>
                  </div>

                  <div class="col-md-8">
                    <div class="card mb-2">
                      <div class="text-sm text-danger mx-auto propertiesmanageresload"></div>
                      <div class="card-body m-1 p-1 propertiesmanageres" style="overflow-x:auto;max-height: calc(100vh - 8rem);overflow-y:auto;">
                        <!-- all data is loaded here -->
                        <p class="text-info text-center">Please Wait ... <img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading..."></p>
                      </div>
                    </div>
                  </div>
                </div>


                

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

  <!-- start modal page delete property confirmation -->
  <div class="modal fade mt-5" id="modalconfirmdel">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger m-0 p-2">
          <h6 class="modal-title mx-auto" id="modalconfirmdel-title">Confirm Deleting</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body bg-light m-0 p-1" id="modalconfirmdel-body" style="padding: 10px;margin:10px;max-height:calc(50vh);overflow-y: auto;">
          <p>Message Body</p>
        </div>
        
      </div>
    </div>
  </div>
  <!-- end modal page delete property confirmation -->

  <!-- start modal page new property confirmation -->
  <div class="modal fade" id="modalnewproperty">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info m-0 p-2">
          <h6 class="modal-title mx-auto" id="modalnewproperty-title">Add New Property</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body bg-light text-xs m-0 p-1" id="modalnewproperty-body" style="max-height:calc(100vh-12em);overflow-y: auto;">
          <p>Message Body</p>
        </div>
        
      </div>
    </div>
  </div>
  <!-- end modal page new property confirmation -->

  <!-- start modal page edit property confirmation -->
  <div class="modal fade" id="modaleditproperty">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info m-0 p-2">
          <h6 class="modal-title mx-auto" id="modaleditproperty-title">Add Edit Property</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body bg-light text-xs m-0 p-1" id="modaleditproperty-body" style="max-height:calc(100vh-12em);overflow-y: auto;">
          <p>Message Body</p>
        </div>
        
      </div>
    </div>
  </div>
  <!-- end modal page edit property confirmation -->

  <!-- start modal page new property confirmation -->
  <div class="modal fade" id="modalnewtenantassign">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-info m-0 p-2">
          <h6 class="modal-title mx-auto" id="modalnewtenantassign-title">Assign Tenant to House</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body bg-light text-xs m-0 p-1" id="modalnewtenantassign-body" style="max-height:calc(100vh-12em);overflow-y: auto;">
          <p>Message Body</p>
        </div>
        
      </div>
    </div>
  </div>
  <!-- end modal page new property confirmation -->

  <!-- start modal page delete property confirmation -->
  <div class="modal fade mt-5" id="modalconfirmsaveproperty">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-success m-0 p-2">
          <h6 class="modal-title mx-auto" id="modalconfirmsaveproperty-title">Confirm Save</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body bg-light m-0 p-1" id="modalconfirmsaveproperty-body" style="padding: 10px;margin:10px;max-height:calc(50vh);overflow-y: auto;">
          <p>Message Body</p>
        </div>
        
      </div>
    </div>
  </div>
  <!-- end modal page delete property confirmation -->
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
    getProperties();
    getTenants('New','Not Assigned');
    var vacanthousename='';
    var vacanthousenamefrom='';

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

  $(document).on('click','.btnplotmgrinfo',(function(e){
    getProperties();
  }));

  $(document).on('click','.btnplotmgrnew',(function(e){
    $("#modalnewproperty-body").html(''+
        '<form role="form" class="form-horizontal" method="POST" name="formplotmgrsaveplot" id="formplotmgrsaveplot">'+
          '<div class="row m-0 p-0">'+
          '@csrf'+
          '<div class="col-sm-6 m-0 p-1">'+
              '<div class="card card-primary card-outline" style="margin-bottom: 5%;min-height: 300px;text-align: center;">'+
                '<div class="card-body">'+

                  '<div class="form-group row">'+
                      '<label for="Plotname" class="col-md-4 col-form-label text-md-right">{{ __('Property Name') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="Plotname" type="text" class="form-control @error('Plotname') is-invalid @enderror" name="Plotname" value="{{ old('Plotname') }}" placeholder="Property Name" required autocomplete="Plotname" autofocus>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Plotarea" class="col-md-4 col-form-label text-md-right">{{ __('Property Area') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="Plotarea" type="text" class="form-control @error('Plotarea') is-invalid @enderror" name="Plotarea" value="{{ old('Plotarea') }}" placeholder="Property Area" required autocomplete="Plotarea" autofocus>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Plotcode" class="col-md-4 col-form-label text-md-right">{{ __('Property Code') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="Plotcode" type="text" class="form-control @error('Plotcode') is-invalid @enderror" name="Plotcode" value="{{ old('Plotcode') }}" placeholder="Property Code" required autocomplete="Plotcode" autofocus>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Plotaddr" class="col-md-4 col-form-label text-md-right">{{ __('Property Addr') }}</label>'+

                      '<div class="col-md-8">'+
                          '<textarea  id="Plotaddr" type="text" class="form-control @error('Plotaddr') is-invalid @enderror" name="Plotaddr" placeholder="Property Address" required autocomplete="Plotaddr" autofocus>{{ old('Plotaddr') }}</textarea>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Plotdesc" class="col-md-4 col-form-label text-md-right">{{ __('Property Desc') }}</label>'+

                      '<div class="col-md-8">'+
                          '<textarea id="Plotdesc" type="text" class="form-control @error('Plotdesc') is-invalid @enderror" name="Plotdesc" placeholder="Property Description" required autocomplete="Plotdesc" autofocus>{{ old('Plotdesc') }}</textarea>'+
                      '</div>'+
                  '</div>'+

                '</div>'+
              '</div>'+
          '</div>'+

          '<div class="col-sm-6 m-0 p-1">'+
              '<div class="card card-primary card-outline" style="margin-bottom: 5%;min-height: 300px;text-align: center;">'+
                '<div class="card-body">'+

                  '<div class="form-group row">'+
                      '<label for="Waterbill" class="col-md-4 col-form-label text-md-right">{{ __('Water Bill') }}</label>'+

                      '<div class="col-md-8" style="background-color: white;color: black;padding-top: 12px;text-align:left;">'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                                '<input type="radio" required class="@error('Waterbill') is-invalid @enderror" name="Waterbill" value="Monthly" autocomplete="Waterbill"> Paid Monthly'+
                          '</label>'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                              '<input type="radio" required class="@error('Waterbill') is-invalid @enderror" name="Waterbill" value="None" autocomplete="Waterbill"> None'+
                          '</label>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Waterdeposit" class="col-md-4 col-form-label text-md-right">{{ __('Water Deposit') }}</label>'+

                      '<div class="col-md-8"  style="background-color: white;color: black;padding-top: 12px;text-align:left;">'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                                '<input type="radio" required class="@error('Waterdeposit') is-invalid @enderror" name="Waterdeposit" value="Once" autocomplete="Waterdeposit"> Paid Once'+
                          '</label>'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                              '<input type="radio" required class="@error('Waterdeposit') is-invalid @enderror" name="Waterdeposit" value="None" autocomplete="Waterdeposit"> None'+
                          '</label>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Deposit" class="col-md-4 col-form-label text-md-right">{{ __('Deposit') }}</label>'+

                      '<div class="col-md-8"  style="background-color: white;color: black;padding-top: 12px;text-align:left;">'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                                '<input type="radio" required class="@error('Deposit') is-invalid @enderror" name="Deposit" value="Once" autocomplete="Deposit"> Paid Once'+
                          '</label>'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                              '<input type="radio" required class="@error('Deposit') is-invalid @enderror" name="Deposit" value="None" autocomplete="Deposit"> None'+
                          '</label>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Garbage" class="col-md-4 col-form-label text-md-right">{{ __('Garbage') }}</label>'+

                      '<div class="col-md-8"  style="background-color: white;color: black;padding-top: 12px;text-align:left;">'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                                '<input type="radio" required class="@error('Garbage') is-invalid @enderror" name="Garbage" value="Monthly" autocomplete="Garbage"> Paid Monthly'+
                          '</label>'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                              '<input type="radio" required class="@error('Garbage') is-invalid @enderror" name="Garbage" value="None" autocomplete="Garbage"> None'+
                          '</label>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Kplcdeposit" class="col-md-4 col-form-label text-md-right">{{ __('KPLC Deposit') }}</label>'+

                      '<div class="col-md-8"  style="background-color: white;color: black;padding-top: 12px;text-align:left;">'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                                '<input type="radio" required class="@error('Kplcdeposit') is-invalid @enderror" name="Kplcdeposit" value="Once" autocomplete="Kplcdeposit"> Paid Once'+
                          '</label>'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                              '<input type="radio" required class="@error('Kplcdeposit') is-invalid @enderror" name="Kplcdeposit" value="None" autocomplete="Kplcdeposit"> None'+
                          '</label>'+
                      '</div>'+
                  '</div>'+


                  '<div class="form-group row">'+
                      '<label for="Outsourced" class="col-md-4 col-form-label text-md-right">{{ __('Outsourced Water') }}</label>'+

                      '<div class="col-md-8"  style="background-color: white;color: black;padding-top: 12px;text-align:left;">'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                                '<input type="radio" required class="@error('Outsourced') is-invalid @enderror" name="Outsourced" value="Yes" autocomplete="Outsourced"> Yes'+
                          '</label>'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                              '<input type="radio" required class="@error('Outsourced') is-invalid @enderror" name="Outsourced" value="None" autocomplete="Outsourced"> None'+
                          '</label>'+
                      '</div>'+
                  '</div>'+
                  
                '</div>'+
              '</div>'+
          '</div>'+
          '<div class="col-sm-12">'+
              '<button  class="btn btn-success float-sm-right" name="submitplotbtn" id="submitplotbtn"  type="submit" >Save Property Information</button>'+
          '</div>'+
      '</div>'+
      '</form>');
    
    $("#modalnewproperty-title").html('Add New Property');
    $("#modalnewproperty").modal('show');

    $("#formplotmgrsaveplot").on('submit',(function(e){
      e.preventDefault();
      let plotname=document.getElementById('Plotname').value;
      let plotcode=document.getElementById('Plotcode').value;

      $("#modalconfirmsaveproperty-body").html(''+
      '<form action="/properties/manage/property/delete" name="formplotmgrsaveconfirmplot" id="formplotmgrsaveconfirmplot" method="post">'+
        '@csrf'+
        'Sure to Save <br/><b>'+ 'Property :' + plotname+' ('+ plotcode +') </b>?<br/>'+
        '<button class="btn btn-success float-sm-right modalconfirmsaveproperty-okay">Save Now</button>'+
        '</form>');
      $("#modalconfirmsaveproperty-title").html('Saving Property <b>'+ plotname+' ('+ plotcode +') </b>');
      $("#modalconfirmsaveproperty").modal('show');
      
      $("#formplotmgrsaveconfirmplot").on('submit',(function(e){
        e.preventDefault();
        $(".modalconfirmsaveproperty-okay").html("Saving... "+'<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
        $.ajax({
            url:'/properties/manage/property/save',
            type:"POST",
            data:new FormData(document.getElementById('formplotmgrsaveplot')),
            processData:false,
            contentType:false,
            cache:false,
            success:function(data){
              if(data['error']){
                  $(document).Toasts('create', {
                      title: 'Error in Saving',
                      class: 'bg-warning',
                      position: 'topRight',
                      body: data['error']
                  })
                  $("#modalconfirmsaveproperty").modal('hide');
              }
              else{
                  $(document).Toasts('create', {
                      title: 'Saving',
                      class: 'bg-success',
                      position: 'topRight',
                      body: data['success']
                  })
                  $("#modalconfirmsaveproperty").modal('hide');
                  getProperties();
              }
            },
            error: function(xhr, status, error){
              var errorMessage = xhr.status + ': ' + xhr.statusText
              if(xhr.status==419){
                window.location.href='';
              }
              if (errorMessage=="0: error") {
                  errorMessage="Internet Connection Interrupted.";
              }
              else if(errorMessage=="404: Not Found"){
                  errorMessage="Could Not Process Data."
              }
              else if(errorMessage=="500: Internal Server Error"){
                  errorMessage="Failed due to Some Technical Error In a Server."
              }
              if(xhr.status==422){
                errorMessage = xhr.responseText;
              }

              if(xhr.status==404){
                errorMessage ="Could not Locate Resource.";
              }

              if(xhr.status==500){
                errorMessage = "Failed due to Some Technical Error In a Server."
              }
              $(document).Toasts('create', {
                  title: 'Error Saving',
                  class: 'bg-warning',
                  position: 'topRight',
                  body: errorMessage
              })
              $('.modalconfirmsaveproperty-okay').html('<h6 class="text-center">Try Saving Again</h6>');
            }
          });  
      }));
      
    }));
  
  }));
  
  $(document).on('click','.btnplotmgrnewtenant',(function(e){
    $("#modalnewproperty-body").html(''+
        '<form role="form" class="form-horizontal" method="POST" name="formplotmgrsavetenant" id="formplotmgrsavetenant">'+
          '<div class="row m-0 p-0">'+
          '@csrf'+
          '<div class="col-sm-6 m-0 p-1">'+
              '<div class="card card-primary card-outline" style="margin-bottom: 5%;text-align: center;">'+
                '<div class="card-body">'+

                  '<div class="form-group row">'+
                      '<label for="Fname" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="Fname" type="text" class="form-control @error('Fname') is-invalid @enderror" name="Fname" value="{{ old('Fname') }}" placeholder="First Name" required autocomplete="Fname" autofocus>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Oname" class="col-md-4 col-form-label text-md-right">{{ __('Other Name') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="Oname" type="text" class="form-control @error('Oname') is-invalid @enderror" name="Oname" value="{{ old('Oname') }}" placeholder="Other Name" required autocomplete="Oname" autofocus>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Gender" class="col-md-4 col-form-label text-md-right">{{ __('Gender') }}</label>'+

                      '<div class="col-md-8"  style="background-color: white;color: black;padding-top: 12px;text-align:left;">'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                                '<input type="radio" required class="@error('Gender') is-invalid @enderror" name="Gender" value="Male" autocomplete="Gender"> Male'+
                          '</label>'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                              '<input type="radio" required class="@error('Gender') is-invalid @enderror" name="Gender" value="Female" autocomplete="Gender"> Female'+
                          '</label>'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                              '<input type="radio" required class="@error('Gender') is-invalid @enderror" name="Gender" value="Other" autocomplete="Gender"> Other'+
                          '</label>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="IDno" class="col-md-4 col-form-label text-md-right">{{ __('IDNo') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="IDno" type="text" class="form-control @error('IDno') is-invalid @enderror" name="IDno" value="{{ old('IDno') }}" placeholder="Number(ID or Passport)" required autocomplete="IDno" autofocus>'+
                      '</div>'+
                  '</div>'+

                '</div>'+
              '</div>'+
          '</div>'+

          '<div class="col-sm-6 m-0 p-1">'+
              '<div class="card card-primary card-outline" style="margin-bottom: 5%;text-align: center;">'+
                '<div class="card-body">'+
                
                  '<div class="form-group row">'+
                      '<label for="HudumaNo" class="col-md-4 col-form-label text-md-right">{{ __('HudumaNo') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="HudumaNo" type="text" class="form-control @error('HudumaNo') is-invalid @enderror" name="HudumaNo" value="{{ old('HudumaNo') }}" placeholder="Huduma Number" autocomplete="HudumaNo" autofocus>'+
                      '</div>'+
                  '</div>'+
                  
                  '<div class="form-group row">'+
                      '<label for="Phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="Phone" type="text" class="form-control @error('Phone') is-invalid @enderror" name="Phone" value="{{ old('Phone') }}" placeholder="7xxxxxxxx" required autocomplete="Phone" autofocus>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="Email" type="email" class="form-control @error('Email') is-invalid @enderror" name="Email" value="{{ old('Email') }}" placeholder="Tenant\'s Email" autocomplete="Email" autofocus>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Status" class="col-md-4 col-form-label text-md-right">{{ __('Status') }}</label>'+

                      '<div class="col-md-8" style="background-color: white;color: black;padding-top: 12px;text-align:left;">'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                                '<input type="radio" required class="@error('Status') is-invalid @enderror" name="Status" value="New" autocomplete="Status"> Tenant'+
                          '</label>'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                              '<input type="radio" required class="@error('Status') is-invalid @enderror" name="Status" value="Other" autocomplete="Status"> Other'+
                          '</label>'+
                      '</div>'+
                  '</div>'+
                  
                '</div>'+
              '</div>'+
          '</div>'+
          '<div class="col-sm-12">'+
              '<button  class="btn btn-success float-sm-right" name="submitplotbtn" id="submitplotbtn"  type="submit" >Save Tenant Information</button>'+
          '</div>'+
      '</div>'+
      '</form>');
    $("#modalnewproperty-title").html('Add New Tenant');
    $("#modalnewproperty").modal('show');

    $(document).on('change','#IDno', function(){ 
      var IDno=$('#IDno').val();
        $('#HudumaNo').val(IDno);
    });

    $("#formplotmgrsavetenant").on('submit',(function(e){
      e.preventDefault();
      let fname=document.getElementById('Fname').value;
      $("#modalconfirmsaveproperty-body").html(''+
      '<form action="/properties/manage/property/delete" name="formplotmgrsaveconfirmtenant" id="formplotmgrsaveconfirmtenant" method="post">'+
        '@csrf'+
        'Sure to Save <br/><b>'+ 'Tenant :' + fname+' </b>?<br/>'+
        '<button class="btn btn-success float-sm-right modalconfirmsaveproperty-okay">Save Now</button>'+
        '</form>');
      $("#modalconfirmsaveproperty-title").html('Saving Tenant <b>'+ fname+' </b>');
      $("#modalconfirmsaveproperty").modal('show');
      
      $("#formplotmgrsaveconfirmtenant").on('submit',(function(e){
        e.preventDefault();
        $(".modalconfirmsaveproperty-okay").html("Saving... "+'<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
        $.ajax({
            url:'/properties/manage/tenant/save',
            type:"POST",
            data:new FormData(document.getElementById('formplotmgrsavetenant')),
            processData:false,
            contentType:false,
            cache:false,
            success:function(data){
              if(data['error']){
                  $(document).Toasts('create', {
                      title: 'Error in Saving',
                      class: 'bg-warning',
                      position: 'topRight',
                      body: data['error']
                  })
                  $("#modalconfirmsaveproperty").modal('hide');
              }
              else{
                  $(document).Toasts('create', {
                      title: 'Saving',
                      class: 'bg-success',
                      position: 'topRight',
                      body: data['success']
                  })
                  $("#modalconfirmsaveproperty").modal('hide');
                  getTenants('New','Not Assigned');
              }
            },
            error: function(xhr, status, error){
              var errorMessage = xhr.status + ': ' + xhr.statusText
              if(xhr.status==419){
                window.location.href='';
              }
              if (errorMessage=="0: error") {
                  errorMessage="Internet Connection Interrupted.";
              }
              else if(errorMessage=="404: Not Found"){
                  errorMessage="Could Not Process Data."
              }
              else if(errorMessage=="500: Internal Server Error"){
                  errorMessage="Failed due to Some Technical Error In a Server."
              }
              if(xhr.status==422){
                errorMessage = xhr.responseText;
              }

              if(xhr.status==404){
                errorMessage ="Could not Locate Resource.";
              }

              if(xhr.status==500){
                errorMessage = "Failed due to Some Technical Error In a Server."
              }
              $(document).Toasts('create', {
                  title: 'Error Saving',
                  class: 'bg-warning',
                  position: 'topRight',
                  body: errorMessage
              })
              $('.modalconfirmsaveproperty-okay').html('<h6 class="text-center">Try Saving Again</h6>');
            }
          });  
      }));
      
    }));
  
  }));

  $(document).on('click','.btnplotmgrnewhouse',(function(e){
    var plotid = $(this).data("id0");
    var plotname = $(this).data("id1");
    var plotcode = $(this).data("id2");
    $("#modalnewproperty-body").html(''+
        '<form role="form" class="form-horizontal" method="POST" name="formplotmgrsavehouse" id="formplotmgrsavehouse">'+
          '<div class="row m-0 p-0">'+
          '@csrf'+
          '<div class="col-sm-6 m-0 p-1">'+
              '<div class="card card-primary card-outline" style="margin-bottom: 5%;text-align: center;">'+
                '<div class="card-body">'+

                  '<div class="form-group row">'+
                      '<label for="Housename" class="col-md-4 col-form-label text-md-right">{{ __('House Name') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="Plotid" type="hidden" class="form-control @error('Plotid') is-invalid @enderror" name="Plotid" value="'+ plotid+'" placeholder="Property Id" required autocomplete="Plotid" autofocus>'+
                          '<input id="Plotcode" type="hidden" class="form-control @error('Plotcode') is-invalid @enderror" name="Plotcode" value="'+ plotcode+'" placeholder="Property Code" required autocomplete="Plotcode" autofocus>'+
                          '<input id="Plotname" type="hidden" class="form-control @error('Plotname') is-invalid @enderror" name="Plotname" value="'+ plotname+'" placeholder="Property Name" required autocomplete="Plotname" autofocus>'+
                          '<input id="Housename" type="text" class="form-control @error('Housename') is-invalid @enderror" name="Housename" value="'+ plotcode+'-" placeholder="Full Housename with Property Code i.e NG-A2,E1-D5,D1-G4" required autocomplete="Housename" autofocus>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Rent" class="col-md-4 col-form-label text-md-right">{{ __('House Rent') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="Rent" type="text" class="form-control @error('Rent') is-invalid @enderror" name="Rent" value="{{ old('Rent') }}" placeholder="0.00" required autocomplete="Rent" autofocus>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Deposit" class="col-md-4 col-form-label text-md-right">{{ __('House Deposit') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="Deposit" type="text" class="form-control @error('Deposit') is-invalid @enderror" name="Deposit" value="{{ old('Deposit') }}" placeholder="0.00" required autocomplete="Deposit" autofocus>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Kplc" class="col-md-4 col-form-label text-md-right">{{ __('Kplc Deposit') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="Kplc" type="text" class="form-control @error('Kplc') is-invalid @enderror" name="Kplc" value="{{ old('Kplc') }}" placeholder="0.00" required autocomplete="Kplc" autofocus>'+
                      '</div>'+
                  '</div>'+
                  

                '</div>'+
              '</div>'+
          '</div>'+

          '<div class="col-sm-6 m-0 p-1">'+
              '<div class="card card-primary card-outline" style="margin-bottom: 5%;text-align: center;">'+
                '<div class="card-body">'+

                '<div class="form-group row">'+
                    '<label for="Water" class="col-md-4 col-form-label text-md-right">{{ __('Water Deposit') }}</label>'+

                    '<div class="col-md-8">'+
                        '<input id="Water" type="text" class="form-control @error('Water') is-invalid @enderror" name="Water" value="{{ old('Water') }}" placeholder="0.00" required autocomplete="Water" autofocus>'+
                    '</div>'+
                '</div>'+

                '<div class="form-group row">'+
                    '<label for="Lease" class="col-md-4 col-form-label text-md-right">{{ __('Lease Amount') }}</label>'+

                    '<div class="col-md-8">'+
                        '<input id="Lease" type="text" class="form-control @error('Lease') is-invalid @enderror" name="Lease" value="{{ old('Lease') }}" placeholder="0.00" required autocomplete="Lease" autofocus>'+
                    '</div>'+
                '</div>'+

                '<div class="form-group row">'+
                    '<label for="Garbage" class="col-md-4 col-form-label text-md-right">{{ __('Garbage Deposit') }}</label>'+

                    '<div class="col-md-8">'+
                        '<input id="Garbage" type="text" class="form-control @error('Garbage') is-invalid @enderror" name="Garbage" value="{{ old('Garbage') }}" placeholder="0.00" required autocomplete="Garbage" autofocus>'+
                    '</div>'+
                '</div>'+

                '<div class="form-group row">'+
                    '<label for="DueDay" class="col-md-4 col-form-label text-md-right">{{ __('Due Date ') }}</label>'+

                    '<div class="col-md-8">'+
                        '<input id="DueDay" type="number" min="1" max="31" class="form-control @error('DueDay') is-invalid @enderror" name="DueDay" value="{{ old('DueDay') }}" placeholder="Due Day" required autocomplete="DueDay" autofocus>'+
                    '</div>'+
                '</div>'+

                '</div>'+
              '</div>'+
          '</div>'+
          '<div class="col-sm-12">'+
              '<button  class="btn btn-success float-sm-right" name="submitplotbtn" id="submitplotbtn"  type="submit" >Save House Information</button>'+
          '</div>'+
      '</div>'+
      '</form>');
    $("#modalnewproperty-title").html('Add New House for Property :' + plotname+' ('+ plotcode +')');
    $("#modalnewproperty").modal('show');

    $(document).on('change','#Rent', function(){ 
     var Rent=$('#Rent').val();
      $('#Deposit').val(Rent);
    });

    $("#formplotmgrsavehouse").on('submit',(function(e){
      e.preventDefault();
      let housename=document.getElementById('Housename').value;

      $("#modalconfirmsaveproperty-body").html(''+
      '<form action="/properties/manage/house/save" name="formplotmgrsaveconfirmhouse" id="formplotmgrsaveconfirmhouse" method="post">'+
        '@csrf'+
        'Sure to Save <br/><b>'+ 'House :' + housename+' </b> For Property '+ plotcode +' ?<br/>'+
        '<button class="btn btn-success float-sm-right modalconfirmsaveproperty-okay">Save Now</button>'+
        '</form>');
      $("#modalconfirmsaveproperty-title").html('Saving House <b>'+ housename+' </b> For Property '+ plotcode);
      $("#modalconfirmsaveproperty").modal('show');
      
      $("#formplotmgrsaveconfirmhouse").on('submit',(function(e){
        e.preventDefault();
        $(".modalconfirmsaveproperty-okay").html("Saving... "+'<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
        $.ajax({
            url:'/properties/manage/house/save',
            type:"POST",
            data:new FormData(document.getElementById('formplotmgrsavehouse')),
            processData:false,
            contentType:false,
            cache:false,
            success:function(data){
              if(data['error']){
                  $(document).Toasts('create', {
                      title: 'Error in Saving',
                      class: 'bg-warning',
                      position: 'topRight',
                      body: data['error']
                  })
                  $("#modalconfirmsaveproperty").modal('hide');
              }
              else{
                  $(document).Toasts('create', {
                      title: 'Saving',
                      class: 'bg-success',
                      position: 'topRight',
                      body: data['success']
                  })
                  $("#modalconfirmsaveproperty").modal('hide');
                  getProperties();
                  getHouses(plotid,plotcode,plotname);
              }
            },
            error: function(xhr, status, error){
              var errorMessage = xhr.status + ': ' + xhr.statusText
              if(xhr.status==419){
                window.location.href='';
              }
              if (errorMessage=="0: error") {
                  errorMessage="Internet Connection Interrupted.";
              }
              else if(errorMessage=="404: Not Found"){
                  errorMessage="Could Not Process Data."
              }
              else if(errorMessage=="500: Internal Server Error"){
                  errorMessage="Failed due to Some Technical Error In a Server."
              }
              if(xhr.status==422){
                errorMessage = xhr.responseText;
              }

              if(xhr.status==404){
                errorMessage ="Could not Locate Resource.";
              }

              if(xhr.status==500){
                errorMessage = "Failed due to Some Technical Error In a Server."
              }
              $(document).Toasts('create', {
                  title: 'Error Saving',
                  class: 'bg-warning',
                  position: 'topRight',
                  body: errorMessage
              })
              $('.modalconfirmsaveproperty-okay').html('<h6 class="text-center">Try Saving Again</h6>');
            }
          });  
      }));
      
    }));
  
  }));

  $(document).on('click','.btnplotmgreditplot',(function(e){
    var plotid = $(this).data("id0");
    var plotname = $(this).data("id1");
    var plotcode = $(this).data("id2");
    var plotarea = $(this).data("id3");
    var plotaddr = $(this).data("id4");
    var plotdesc = $(this).data("id5");
    var waterbill = $(this).data("id6");
    var deposit = $(this).data("id7");
    var waterdeposit = $(this).data("id8");
    var outsourced = $(this).data("id9");
    var garbage = $(this).data("id10");
    var kplcdeposit = $(this).data("id11");
    $("#modaleditproperty-body").html(''+
        '<form role="form" class="form-horizontal" method="POST" name="formplotmgreditplot" id="formplotmgreditplot">'+
          '<div class="row m-0 p-0">'+
          '@csrf'+
          '<div class="col-sm-6 m-0 p-1">'+
              '<div class="card card-primary card-outline" style="margin-bottom: 5%;min-height: 300px;text-align: center;">'+
                '<div class="card-body">'+

                  '<div class="form-group row">'+
                      '<label for="Plotname" class="col-md-4 col-form-label text-md-right">{{ __('Property Name') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="Plotid" type="hidden" class="form-control @error('Plotid') is-invalid @enderror" name="Plotid" value="'+ plotid+'" placeholder="Property Id" required autocomplete="Plotid" autofocus>'+
                          '<input id="Plotname" type="text" class="form-control @error('Plotname') is-invalid @enderror" name="Plotname" value="'+ plotname+'" placeholder="Property Name" required autocomplete="Plotname" autofocus>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Plotarea" class="col-md-4 col-form-label text-md-right">{{ __('Property Area') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="Plotarea" type="text" class="form-control @error('Plotarea') is-invalid @enderror" name="Plotarea" value="'+ plotarea+'" placeholder="Property Area" required autocomplete="Plotarea" autofocus>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Plotcode" class="col-md-4 col-form-label text-md-right">{{ __('Property Code') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="Plotcode" type="text" class="form-control @error('Plotcode') is-invalid @enderror" name="Plotcode" value="'+ plotcode+'" placeholder="Property Code" required autocomplete="Plotcode" autofocus>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Plotaddr" class="col-md-4 col-form-label text-md-right">{{ __('Property Addr') }}</label>'+

                      '<div class="col-md-8">'+
                          '<textarea  id="Plotaddr" type="text" class="form-control @error('Plotaddr') is-invalid @enderror" name="Plotaddr" placeholder="Property Address" required autocomplete="Plotaddr" autofocus>'+ plotaddr+'</textarea>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Plotdesc" class="col-md-4 col-form-label text-md-right">{{ __('Property Desc') }}</label>'+

                      '<div class="col-md-8">'+
                          '<textarea id="Plotdesc" type="text" class="form-control @error('Plotdesc') is-invalid @enderror" name="Plotdesc" placeholder="Property Description" required autocomplete="Plotdesc" autofocus>'+ plotdesc+'</textarea>'+
                      '</div>'+
                  '</div>'+

                '</div>'+
              '</div>'+
          '</div>'+

          '<div class="col-sm-6 m-0 p-1">'+
              '<div class="card card-primary card-outline" style="margin-bottom: 5%;min-height: 300px;text-align: center;">'+
                '<div class="card-body">'+

                  '<div class="form-group row">'+
                      '<label for="Waterbill" class="col-md-4 col-form-label text-md-right">{{ __('Water Bill') }}</label>'+

                      '<div class="col-md-8" style="background-color: white;color: black;padding-top: 12px;text-align:left;">'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                                '<input type="radio" required '+(waterbill=="Monthly"?"checked":"")+' class="@error('Waterbill') is-invalid @enderror" name="Waterbill" value="Monthly" autocomplete="Waterbill"> Paid Monthly'+
                          '</label>'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                              '<input type="radio" required '+(waterbill=="Monthly"?"":"checked")+' class="@error('Waterbill') is-invalid @enderror" name="Waterbill" value="None" autocomplete="Waterbill"> None'+
                          '</label>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Waterdeposit" class="col-md-4 col-form-label text-md-right">{{ __('Water Deposit') }}</label>'+

                      '<div class="col-md-8"  style="background-color: white;color: black;padding-top: 12px;text-align:left;">'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                                '<input type="radio" required '+(waterdeposit=="Once"?"checked":"")+' class="@error('Waterdeposit') is-invalid @enderror" name="Waterdeposit" value="Once" autocomplete="Waterdeposit"> Paid Once'+
                          '</label>'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                              '<input type="radio" required '+(waterdeposit=="Once"?"":"checked")+' class="@error('Waterdeposit') is-invalid @enderror" name="Waterdeposit" value="None" autocomplete="Waterdeposit"> None'+
                          '</label>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Deposit" class="col-md-4 col-form-label text-md-right">{{ __('Deposit') }}</label>'+

                      '<div class="col-md-8"  style="background-color: white;color: black;padding-top: 12px;text-align:left;">'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                                '<input type="radio" required '+(deposit=="Once"?"checked":"")+' class="@error('Deposit') is-invalid @enderror" name="Deposit" value="Once" autocomplete="Deposit"> Paid Once'+
                          '</label>'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                              '<input type="radio" required '+(deposit=="Once"?"":"checked")+' class="@error('Deposit') is-invalid @enderror" name="Deposit" value="None" autocomplete="Deposit"> None'+
                          '</label>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Garbage" class="col-md-4 col-form-label text-md-right">{{ __('Garbage') }}</label>'+

                      '<div class="col-md-8"  style="background-color: white;color: black;padding-top: 12px;text-align:left;">'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                                '<input type="radio" required '+(garbage=="Monthly"?"checked":"")+' class="@error('Garbage') is-invalid @enderror" name="Garbage" value="Monthly" autocomplete="Garbage"> Paid Monthly'+
                          '</label>'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                              '<input type="radio" required '+(garbage=="Monthly"?"":"checked")+' class="@error('Garbage') is-invalid @enderror" name="Garbage" value="None" autocomplete="Garbage"> None'+
                          '</label>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Kplcdeposit" class="col-md-4 col-form-label text-md-right">{{ __('KPLC Deposit') }}</label>'+

                      '<div class="col-md-8"  style="background-color: white;color: black;padding-top: 12px;text-align:left;">'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                                '<input type="radio" required '+(kplcdeposit=="Once"?"checked":"")+' class="@error('Kplcdeposit') is-invalid @enderror" name="Kplcdeposit" value="Once" autocomplete="Kplcdeposit"> Paid Once'+
                          '</label>'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                              '<input type="radio" required '+(kplcdeposit=="Once"?"":"checked")+' class="@error('Kplcdeposit') is-invalid @enderror" name="Kplcdeposit" value="None" autocomplete="Kplcdeposit"> None'+
                          '</label>'+
                      '</div>'+
                  '</div>'+


                  '<div class="form-group row">'+
                      '<label for="Outsourced" class="col-md-4 col-form-label text-md-right">{{ __('Outsourced Water') }}</label>'+

                      '<div class="col-md-8"  style="background-color: white;color: black;padding-top: 12px;text-align:left;">'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                                '<input type="radio" required '+(outsourced=="Yes"?"checked":"")+' class="@error('Outsourced') is-invalid @enderror" name="Outsourced" value="Yes" autocomplete="Outsourced"> Yes'+
                          '</label>'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                              '<input type="radio" required '+(outsourced=="Yes"?"":"checked")+' class="@error('Outsourced') is-invalid @enderror" name="Outsourced" value="None" autocomplete="Outsourced"> None'+
                          '</label>'+
                      '</div>'+
                  '</div>'+
                  
                '</div>'+
              '</div>'+
          '</div>'+
          '<div class="col-sm-12">'+
              '<button  class="btn btn-primary float-sm-right" name="submitplotbtn" id="submitplotbtn"  type="submit" >Update Property Information</button>'+
          '</div>'+
      '</div>'+
      '</form>');
      $("#modaleditproperty-title").html('Update Property :' + plotname+' ('+ plotcode +')');
      
    $("#modaleditproperty").modal('show');

    $("#formplotmgreditplot").on('submit',(function(e){
      e.preventDefault();
      let plotname=document.getElementById('Plotname').value;
      let plotcode=document.getElementById('Plotcode').value;

      $("#modalconfirmsaveproperty-body").html(''+
      '<form action="/properties/manage/property/update" name="formplotmgrsaveconfirmeditplot" id="formplotmgrsaveconfirmeditplot" method="post">'+
        '@csrf'+
        'Sure to Save <br/><b>'+ 'Property :' + plotname+' ('+ plotcode +') </b>?<br/>'+
        '<button class="btn btn-success float-sm-right modalconfirmsaveproperty-okay">Update Property :' + plotname+' Now</button>'+
        '</form>');
      $("#modalconfirmsaveproperty-title").html('Updating Property <b>'+ plotname+' ('+ plotcode +') </b>');
      $("#modalconfirmsaveproperty").modal('show');
      
      $("#formplotmgrsaveconfirmeditplot").on('submit',(function(e){
        e.preventDefault();
        $(".modalconfirmsaveproperty-okay").html("Upating... "+'<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
        $.ajax({
            url:'/properties/manage/property/update',
            type:"POST",
            data:new FormData(document.getElementById('formplotmgreditplot')),
            processData:false,
            contentType:false,
            cache:false,
            success:function(data){
              if(data['error']){
                  $(document).Toasts('create', {
                      title: 'Error in Updating',
                      class: 'bg-warning',
                      position: 'topRight',
                      body: data['error']
                  })
                  $("#modalconfirmsaveproperty").modal('hide');
                  $("#modaleditproperty").modal('hide');
                  
              }
              else{
                  $(document).Toasts('create', {
                      title: 'Updating',
                      class: 'bg-success',
                      position: 'topRight',
                      body: data['success']
                  })
                  $("#modalconfirmsaveproperty").modal('hide');
                  $("#modaleditproperty").modal('hide');
                  getProperties();
              }
            },
            error: function(xhr, status, error){
              var errorMessage = xhr.status + ': ' + xhr.statusText
              if(xhr.status==419){
                window.location.href='';
              }
              if (errorMessage=="0: error") {
                  errorMessage="Internet Connection Interrupted.";
              }
              else if(errorMessage=="404: Not Found"){
                  errorMessage="Could Not Process Data."
              }
              else if(errorMessage=="500: Internal Server Error"){
                  errorMessage="Failed due to Some Technical Error In a Server."
              }
              if(xhr.status==422){
                errorMessage = xhr.responseText;
              }

              if(xhr.status==404){
                errorMessage ="Could not Locate Resource.";
              }

              if(xhr.status==500){
                errorMessage = "Failed due to Some Technical Error In a Server."
              }
              $(document).Toasts('create', {
                  title: 'Error Updating',
                  class: 'bg-warning',
                  position: 'topRight',
                  body: errorMessage
              })
              $('.modalconfirmsaveproperty-okay').html('<h6 class="text-center">Try Updating Again</h6>');
            }
          });  
      }));
      
    }));
  
  }));

  $(document).on('click', '.btnplotmgrdelplot', function(e){
    e.preventDefault();
    var plotid = $(this).data("id0");
    var plotname = $(this).data("id1");
    var plotcode = $(this).data("id2");
    $("#modalconfirmdel-body").html(''+
      '<form action="/properties/manage/property/delete" name="formplotmgrdelplot" id="formplotmgrdelplot" method="post">'+
        '@csrf'+
        'Sure to Delete <br/><b>'+ 'Property :' + plotname+' </b>?<br/>'+
        '<input type="hidden" id="delpid" name="delpid" value="'+plotid+'">'+
        '<button class="btn btn-danger float-sm-right modalconfirmdel-okay">Delete Now</button>'+
      '</form>');
    $("#modalconfirmdel").modal('show');

    $("#formplotmgrdelplot").on('submit',(function(e){
      e.preventDefault();
      let delpiddoc=document.getElementById('delpid');
      let delpid=delpiddoc.value;
      $(".modalconfirmdel-okay").html("Deleting... "+'<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
      $.ajax({
          url:'/properties/manage/property/delete',
          type:"POST",
          data:new FormData(document.getElementById('formplotmgrdelplot')),
          processData:false,
          contentType:false,
          cache:false,
          success:function(data){
            if(data['error']){
                $(document).Toasts('create', {
                    title: 'Error in Deleting',
                    class: 'bg-warning',
                    position: 'topRight',
                    body: data['error']
                })
                $("#modalconfirmdel").modal('hide');
            }
            else{
                $(document).Toasts('create', {
                    title: 'Deleting',
                    class: 'bg-success',
                    position: 'topRight',
                    body: data['success']
                })
                $("#modalconfirmdel").modal('hide');
                getProperties();
            }
          },
          error: function(xhr, status, error){
            var errorMessage = xhr.status + ': ' + xhr.statusText
            if(xhr.status==419){
              window.location.href='';
            }
            if (errorMessage=="0: error") {
                errorMessage="Internet Connection Interrupted.";
            }
            else if(errorMessage=="404: Not Found"){
                errorMessage="Could Not Process Data."
            }
            else if(errorMessage=="500: Internal Server Error"){
                errorMessage="Failed due to Some Technical Error In a Server."
            }
            
            if(xhr.status==422){
              errorMessage = xhr.responseText;
            }

            if(xhr.status==404){
              errorMessage ="Could not Locate Resource.";
            }

            if(xhr.status==500){
              errorMessage = "Failed due to Some Technical Error In a Server."
            }
            $(document).Toasts('create', {
                title: 'Error Deleting',
                class: 'bg-warning',
                position: 'topRight',
                body: errorMessage
            })
            $('.modalconfirmdel-okay').html('<h6 class="text-center">Try Deleting Again</h6>');
          }
        });  
    }));
  }); 

  $(document).on('click','.btnplotmgrhseplot',(function(e){
    var plotid = $(this).data("id0");
    var plotname = $(this).data("id1");
    var plotcode = $(this).data("id2");
    getHouses(plotid,plotcode,plotname);
  }));

  $(document).on('click','.btnplotmgredithouse',(function(e){
    var hid = $(this).data("id0");
    var housename = $(this).data("id1");
    var rent = $(this).data("id2");
    var deposit = $(this).data("id3");
    var water = $(this).data("id4");
    var lease = $(this).data("id5");
    var garbage = $(this).data("id6");
    var dueday = $(this).data("id7");
    var kplc = $(this).data("id8");
    var plotid = $(this).data("id9");
    var plotcode = $(this).data("id10");
    var plotname = $(this).data("id11");
    $("#modaleditproperty-body").html(''+
        '<form role="form" class="form-horizontal" method="POST" name="formplotmgrsaveedithouse" id="formplotmgrsaveedithouse">'+
          '<div class="row m-0 p-0">'+
          '@csrf'+
          '<div class="col-sm-6 m-0 p-1">'+
              '<div class="card card-primary card-outline" style="margin-bottom: 5%;text-align: center;">'+
                '<div class="card-body">'+

                  '<div class="form-group row">'+
                      '<label for="Housename" class="col-md-4 col-form-label text-md-right">{{ __('House Name') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="Plotid" type="hidden" class="form-control @error('Plotid') is-invalid @enderror" name="Plotid" value="'+ plotid+'" placeholder="Property Id" required autocomplete="Plotid" autofocus>'+
                          '<input id="hid" type="hidden" class="form-control @error('hid') is-invalid @enderror" name="hid" value="'+hid+'" placeholder="House Id" required autocomplete="hid" autofocus>'+
                          '<input id="Plotcodes" type="hidden" class="form-control @error('Plotcode') is-invalid @enderror" name="Plotcode" value="'+ plotcode+'" placeholder="Property Code" required autocomplete="Plotcode" autofocus>'+
                          '<input id="Plotnames" type="hidden" class="form-control @error('Plotname') is-invalid @enderror" name="Plotname" value="'+ plotname+'" placeholder="Property Name" required autocomplete="Plotname" autofocus>'+
                          '<input id="Housename" type="text" class="form-control @error('Housename') is-invalid @enderror" name="Housename" value="'+ housename+'" placeholder="Full Housename with Property Code i.e NG-A2,E1-D5,D1-G4" required autocomplete="Housename" autofocus>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Rent" class="col-md-4 col-form-label text-md-right">{{ __('House Rent') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="Rent" type="text" class="form-control @error('Rent') is-invalid @enderror" name="Rent" value="'+rent+'" placeholder="0.00" required autocomplete="Rent" autofocus>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Deposit" class="col-md-4 col-form-label text-md-right">{{ __('House Deposit') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="Deposit" type="text" class="form-control @error('Deposit') is-invalid @enderror" name="Deposit" value="'+deposit+'" placeholder="0.00" required autocomplete="Deposit" autofocus>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Kplc" class="col-md-4 col-form-label text-md-right">{{ __('Kplc Deposit') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="Kplc" type="text" class="form-control @error('Kplc') is-invalid @enderror" name="Kplc" value="'+kplc+'" placeholder="0.00" required autocomplete="Kplc" autofocus>'+
                      '</div>'+
                  '</div>'+
                  

                '</div>'+
              '</div>'+
          '</div>'+

          '<div class="col-sm-6 m-0 p-1">'+
              '<div class="card card-primary card-outline" style="margin-bottom: 5%;text-align: center;">'+
                '<div class="card-body">'+

                '<div class="form-group row">'+
                    '<label for="Water" class="col-md-4 col-form-label text-md-right">{{ __('Water Deposit') }}</label>'+

                    '<div class="col-md-8">'+
                        '<input id="Water" type="text" class="form-control @error('Water') is-invalid @enderror" name="Water" value="'+water+'" placeholder="0.00" required autocomplete="Water" autofocus>'+
                    '</div>'+
                '</div>'+

                '<div class="form-group row">'+
                    '<label for="Lease" class="col-md-4 col-form-label text-md-right">{{ __('Lease Amount') }}</label>'+

                    '<div class="col-md-8">'+
                        '<input id="Lease" type="text" class="form-control @error('Lease') is-invalid @enderror" name="Lease" value="'+lease+'" placeholder="0.00" required autocomplete="Lease" autofocus>'+
                    '</div>'+
                '</div>'+

                '<div class="form-group row">'+
                    '<label for="Garbage" class="col-md-4 col-form-label text-md-right">{{ __('Garbage Deposit') }}</label>'+

                    '<div class="col-md-8">'+
                        '<input id="Garbage" type="text" class="form-control @error('Garbage') is-invalid @enderror" name="Garbage" value="'+garbage+'" placeholder="0.00" required autocomplete="Garbage" autofocus>'+
                    '</div>'+
                '</div>'+

                '<div class="form-group row">'+
                    '<label for="DueDay" class="col-md-4 col-form-label text-md-right">{{ __('Due Date ') }}</label>'+

                    '<div class="col-md-8">'+
                        '<input id="DueDay" type="number" min="1" max="31" class="form-control @error('DueDay') is-invalid @enderror" name="DueDay" value="'+dueday+'" placeholder="Due Day" required autocomplete="DueDay" autofocus>'+
                    '</div>'+
                '</div>'+

                '</div>'+
              '</div>'+
          '</div>'+
          '<div class="col-sm-12">'+
              '<button  class="btn btn-success float-sm-right" name="submitplotbtn" id="submitplotbtn"  type="submit" >Update House Information</button>'+
          '</div>'+
      '</div>'+
      '</form>');
    $("#modaleditproperty-title").html('Update House: ' + housename+' for Property :' + plotname+' ('+ plotcode +')');
    $("#modaleditproperty").modal('show');

    $(document).on('change','#Rent', function(){ 
     var Rent=$('#Rent').val();
      $('#Deposit').val(Rent);
    });

    $("#formplotmgrsaveedithouse").on('submit',(function(e){
      e.preventDefault();
      let housename=document.getElementById('Housename').value;
      let plotcode=document.getElementById('Plotcodes').value;

      $("#modalconfirmsaveproperty-body").html(''+
      '<form action="/properties/manage/house/save" name="formplotmgrsaveconfirmedithouse" id="formplotmgrsaveconfirmedithouse" method="post">'+
        '@csrf'+
        'Sure to Update <br/><b>'+ 'House :' + housename+' </b> For Property '+ plotcode +' ?<br/>'+
        '<button class="btn btn-success float-sm-right modalconfirmsaveproperty-okay">Update ' + housename+' </button>'+
        '</form>');
      $("#modalconfirmsaveproperty-title").html('Updating House <b>'+ housename+' </b> For Property '+ plotcode);
      $("#modalconfirmsaveproperty").modal('show');
      
      $("#formplotmgrsaveconfirmedithouse").on('submit',(function(e){
        e.preventDefault();
        $(".modalconfirmsaveproperty-okay").html("Updating... "+'<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
        $.ajax({
            url:'/properties/manage/house/update',
            type:"POST",
            data:new FormData(document.getElementById('formplotmgrsaveedithouse')),
            processData:false,
            contentType:false,
            cache:false,
            success:function(data){
              if(data['error']){
                  $(document).Toasts('create', {
                      title: 'Error in Saving',
                      class: 'bg-warning',
                      position: 'topRight',
                      body: data['error']
                  })
                  $("#modalconfirmsaveproperty").modal('hide');
              }
              else{
                  $(document).Toasts('create', {
                      title: 'Saving',
                      class: 'bg-success',
                      position: 'topRight',
                      body: data['success']
                  })
                  $("#modalconfirmsaveproperty").modal('hide');
                  getHouses(plotid,plotcode,plotname);
              }
            },
            error: function(xhr, status, error){
              var errorMessage = xhr.status + ': ' + xhr.statusText
              if(xhr.status==419){
                window.location.href='';
              }
              if (errorMessage=="0: error") {
                  errorMessage="Internet Connection Interrupted.";
              }
              else if(errorMessage=="404: Not Found"){
                  errorMessage="Could Not Process Data."
              }
              else if(errorMessage=="500: Internal Server Error"){
                  errorMessage="Failed due to Some Technical Error In a Server."
              }
              if(xhr.status==422){
                errorMessage = xhr.responseText;
              }

              if(xhr.status==404){
                errorMessage ="Could not Locate Resource.";
              }

              if(xhr.status==500){
                errorMessage = "Failed due to Some Technical Error In a Server."
              }
              $(document).Toasts('create', {
                  title: 'Error Saving',
                  class: 'bg-warning',
                  position: 'topRight',
                  body: errorMessage
              })
              $('.modalconfirmsaveproperty-okay').html('<h6 class="text-center">Try Updating Again</h6>');
            }
          });  
      }));
      
    }));
  
  }));

  $(document).on('click', '.btnplotmgrdelhouse', function(e){
    e.preventDefault();
    var houseid = $(this).data("id0");
    var housename = $(this).data("id1");
    var plot = $(this).data("id2");
    var plotcode = $(this).data("id3");
    var plotname = $(this).data("id4");
    $("#modalconfirmdel-body").html(''+
      '<form action="/properties/manage/property/delete" name="formplotmgrdelhouse" id="formplotmgrdelhouse" method="post">'+
        '@csrf'+
        'Sure to Delete <br/><b>'+ 'House :' + housename+' </b>?<br/>'+
        '<input type="hidden" id="delhid" name="delhid" value="'+houseid+'">'+
        '<button class="btn btn-danger float-sm-right modalconfirmdel-okay">Delete Now</button>'+
      '</form>');
    $("#modalconfirmdel").modal('show');

    $("#formplotmgrdelhouse").on('submit',(function(e){
      e.preventDefault();
      $(".modalconfirmdel-okay").html("Deleting... "+'<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
      $.ajax({
          url:'/properties/manage/house/delete',
          type:"POST",
          data:new FormData(document.getElementById('formplotmgrdelhouse')),
          processData:false,
          contentType:false,
          cache:false,
          success:function(data){
            if(data['error']){
                $(document).Toasts('create', {
                    title: 'Error in Deleting',
                    class: 'bg-warning',
                    position: 'topRight',
                    body: data['error']
                })
                $("#modalconfirmdel").modal('hide');
            }
            else{
                $(document).Toasts('create', {
                    title: 'Deleting',
                    class: 'bg-success',
                    position: 'topRight',
                    body: data['success']
                })
                $("#modalconfirmdel").modal('hide');
                getHouses(plot,plotcode,plotname)
            }
          },
          error: function(xhr, status, error){
            var errorMessage = xhr.status + ': ' + xhr.statusText
            if(xhr.status==419){
              window.location.href='';
            }
            if (errorMessage=="0: error") {
                errorMessage="Internet Connection Interrupted.";
            }
            else if(errorMessage=="404: Not Found"){
                errorMessage="Could Not Process Data."
            }
            else if(errorMessage=="500: Internal Server Error"){
                errorMessage="Failed due to Some Technical Error In a Server."
            }
            
            if(xhr.status==422){
              errorMessage = xhr.responseText;
            }

            if(xhr.status==404){
              errorMessage ="Could not Locate Resource.";
            }

            if(xhr.status==500){
              errorMessage = "Failed due to Some Technical Error In a Server."
            }
            $(document).Toasts('create', {
                title: 'Error Deleting',
                class: 'bg-warning',
                position: 'topRight',
                body: errorMessage
            })
            $('.modalconfirmdel-okay').html('<h6 class="text-center">Try Deleting Again</h6>');
          }
        });  
    }));
  }); 

  $(document).on('click', '.btnplotmgrdeltenant', function(e){
    e.preventDefault();
    var tenantid = $(this).data("id0");
    var tenantname = $(this).data("id1");
    var status = $(this).data("id2");
    var statusvalue = $(this).data("id3");
    $("#modalconfirmdel-body").html(''+
      '<form action="/properties/manage/property/delete" name="formplotmgrdeltenant" id="formplotmgrdeltenant" method="post">'+
        '@csrf'+
        'Sure to Delete <br/><b>'+ 'Tenant :' + tenantname+' </b>?<br/>'+
        '<input type="hidden" id="deltid" name="deltid" value="'+tenantid+'">'+
        '<button class="btn btn-danger float-sm-right modalconfirmdel-okay">Delete Now</button>'+
      '</form>');
    $("#modalconfirmdel").modal('show');

    $("#formplotmgrdeltenant").on('submit',(function(e){
      e.preventDefault();
      $(".modalconfirmdel-okay").html("Deleting... "+'<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
      $.ajax({
          url:'/properties/manage/tenant/delete',
          type:"POST",
          data:new FormData(document.getElementById('formplotmgrdeltenant')),
          processData:false,
          contentType:false,
          cache:false,
          success:function(data){
            if(data['error']){
                $(document).Toasts('create', {
                    title: 'Error in Deleting',
                    class: 'bg-warning',
                    position: 'topRight',
                    body: data['error']
                })
                $("#modalconfirmdel").modal('hide');
            }
            else{
                $(document).Toasts('create', {
                    title: 'Deleting',
                    class: 'bg-success',
                    position: 'topRight',
                    body: data['success']
                })
                $("#modalconfirmdel").modal('hide');
                getTenants(status,statusvalue);
            }
          },
          error: function(xhr, status, error){
            var errorMessage = xhr.status + ': ' + xhr.statusText
            if(xhr.status==419){
              window.location.href='';
            }
            if (errorMessage=="0: error") {
                errorMessage="Internet Connection Interrupted.";
            }
            else if(errorMessage=="404: Not Found"){
                errorMessage="Could Not Process Data."
            }
            else if(errorMessage=="500: Internal Server Error"){
                errorMessage="Failed due to Some Technical Error In a Server."
            }
            
            if(xhr.status==422){
              errorMessage = xhr.responseText;
            }

            if(xhr.status==404){
              errorMessage ="Could not Locate Resource.";
            }

            if(xhr.status==500){
              errorMessage = "Failed due to Some Technical Error In a Server."
            }
            $(document).Toasts('create', {
                title: 'Error Deleting',
                class: 'bg-warning',
                position: 'topRight',
                body: errorMessage
            })
            $('.modalconfirmdel-okay').html('<h6 class="text-center">Try Deleting Again</h6>');
          }
        });  
    }));
  }); 

  $(document).on('click','.btnplotmgredittenant',(function(e){
    var tid = $(this).data("id0");
    var Fname = $(this).data("id1");
    var Oname = $(this).data("id2");
    var Phone = $(this).data("id3");
    var IDno = $(this).data("id4");
    var HudumaNo = $(this).data("id5");
    var Email = $(this).data("id6");
    var Gender = $(this).data("id7");
    var statuss = $(this).data("id8");
    var statusvalue = $(this).data("id9");
    $("#modaleditproperty-body").html(''+
        '<form role="form" class="form-horizontal" method="POST" name="formplotmgrsaveedittenant" id="formplotmgrsaveedittenant">'+
          '<div class="row m-0 p-0">'+
          '@csrf'+
          '<div class="col-sm-6 m-0 p-1">'+
              '<div class="card card-primary card-outline" style="margin-bottom: 5%;text-align: center;">'+
                '<div class="card-body">'+

                  '<div class="form-group row">'+
                      '<label for="Fname" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="tid" type="hidden" class="form-control @error('tid') is-invalid @enderror" name="tid" value="'+tid+'" required autocomplete="tid" autofocus>'+
                          '<input id="Fname" type="text" class="form-control @error('Fname') is-invalid @enderror" name="Fname" value="'+Fname+'" placeholder="First Name" required autocomplete="Fname" autofocus>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Oname" class="col-md-4 col-form-label text-md-right">{{ __('Other Name') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="Oname" type="text" class="form-control @error('Oname') is-invalid @enderror" name="Oname" value="'+Oname+'" placeholder="Other Name" required autocomplete="Oname" autofocus>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Gender" class="col-md-4 col-form-label text-md-right">{{ __('Gender') }}</label>'+
                      
                      '<div class="col-md-8"  style="background-color: white;color: black;padding-top: 12px;text-align:left;">'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                                '<input type="radio" required '+(Gender=="Male"?"checked":"")+' class="@error('Gender') is-invalid @enderror" name="Gender" value="Male" autocomplete="Gender"> Male'+
                          '</label>'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                              '<input type="radio" required '+(Gender=="Female"?"checked":"")+' class="@error('Gender') is-invalid @enderror" name="Gender" value="Female" autocomplete="Gender"> Female'+
                          '</label>'+
                          '<label style="margin-right: 8px;cursor: pointer;">'+
                              '<input type="radio" required '+(Gender=="Other"?"checked":"")+' class="@error('Gender') is-invalid @enderror" name="Gender" value="Other" autocomplete="Gender"> Other'+
                          '</label>'+
                      '</div>'+
                  '</div>'+

                '</div>'+
              '</div>'+
          '</div>'+

          '<div class="col-sm-6 m-0 p-1">'+
              '<div class="card card-primary card-outline" style="margin-bottom: 5%;text-align: center;">'+
                '<div class="card-body">'+
                
                  '<div class="form-group row">'+
                      '<label for="IDno" class="col-md-4 col-form-label text-md-right">{{ __('IDNo') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="IDno" type="text" class="form-control @error('IDno') is-invalid @enderror" name="IDno" value="'+IDno+'" placeholder="Number(ID or Passport)" required autocomplete="IDno" autofocus>'+
                      '</div>'+
                  '</div>'+
                  
                  '<div class="form-group row">'+
                      '<label for="Phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="Phone" type="text" class="form-control @error('Phone') is-invalid @enderror" name="Phone" value="'+Phone+'" placeholder="7xxxxxxxx" required autocomplete="Phone" autofocus>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row">'+
                      '<label for="Email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>'+

                      '<div class="col-md-8">'+
                          '<input id="Email" type="email" class="form-control @error('Email') is-invalid @enderror" name="Email" value="'+Email+'" placeholder="Tenant\'s Email" autocomplete="Email" autofocus>'+
                      '</div>'+
                  '</div>'+
                  
                '</div>'+
              '</div>'+
          '</div>'+
          '<div class="col-sm-12">'+
              '<button  class="btn btn-success float-sm-right" name="submitplotbtn" id="submitplotbtn"  type="submit" >Update Tenant Information</button>'+
          '</div>'+
      '</div>'+
      '</form>');
      $("#modaleditproperty-title").html('Update Tenant: ' + Fname +' '+ Oname);
    $("#modaleditproperty").modal('show');

    $(document).on('change','#IDno', function(){ 
      var IDno=$('#IDno').val();
        $('#HudumaNo').val(IDno);
    });

    $("#formplotmgrsaveedittenant").on('submit',(function(e){
      e.preventDefault();
      $("#modalconfirmsaveproperty-body").html(''+
      '<form action="/properties/manage/tenant/update" name="formplotmgrsaveconfirmedittenant" id="formplotmgrsaveconfirmedittenant" method="post">'+
        '@csrf'+
        'Sure to Save <br/><b>'+ 'Tenant :' + Fname+' ' + Oname+' </b>?<br/>'+
        '<button class="btn btn-success float-sm-right modalconfirmsaveproperty-okay">Save Now</button>'+
        '</form>');
      $("#modalconfirmsaveproperty-title").html('Updating Tenant <b>'+ Fname+' </b>');
      $("#modalconfirmsaveproperty").modal('show');
      
      $("#formplotmgrsaveconfirmedittenant").on('submit',(function(e){
        e.preventDefault();
        $(".modalconfirmsaveproperty-okay").html("Updating... "+'<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
        $.ajax({
            url:'/properties/manage/tenant/update',
            type:"POST",
            data:new FormData(document.getElementById('formplotmgrsaveedittenant')),
            processData:false,
            contentType:false,
            cache:false,
            success:function(data){
              if(data['error']){
                  $(document).Toasts('create', {
                      title: 'Error in Updating',
                      class: 'bg-warning',
                      position: 'topRight',
                      body: data['error']
                  })
                  $("#modalconfirmsaveproperty").modal('hide');
              }
              else{
                  $(document).Toasts('create', {
                      title: 'Updating',
                      class: 'bg-success',
                      position: 'topRight',
                      body: data['success']
                  })
                  $("#modalconfirmsaveproperty").modal('hide');
                  getTenants(statuss,statusvalue);
              }
            },
            error: function(xhr, status, error){
              var errorMessage = xhr.status + ': ' + xhr.statusText
              if(xhr.status==419){
                window.location.href='';
              }
              if (errorMessage=="0: error") {
                  errorMessage="Internet Connection Interrupted.";
              }
              else if(errorMessage=="404: Not Found"){
                  errorMessage="Could Not Process Data."
              }
              else if(errorMessage=="500: Internal Server Error"){
                  errorMessage="Failed due to Some Technical Error In a Server."
              }
              if(xhr.status==422){
                errorMessage = xhr.responseText;
              }

              if(xhr.status==404){
                errorMessage ="Could not Locate Resource.";
              }

              if(xhr.status==500){
                errorMessage = "Failed due to Some Technical Error In a Server."
              }
              $(document).Toasts('create', {
                  title: 'Error Updating',
                  class: 'bg-warning',
                  position: 'topRight',
                  body: errorMessage
              })
              $('.modalconfirmsaveproperty-okay').html('<h6 class="text-center">Try Updating Again</h6>');
            }
          });  
      }));
      
    }));
  
  }));

  $(document).on('click','.btnplotmgrassign',(function(e){
    var tid = $(this).data("id0");
    var Fname = $(this).data("id1");
    var Oname = $(this).data("id2");
    var Phone = $(this).data("id3");
    var IDno = $(this).data("id4");
    var HudumaNo = $(this).data("id5");
    var Email = $(this).data("id6");
    var Gender = $(this).data("id7");
    var statuss = $(this).data("id8");
    var statusvalue = $(this).data("id9");
    let genderdetails='';
    if(Gender=='Male'){
      genderdetails='Male';
    }
    else if(Gender=='Female'){
      genderdetails='Female';
    }
    else{
      genderdetails='Other';
    }
    
    $("#modalnewtenantassign-body").html(''+
        '<form role="form" class="form-horizontal" method="POST" name="formplotmgrsaveassigntenant" id="formplotmgrsaveassigntenant">'+
          '<div class="row m-0 p-0">'+
          '@csrf'+
          '<div class="col-sm-6 m-0 p-1">'+
              '<div class="card card-primary card-outline" style="margin-bottom: 5%;text-align: center;">'+
                '<div class="">'+

                  '<div class="form-group row bg-gray m-0 p-1">'+
                      '<label for="Tenantname" class="col-md-4 col-form-label text-md-right">{{ __('Tenant Name') }}</label>'+

                      '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                          '<input id="tid" type="hidden" class="form-control @error('tid') is-invalid @enderror" name="tid" value="'+tid+'" required autocomplete="tid" autofocus>'+
                          '<span style="margin-right: 8px;">'+Fname+' '+Oname+'</span>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row bg-gray m-0 p-1">'+
                      '<label for="IDno" class="col-md-4 col-form-label text-md-right">{{ __('ID or Pass No') }}</label>'+

                      '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                        '<span style="margin-right: 8px;">'+ IDno+ '</span>'+
                      '</div>'+
                  '</div>'+
                  
                  '<div class="form-group row bg-gray m-0 p-1">'+
                      '<label for="Phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>'+

                      '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                        '<span style="margin-right: 8px;">'+ Phone+ '</span>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row bg-gray m-0 p-1">'+
                      '<label for="Gender" class="col-md-4 col-form-label text-md-right">{{ __('Gender') }}</label>'+
                      
                      '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+  
                        '<span style="margin-right: 8px;">'+ genderdetails+ '</span>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row m-0 p-1">'+
                      '<label for="Gender" class="col-md-12 col-form-label text-md-center">Select House to Assign '+Fname+' '+Oname+
                        '<button type="button" class="btn btn-outline-primary text-xs p-0 m-0 ml-1 pr-1 pl-1" onclick="getVacantHouses()" >'+
                          '<i class="fas fa-refresh"> Refresh</i>'+
                        '</button>'+
                        '</label>'+
                      '<div class="text-sm text-danger mx-auto propertiesmanagehousevacantresload"></div>'+
                      '<div class="card-body m-1 p-1 propertiesmanagehousevacant" style="overflow-x:auto;max-height: calc(50vh - 11rem);overflow-y:auto;"></div>'+
                  '</div>'+

                '</div>'+
              '</div>'+
          '</div>'+

          '<div class="col-sm-6 m-0 p-1">'+
              '<div class="card card-primary card-outline" style="margin-bottom: 5%;text-align: center;">'+
                '<div class="">'+

                  '<div class="m-0 p-1">'+
                      '<div class="text-sm text-danger mx-auto propertiesmanagehousevacantselresload"></div>'+
                      '<div class="card-body m-1 p-1 propertiesmanagehousevacantsel" >Please Select Vacant House</div>'+
                  '</div>'+

                  '<div class="form-group row m-0 mt-1 p-1">'+
                    '<label for="DateAssigned" class="col-md-4 col-form-label text-md-right">{{ __('Assign') }}</label>'+

                    '<div class="col-md-8">'+
                        '<input id="DateAssigned" type="date" class="form-control @error('DateAssigned') is-invalid @enderror" name="DateAssigned" value="{{ old('DateAssigned') }}" placeholder="huduma number" required autocomplete="DateAssigned" autofocus>'+
                      '</div>'+
                  '</div>'+
                      '<div class="form-group row m-0 mt-1 p-1">'+
                        '<label for="DateAssigned" class="col-md-4 col-form-label text-md-right">{{ __('Nature') }}</label>'+

                        '<div class="col-md-8" style="background-color: white;color: black;padding-top: 12px;text-align:left;">'+
                            '<label style="margin-right: 8px;cursor: pointer;">'+
                                  '<input type="radio" required class="@error('Nature') is-invalid @enderror" name="Nature" value="Existing" autocomplete="Nature"> No Deposit'+
                            '</label>'+
                            '<label style="margin-right: 8px;cursor: pointer;">'+
                                '<input type="radio" required class="@error('Nature') is-invalid @enderror" name="Nature" value="New" autocomplete="Nature"> With Deposit'+
                            '</label>'+
                        '</div>'+
                    '</div>'+
                  
                '</div>'+
              '</div>'+
          '</div>'+
          '<div class="col-sm-12">'+
              '<button  class="btn btn-success float-sm-right" name="submitplotbtn" id="submitplotbtn"  type="submit" >Assign Tenant to House</button>'+
          '</div>'+
      '</div>'+
      '</form>');
    $("#modalnewtenantassign-title").html('Assign Tenant: ' + Fname +' '+ Oname);
    $("#modalnewtenantassign").modal('show');
    getVacantHouses();
    $(document).on('click','.btnplotmgselecthousevacant',(function(e){
      var hid = $(this).data("id0");
      vacanthousename = $(this).data("id1");
      var rent = $(this).data("id2");
      var deposit = $(this).data("id3");
      var water = $(this).data("id4");
      var lease = $(this).data("id5");
      var garbage = $(this).data("id6");
      var dueday = $(this).data("id7");
      var kplc = $(this).data("id8");
      var plotid = $(this).data("id9");
      var plotcode = $(this).data("id10");
      var plotname = $(this).data("id11");
      $('.propertiesmanagehousevacantselresload').html('Loading Selected House... <img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
     
      let propertiesmanagehousevacantsel='';
      propertiesmanagehousevacantsel=propertiesmanagehousevacantsel+(''+
      '<label for="Gender" class="col-md-12 col-form-label text-md-center">Assign '+Fname+' '+Oname+' to House : '+vacanthousename+'</label>'+
      '<input id="hid" type="hidden" class="form-control @error('hid') is-invalid @enderror" name="hid" value="'+hid+'" required autocomplete="hid" autofocus>'+

        '<div class="form-group row bg-gray m-0 p-1">'+
            '<label for="IDno" class="col-md-4 col-form-label text-md-right">{{ __('Rent $ Bin') }}</label>'+

            '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
              '<span style="margin-right: 8px;">'+ (rent + garbage) + '</span>'+
            '</div>'+
        '</div>'+
        '<div class="form-group row bg-gray m-0 p-1">'+
            '<label for="Phone" class="col-md-4 col-form-label text-md-right">{{ __('Lease') }}</label>'+

            '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
              '<span style="margin-right: 8px;">'+ lease+ '</span>'+
            '</div>'+
        '</div>'+

        '<div class="form-group row bg-gray m-0 p-1">'+
            '<label for="Phone" class="col-md-4 col-form-label text-md-right">{{ __('Deposit') }}</label>'+

            '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
              '<span style="margin-right: 8px;">'+ (deposit + water + kplc) + '</span>'+
            '</div>'+
        '</div>'+

        '<div class="form-group row bg-gray m-0 p-1">'+
            '<label for="Phone" class="col-md-4 col-form-label text-md-right">{{ __('No Deposit') }}</label>'+

            '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
              '<span style="margin-right: 8px;">'+ (rent + garbage) + '</span>'+
            '</div>'+
        '</div>'+

        '<div class="form-group row bg-gray m-0 p-1">'+
            '<label for="Phone" class="col-md-4 col-form-label text-md-right">{{ __('With Deposit') }}</label>'+

            '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
              '<span style="margin-right: 8px;">'+ (rent + garbage + deposit + water + kplc + lease) + '</span>'+
            '</div>'+
        '</div>');
      $('.propertiesmanagehousevacantsel').html(propertiesmanagehousevacantsel);

      $('.propertiesmanagehousevacantselresload').html('');

    }));


    
    $("#formplotmgrsaveassigntenant").on('submit',(function(e){
      e.preventDefault();
      if(vacanthousename==''){
        alert('Please Select House Assigning TO');
        return false;
      }
      else{
        $("#modalconfirmsaveproperty-body").html(''+
        '<form name="formplotmgrsaveconfirmassigntenant" id="formplotmgrsaveconfirmassigntenant" method="post">'+
          '@csrf'+
          'Sure to Assign <br/><b>'+ 'Tenant: <b>' + Fname+' ' + Oname+' </b> to House: <b>' + vacanthousename+' </b>?<br/>'+
          '<button class="btn btn-success float-sm-right modalconfirmsaveproperty-okay">Assign Now</button>'+
          '</form>');
        $("#modalconfirmsaveproperty-title").html('Assign Tenant: <b>' + Fname+' ' + Oname+' </b> to House: <b>' + vacanthousename+' </b>');
        $("#modalconfirmsaveproperty").modal('show');
        
        $("#formplotmgrsaveconfirmassigntenant").on('submit',(function(e){
          e.preventDefault();
          $(".modalconfirmsaveproperty-okay").html("Assigning House... "+'<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
          $.ajax({
              url:'/properties/manage/tenant/assign',
              type:"POST",
              data:new FormData(document.getElementById('formplotmgrsaveassigntenant')),
              processData:false,
              contentType:false,
              cache:false,
              success:function(data){
                if(data['error']){
                    $(document).Toasts('create', {
                        title: 'Error in Assigning',
                        class: 'bg-warning',
                        position: 'topRight',
                        body: data['error']
                    })
                    $("#modalconfirmsaveproperty").modal('hide');
                }
                else{
                    $(document).Toasts('create', {
                        title: 'Assigning',
                        class: 'bg-success',
                        position: 'topRight',
                        body: data['success']
                    })
                    $("#modalconfirmsaveproperty").modal('hide');
                    getVacantHouses();
                    getTenants(statuss,statusvalue);
                }
              },
              error: function(xhr, status, error){
                var errorMessage = xhr.status + ': ' + xhr.statusText
                if(xhr.status==419){
                  window.location.href='';
                }
                if (errorMessage=="0: error") {
                    errorMessage="Internet Connection Interrupted.";
                }
                else if(errorMessage=="404: Not Found"){
                    errorMessage="Could Not Process Data."
                }
                else if(errorMessage=="500: Internal Server Error"){
                    errorMessage="Failed due to Some Technical Error In a Server."
                }
                if(xhr.status==422){
                  errorMessage = xhr.responseText;
                }

                if(xhr.status==404){
                  errorMessage ="Could not Locate Resource.";
                }

                if(xhr.status==500){
                  errorMessage = "Failed due to Some Technical Error In a Server."
                }
                $(document).Toasts('create', {
                    title: 'Error Assigning',
                    class: 'bg-warning',
                    position: 'topRight',
                    body: errorMessage
                })
                $('.modalconfirmsaveproperty-okay').html('<h6 class="text-center">Try Assigning Again</h6>');
              }
            });  
        }));
      }
      
      
    }));
  
  }));
  

  $(document).on('click','.btnplotmgraddhouse',(function(e){
    var tid = $(this).data("id0");
    var Fname = $(this).data("id1");
    var Oname = $(this).data("id2");
    var Phone = $(this).data("id3");
    var IDno = $(this).data("id4");
    var HudumaNo = $(this).data("id5");
    var Email = $(this).data("id6");
    var Gender = $(this).data("id7");
    var statuss = $(this).data("id8");
    var statusvalue = $(this).data("id9");
    let genderdetails='';
    if(Gender=='Male'){
      genderdetails='Male';
    }
    else if(Gender=='Female'){
      genderdetails='Female';
    }
    else{
      genderdetails='Other';
    }
    
    $("#modalnewtenantassign-body").html(''+
        '<form role="form" class="form-horizontal" method="POST" name="formplotmgrsaveaddtenanthouse" id="formplotmgrsaveaddtenanthouse">'+
          '<div class="row m-0 p-0">'+
          '@csrf'+
          '<div class="col-sm-6 m-0 p-1">'+
              '<div class="card card-primary card-outline" style="margin-bottom: 5%;text-align: center;">'+
                '<div class="">'+

                  '<div class="form-group row bg-gray m-0 p-1">'+
                      '<label for="Tenantname" class="col-md-4 col-form-label text-md-right">{{ __('Tenant Name') }}</label>'+

                      '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                          '<input id="tid" type="hidden" class="form-control @error('tid') is-invalid @enderror" name="tid" value="'+tid+'" required autocomplete="tid" autofocus>'+
                          '<span style="margin-right: 8px;">'+Fname+' '+Oname+'</span>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row bg-gray m-0 p-1">'+
                      '<label for="IDno" class="col-md-4 col-form-label text-md-right">{{ __('ID or Pass No') }}</label>'+

                      '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                        '<span style="margin-right: 8px;">'+ IDno+ '</span>'+
                      '</div>'+
                  '</div>'+
                  
                  '<div class="form-group row bg-gray m-0 p-1">'+
                      '<label for="Phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>'+

                      '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                        '<span style="margin-right: 8px;">'+ Phone+ '</span>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row bg-gray m-0 p-1">'+
                      '<label for="Gender" class="col-md-4 col-form-label text-md-right">{{ __('Gender') }}</label>'+
                      
                      '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+  
                        '<span style="margin-right: 8px;">'+ genderdetails+ '</span>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row m-0 p-1">'+
                      '<label for="Gender" class="col-md-12 col-form-label text-md-center">Select House to Add to Tenant '+Fname+' '+Oname+
                        '<button type="button" class="btn btn-outline-primary text-xs p-0 m-0 ml-1 pr-1 pl-1" onclick="getVacantHouses()" >'+
                          '<i class="fas fa-refresh"> Refresh</i>'+
                        '</button>'+
                        '</label>'+
                      '<div class="text-sm text-danger mx-auto propertiesmanagehousevacantresload"></div>'+
                      '<div class="card-body m-1 p-1 propertiesmanagehousevacant" style="overflow-x:auto;max-height: calc(50vh - 11rem);overflow-y:auto;"></div>'+
                  '</div>'+

                '</div>'+
              '</div>'+
          '</div>'+

          '<div class="col-sm-6 m-0 p-1">'+
              '<div class="card card-primary card-outline" style="margin-bottom: 5%;text-align: center;">'+
                '<div class="">'+

                  '<div class="m-0 p-1">'+
                      '<div class="text-sm text-danger mx-auto propertiesmanagehousevacantselresload"></div>'+
                      '<div class="card-body m-1 p-1 propertiesmanagehousevacantsel" >Please Select Vacant House</div>'+
                  '</div>'+

                  '<div class="form-group row m-0 mt-1 p-1">'+
                    '<label for="DateAssigned" class="col-md-4 col-form-label text-md-right">{{ __('Assign') }}</label>'+

                    '<div class="col-md-8">'+
                        '<input id="DateAssigned" type="date" class="form-control @error('DateAssigned') is-invalid @enderror" name="DateAssigned" value="{{ old('DateAssigned') }}" placeholder="huduma number" required autocomplete="DateAssigned" autofocus>'+
                      '</div>'+
                  '</div>'+
                      '<div class="form-group row m-0 mt-1 p-1">'+
                        '<label for="DateAssigned" class="col-md-4 col-form-label text-md-right">{{ __('Nature') }}</label>'+

                        '<div class="col-md-8" style="background-color: white;color: black;padding-top: 12px;text-align:left;">'+
                            '<label style="margin-right: 8px;cursor: pointer;">'+
                                  '<input type="radio" required class="@error('Nature') is-invalid @enderror" name="Nature" value="Existing" autocomplete="Nature"> No Deposit'+
                            '</label>'+
                            '<label style="margin-right: 8px;cursor: pointer;">'+
                                '<input type="radio" required class="@error('Nature') is-invalid @enderror" name="Nature" value="New" autocomplete="Nature"> With Deposit'+
                            '</label>'+
                        '</div>'+
                    '</div>'+
                  
                '</div>'+
              '</div>'+
          '</div>'+
          '<div class="col-sm-12">'+
              '<button  class="btn btn-success float-sm-right" name="submitplotbtn" id="submitplotbtn"  type="submit" >Assign Tenant to House</button>'+
          '</div>'+
      '</div>'+
      '</form>');
    $("#modalnewtenantassign-title").html('Add Another House to Tenant: ' + Fname +' '+ Oname);
    $("#modalnewtenantassign").modal('show');
    getVacantHouses();
    $(document).on('click','.btnplotmgselecthousevacant',(function(e){
      var hid = $(this).data("id0");
      vacanthousename = $(this).data("id1");
      var rent = $(this).data("id2");
      var deposit = $(this).data("id3");
      var water = $(this).data("id4");
      var lease = $(this).data("id5");
      var garbage = $(this).data("id6");
      var dueday = $(this).data("id7");
      var kplc = $(this).data("id8");
      var plotid = $(this).data("id9");
      var plotcode = $(this).data("id10");
      var plotname = $(this).data("id11");
      $('.propertiesmanagehousevacantselresload').html('Loading Selected House... <img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
     
      let propertiesmanagehousevacantsel='';
      propertiesmanagehousevacantsel=propertiesmanagehousevacantsel+(''+
      '<label for="Gender" class="col-md-12 col-form-label text-md-center">Add House : '+vacanthousename+' to '+Fname+' '+Oname+'</label>'+
      '<input id="hid" type="hidden" class="form-control @error('hid') is-invalid @enderror" name="hid" value="'+hid+'" required autocomplete="hid" autofocus>'+

        '<div class="form-group row bg-gray m-0 p-1">'+
            '<label for="IDno" class="col-md-4 col-form-label text-md-right">{{ __('Rent $ Bin') }}</label>'+

            '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
              '<span style="margin-right: 8px;">'+ (rent + garbage) + '</span>'+
            '</div>'+
        '</div>'+
        '<div class="form-group row bg-gray m-0 p-1">'+
            '<label for="Phone" class="col-md-4 col-form-label text-md-right">{{ __('Lease') }}</label>'+

            '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
              '<span style="margin-right: 8px;">'+ lease+ '</span>'+
            '</div>'+
        '</div>'+

        '<div class="form-group row bg-gray m-0 p-1">'+
            '<label for="Phone" class="col-md-4 col-form-label text-md-right">{{ __('Deposit') }}</label>'+

            '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
              '<span style="margin-right: 8px;">'+ (deposit + water + kplc) + '</span>'+
            '</div>'+
        '</div>'+

        '<div class="form-group row bg-gray m-0 p-1">'+
            '<label for="Phone" class="col-md-4 col-form-label text-md-right">{{ __('No Deposit') }}</label>'+

            '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
              '<span style="margin-right: 8px;">'+ (rent + garbage) + '</span>'+
            '</div>'+
        '</div>'+

        '<div class="form-group row bg-gray m-0 p-1">'+
            '<label for="Phone" class="col-md-4 col-form-label text-md-right">{{ __('With Deposit') }}</label>'+

            '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
              '<span style="margin-right: 8px;">'+ (rent + garbage + deposit + water + kplc + lease) + '</span>'+
            '</div>'+
        '</div>');
      $('.propertiesmanagehousevacantsel').html(propertiesmanagehousevacantsel);

      $('.propertiesmanagehousevacantselresload').html('');

    }));


    
    $("#formplotmgrsaveaddtenanthouse").on('submit',(function(ee){
      ee.preventDefault();
      if(vacanthousename==''){
        alert('Please Select House to Add Tenant');
        return false;
      }
      else{
        $("#modalconfirmsaveproperty-body").html(''+
        '<form name="formplotmgrsaveconfirmaddtenanthouse" id="formplotmgrsaveconfirmaddtenanthouse" method="post">'+
          '@csrf'+
          'Sure to Add <br/><b>'+ 'House: <b>' + vacanthousename+' </b> to Tenant: <b>' + Fname+' ' + Oname+' </b>?<br/>'+
          '<button class="btn btn-success float-sm-right modalconfirmsaveproperty-okay">Add House Now</button>'+
          '</form>');
        $("#modalconfirmsaveproperty-title").html('Add House: <b>' + vacanthousename+' </b> to Tenant: <b>' + Fname+' ' + Oname+' </b>');
        $("#modalconfirmsaveproperty").modal('show');
        
        $("#formplotmgrsaveconfirmaddtenanthouse").on('submit',(function(e){
          e.preventDefault();
          $(".modalconfirmsaveproperty-okay").html("Adding House... "+'<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
          $.ajax({
              url:'/properties/manage/tenant/addhouse',
              type:"POST",
              data:new FormData(document.getElementById('formplotmgrsaveaddtenanthouse')),
              processData:false,
              contentType:false,
              cache:false,
              success:function(data){
                if(data['error']){
                    $(document).Toasts('create', {
                        title: 'Error in Adding House',
                        class: 'bg-warning',
                        position: 'topRight',
                        body: data['error']
                    })
                    $("#modalconfirmsaveproperty").modal('hide');
                }
                else{
                    $(document).Toasts('create', {
                        title: 'Adding House',
                        class: 'bg-success',
                        position: 'topRight',
                        body: data['success']
                    })
                    $("#modalconfirmsaveproperty").modal('hide');
                    getVacantHouses();
                    getTenants(statuss,statusvalue);
                }
              },
              error: function(xhr, status, error){
                var errorMessage = xhr.status + ': ' + xhr.statusText
                if(xhr.status==419){
                  window.location.href='';
                }
                if (errorMessage=="0: error") {
                    errorMessage="Internet Connection Interrupted.";
                }
                else if(errorMessage=="404: Not Found"){
                    errorMessage="Could Not Process Data."
                }
                else if(errorMessage=="500: Internal Server Error"){
                    errorMessage="Failed due to Some Technical Error In a Server."
                }
                if(xhr.status==422){
                  errorMessage = xhr.responseText;
                }

                if(xhr.status==404){
                  errorMessage ="Could not Locate Resource.";
                }

                if(xhr.status==500){
                  errorMessage = "Failed due to Some Technical Error In a Server."
                }
                $(document).Toasts('create', {
                    title: 'Error Adding House',
                    class: 'bg-warning',
                    position: 'topRight',
                    body: errorMessage
                })
                $('.modalconfirmsaveproperty-okay').html('<h6 class="text-center">Try Adding House Again</h6>');
              }
            });  
        }));
      }
      
    }));
  
  }));

  
  $(document).on('click','.btnplotmgrreassign',(function(e){
    var tid = $(this).data("id0");
    var Fname = $(this).data("id1");
    var Oname = $(this).data("id2");
    var Phone = $(this).data("id3");
    var IDno = $(this).data("id4");
    var HudumaNo = $(this).data("id5");
    var Email = $(this).data("id6");
    var Gender = $(this).data("id7");
    var statuss = $(this).data("id8");
    var statusvalue = $(this).data("id9");
    let genderdetails='';
    if(Gender=='Male'){
      genderdetails='Male';
    }
    else if(Gender=='Female'){
      genderdetails='Female';
    }
    else{
      genderdetails='Other';
    }
    
    $("#modalnewtenantassign-body").html(''+
        '<form role="form" class="form-horizontal" method="POST" name="formplotmgrsavereassigntenanthouse" id="formplotmgrsavereassigntenanthouse">'+
          '<div class="row m-0 p-0">'+
          '@csrf'+
          '<div class="col-sm-6 m-0 p-1">'+
              '<div class="card card-primary card-outline" style="margin-bottom: 5%;text-align: center;">'+
                '<div class="">'+

                  '<div class="form-group row bg-gray m-0 p-1">'+
                      '<label for="Tenantname" class="col-md-4 col-form-label text-md-right">{{ __('Tenant Name') }}</label>'+

                      '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                          '<input id="tid" type="hidden" class="form-control @error('tid') is-invalid @enderror" name="tid" value="'+tid+'" required autocomplete="tid" autofocus>'+
                          '<span style="margin-right: 8px;">'+Fname+' '+Oname+'</span>'+
                      '</div>'+
                  '</div>'+
                  
                  '<div class="form-group row bg-gray m-0 p-1">'+
                      '<label for="Phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>'+

                      '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                        '<span style="margin-right: 8px;">'+ Phone+ '</span>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row m-0 p-1">'+
                      '<label for="Gender" class="col-md-12 col-form-label text-md-center m-0 p-1 text-success">Select Current House to Re-Assign to Tenant '+
                        '<button type="button" class="btn btn-outline-primary text-xs p-0 m-0 ml-1 pr-1 pl-1" onclick="getTenantCurrentHouses('+tid+')" >'+
                          '<i class="fas fa-refresh"> Refresh</i>'+
                        '</button>'+
                        '</label>'+
                      '<div class="text-sm text-danger mx-auto propertiesmanagecurrenttenanthouseresload"></div>'+
                      '<div class="card-body m-1 p-1 propertiesmanagecurrenttenanthouse" style="overflow-x:auto;max-height: calc(50vh - 13rem);overflow-y:auto;border:2px solid gray;"></div>'+
                  '</div>'+

                  '<div class="form-group row m-0 p-1">'+
                      '<label for="Gender" class="col-md-12 col-form-label text-md-center m-0 p-1 text-success">Select House to Re-Assign to Tenant '+
                        '<button type="button" class="btn btn-outline-primary text-xs p-0 m-0 ml-1 pr-1 pl-1" onclick="getVacantHouses()" >'+
                          '<i class="fas fa-refresh"> Refresh</i>'+
                        '</button>'+
                        '</label>'+
                      '<div class="text-sm text-danger mx-auto propertiesmanagehousevacantresload"></div>'+
                      '<div class="card-body m-1 p-1 propertiesmanagehousevacant" style="overflow-x:auto;max-height: calc(50vh - 11rem);overflow-y:auto;border:2px solid gray;"></div>'+
                  '</div>'+

                '</div>'+
              '</div>'+
          '</div>'+

          '<div class="col-sm-6 m-0 p-1">'+
              '<div class="card card-primary card-outline" style="margin-bottom: 5%;text-align: center;">'+
                '<div class="">'+

                  '<div class="m-0 p-1">'+
                    '<div class="row m-0 p-0">'+
                      '<div class="col-6 m-0 p-0">'+
                        '<div class="text-sm text-danger mx-auto propertiesmanagehousefromselresload"></div>'+
                        '<div class="card-body m-1 p-1 propertiesmanagehousefromsel" style="overflow-x:auto;max-height: calc(50vh - 10rem);overflow-y:auto;border:2px solid gray;">House Re-Assigning FROM</div>'+
                      '</div>'+
                      '<div class="col-6 m-0 p-0">'+
                        '<div class="text-sm text-danger mx-auto propertiesmanagehousevacantselresload"></div>'+
                        '<div class="card-body m-1 p-1 propertiesmanagehousevacantsel" style="overflow-x:auto;max-height: calc(50vh - 10rem);overflow-y:auto;border:2px solid gray;" >House Re-Assigning TO</div>'+
                      '</div>'+
                    '</div>'+
                  '</div>'+

                  '<div class="m-0 p-1">'+
                    '<div class="row m-0 p-0">'+

                      '<div class="col-6 m-0 p-0">'+
                        '<div class="text-sm text-danger mx-auto propertiesmanagehousereassigningselresload"></div>'+
                        '<div class="card-body m-1 p-1 propertiesmanagehousereassigningsel" ></div>'+
                      '</div>'+
                      '<div class="col-6 m-0 p-0">'+
                        '<div class="form-group row m-0 mt-1 p-1">'+
                          '<label for="DateAssigned" class="col-md-4 col-form-label text-md-right">{{ __('Re-Assign') }}</label>'+

                          '<div class="col-md-8">'+
                              '<input id="DateAssigned" type="date" class="form-control @error('DateAssigned') is-invalid @enderror" name="DateAssigned" value="{{ old('DateAssigned') }}" required autocomplete="DateAssigned" autofocus>'+
                          '</div>'+
                        '</div>'+
                        '<div class="propertiesmanagehousereassigningsel1" ></div>'+
                        '<div class="form-group row m-0 mt-1 p-1">'+
                          '<label class="col-md-12 col-form-label text-md-center text-xs text-muted">Choose Current and House Changing to then Date of Changing House</label>'+

                        '</div>'+
                      '</div>'+

                      '</div>'+
                  '</div>'+

                  
                '</div>'+
              '</div>'+
          '</div>'+
          '<div class="col-sm-12">'+
              '<button  class="btn btn-success float-sm-right" name="submitplotbtn" id="submitplotbtn"  type="submit" >Assign Tenant to House</button>'+
          '</div>'+
      '</div>'+
      '</form>');
    $("#modalnewtenantassign-title").html('Reassign Another House to Tenant: ' + Fname +' '+ Oname);
    $("#modalnewtenantassign").modal('show');
    getTenantCurrentHouses(tid);
    getVacantHouses();
    
    var reassignlease=0;
    var reassigndeposits=0;
    var reassignhouse=0;
    var fromdeposits=0;

    //on select current house
    $(document).on('click','.btnplotmgselectcurrenttenanthouse',(function(e){
      var fromhid = $(this).data("id0");
      vacanthousenamefrom = $(this).data("id1");
      var rent = $(this).data("id2");
      var deposit = $(this).data("id3");
      var water = $(this).data("id4");
      var lease = $(this).data("id5");
      var garbage = $(this).data("id6");
      var dueday = $(this).data("id7");
      var kplc = $(this).data("id8");
      var plotid = $(this).data("id9");
      var plotcode = $(this).data("id10");
      var plotname = $(this).data("id11");
      fromdeposits=(deposit+water+kplc);

      $('.propertiesmanagehousefromselresload').html('Loading Selected House... <img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
      $('.propertiesmanagehousereassigningsel').html('');
      let propertiesmanagehousefromsel='';
      propertiesmanagehousefromsel=propertiesmanagehousefromsel+(''+
      '<label for="Gender" class="col-md-12 col-form-label text-md-center">Re-Assign FROM: '+ (vacanthousenamefrom) + '</label>'+
        '<input id="fromhid" type="hidden" class="form-control" name="fromhid" value="'+fromhid+'" required>'+

        '<div class="form-group row bg-gray m-0 p-1">'+
            '<label for="IDno" class="col-md-5 col-form-label text-md-right p-1">{{ __('Rent $ Bin') }}</label>'+

            '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
              '<span style="margin-right: 8px;">'+ (rent + garbage) + '</span>'+
            '</div>'+
        '</div>'+

        '<div class="form-group row bg-gray m-0 p-1">'+
            '<label for="Phone" class="col-md-5 col-form-label text-md-right p-1">{{ __('Deposit') }}</label>'+
            '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
              '<span style="margin-right: 8px;">'+ (deposit + water + kplc + lease) + '</span>'+
            '</div>'+
        '</div>'+

        '<div class="form-group row bg-gray m-0 p-1">'+
            '<label for="Phone" class="col-md-5 col-form-label text-md-right p-1">{{ __('Refundable') }}</label>'+

            '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
              '<span style="margin-right: 8px;">'+ (deposit + water + kplc) + '</span>'+
            '</div>'+
        '</div>');
      $('.propertiesmanagehousefromsel').html(propertiesmanagehousefromsel);

      $('.propertiesmanagehousefromselresload').html('');

    }));
    //on selecting vacant house 
    $(document).on('click','.btnplotmgselecthousevacant',(function(e){
      if(vacanthousenamefrom==''){
        alert('Please Select Current House Re-Assigning From');
        return false;
      }
      else{
        var hid = $(this).data("id0");
        vacanthousename = $(this).data("id1");
        var rent = $(this).data("id2");
        var deposit = $(this).data("id3");
        var water = $(this).data("id4");
        var lease = $(this).data("id5");
        var garbage = $(this).data("id6");
        var dueday = $(this).data("id7");
        var kplc = $(this).data("id8");
        var plotid = $(this).data("id9");
        var plotcode = $(this).data("id10");
        var plotname = $(this).data("id11");
        reassignlease=(lease);
        reassigndeposits=(deposit+water+kplc);
        reassignhouse=(rent+garbage);

        $('.propertiesmanagehousevacantselresload').html('Loading Selected House... <img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
      
        let propertiesmanagehousevacantsel='';
        propertiesmanagehousevacantsel=propertiesmanagehousevacantsel+(''+
        '<label for="Gender" class="col-md-12 col-form-label text-md-center">Re-Assign TO : '+ (vacanthousename) + '</label>'+
        '<input id="hid" type="hidden" class="form-control @error('hid') is-invalid @enderror" name="hid" value="'+hid+'" required autocomplete="hid" autofocus>'+

          '<div class="form-group row bg-gray m-0 p-1">'+
              '<label for="IDno" class="col-md-5 col-form-label text-md-right p-1">{{ __('Rent $ Bin') }}</label>'+

              '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                '<span style="margin-right: 8px;">'+ (rent + garbage) + '</span>'+
              '</div>'+
          '</div>'+

          '<div class="form-group row bg-gray m-0 p-1">'+
              '<label for="Phone" class="col-md-5 col-form-label text-md-right p-1">{{ __('Deposit') }}</label>'+
              '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                '<span style="margin-right: 8px;">'+ (deposit + water + kplc + lease) + '</span>'+
              '</div>'+
          '</div>'+

          '<div class="form-group row bg-gray m-0 p-1">'+
              '<label for="Phone" class="col-md-5 col-form-label text-md-right p-1">{{ __('Refundable') }}</label>'+

              '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                '<span style="margin-right: 8px;">'+ (deposit + water + kplc) + '</span>'+
              '</div>'+
          '</div>');
        $('.propertiesmanagehousevacantsel').html(propertiesmanagehousevacantsel);

        $('.propertiesmanagehousevacantselresload').html('');

        //display reassigning information
        let reassignAmount='';
        let balances=((reassigndeposits-fromdeposits)+500);
        if(balances>0){
          reassignAmount=('<div class="form-group row bg-gray m-0 p-1">'+
              '<input id="Arrears" type="hidden" class="form-control" name="Arrears" value="'+balances+'" required >'+
              '<input id="Excess" type="hidden" class="form-control" name="Excess" value="0.00" required >'+
              '<label for="IDno" class="col-md-5 col-form-label text-md-right p-1">{{ __('Arrears') }}</label>'+
                  '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                    '<span style="margin-right: 8px;">'+ balances + '</span>'+
                  '</div>'+
              '</div>');
        }
        else{
          reassignAmount=('<div class="form-group row bg-gray m-0 p-1">'+
              '<input id="Arrears" type="hidden" class="form-control" name="Arrears" value="0.00" required >'+
              '<input id="Excess" type="hidden" class="form-control" name="Excess" value="'+Math.abs(balances)+'" required >'+
              '<label for="IDno" class="col-md-5 col-form-label text-md-right p-1">{{ __('Excess') }}</label>'+
                  '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                    '<span style="margin-right: 8px;">'+ Math.abs(balances) + '</span>'+
                  '</div>'+
              '</div>');
        }
        
        $('.propertiesmanagehousereassigningselresload').html('Loading Selected House... <img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
      
        let propertiesmanagehousereassigningsel='';
        propertiesmanagehousereassigningsel=propertiesmanagehousereassigningsel+(''+
        '<label for="Gender" class="col-md-12 col-form-label text-md-center text-xs">Re-Assign '+vacanthousenamefrom+' To: '+vacanthousename+' </label>'+
          
          '<div class="form-group row bg-gray m-0 p-1">'+
              '<label for="IDno" class="col-md-5 col-form-label text-md-right p-1">{{ __('Rent $ Bin') }}</label>'+

              '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                '<span style="margin-right: 8px;">'+ (rent + garbage) + '</span>'+
              '</div>'+
          '</div>'+
          
          '<div class="form-group row bg-gray m-0 p-1">'+
              '<label for="Phone" class="col-md-5 col-form-label text-md-right p-1">{{ __('Deposit') }}</label>'+
              '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                '<span style="margin-right: 8px;">'+ (deposit + water + kplc + lease) + '</span>'+
              '</div>'+
          '</div>'+

          '<div class="form-group row bg-gray m-0 p-1">'+
              '<label for="Phone" class="col-md-5 col-form-label text-md-right p-1">{{ __('Lease') }}</label>'+
              '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                '<span style="margin-right: 8px;">'+ (deposit + water + kplc + lease) + '</span>'+
              '</div>'+
          '</div>');
          
        $('.propertiesmanagehousereassigningsel').html(propertiesmanagehousereassigningsel);

        let propertiesmanagehousereassigningsel1='';
        propertiesmanagehousereassigningsel1=propertiesmanagehousereassigningsel1+reassignAmount;
          
        $('.propertiesmanagehousereassigningsel1').html(propertiesmanagehousereassigningsel1);

        $('.propertiesmanagehousereassigningselresload').html('');
      }
      
    }));


    
    $("#formplotmgrsavereassigntenanthouse").on('submit',(function(ee){
      ee.preventDefault();
      if(vacanthousenamefrom==''){
        alert('Please Select Current House Re-Assigning From');
        return false;
      }
      else if(vacanthousename==''){
        alert('Please Select House Re-Assigning To');
        return false;
      }
      else{
        $("#modalconfirmsaveproperty-body").html(''+
        '<form name="formplotmgrsaveconfirmreassigntenanthouse" id="formplotmgrsaveconfirmreassigntenanthouse" method="post">'+
          '@csrf'+
          'Sure to Re-Assign <br/><b>'+ 'Tenant: <b>' + Fname+' ' + Oname +' </b> from House: <b>' + vacanthousename +' </b> To: <b>' + vacanthousenamefrom +' </b>?<br/>'+
          '<button class="btn btn-success float-sm-right modalconfirmsaveproperty-okay">Re-Assign Tenant Now</button>'+
          '</form>');
        $("#modalconfirmsaveproperty-title").html('Re-Assign Tenant: <b>' + Fname+' ' + Oname+' </b> from House: <b>' +vacanthousename +' </b>To: <b>' + vacanthousenamefrom +' </b>?<br/>');
        $("#modalconfirmsaveproperty").modal('show');
        
        $("#formplotmgrsaveconfirmreassigntenanthouse").on('submit',(function(e){
          e.preventDefault();
          $(".modalconfirmsaveproperty-okay").html("Re-Assigning Tenant... "+'<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
          $.ajax({
              url:'/properties/manage/tenant/reassignhouse',
              type:"POST",
              data:new FormData(document.getElementById('formplotmgrsavereassigntenanthouse')),
              processData:false,
              contentType:false,
              cache:false,
              success:function(data){
                if(data['error']){
                    $(document).Toasts('create', {
                        title: 'Error in Re-Assigning Tenant',
                        class: 'bg-warning',
                        position: 'topRight',
                        body: data['error']
                    })
                    $("#modalconfirmsaveproperty").modal('hide');
                }
                else{
                    $(document).Toasts('create', {
                        title: 'Re-Assigning Tenant',
                        class: 'bg-success',
                        position: 'topRight',
                        body: data['success']
                    })
                    $("#modalconfirmsaveproperty").modal('hide');
                    getTenantCurrentHouses(tid);
                    getVacantHouses();
                    getTenants(statuss,statusvalue);

                }
              },
              error: function(xhr, status, error){
                var errorMessage = xhr.status + ': ' + xhr.statusText
                if(xhr.status==419){
                  window.location.href='';
                }
                if (errorMessage=="0: error") {
                    errorMessage="Internet Connection Interrupted.";
                }
                else if(errorMessage=="404: Not Found"){
                    errorMessage="Could Not Process Data."
                }
                else if(errorMessage=="500: Internal Server Error"){
                    errorMessage="Failed due to Some Technical Error In a Server."
                }
                if(xhr.status==422){
                  errorMessage = xhr.responseText;
                }

                if(xhr.status==404){
                  errorMessage ="Could not Locate Resource.";
                }

                if(xhr.status==500){
                  errorMessage = "Failed due to Some Technical Error In a Server."
                }
                $(document).Toasts('create', {
                    title: 'Error Re-Assigning Tenant',
                    class: 'bg-warning',
                    position: 'topRight',
                    body: errorMessage
                })
                $('.modalconfirmsaveproperty-okay').html('<h6 class="text-center">Try Re-Assigning Tenant Again</h6>');
              }
            });  
        }));
      }
      
    }));
  
  }));

  $(document).on('click','.btnplotmgrtransfer',(function(e){
    var tid = $(this).data("id0");
    var Fname = $(this).data("id1");
    var Oname = $(this).data("id2");
    var Phone = $(this).data("id3");
    var IDno = $(this).data("id4");
    var HudumaNo = $(this).data("id5");
    var Email = $(this).data("id6");
    var Gender = $(this).data("id7");
    var statuss = $(this).data("id8");
    var statusvalue = $(this).data("id9");
    let genderdetails='';
    if(Gender=='Male'){
      genderdetails='Male';
    }
    else if(Gender=='Female'){
      genderdetails='Female';
    }
    else{
      genderdetails='Other';
    }
    
    $("#modalnewtenantassign-body").html(''+
        '<form role="form" class="form-horizontal" method="POST" name="formplotmgrsavereassigntenanthouse" id="formplotmgrsavereassigntenanthouse">'+
          '<div class="row m-0 p-0">'+
          '@csrf'+
          '<div class="col-sm-6 m-0 p-1">'+
              '<div class="card card-primary card-outline" style="margin-bottom: 5%;text-align: center;">'+
                '<div class="">'+

                  '<div class="form-group row bg-gray m-0 p-1">'+
                      '<label for="Tenantname" class="col-md-4 col-form-label text-md-right">{{ __('Tenant Name') }}</label>'+

                      '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                          '<input id="tid" type="hidden" class="form-control @error('tid') is-invalid @enderror" name="tid" value="'+tid+'" required autocomplete="tid" autofocus>'+
                          '<span style="margin-right: 8px;">'+Fname+' '+Oname+'</span>'+
                      '</div>'+
                  '</div>'+
                  
                  '<div class="form-group row bg-gray m-0 p-1">'+
                      '<label for="Phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>'+

                      '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                        '<span style="margin-right: 8px;">'+ Phone+ '</span>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row m-0 p-1">'+
                      '<label for="Gender" class="col-md-12 col-form-label text-md-center m-0 p-1 text-success">Select Current House to Re-Assign to Tenant '+
                        '<button type="button" class="btn btn-outline-primary text-xs p-0 m-0 ml-1 pr-1 pl-1" onclick="getTenantCurrentHouses('+tid+')" >'+
                          '<i class="fas fa-refresh"> Refresh</i>'+
                        '</button>'+
                        '</label>'+
                      '<div class="text-sm text-danger mx-auto propertiesmanagecurrenttenanthouseresload"></div>'+
                      '<div class="card-body m-1 p-1 propertiesmanagecurrenttenanthouse" style="overflow-x:auto;max-height: calc(50vh - 13rem);overflow-y:auto;border:2px solid gray;"></div>'+
                  '</div>'+

                  '<div class="form-group row m-0 p-1">'+
                      '<label for="Gender" class="col-md-12 col-form-label text-md-center m-0 p-1 text-success">Select House to Re-Assign to Tenant '+
                        '<button type="button" class="btn btn-outline-primary text-xs p-0 m-0 ml-1 pr-1 pl-1" onclick="getVacantHouses()" >'+
                          '<i class="fas fa-refresh"> Refresh</i>'+
                        '</button>'+
                        '</label>'+
                      '<div class="text-sm text-danger mx-auto propertiesmanagehousevacantresload"></div>'+
                      '<div class="card-body m-1 p-1 propertiesmanagehousevacant" style="overflow-x:auto;max-height: calc(50vh - 11rem);overflow-y:auto;border:2px solid gray;"></div>'+
                  '</div>'+

                '</div>'+
              '</div>'+
          '</div>'+

          '<div class="col-sm-6 m-0 p-1">'+
              '<div class="card card-primary card-outline" style="margin-bottom: 5%;text-align: center;">'+
                '<div class="">'+

                  '<div class="m-0 p-1">'+
                    '<div class="row m-0 p-0">'+
                      '<div class="col-6 m-0 p-0">'+
                        '<div class="text-sm text-danger mx-auto propertiesmanagehousefromselresload"></div>'+
                        '<div class="card-body m-1 p-1 propertiesmanagehousefromsel" style="overflow-x:auto;max-height: calc(50vh - 10rem);overflow-y:auto;border:2px solid gray;">House Re-Assigning FROM</div>'+
                      '</div>'+
                      '<div class="col-6 m-0 p-0">'+
                        '<div class="text-sm text-danger mx-auto propertiesmanagehousevacantselresload"></div>'+
                        '<div class="card-body m-1 p-1 propertiesmanagehousevacantsel" style="overflow-x:auto;max-height: calc(50vh - 10rem);overflow-y:auto;border:2px solid gray;" >House Re-Assigning TO</div>'+
                      '</div>'+
                    '</div>'+
                  '</div>'+

                  '<div class="m-0 p-1">'+
                    '<div class="row m-0 p-0">'+

                      '<div class="col-6 m-0 p-0">'+
                        '<div class="text-sm text-danger mx-auto propertiesmanagehousereassigningselresload"></div>'+
                        '<div class="card-body m-1 p-1 propertiesmanagehousereassigningsel" ></div>'+
                      '</div>'+
                      '<div class="col-6 m-0 p-0">'+
                        '<div class="form-group row m-0 mt-1 p-1">'+
                          '<label for="DateAssigned" class="col-md-4 col-form-label text-md-right">{{ __('Re-Assign') }}</label>'+

                          '<div class="col-md-8">'+
                              '<input id="DateAssigned" type="date" class="form-control @error('DateAssigned') is-invalid @enderror" name="DateAssigned" value="{{ old('DateAssigned') }}" required autocomplete="DateAssigned" autofocus>'+
                          '</div>'+
                        '</div>'+
                        '<div class="propertiesmanagehousereassigningsel1" ></div>'+
                        '<div class="form-group row m-0 mt-1 p-1">'+
                          '<label class="col-md-12 col-form-label text-md-center text-xs text-muted">Choose Current and House Changing to then Date of Changing House</label>'+

                        '</div>'+
                      '</div>'+

                      '</div>'+
                  '</div>'+

                  
                '</div>'+
              '</div>'+
          '</div>'+
          '<div class="col-sm-12">'+
              '<button  class="btn btn-success float-sm-right" name="submitplotbtn" id="submitplotbtn"  type="submit" >Assign Tenant to House</button>'+
          '</div>'+
      '</div>'+
      '</form>');
    $("#modalnewtenantassign-title").html('Reassign Another House to Tenant: ' + Fname +' '+ Oname);
    $("#modalnewtenantassign").modal('show');
    getTenantCurrentHouses(tid);
    getVacantHouses();
    
    var reassignlease=0;
    var reassigndeposits=0;
    var reassignhouse=0;
    var fromdeposits=0;

    //on select current house
    $(document).on('click','.btnplotmgselectcurrenttenanthouse',(function(e){
      var fromhid = $(this).data("id0");
      vacanthousenamefrom = $(this).data("id1");
      var rent = $(this).data("id2");
      var deposit = $(this).data("id3");
      var water = $(this).data("id4");
      var lease = $(this).data("id5");
      var garbage = $(this).data("id6");
      var dueday = $(this).data("id7");
      var kplc = $(this).data("id8");
      var plotid = $(this).data("id9");
      var plotcode = $(this).data("id10");
      var plotname = $(this).data("id11");
      fromdeposits=(deposit+water+kplc);

      $('.propertiesmanagehousefromselresload').html('Loading Selected House... <img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
      $('.propertiesmanagehousereassigningsel').html('');
      let propertiesmanagehousefromsel='';
      propertiesmanagehousefromsel=propertiesmanagehousefromsel+(''+
      '<label for="Gender" class="col-md-12 col-form-label text-md-center">Re-Assign FROM: '+ (vacanthousenamefrom) + '</label>'+
        '<input id="fromhid" type="hidden" class="form-control" name="fromhid" value="'+fromhid+'" required>'+

        '<div class="form-group row bg-gray m-0 p-1">'+
            '<label for="IDno" class="col-md-5 col-form-label text-md-right p-1">{{ __('Rent $ Bin') }}</label>'+

            '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
              '<span style="margin-right: 8px;">'+ (rent + garbage) + '</span>'+
            '</div>'+
        '</div>'+

        '<div class="form-group row bg-gray m-0 p-1">'+
            '<label for="Phone" class="col-md-5 col-form-label text-md-right p-1">{{ __('Deposit') }}</label>'+
            '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
              '<span style="margin-right: 8px;">'+ (deposit + water + kplc + lease) + '</span>'+
            '</div>'+
        '</div>'+

        '<div class="form-group row bg-gray m-0 p-1">'+
            '<label for="Phone" class="col-md-5 col-form-label text-md-right p-1">{{ __('Refundable') }}</label>'+

            '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
              '<span style="margin-right: 8px;">'+ (deposit + water + kplc) + '</span>'+
            '</div>'+
        '</div>');
      $('.propertiesmanagehousefromsel').html(propertiesmanagehousefromsel);

      $('.propertiesmanagehousefromselresload').html('');

    }));
    //on selecting vacant house 
    $(document).on('click','.btnplotmgselecthousevacant',(function(e){
      if(vacanthousenamefrom==''){
        alert('Please Select Current House Re-Assigning From');
        return false;
      }
      else{
        var hid = $(this).data("id0");
        vacanthousename = $(this).data("id1");
        var rent = $(this).data("id2");
        var deposit = $(this).data("id3");
        var water = $(this).data("id4");
        var lease = $(this).data("id5");
        var garbage = $(this).data("id6");
        var dueday = $(this).data("id7");
        var kplc = $(this).data("id8");
        var plotid = $(this).data("id9");
        var plotcode = $(this).data("id10");
        var plotname = $(this).data("id11");
        reassignlease=(lease);
        reassigndeposits=(deposit+water+kplc);
        reassignhouse=(rent+garbage);

        $('.propertiesmanagehousevacantselresload').html('Loading Selected House... <img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
      
        let propertiesmanagehousevacantsel='';
        propertiesmanagehousevacantsel=propertiesmanagehousevacantsel+(''+
        '<label for="Gender" class="col-md-12 col-form-label text-md-center">Re-Assign TO : '+ (vacanthousename) + '</label>'+
        '<input id="hid" type="hidden" class="form-control @error('hid') is-invalid @enderror" name="hid" value="'+hid+'" required autocomplete="hid" autofocus>'+

          '<div class="form-group row bg-gray m-0 p-1">'+
              '<label for="IDno" class="col-md-5 col-form-label text-md-right p-1">{{ __('Rent $ Bin') }}</label>'+

              '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                '<span style="margin-right: 8px;">'+ (rent + garbage) + '</span>'+
              '</div>'+
          '</div>'+

          '<div class="form-group row bg-gray m-0 p-1">'+
              '<label for="Phone" class="col-md-5 col-form-label text-md-right p-1">{{ __('Deposit') }}</label>'+
              '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                '<span style="margin-right: 8px;">'+ (deposit + water + kplc + lease) + '</span>'+
              '</div>'+
          '</div>'+

          '<div class="form-group row bg-gray m-0 p-1">'+
              '<label for="Phone" class="col-md-5 col-form-label text-md-right p-1">{{ __('Refundable') }}</label>'+

              '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                '<span style="margin-right: 8px;">'+ (deposit + water + kplc) + '</span>'+
              '</div>'+
          '</div>');
        $('.propertiesmanagehousevacantsel').html(propertiesmanagehousevacantsel);

        $('.propertiesmanagehousevacantselresload').html('');

        //display reassigning information
        let reassignAmount='';
        let balances=((reassigndeposits-fromdeposits)+500);
        if(balances>0){
          reassignAmount=('<div class="form-group row bg-gray m-0 p-1">'+
              '<input id="Arrears" type="hidden" class="form-control" name="Arrears" value="'+balances+'" required >'+
              '<input id="Excess" type="hidden" class="form-control" name="Excess" value="0.00" required >'+
              '<label for="IDno" class="col-md-5 col-form-label text-md-right p-1">{{ __('Arrears') }}</label>'+
                  '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                    '<span style="margin-right: 8px;">'+ balances + '</span>'+
                  '</div>'+
              '</div>');
        }
        else{
          reassignAmount=('<div class="form-group row bg-gray m-0 p-1">'+
              '<input id="Arrears" type="hidden" class="form-control" name="Arrears" value="0.00" required >'+
              '<input id="Excess" type="hidden" class="form-control" name="Excess" value="'+Math.abs(balances)+'" required >'+
              '<label for="IDno" class="col-md-5 col-form-label text-md-right p-1">{{ __('Excess') }}</label>'+
                  '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                    '<span style="margin-right: 8px;">'+ Math.abs(balances) + '</span>'+
                  '</div>'+
              '</div>');
        }
        
        $('.propertiesmanagehousereassigningselresload').html('Loading Selected House... <img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
      
        let propertiesmanagehousereassigningsel='';
        propertiesmanagehousereassigningsel=propertiesmanagehousereassigningsel+(''+
        '<label for="Gender" class="col-md-12 col-form-label text-md-center text-xs">Re-Assign '+vacanthousenamefrom+' To: '+vacanthousename+' </label>'+
          
          '<div class="form-group row bg-gray m-0 p-1">'+
              '<label for="IDno" class="col-md-5 col-form-label text-md-right p-1">{{ __('Rent $ Bin') }}</label>'+

              '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                '<span style="margin-right: 8px;">'+ (rent + garbage) + '</span>'+
              '</div>'+
          '</div>'+
          
          '<div class="form-group row bg-gray m-0 p-1">'+
              '<label for="Phone" class="col-md-5 col-form-label text-md-right p-1">{{ __('Deposit') }}</label>'+
              '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                '<span style="margin-right: 8px;">'+ (deposit + water + kplc + lease) + '</span>'+
              '</div>'+
          '</div>'+

          '<div class="form-group row bg-gray m-0 p-1">'+
              '<label for="Phone" class="col-md-5 col-form-label text-md-right p-1">{{ __('Lease') }}</label>'+
              '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                '<span style="margin-right: 8px;">'+ (deposit + water + kplc + lease) + '</span>'+
              '</div>'+
          '</div>');
          
        $('.propertiesmanagehousereassigningsel').html(propertiesmanagehousereassigningsel);

        let propertiesmanagehousereassigningsel1='';
        propertiesmanagehousereassigningsel1=propertiesmanagehousereassigningsel1+reassignAmount;
          
        $('.propertiesmanagehousereassigningsel1').html(propertiesmanagehousereassigningsel1);

        $('.propertiesmanagehousereassigningselresload').html('');
      }
      
    }));


    
    $("#formplotmgrsavereassigntenanthouse").on('submit',(function(ee){
      ee.preventDefault();
      if(vacanthousenamefrom==''){
        alert('Please Select Current House Re-Assigning From');
        return false;
      }
      else if(vacanthousename==''){
        alert('Please Select House Re-Assigning To');
        return false;
      }
      else{
        $("#modalconfirmsaveproperty-body").html(''+
        '<form name="formplotmgrsaveconfirmreassigntenanthouse" id="formplotmgrsaveconfirmreassigntenanthouse" method="post">'+
          '@csrf'+
          'Sure to Re-Assign <br/><b>'+ 'Tenant: <b>' + Fname+' ' + Oname +' </b> from House: <b>' + vacanthousename +' </b> To: <b>' + vacanthousenamefrom +' </b>?<br/>'+
          '<button class="btn btn-success float-sm-right modalconfirmsaveproperty-okay">Re-Assign Tenant Now</button>'+
          '</form>');
        $("#modalconfirmsaveproperty-title").html('Re-Assign Tenant: <b>' + Fname+' ' + Oname+' </b> from House: <b>' +vacanthousename +' </b>To: <b>' + vacanthousenamefrom +' </b>?<br/>');
        $("#modalconfirmsaveproperty").modal('show');
        
        $("#formplotmgrsaveconfirmreassigntenanthouse").on('submit',(function(e){
          e.preventDefault();
          $(".modalconfirmsaveproperty-okay").html("Re-Assigning Tenant... "+'<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
          $.ajax({
              url:'/properties/manage/tenant/reassignhouse',
              type:"POST",
              data:new FormData(document.getElementById('formplotmgrsavereassigntenanthouse')),
              processData:false,
              contentType:false,
              cache:false,
              success:function(data){
                if(data['error']){
                    $(document).Toasts('create', {
                        title: 'Error in Re-Assigning Tenant',
                        class: 'bg-warning',
                        position: 'topRight',
                        body: data['error']
                    })
                    $("#modalconfirmsaveproperty").modal('hide');
                }
                else{
                    $(document).Toasts('create', {
                        title: 'Re-Assigning Tenant',
                        class: 'bg-success',
                        position: 'topRight',
                        body: data['success']
                    })
                    $("#modalconfirmsaveproperty").modal('hide');
                    getTenantCurrentHouses(tid);
                    getVacantHouses();
                    getTenants(statuss,statusvalue);

                }
              },
              error: function(xhr, status, error){
                var errorMessage = xhr.status + ': ' + xhr.statusText
                if(xhr.status==419){
                  window.location.href='';
                }
                if (errorMessage=="0: error") {
                    errorMessage="Internet Connection Interrupted.";
                }
                else if(errorMessage=="404: Not Found"){
                    errorMessage="Could Not Process Data."
                }
                else if(errorMessage=="500: Internal Server Error"){
                    errorMessage="Failed due to Some Technical Error In a Server."
                }
                if(xhr.status==422){
                  errorMessage = xhr.responseText;
                }

                if(xhr.status==404){
                  errorMessage ="Could not Locate Resource.";
                }

                if(xhr.status==500){
                  errorMessage = "Failed due to Some Technical Error In a Server."
                }
                $(document).Toasts('create', {
                    title: 'Error Re-Assigning Tenant',
                    class: 'bg-warning',
                    position: 'topRight',
                    body: errorMessage
                })
                $('.modalconfirmsaveproperty-okay').html('<h6 class="text-center">Try Re-Assigning Tenant Again</h6>');
              }
            });  
        }));
      }
      
    }));
  
  }));

  $(document).on('click','.btnplotmgrvacatetenant',(function(e){
    var tid = $(this).data("id0");
    var Fname = $(this).data("id1");
    var Oname = $(this).data("id2");
    var Phone = $(this).data("id3");
    var IDno = $(this).data("id4");
    var HudumaNo = $(this).data("id5");
    var Email = $(this).data("id6");
    var Gender = $(this).data("id7");
    var statuss = $(this).data("id8");
    var statusvalue = $(this).data("id9");
    let genderdetails='';
    if(Gender=='Male'){
      genderdetails='Male';
    }
    else if(Gender=='Female'){
      genderdetails='Female';
    }
    else{
      genderdetails='Other';
    }
    
    $("#modalnewtenantassign-body").html(''+
        '<form role="form" class="form-horizontal" method="POST" name="formplotmgrsavevacatetenanthouse" id="formplotmgrsavevacatetenanthouse">'+
          '<div class="row m-0 p-0">'+
          '@csrf'+
          '<div class="col-sm-6 m-0 p-1">'+
              '<div class="card card-primary card-outline" style="margin-bottom: 5%;text-align: center;">'+
                '<div class="">'+

                  '<div class="form-group row bg-gray m-0 p-1">'+
                      '<label for="Tenantname" class="col-md-4 col-form-label text-md-right">{{ __('Tenant Name') }}</label>'+

                      '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                          '<input id="tid" type="hidden" class="form-control @error('tid') is-invalid @enderror" name="tid" value="'+tid+'" required autocomplete="tid" autofocus>'+
                          '<span style="margin-right: 8px;">'+Fname+' '+Oname+'</span>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row bg-gray m-0 p-1">'+
                      '<label for="IDno" class="col-md-4 col-form-label text-md-right">{{ __('IDno') }}</label>'+

                      '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                        '<span style="margin-right: 8px;">'+ IDno+ '</span>'+
                      '</div>'+
                  '</div>'+
                  
                  '<div class="form-group row bg-gray m-0 p-1">'+
                      '<label for="Phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>'+

                      '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                        '<span style="margin-right: 8px;">'+ Phone+ '</span>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row bg-gray m-0 p-1">'+
                      '<label for="Email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>'+

                      '<div class="col-md-8" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
                        '<span style="margin-right: 8px;">'+ Email+ '</span>'+
                      '</div>'+
                  '</div>'+

                  '<div class="form-group row m-0 p-1">'+
                      '<label class="col-md-12 col-form-label text-md-center m-0 p-1 text-success">Select House to Vacate this Tenant '+
                        '<button type="button" class="btn btn-outline-primary text-xs p-0 m-0 ml-1 pr-1 pl-1" onclick="getTenantVacateHouses('+tid+')" >'+
                          '<i class="fas fa-refresh"> Refresh</i>'+
                        '</button>'+
                        '</label>'+
                      '<div class="text-sm text-danger mx-auto propertiesmanagevacatetenanthouseresload"></div>'+
                      '<div class="card-body m-1 p-1 propertiesmanagevacatetenanthouse" style="overflow-x:auto;max-height: calc(50vh - 11rem);overflow-y:auto;border:2px solid gray;"></div>'+
                  '</div>'+


                '</div>'+
              '</div>'+
          '</div>'+

          '<div class="col-sm-6 m-0 p-1">'+
              '<div class="card card-primary card-outline" style="margin-bottom: 5%;text-align: center;">'+
                '<div class="">'+

                  '<div class="m-0 p-1">'+
                    '<div class="row m-0 p-0">'+
                      '<div class="col-6 m-0 p-0">'+
                        '<div class="text-sm text-danger mx-auto propertiesmanagehousefromselresload"></div>'+
                        '<div class="card-body m-1 p-1 propertiesmanagehousefromsel" style="overflow-x:auto;max-height: calc(50vh);overflow-y:auto;"></div>'+
                      '</div>'+

                      '<div class="col-6 m-0 p-0">'+
                        '<div class="card-body m-1 p-1 propertiesmanagehousevacateinfosel" style="overflow-x:auto;max-height: calc(50vh);overflow-y:auto;"></div>'+
                      '</div>'+

                      
                      '<div class="form-group row m-0  p-1">'+
                        '<label class="col-md-12 col-form-label text-md-center text-xs text-muted">Damages, Transaction Cost, To Refund and Vacating Date Values should be Updated.</label>'+

                      '</div>'+

                    '</div>'+
                  '</div>'+
                  
                '</div>'+
              '</div>'+
          '</div>'+

          '<div class="col-sm-12">'+
              '<button  class="btn btn-success float-sm-right" name="submitplotbtn" id="submitplotbtn"  type="submit" >Vacate Tenant from House</button>'+
          '</div>'+
      '</div>'+
      '</form>');
    $("#modalnewtenantassign-title").html('Vacate Tenant: ' + Fname +' '+ Oname);
    $("#modalnewtenantassign").modal('show');
    getTenantVacateHouses(tid);

    var reassignlease=0;
    var reassigndeposits=0;
    var reassignhouse=0;
    var fromdeposits=0;

    //on select current house
    $(document).on('click','.btnplotmgselectvacatetenanthouse',(function(e){
      var fromhid = $(this).data("id0");
      vacanthousenamefrom = $(this).data("id1");
      var rent = $(this).data("id2");
      var deposit = $(this).data("id3");
      var water = $(this).data("id4");
      var lease = $(this).data("id5");
      var garbage = $(this).data("id6");
      var dueday = $(this).data("id7");
      var kplc = $(this).data("id8");
      var plotid = $(this).data("id9");
      var plotcode = $(this).data("id10");
      var plotname = $(this).data("id11");
      var TotalUsed = $(this).data("id12");
      var TotalPaid = $(this).data("id13");
      var Balance = $(this).data("id14");
      var Refund = $(this).data("id15");
      var dateToMonthName = $(this).data("id16");
      var aid = $(this).data("id17");
      

      fromdeposits=(deposit+water+kplc);

      $('.propertiesmanagehousefromselresload').html('Loading Selected House... <img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
      $('.propertiesmanagehousereassigningsel').html('');
      let propertiesmanagehousefromsel='';

      propertiesmanagehousefromsel=propertiesmanagehousefromsel+(''+
      '<label for="Gender" class="col-md-12 col-form-label text-md-center">Vacate House: '+ (vacanthousenamefrom) + '</label>'+
        '<input id="fromhid" type="hidden" class="form-control" name="fromhid" value="'+fromhid+'" required>'+
        '<input id="aid" type="hidden" class="form-control" name="aid" value="'+aid+'" required>'+
        '<input id="Arrears" type="hidden" class="form-control" name="Arrears" value="'+Balance+'" required>'+
        '<input id="Deposit" type="hidden" class="form-control" name="Deposit" value="'+deposit+'" required>'+

        '<div class="form-group row bg-gray m-0 p-1">'+
            '<label for="IDno" class="col-md-5 col-form-label text-md-right p-1">{{ __('Total Used') }}</label>'+

            '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
              '<span style="margin-right: 8px;">'+ (TotalUsed) + '</span>'+
            '</div>'+
        '</div>'+

        '<div class="form-group row bg-gray m-0 p-1">'+
            '<label for="Phone" class="col-md-5 col-form-label text-md-right p-1">{{ __('Total Paid') }}</label>'+
            '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
              '<span style="margin-right: 8px;">'+ (TotalPaid) + '</span>'+
            '</div>'+
        '</div>'+

        '<div class="form-group row bg-gray m-0 p-1">'+
            '<label for="Phone" class="col-md-5 col-form-label text-md-right p-1">{{ __('Deposit') }}</label>'+
            '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
              '<span style="margin-right: 8px;">'+ (deposit) + '</span>'+
            '</div>'+
        '</div>'+


        '<div class="form-group row bg-gray m-0 p-1">'+
            '<label for="Phone" class="col-md-5 col-form-label text-md-right p-1">{{ __('Arrears') }}</label>'+
            '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
              '<span style="margin-right: 8px;">'+ (Balance) + '</span>'+
            '</div>'+
        '</div>'+

        '<div class="form-group row bg-gray m-0 p-1">'+
            '<label for="Phone" class="col-md-5 col-form-label text-md-right p-1">{{ __('Refund') }}</label>'+
            '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
              '<span style="margin-right: 8px;">'+ (Refund) + '</span>'+
            '</div>'+
        '</div>'+
        
        '<div class="form-group row bg-gray m-0 p-1">'+
            '<label for="Phone" class="col-md-5 col-form-label text-md-right p-1">{{ __('Assigned') }}</label>'+

            '<div class="col-md-7 p-1" style="background-color: white;color: black;border-radius:10px;padding: 5px;text-align:left;">'+
              '<span style="margin-right: 8px;">'+ (dateToMonthName) + '</span>'+
            '</div>'+
        '</div>');
      $('.propertiesmanagehousefromsel').html(propertiesmanagehousefromsel);

      let propertiesmanagehousevacateinfosel='';

      propertiesmanagehousevacateinfosel=propertiesmanagehousevacateinfosel+(''+
        '<div class="form-group row m-0 mt-1 p-1">'+
          '<label for="Damages" class="col-md-5 col-form-label text-md-right m-0 p-1">{{ __('Damages') }}</label>'+

          '<div class="col-md-7 p-1">'+
              '<input id="Damages" type="text" class="form-control" name="Damages" value="{{ old('Damages') }}" placeholder="0.00" required autocomplete="Damages" autofocus>'+
          '</div>'+
        '</div>'+

        '<div class="form-group row m-0 mt-1 p-1">'+
          '<label for="Transaction" class="col-md-5 col-form-label text-md-right m-0 p-1">{{ __('Transaction') }}</label>'+

          '<div class="col-md-7 p-1">'+
              '<input id="Transaction" type="text" class="form-control" name="Transaction" value="{{ old('Transaction') }}" placeholder="0.00" required autocomplete="Transaction" autofocus>'+
          '</div>'+
        '</div>'+

        '<div class="form-group row m-0 mt-1 p-1">'+
          '<label for="Refund" class="col-md-5 col-form-label text-md-right m-0 p-1">{{ __('To Refund') }}</label>'+

          '<div class="col-md-7 p-1">'+
              '<input id="Refund" type="text" class="form-control" name="Refund" value="'+ (Refund) + '" required placeholder="0.00" autocomplete="Refund" autofocus>'+
          '</div>'+
        '</div>'+

        '<div class="form-group row m-0 mt-1 p-1">'+
          '<label for="DateAssigned" class="col-md-5 col-form-label text-md-right m-0 p-1">{{ __('Vacating') }}</label>'+

          '<div class="col-md-7 p-1">'+
              '<input id="DateAssigned" type="date" class="form-control" name="DateAssigned" value="{{ old('DateAssigned') }}" required autocomplete="DateAssigned" autofocus>'+
          '</div>'+
        '</div>');

      $('.propertiesmanagehousevacateinfosel').html(propertiesmanagehousevacateinfosel);


      $('.propertiesmanagehousefromselresload').html('');

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
          var Depositss=new Number($('#Deposit').val());
          var Arrears=new Number($('#Arrears').val());
          var Transaction=new Number($('#Transaction').val());
          var total=new Number(Depositss-(thisDamages+Arrears+Transaction));
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
          var Depositss=new Number($('#Deposit').val());
          var Arrears=new Number($('#Arrears').val());
          var Damages=new Number($('#Damages').val());
          var total=new Number(Depositss-(thisTransaction+Arrears+Damages));
          $('#Refund').val(total);
        }
      });

    }));
    
    
    $("#formplotmgrsavevacatetenanthouse").on('submit',(function(ee){
      ee.preventDefault();
      if(vacanthousenamefrom==''){
        alert('Please Select Current House Vacating');
        return false;
      }
      else{
        $("#modalconfirmsaveproperty-body").html(''+
        '<form name="formplotmgrsaveconfirmvacatetenanthouse" id="formplotmgrsaveconfirmvacatetenanthouse" method="post">'+
          '@csrf'+
          'Sure to Vacate <br/><b>'+ 'Tenant: <b>' + Fname+' ' + Oname +' </b> from House: <b>' + vacanthousenamefrom +' </b>?<br/>'+
          '<button class="btn btn-success float-sm-right modalconfirmsaveproperty-okay">Vacate Tenant Now</button>'+
          '</form>');
        $("#modalconfirmsaveproperty-title").html('Vacate Tenant: <b>' + Fname+' ' + Oname+' </b> from House: <b>' +vacanthousenamefrom +' </b>?<br/>');
        $("#modalconfirmsaveproperty").modal('show');
        
        $("#formplotmgrsaveconfirmvacatetenanthouse").on('submit',(function(e){
          e.preventDefault();
          $(".modalconfirmsaveproperty-okay").html("Vacating Tenant... "+'<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
          $.ajax({
              url:'/properties/manage/tenant/vacate',
              type:"POST",
              data:new FormData(document.getElementById('formplotmgrsavevacatetenanthouse')),
              processData:false,
              contentType:false,
              cache:false,
              success:function(data){
                if(data['error']){
                    $(document).Toasts('create', {
                        title: 'Error in Vacating Tenant',
                        class: 'bg-warning',
                        position: 'topRight',
                        body: data['error']
                    })
                    $("#modalconfirmsaveproperty").modal('hide');
                }
                else{
                    $(document).Toasts('create', {
                        title: 'Vacating Tenant',
                        class: 'bg-success',
                        position: 'topRight',
                        body: data['success']
                    })
                    $("#modalconfirmsaveproperty").modal('hide');
                    getTenantVacateHouses(tid);
                    getTenants(statuss,statusvalue);

                }
              },
              error: function(xhr, status, error){
                var errorMessage = xhr.status + ': ' + xhr.statusText
                if(xhr.status==419){
                  window.location.href='';
                }
                if (errorMessage=="0: error") {
                    errorMessage="Internet Connection Interrupted.";
                }
                else if(errorMessage=="404: Not Found"){
                    errorMessage="Could Not Process Data."
                }
                else if(errorMessage=="500: Internal Server Error"){
                    errorMessage="Failed due to Some Technical Error In a Server."
                }
                if(xhr.status==422){
                  errorMessage = xhr.responseText;
                }

                if(xhr.status==404){
                  errorMessage ="Could not Locate Resource.";
                }

                if(xhr.status==500){
                  errorMessage = "Failed due to Some Technical Error In a Server."
                }
                $(document).Toasts('create', {
                    title: 'Error Vacating Tenant',
                    class: 'bg-warning',
                    position: 'topRight',
                    body: errorMessage
                })
                $('.modalconfirmsaveproperty-okay').html('<h6 class="text-center">Try Vacating Tenant Again</h6>');
              }
            });  
        }));
      }
      
    }));
  
  }));

  $(document).on('click','.btnplotmgrtenantplot',(function(e){
    var status = $(this).data("id0");
    var statusvalue = $(this).data("id1");
    if(statusvalue==''){
      statusvalue=status;
    }
    getTenants(status,statusvalue);
  }));
  
  function getTenants(status,statusvalue){
    $('.propertiesmanagetenantresload').html("Loading "+statusvalue+" Tenants ... "+'<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
    $('.propertiesmanageresload').html("Loading "+statusvalue+" Tenants ... "+'<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
    $('.propertiesmanagesidetenant').html('');
    $('.propertiesmanageres').html('');
    $.ajax({
      headers:{
          'X-CSRF-TOKEN':$('meta[name="csrf-token"').attr('content')
        },
      type:'GET',
      url:'/properties/manage/tenants/'+status,
      success: function(data)
      {
        $('.propertiesmanagetenantresload').html('');
        $('.propertiesmanageresload').html('');
      
        var propertyinfo=data['alltenantsinfo'];
        var propertycount=data['alltenantscount'];
        let headerdata='';
        
        headerdata=('<p class="text-success text-center m-0 p-0">'+statusvalue+'('+propertycount+') Tenants '+
        '<a href="/properties/download/Reports/TenantsInfo" class="btn btn-outline-info" style="float:right;padding:2px;margin-right:5px;margin-bottom:2px;"><i class="fa fa-download"></i> All Properties</a>'+
        '<a href="/properties/download/Reports/TenantsInfo/'+status+'" class="btn btn-outline-success" style="float:right;padding:2px;margin-right:5px;margin-bottom:2px;"><i class="fa fa-download"></i> '+statusvalue+'</a>'+
        '</p>'+

          '<table id="example1" class="table table-bordered table-striped">'+
              '<thead>'+
                '<tr style="color: #77B5ED;">'+
                  '<th class="m-0 p-1">Sno</th>'+
                  '<th class="m-0 p-1">Tenant Name</th>'+
                  '<th class="m-0 p-1">Status</th>'+
                  '<th class="m-0 p-1">Nos</th>'+
                  '<th class="m-0 p-1">Hse</th>'+
                  '<th class="m-0 p-1">Actions</th>'+
                '</tr>'+
              '</thead>'+
            '<tbody class="propertiesmanagepropertiestenants">');
        $('.propertiesmanageres').html('');
        let outputdata='';
        let propertiesmanagesidetenant='';
        propertiesmanagesidetenant=propertiesmanagesidetenant+('<p class="text-success text-center m-0 p-0">'+statusvalue+'('+propertycount+') Tenants</p>');
        let sno=0;
        let total=propertyinfo.length;
        for (var i = 0; i < propertyinfo.length; i++) {
          sno++;
          //fill properties results
          if(status=='New' || status=='Vacated'){
            outputdata=outputdata+(''+
              '<tr class="text-xs" style="padding:0px;margin:2px;background-color:#FFFFFF;">'+
                  '<td class="m-0 p-1">'+(i+1)+'</td>'+
                  '<td class="m-0 p-1">'+propertyinfo[i]['tenantname']+'</td>'+
                  '<td class="m-0 p-1">'+propertyinfo[i]['Status']+'</td>'+
                  '<td class="m-0 p-1">'+propertyinfo[i]['totalhouses']+'</td>'+
                  '<td class="m-0 p-1">'+propertyinfo[i]['houses']+'</td>'+
                  '<td class="m-0 p-1">'+
                    '<button type="button" class="btn btn-outline-success text-xs p-0 m-0 ml-1 pr-1 pl-1 btnplotmgrassign" '+
                        'data-id0="'+propertyinfo[i]['id']+'" data-id1="'+propertyinfo[i]['Fname']+'" data-id2="'+propertyinfo[i]['Oname']+'" '+
                        'data-id3="'+propertyinfo[i]['Phone']+'" data-id4="'+propertyinfo[i]['IDno']+'" data-id5="'+propertyinfo[i]['HudumaNo']+'" '+
                        'data-id6="'+propertyinfo[i]['Email']+'" data-id7="'+propertyinfo[i]['Gender']+'" data-id8="'+propertyinfo[i]['statuss']+'" data-id9="'+propertyinfo[i]['statusvalue']+'">'+
                          ' Assign'+
                    '</button>'+
                    '<button type="button" class="btn btn-outline-primary text-xs p-0 m-0 ml-1 pr-1 pl-1 btnplotmgredittenant" '+
                        'data-id0="'+propertyinfo[i]['id']+'" data-id1="'+propertyinfo[i]['Fname']+'" data-id2="'+propertyinfo[i]['Oname']+'" '+
                        'data-id3="'+propertyinfo[i]['Phone']+'" data-id4="'+propertyinfo[i]['IDno']+'" data-id5="'+propertyinfo[i]['HudumaNo']+'" '+
                        'data-id6="'+propertyinfo[i]['Email']+'" data-id7="'+propertyinfo[i]['Gender']+'" data-id8="'+propertyinfo[i]['statuss']+'" data-id9="'+propertyinfo[i]['statusvalue']+'">'+
                      '<i class="fas fa-edit"></i>'+
                    '</button>'+
                    '<button type="button" class="btn btn-outline-danger text-xs p-0 m-0 ml-1 pr-1 pl-1 btnplotmgrdeltenant" '+
                      'data-id0="'+propertyinfo[i]['id']+'" data-id1="'+propertyinfo[i]['tenantname']+'" data-id2="'+propertyinfo[i]['statuss']+'" data-id3="'+propertyinfo[i]['statusvalue']+'">'+
                      '<i class="fas fa-trash"></i>'+
                    '</button>'+
                  '</td>'+
              '</tr>');
          }
          else{
            outputdata=outputdata+(''+
              '<tr class="text-xs" style="padding:0px;margin:2px;background-color:#FFFFFF;">'+
                  '<td class="m-0 p-1">'+(i+1)+'</td>'+
                  '<td class="m-0 p-1" style="font-size:11px;">'+propertyinfo[i]['tenantname']+'</td>'+
                  '<td class="m-0 p-1" style="font-size:10px;">'+propertyinfo[i]['Status']+'</td>'+
                  '<td class="m-0 p-1">'+propertyinfo[i]['totalhouses']+'</td>'+
                  '<td class="m-0 p-1">'+propertyinfo[i]['houses']+'</td>'+
                  '<td class="m-0 p-1">'+
                      '<button type="button" class="btn btn-outline-info text-xs p-0 m-0 mb-1 ml-1 pr-1 pl-1 btnplotmgraddhouse" '+
                        'data-id0="'+propertyinfo[i]['id']+'" data-id1="'+propertyinfo[i]['Fname']+'" data-id2="'+propertyinfo[i]['Oname']+'" '+
                        'data-id3="'+propertyinfo[i]['Phone']+'" data-id4="'+propertyinfo[i]['IDno']+'" data-id5="'+propertyinfo[i]['HudumaNo']+'" '+
                        'data-id6="'+propertyinfo[i]['Email']+'" data-id7="'+propertyinfo[i]['Gender']+'" data-id8="'+propertyinfo[i]['statuss']+'" data-id9="'+propertyinfo[i]['statusvalue']+'" title="Add House to this Tenant" style="font-size:11px;">'+
                            ' Add'+
                      '</button>'+
                      // '<button type="button" class="btn btn-outline-info text-xs p-0 m-0 mb-1 ml-1 pr-1 pl-1 btnplotmgrtransfer" '+
                      //   'data-id0="'+propertyinfo[i]['id']+'" data-id1="'+propertyinfo[i]['Fname']+'" data-id2="'+propertyinfo[i]['Oname']+'" '+
                      //   'data-id3="'+propertyinfo[i]['Phone']+'" data-id4="'+propertyinfo[i]['IDno']+'" data-id5="'+propertyinfo[i]['HudumaNo']+'" '+
                      //   'data-id6="'+propertyinfo[i]['Email']+'" data-id7="'+propertyinfo[i]['Gender']+'" data-id8="'+propertyinfo[i]['statuss']+'" data-id9="'+propertyinfo[i]['statusvalue']+'" title="Transfer Tenant House to Another Tenant" style="font-size:11px;">'+
                      //       '<i class="fas fa-paper-plane text-xs"> Transfer</i>'+
                      // '</button>'+
                      '<button type="button" class="btn btn-outline-success text-xs p-0 m-0 mb-1 ml-1 pr-1 pl-1 btnplotmgrreassign" '+
                        'data-id0="'+propertyinfo[i]['id']+'" data-id1="'+propertyinfo[i]['Fname']+'" data-id2="'+propertyinfo[i]['Oname']+'" '+
                        'data-id3="'+propertyinfo[i]['Phone']+'" data-id4="'+propertyinfo[i]['IDno']+'" data-id5="'+propertyinfo[i]['HudumaNo']+'" '+
                        'data-id6="'+propertyinfo[i]['Email']+'" data-id7="'+propertyinfo[i]['Gender']+'" data-id8="'+propertyinfo[i]['statuss']+'" data-id9="'+propertyinfo[i]['statusvalue']+'" title="Re- Assign or Change Tenant House to Another House" style="font-size:11px;">'+
                            '<i class="fas fa-fa-retweet text-xs"> Change</i>'+
                      '</button>'+
                      '<button type="button" class="btn btn-outline-primary text-xs p-0 m-0 mb-1 ml-1 pr-1 pl-1 btnplotmgredittenant" '+
                        ' data-id0="'+propertyinfo[i]['id']+'" data-id1="'+propertyinfo[i]['Fname']+'" data-id2="'+propertyinfo[i]['Oname']+'" '+
                        ' data-id3="'+propertyinfo[i]['Phone']+'" data-id4="'+propertyinfo[i]['IDno']+'" data-id5="'+propertyinfo[i]['HudumaNo']+'" '+
                        ' data-id6="'+propertyinfo[i]['Email']+'" data-id7="'+propertyinfo[i]['Gender']+'" data-id8="'+propertyinfo[i]['statuss']+'" data-id9="'+propertyinfo[i]['statusvalue']+'">'+
                        '<i class="fas fa-edit"></i>'+
                      '</button>'+
                      '<button type="button" class="btn btn-outline-danger text-xs p-0 m-0 mb-1 ml-1 pr-1 pl-1 btnplotmgrdeltenant" '+
                        'data-id0="'+propertyinfo[i]['id']+'" data-id1="'+propertyinfo[i]['tenantname']+'" data-id2="'+propertyinfo[i]['statuss']+'" data-id3="'+propertyinfo[i]['statusvalue']+'">'+
                        '<i class="fas fa-trash"></i>'+
                      '</button>'+
                  '</td>'+
              '</tr>');
          }
          //fill properties side bar
          if(status=='New' || status=='Vacated'){
            propertiesmanagesidetenant=propertiesmanagesidetenant+(''+
            '<div class="col-12 m-1 p-0" >'+
              '<div class="elevation-1 m-0 p-0">'+
                '<div class="card-header bg-white  btn-tool m-0 p-0">'+
                  '<h4 class="text-xs text-left m-0 p-1">'+
                    '<span class="text-left text-xs">'+
                      '<span class="text-dark text-xs mx-auto m-0 p-1" title="'+propertyinfo[i]['Plotname']+'"> '+
                        '<b> '+(i+1)+'. '+propertyinfo[i]['tenantname']+'</b> ('+propertyinfo[i]['houses']+')'+
                      '</span>'+
                      '<span class="text-info text-xs float-right m-0 p-0">'+
                        '<button type="button" class="btn btn-outline-success text-xs p-0 m-0 ml-1 pr-1 pl-1 btnplotmgrassign" '+
                          'data-id0="'+propertyinfo[i]['id']+'" data-id1="'+propertyinfo[i]['Fname']+'" data-id2="'+propertyinfo[i]['Oname']+'" '+
                          'data-id3="'+propertyinfo[i]['Phone']+'" data-id4="'+propertyinfo[i]['IDno']+'" data-id5="'+propertyinfo[i]['HudumaNo']+'" '+
                          'data-id6="'+propertyinfo[i]['Email']+'" data-id7="'+propertyinfo[i]['Gender']+'" data-id8="'+propertyinfo[i]['statuss']+'" data-id9="'+propertyinfo[i]['statusvalue']+'">'+
                          ' Assign'+
                        '</button>'+

                      '</span>'+
                  ' </span>'+
                  '</h4>'+
                '</div>'+
              '</div>'+
            '</div>');
          }
          else{
            propertiesmanagesidetenant=propertiesmanagesidetenant+(''+
            '<div class="col-12 m-1 p-0" >'+
              '<div class="elevation-1 m-0 p-0">'+
                '<div class="card-header bg-white  btn-tool m-0 p-0">'+
                  '<h4 class="text-xs text-left m-0 p-1">'+
                    '<span class="text-left text-xs">'+
                      '<span class="text-dark text-xs mx-auto m-0 p-1" title="'+propertyinfo[i]['Plotname']+'"> '+
                        '<b> '+(i+1)+'. '+propertyinfo[i]['tenantname']+'</b> ('+propertyinfo[i]['houses']+')'+
                      '</span>'+
                      '<span class="text-info text-xs float-right m-0 p-0">'+
                        '<button type="button" class="btn btn-outline-danger text-xs p-0 m-0 ml-1 pr-1 pl-1 btnplotmgrvacatetenant" '+
                          ' data-id0="'+propertyinfo[i]['id']+'" data-id1="'+propertyinfo[i]['Fname']+'" data-id2="'+propertyinfo[i]['Oname']+'" '+
                          ' data-id3="'+propertyinfo[i]['Phone']+'" data-id4="'+propertyinfo[i]['IDno']+'" data-id5="'+propertyinfo[i]['HudumaNo']+'" '+
                          ' data-id6="'+propertyinfo[i]['Email']+'" data-id7="'+propertyinfo[i]['Gender']+'" data-id8="'+propertyinfo[i]['statuss']+'" data-id9="'+propertyinfo[i]['statusvalue']+'">'+
                            ' Vacate'+
                        ' </button>'+

                      '</span>'+
                  ' </span>'+
                  '</h4>'+
                '</div>'+
              '</div>'+
            '</div>');
          }
        }
        $('.propertiesmanagesidetenant').html(propertiesmanagesidetenant);
        
        let footerdata='';
        footerdata+=(''+
            '</tbody>'+
          '</table>');
        $('.propertiesmanageres').html(headerdata);
        $('.propertiesmanagepropertiestenants').html(outputdata);
        $('.propertiesmanageres').append(footerdata);
        
      },
      error: function(xhr, status, error){
        var errorMessage = xhr.status + ': ' + xhr.statusText
        if(xhr.status==419){
          window.location.href='';
        }
        if (errorMessage=="0: error") {
            errorMessage="No Connection" 
        }
        $('.propertiesmanagetenantresload').html('');
        $('.propertiesmanageresload').html('');
        $('.propertiesmanageres').html('<div class="text-sm text-danger text-center">'+errorMessage+'<br>Could Not Load Tenants Data </div>');
        $('.propertiesmanagesidetenant').html('<div class="text-sm text-danger text-center">'+errorMessage+'<br>Could Not Load Tenants Data </div>');
      }
    });
  }

  function getHouses(plotid,plotcode,plotname){
    $('.propertiesmanagehouseresload').html("Loading "+plotname+" Houses... "+'<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
    $('.propertiesmanageresload').html("Loading "+plotname+" Houses... "+'<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
    $('.propertiesmanageres').html('');
    $.ajax({
      headers:{
          'X-CSRF-TOKEN':$('meta[name="csrf-token"').attr('content')
        },
      type:'GET',
      url:'/properties/manage/houses/'+plotid,
      success: function(data)
      {
        $('.propertiesmanagehouseresload').html('');
        $('.propertiesmanageresload').html('');
      
        var propertyinfo=data['housesinfo'];
        let headerdata='';
        headerdata=('<p class="text-success text-center m-0 p-0">'+plotname+'('+plotcode+') Houses</p>'+
        '<form role="form" name="seachedhousesform" id="seachedhousesform" class="form-horizontal" action="/updatehouse" method="post" enctype="multipart/form-data">'+
                '@csrf'+
                '<div class="form-group row m-0 p-0 text-xs">'+
                    '<label for="Housename" class="col-md-1 col-form-label m-0 p-0 text-md-right">Details</label>'+

                    '<div class="col-md-3 m-0 p-1">'+
                        '<select name="updatefield" id="updatefield" class="select form-control" required="required">'+
                            '<option value="">Choose what to Update...</option>'+
                            '<option value="Rent">Rent</option>'+
                            '<option value="Deposit">House Deposit</option>'+
                            '<option value="Kplc">Kplc Deposit</option>'+
                            '<option value="Water">Water Deposit</option>'+
                            '<option value="Lease">Lease Deposit</option>'+
                            '<option value="Garbage">Garbage Deposit</option>'+
                            '<option value="DueDay">DueDay</option>'+
                            '<option value="PrevName">Previous House Name</option>'+
                        '</select>'+
                    '</div>'+
                    '<label for="Housename" class="col-md-1 col-form-label m-0 p-0 text-md-right">Value</label>'+

                    '<div class="col-md-3 m-0 p-1">'+
                        '<input type="text" name="updatevalue" id="updatevalue" placeholder="Enter Value to Update" class="form-control" required="required" >'+
                    '</div>'+
                    '<div class="col-sm-3 m-0 p-1">'+
                        '<button  class="btn btn-warning text-xs" name="submitupdatebtn" id="submitupdatebtn"  type="submit" >Update Selected</button>'+
                    '</div>'+
                    '<div class="col-sm-1 m-0 p-1" style="padding: 0px;">'+
                          '<span style="position:fixed;z-index:999999;color:red;font-size:15px;padding: 2px;">(<i class="badge" id="selectedhousesforupdate" style="font-size:16px;">0</i>)</span>'+
                    '</div>'+
                '</div>'+
                '<table id="example1" class="table table-bordered table-striped">'+
              '<thead>'+
                '<tr style="color: #77B5ED;">'+
                  '<th class="m-0 p-1">Sno</th>'+
                  '<th class="m-0 p-1">House</th>'+
                  '<th class="m-0 p-1">Tenant</th>'+
                  '<th class="m-0 p-1">Rent</th>'+
                  '<th class="m-0 p-1">Deposit</th>'+
                  '<th class="m-0 p-1">KPLC</th>'+
                  '<th class="m-0 p-1">Water</th>'+
                  '<th class="m-0 p-1">Lease</th>'+
                  '<th class="m-0 p-1">Garbage</th>'+
                  '<th class="m-0 p-1">Due</th>'+
                  '<th class="m-0 p-1">Status</th>'+
                '</tr>'+
              '</thead>'+
            '<tbody class="propertiesmanagehouses">');
        $('.propertiesmanageres').html('');
        let outputdata='';
        let propertiesmanagesidehouse='';
        propertiesmanagesidehouse=propertiesmanagesidehouse+('<p class="text-success text-center m-0 p-0">Property: '+plotname+'</p>');
        let sno=0;
        let total=propertyinfo.length;
        for (var i = 0; i < propertyinfo.length; i++) {
          sno++;
          //fill properties results
          outputdata=outputdata+(''+
            '<tr class="text-xs unstatementvaluesdiv" style="padding:0px;margin:2px;background-color:#FFFFFF;" data-id1="houseno'+(i+1)+'">'+
                '<td class="m-0 p-1"><label class="col-lg-12" style="font-size:12px;cursor:pointer;"> '+
                '<input type="checkbox" name="houseno[]" id="houseno'+(i+1)+'" class="selectedhousesforupdate"  value="'+propertyinfo[i]['id']+':'+propertyinfo[i]['Housename']+'"> '+(i+1)+'</label></td>'+
                '<td class="m-0 p-1">'+propertyinfo[i]['Housename']+'</td>'+
                '<td class="m-0 p-1">'+propertyinfo[i]['tenantname']+'</td>'+
                '<td class="m-0 p-1">'+propertyinfo[i]['Rent']+'</td>'+
                '<td class="m-0 p-1">'+propertyinfo[i]['Deposit']+'</td>'+
                '<td class="m-0 p-1">'+propertyinfo[i]['Kplc']+'</td>'+
                '<td class="m-0 p-1">'+propertyinfo[i]['Water']+'</td>'+
                '<td class="m-0 p-1">'+propertyinfo[i]['Lease']+'</td>'+
                '<td class="m-0 p-1">'+propertyinfo[i]['Garbage']+'</td>'+
                '<td class="m-0 p-1">'+propertyinfo[i]['DueDay']+'</td>'+
                '<td class="m-0 p-1">'+propertyinfo[i]['Status']+'</td>'+
            '</tr>');
         
          //fill properties side bar
          propertiesmanagesidehouse=propertiesmanagesidehouse+(''+
            '<div class="col-12 m-1 p-0" >'+
              '<div class="elevation-1 m-0 p-0">'+
                '<div class="card-header bg-white  btn-tool m-0 p-0">'+
                  '<h4 class="text-xs text-left m-0 p-1">'+
                    '<span class="text-left text-xs">'+
                      '<span class="text-dark text-xs mx-auto m-0 p-1" title="'+propertyinfo[i]['Housename']+'"> '+
                        '<b> '+(i+1)+' . '+propertyinfo[i]['Housename']+'</b>('+propertyinfo[i]['tenantname']+')'+
                      '</span>'+
                      '<span class="text-info text-xs float-right m-0 p-0">'+
                        '<button type="button" class="btn btn-outline-primary text-xs p-0 m-0 ml-1 pr-1 pl-1 btnplotmgredithouse" '+
                        ' data-id0="'+propertyinfo[i]['id']+'" data-id1="'+propertyinfo[i]['Housename']+'" data-id2="'+propertyinfo[i]['Rent']+'" '+
                        ' data-id3="'+propertyinfo[i]['Deposit']+'" data-id4="'+propertyinfo[i]['Water']+'" data-id5="'+propertyinfo[i]['Lease']+'" '+
                        ' data-id6="'+propertyinfo[i]['Garbage']+'" data-id7="'+propertyinfo[i]['DueDay']+'" data-id8="'+propertyinfo[i]['Kplc']+'" '+
                        ' data-id9="'+propertyinfo[i]['Plot']+'" data-id10="'+propertyinfo[i]['plotcode']+'" data-id11="'+propertyinfo[i]['plotname']+'" >'+
                          '<i class="fas fa-edit"></i>'+
                        '</button>'+
                        '<button type="button" class="btn btn-outline-danger text-xs p-0 m-0 ml-1 pr-1 pl-1 btnplotmgrdelhouse" '+
                          'data-id0="'+propertyinfo[i]['id']+'" data-id1="'+propertyinfo[i]['Housename']+'" data-id2="'+propertyinfo[i]['Plot']+'" '+
                          'data-id3="'+propertyinfo[i]['plotcode']+'" data-id2="'+propertyinfo[i]['plotname']+'">'+
                        ' <i class="fas fa-trash"></i>'+
                      ' </button>'+

                      '</span>'+
                  ' </span>'+
                  '</h4>'+
                '</div>'+
              '</div>'+
            '</div>');
        }
        $('.propertiesmanagesidehouse').html(propertiesmanagesidehouse);
        
        let footerdata='';
        footerdata+=(''+
            '</tbody>'+
          '</table>'+
          '</form>');
        $('.propertiesmanageres').html(headerdata);
        $('.propertiesmanagehouses').html(outputdata);
        $('.propertiesmanageres').append(footerdata);

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
        
      },
      error: function(xhr, status, error){
        var errorMessage = xhr.status + ': ' + xhr.statusText
        if(xhr.status==419){
          window.location.href='';
        }
        if (errorMessage=="0: error") {
            errorMessage="No Connection" 
        }
        $('.propertiesmanagehouseresload').html('');
        $('.propertiesmanageresload').html('');
        $('.propertiesmanageres').html('<div class="text-sm text-danger text-center">'+errorMessage+'<br>Could Not Load Properties Data </div>');
      }
    });
  }

  function getVacantHouses(){
    vacanthousename='';
    vacanthousenamefrom='';
    $('.propertiesmanagehousevacantresload').html('Loading Vacant Houses... <img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
    $('.propertiesmanagehousevacantsel').html('Please Select Vacant House');
    $('.propertiesmanagehousefromsel').html('Please Select House Re-Assigning FROM');
    $('.propertiesmanagehousereassigningsel').html('');
    $('.propertiesmanagehousereassigningsel1').html('');
    $.ajax({
      headers:{
          'X-CSRF-TOKEN':$('meta[name="csrf-token"').attr('content')
        },
      type:'GET',
      url:'/properties/manage/houses/vacant/',
      success: function(data)
      {
        $('.propertiesmanagehousevacantresload').html('');
      
        var propertyinfo=data['housesinfo'];
        
        let outputdata='';
        let propertiesmanagehousevacant='';
        // propertiesmanagehousevacant=propertiesmanagehousevacant+('<p class="text-success text-center m-0 p-0">Property: '+plotname+'</p>');
        let sno=0;
        let total=propertyinfo.length;
        for (var i = 0; i < propertyinfo.length; i++) {
          sno++;
          
          //fill properties side bar
          propertiesmanagehousevacant=propertiesmanagehousevacant+(''+
            '<div class="col-12 m-1 p-0" >'+
              '<div class="elevation-1 m-0 p-0">'+
                '<div class="card-header bg-white  btn-tool m-0 p-0">'+
                  '<h4 class="text-xs text-left m-0 p-1">'+
                    '<span class="text-left text-xs">'+
                      '<span class="text-dark text-xs mx-auto m-0 p-1" title="'+propertyinfo[i]['Housename']+'"> '+
                        '<b> '+(i+1)+' . '+propertyinfo[i]['Housename']+'</b>('+propertyinfo[i]['tenantname']+')'+
                      '</span>'+
                      '<span class="text-info text-xs float-right m-0 p-0">'+
                        '<button type="button" class="btn btn-outline-primary text-xs p-0 m-0 ml-1 pr-1 pl-1 btnplotmgselecthousevacant" '+
                        ' data-id0="'+propertyinfo[i]['id']+'" data-id1="'+propertyinfo[i]['Housename']+'" data-id2="'+propertyinfo[i]['Rent']+'" '+
                        ' data-id3="'+propertyinfo[i]['Deposit']+'" data-id4="'+propertyinfo[i]['Water']+'" data-id5="'+propertyinfo[i]['Lease']+'" '+
                        ' data-id6="'+propertyinfo[i]['Garbage']+'" data-id7="'+propertyinfo[i]['DueDay']+'" data-id8="'+propertyinfo[i]['Kplc']+'" '+
                        ' data-id9="'+propertyinfo[i]['Plot']+'" data-id10="'+propertyinfo[i]['plotcode']+'" data-id11="'+propertyinfo[i]['plotname']+'" >'+
                          '<i class="fas fa-check"> Select House</i>'+
                        '</button>'+

                      '</span>'+
                  ' </span>'+
                  '</h4>'+
                '</div>'+
              '</div>'+
            '</div>');
        }
        $('.propertiesmanagehousevacant').html(propertiesmanagehousevacant);
        
        
      },
      error: function(xhr, status, error){
        var errorMessage = xhr.status + ': ' + xhr.statusText
        if(xhr.status==419){
          window.location.href='';
        }
        if (errorMessage=="0: error") {
            errorMessage="No Connection" 
        }
        $('.propertiesmanagehousevacantresload').html('');
        $('.propertiesmanagehousevacant').html('<div class="text-sm text-danger text-center">'+errorMessage+'<br>Could Not Load Vacant Houses </div>');
      }
    });
  }

  function getTenantCurrentHouses(tid){
    vacanthousename='';
    vacanthousenamefrom='';
    $('.propertiesmanagecurrenttenanthouseresload').html('Loading Current Tenant Houses... <img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
    $('.propertiesmanagehousefromsel').html('Please Select House Re-Assigning FROM');
    $('.propertiesmanagehousevacantsel').html('Please Select Vacant House');
    $('.propertiesmanagehousereassigningsel').html('');
    $('.propertiesmanagehousereassigningsel1').html('');
    
    $.ajax({
      headers:{
          'X-CSRF-TOKEN':$('meta[name="csrf-token"').attr('content')
        },
      type:'GET',
      url:'/properties/manage/houses/tenant/'+tid,
      success: function(data)
      {
        $('.propertiesmanagecurrenttenanthouseresload').html('');
      
        var propertyinfo=data['housesinfo'];
        
        let outputdata='';
        let propertiesmanagecurrenttenanthouse='';
        // propertiesmanagecurrenttenanthouse=propertiesmanagecurrenttenanthouse+('<p class="text-success text-center m-0 p-0">Property: '+plotname+'</p>');
        let sno=0;
        let total=propertyinfo.length;
        for (var i = 0; i < propertyinfo.length; i++) {
          sno++;
          
          //fill properties side bar
          propertiesmanagecurrenttenanthouse=propertiesmanagecurrenttenanthouse+(''+
            '<div class="col-12 m-1 p-0" >'+
              '<div class="elevation-1 m-0 p-0">'+
                '<div class="card-header bg-white  btn-tool m-0 p-0">'+
                  '<h4 class="text-xs text-left m-0 p-1">'+
                    '<span class="text-left text-xs">'+
                      '<span class="text-dark text-xs mx-auto m-0 p-1" title="'+propertyinfo[i]['Housename']+'"> '+
                        '<b> '+(i+1)+' . '+propertyinfo[i]['Housename']+'</b>('+propertyinfo[i]['tenantname']+')'+
                      '</span>'+
                      '<span class="text-info text-xs float-right m-0 p-0">'+
                        '<button type="button" class="btn btn-outline-primary text-xs p-0 m-0 ml-1 pr-1 pl-1 btnplotmgselectcurrenttenanthouse" '+
                        ' data-id0="'+propertyinfo[i]['id']+'" data-id1="'+propertyinfo[i]['Housename']+'" data-id2="'+propertyinfo[i]['Rent']+'" '+
                        ' data-id3="'+propertyinfo[i]['Deposit']+'" data-id4="'+propertyinfo[i]['Water']+'" data-id5="'+propertyinfo[i]['Lease']+'" '+
                        ' data-id6="'+propertyinfo[i]['Garbage']+'" data-id7="'+propertyinfo[i]['DueDay']+'" data-id8="'+propertyinfo[i]['Kplc']+'" '+
                        ' data-id9="'+propertyinfo[i]['Plot']+'" data-id10="'+propertyinfo[i]['plotcode']+'" data-id11="'+propertyinfo[i]['plotname']+'" >'+
                          '<i class="fas fa-check"> Re-Assign</i>'+
                        '</button>'+

                      '</span>'+
                  ' </span>'+
                  '</h4>'+
                '</div>'+
              '</div>'+
            '</div>');
        }
        $('.propertiesmanagecurrenttenanthouse').html(propertiesmanagecurrenttenanthouse);
        
      },
      error: function(xhr, status, error){
        var errorMessage = xhr.status + ': ' + xhr.statusText
        if(xhr.status==419){
          window.location.href='';
        }
        if (errorMessage=="0: error") {
            errorMessage="No Connection" 
        }
        $('.propertiesmanagecurrenttenanthouseresload').html('');
        $('.propertiesmanagecurrenttenanthouse').html('<div class="text-sm text-danger text-center">'+errorMessage+'<br>Could Not Load Vacant Houses </div>');
      }
    });
  }

  function getTenantVacateHouses(tid){
    vacanthousename='';
    vacanthousenamefrom='';
    $('.propertiesmanagevacatetenanthouseresload').html('Loading Current Tenant Houses... <img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
    $('.propertiesmanagehousefromsel').html('Please Select House to Vacate');
    $('.propertiesmanagehousevacateinfosel').html('');
    
    $.ajax({
      headers:{
          'X-CSRF-TOKEN':$('meta[name="csrf-token"').attr('content')
        },
      type:'GET',
      url:'/properties/manage/houses/vacate/tenant/'+tid,
      success: function(data)
      {
        $('.propertiesmanagevacatetenanthouseresload').html('');
      
        var propertyinfo=data['housesinfo'];
        
        let outputdata='';
        let propertiesmanagevacatetenanthouse='';
        // propertiesmanagevacatetenanthouse=propertiesmanagevacatetenanthouse+('<p class="text-success text-center m-0 p-0">Property: '+plotname+'</p>');
        let sno=0;
        let total=propertyinfo.length;
        for (var i = 0; i < propertyinfo.length; i++) {
          sno++;
          
          //fill properties side bar
          propertiesmanagevacatetenanthouse=propertiesmanagevacatetenanthouse+(''+
            '<div class="col-12 m-1 p-0" >'+
              '<div class="elevation-1 m-0 p-0">'+
                '<div class="card-header bg-white  btn-tool m-0 p-0">'+
                  '<h4 class="text-xs text-left m-0 p-1">'+
                    '<span class="text-left text-xs">'+
                      '<span class="text-dark text-xs mx-auto m-0 p-1" title="'+propertyinfo[i]['Housename']+'"> '+
                        '<b> '+(i+1)+' . '+propertyinfo[i]['Housename']+'</b>('+propertyinfo[i]['tenantname']+')'+
                      '</span>'+
                      '<span class="text-info text-xs float-right m-0 p-0">'+
                        '<button type="button" class="btn btn-outline-danger text-xs p-0 m-0 ml-1 pr-1 pl-1 btnplotmgselectvacatetenanthouse" '+
                        ' data-id0="'+propertyinfo[i]['id']+'" data-id1="'+propertyinfo[i]['Housename']+'" data-id2="'+propertyinfo[i]['Rent']+'" '+
                        ' data-id3="'+propertyinfo[i]['Deposit']+'" data-id4="'+propertyinfo[i]['Water']+'" data-id5="'+propertyinfo[i]['Lease']+'" '+
                        ' data-id6="'+propertyinfo[i]['Garbage']+'" data-id7="'+propertyinfo[i]['DueDay']+'" data-id8="'+propertyinfo[i]['Kplc']+'" '+
                        ' data-id9="'+propertyinfo[i]['Plot']+'" data-id10="'+propertyinfo[i]['plotcode']+'" data-id11="'+propertyinfo[i]['plotname']+'" '+
                        ' data-id12="'+propertyinfo[i]['TotalUsed']+'" data-id13="'+propertyinfo[i]['TotalPaid']+'" data-id14="'+propertyinfo[i]['Balance']+'" '+
                        ' data-id15="'+propertyinfo[i]['Refund']+'" data-id16="'+propertyinfo[i]['dateToMonthName']+'" data-id17="'+propertyinfo[i]['aid']+'" >'+
                          '<i class="fas fa-times"> Vacate</i>'+
                        '</button>'+

                      '</span>'+
                  ' </span>'+
                  '</h4>'+
                '</div>'+
              '</div>'+
            '</div>');
        }
        $('.propertiesmanagevacatetenanthouse').html(propertiesmanagevacatetenanthouse);
        
      },
      error: function(xhr, status, error){
        var errorMessage = xhr.status + ': ' + xhr.statusText
        if(xhr.status==419){
          window.location.href='';
        }
        if (errorMessage=="0: error") {
            errorMessage="No Connection" 
        }
        $('.propertiesmanagevacatetenanthouseresload').html('');
        $('.propertiesmanagevacatetenanthouse').html('<div class="text-sm text-danger text-center">'+errorMessage+'<br>Could Not Load Vacant Houses </div>');
      }
    });
  }
  
  function getProperties(){
    $('.propertiesmanageresload').html("Loading... "+'<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
    $('.propertiesmanageres').html('');
    $('.propertiesmanagesideproperty').html('');
    $('.propertiesmanagedropdownproperty').html('');
    $.ajax({
      headers:{
          'X-CSRF-TOKEN':$('meta[name="csrf-token"').attr('content')
        },
      type:'POST',
      url:'/properties/manage/info',
      success: function(data)
      {
        $('.propertiesmanageresload').html('');
      
        var propertyinfo=data['propertyinfo'];
        let headerdata='';
        headerdata=('<p class="text-success text-center m-0 p-0">All Properties Information</p><table id="example1" class="table table-bordered table-striped">'+
              '<thead>'+
                '<tr style="color: #77B5ED;">'+
                  '<th class="m-0 p-1">Sno</th>'+
                  '<th class="m-0 p-1">Name</th>'+
                  '<th class="m-0 p-1">Code</th>'+
                  '<th class="m-0 p-1">Water</th>'+
                  '<th class="m-0 p-1">Deposit</th>'+
                  '<th class="m-0 p-1">WDeposit</th>'+
                  '<th class="m-0 p-1">Others</th>'+
                  '<th class="m-0 p-1">Garbage</th>'+
                  '<th class="m-0 p-1">KPLCD</th>'+
                '</tr>'+
              '</thead>'+
            '<tbody class="propertiesmanageproperties">');
        $('.propertiesmanageres').html('');
        let outputdata='';
        let propertiesmanagesideproperty='';
        let propertiesmanagedropdownproperty='';
        propertiesmanagedropdownproperty=propertiesmanagedropdownproperty+(''+
            '</button>'+'<button type="button" class="btn btn-outline-info text-left col-5 p-0 m-0 mb-1 ml-1 pr-1 pl-1 btnplotmgrtenantplot" '+
                'data-id0="Vacated" data-id1="">'+
                '<span class="text-xs">Vacated</span>'+
            '</button>'+
            
            '<button type="button" class="btn btn-outline-info text-left col-5 p-0 m-0 mb-1 ml-1 pr-1 pl-1 btnplotmgrtenantplot" '+
                'data-id0="New" data-id1="Not Assigned">'+
                '<span class="text-xs">New</span>'+
            '</button>');
        let sno=0;
        let total=propertyinfo.length;
        for (var i = 0; i < propertyinfo.length; i++) {
          sno++;
          //fill properties results
          outputdata=outputdata+(''+
            '<tr class="text-xs" style="padding:0px;margin:2px;background-color:#FFFFFF;">'+
                '<td class="m-0 p-1">'+(i+1)+'</td>'+
                '<td class="m-0 p-1">'+propertyinfo[i]['Plotname']+'</td>'+
                '<td class="m-0 p-1">'+propertyinfo[i]['Plotcode']+'</td>'+
                '<td class="m-0 p-1">'+propertyinfo[i]['Waterbill']+'</td>'+
                '<td class="m-0 p-1">'+propertyinfo[i]['Deposit']+'</td>'+
                '<td class="m-0 p-1">'+propertyinfo[i]['Waterdeposit']+'</td>'+
                '<td class="m-0 p-1">'+propertyinfo[i]['Outsourced']+'</td>'+
                '<td class="m-0 p-1">'+propertyinfo[i]['Garbage']+'</td>'+
                '<td class="m-0 p-1">'+propertyinfo[i]['Kplcdeposit']+'</td>'+
            '</tr>');
          //fill properties drop down
          propertiesmanagedropdownproperty=propertiesmanagedropdownproperty+(''+
            '<button type="button" class="btn btn-outline-info text-left col-5 p-0 m-0 mb-1 ml-1 pr-1 pl-1 btnplotmgrtenantplot" '+
                'data-id0="'+propertyinfo[i]['id']+'" data-id1="'+propertyinfo[i]['Plotname']+'" data-id2="'+propertyinfo[i]['Plotcode']+'">'+
                '<span class="text-xs">'+propertyinfo[i]['Plotcode']+'</span>'+
            '</button>');
          //fill properties side bar
          propertiesmanagesideproperty=propertiesmanagesideproperty+(''+
            '<div class="col-12 m-1 p-0" >'+
              '<div class="elevation-1 m-0 p-0">'+
                '<div class="card-header bg-white  btn-tool m-0 p-0">'+
                  '<h4 class="text-xs text-left m-0 p-1">'+
                    '<span class="text-left text-xs">'+
                      '<span class="text-dark text-xs mx-auto m-0 p-1" title="'+propertyinfo[i]['Plotname']+'"> '+
                        '<b> '+(i+1)+' . '+propertyinfo[i]['Plotcode']+'</b>'+
                      '</span>'+
                      '<span class="text-info text-xs float-right m-0 p-0">'+
                        '<button type="button" class="btn btn-outline-success text-xs p-0 m-0 ml-1 pr-1 pl-1 btnplotmgrnewhouse" '+
                          'data-id0="'+propertyinfo[i]['id']+'" data-id1="'+propertyinfo[i]['Plotname']+'" data-id2="'+propertyinfo[i]['Plotcode']+'">'+
                          '<i class="fas fa-plus-circle text-xs"> House</i>'+
                        '</button>'+
                        '<button type="button" class="btn btn-outline-info text-xs p-0 m-0 ml-1 pr-1 pl-1 btnplotmgrhseplot" '+
                          'data-id0="'+propertyinfo[i]['id']+'" data-id1="'+propertyinfo[i]['Plotname']+'" data-id2="'+propertyinfo[i]['Plotcode']+'">'+
                          'Houses'+
                        '</button>'+
                        
                        '<button type="button" class="btn btn-outline-info text-xs p-0 m-0 ml-1 pr-1 pl-1 btnplotmgrtenantplot" '+
                          'data-id0="'+propertyinfo[i]['id']+'" data-id1="'+propertyinfo[i]['Plotname']+'" data-id2="'+propertyinfo[i]['Plotcode']+'">'+
                          'Tenants'+
                        '</button>'+
                        
                        '<button type="button" class="btn btn-outline-primary text-xs p-0 m-0 ml-1 pr-1 pl-1 btnplotmgreditplot" '+
                        ' data-id0="'+propertyinfo[i]['id']+'" data-id1="'+propertyinfo[i]['Plotname']+'" data-id2="'+propertyinfo[i]['Plotcode']+'" '+
                        ' data-id3="'+propertyinfo[i]['Plotarea']+'" data-id4="'+propertyinfo[i]['Plotaddr']+'" data-id5="'+propertyinfo[i]['Plotdesc']+'" '+
                        ' data-id6="'+propertyinfo[i]['Waterbill']+'" data-id7="'+propertyinfo[i]['Deposit']+'" data-id8="'+propertyinfo[i]['Waterdeposit']+'" '+
                        ' data-id9="'+propertyinfo[i]['Outsourced']+'" data-id10="'+propertyinfo[i]['Garbage']+'" data-id11="'+propertyinfo[i]['Kplcdeposit']+'" >'+
                          '<i class="fas fa-edit"></i>'+
                        '</button>'+
                        '<button type="button" class="btn btn-outline-danger text-xs p-0 m-0 ml-1 pr-1 pl-1 btnplotmgrdelplot" '+
                          'data-id0="'+propertyinfo[i]['id']+'" data-id1="'+propertyinfo[i]['Plotname']+'" data-id2="'+propertyinfo[i]['Plotcode']+'">'+
                        ' <i class="fas fa-trash"></i>'+
                      ' </button>'+

                      '</span>'+
                  ' </span>'+
                  '</h4>'+
                '</div>'+
              '</div>'+
            '</div>');
        }
        $('.propertiesmanagesideproperty').html(propertiesmanagesideproperty);
        $('.propertiesmanagedropdownproperty').html(propertiesmanagedropdownproperty);
        
        let footerdata='';
        footerdata+=(''+
            '</tbody>'+
          '</table>');
        $('.propertiesmanageres').html(headerdata);
        $('.propertiesmanageproperties').html(outputdata);
        $('.propertiesmanageres').append(footerdata);
        
      },
      error: function(xhr, status, error){
        var errorMessage = xhr.status + ': ' + xhr.statusText
        if(xhr.status==419){
          window.location.href='';
        }
        if (errorMessage=="0: error") {
            errorMessage="No Connection" 
        }
        $('.propertiesmanageresload').html('');
        $('.propertiesmanageres').html('<div class="text-sm text-danger text-center">'+errorMessage+'<br>Could Not Load Properties Data </div>');
      }
    });
  }
  
</script>

@endpush