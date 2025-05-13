@extends('layouts.adminheader')
@section('title','Documents | Wagitonga Agencies Limited')
@section('HeaderTitle')

@endsection
@section('css')
  
  <!-- dropzonejs -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/dropzone/basic.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/dropzone/dropzone.css') }}">
  
@endsection
@section('sidebarlinks')

  <li class="nav-item menu-open mt-1">
    <a href="#" class="nav-link bg-black text-light m-0 p-1">
      <i class="nav-icon fa fa-university"></i>
      <p>
        Properties
        <i class="fas fa-angle-left right"></i>
        
      </p>
    </a>
    <ul class="nav nav-treeview navbar-info pt-1">

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

      <li class="nav-item">
        <a href="/properties/View/Documents" class="nav-link bg-secondary">
          <i class="fa fa-truck nav-icon"></i>
          <p>View Documents</p>
        </a>
      </li>

    </ul>
  </li>

  <li class="nav-item menu-open mt-1">
    <a href="#" class="nav-link bg-black text-light m-0 p-1">
      <i class="nav-icon fa fa-tint"></i>
      <p>
        Waterbill
        <i class="fas fa-angle-left right"></i>
        
      </p>
    </a>
    <ul class="nav nav-treeview navbar-info">

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

  <li class="nav-item menu-open mt-1">
    <a href="#" class="nav-link bg-black text-light m-0 p-1">
      <i class="nav-icon fa fa-comments"></i>
      <p>
        Messages
        <i class="fas fa-angle-left right"></i>
        
      </p>
    </a>
    <ul class="nav nav-treeview navbar-info">
      
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


  <li class="nav-item menu-open mt-1">
    <a href="#" class="nav-link bg-black text-light m-0 p-1">
      <i class="nav-icon fa fa-envelope"></i>
      <p>
        Mail
        <i class="fas fa-angle-left right"></i>
        
      </p>
    </a>
    <ul class="nav nav-treeview navbar-info">
      
    <li class="nav-item">
        <a href="/mail/getmail" class="nav-link">
          <i class="fa fa-inbox nav-icon"></i>
          <p>Mails</p>
        </a>
      </li>
    </ul>
  </li>
  <li class="nav-item menu-open mt-1">
    <a href="#" class="nav-link bg-black text-light m-0 p-1">
      <i class="nav-icon fa fa-cogs"></i>
      <p>
        Others
        <i class="fas fa-angle-left right"></i>
        
      </p>
    </a>
    <ul class="nav nav-treeview navbar-info">
      
      <li class="nav-item">
        <a href="/properties/frequentlyasked" class="nav-link">
          <i class="nav-icon fas fa-th"></i>
          <p>
            FAQs
          </p>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/profile/change-password">
          <i class="fas fa-lock nav-icon"></i>
          <p>Change Password</p>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/homeusers/create">
          <i class="fas fa-plus nav-icon nav-icon"></i> 
          <p>New User</p>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/users">
          <i class="fas fa-users nav-icon nav-icon"></i> 
          <p>Users</p>  
      </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/agencyinfo">
          <i class="fas fa-tree nav-icon"></i> 
          <p>Agency</p>
        </a>
      </li>

    </ul>
  </li>

@endsection
@section('content')
<div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-2">
                        <div class="card-body m-1 p-1">
                            <div class="row m-0 p-0">
                                <div class="col-12  m-1 p-0">
                                    <div>
                                        <form role="form" class="form-horizontal" method="POST" action="/properties/documents/upload" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row m-0 p-0">
                                                <div class="col-12 m-0 p-0">
                                                    <form></form>
                                                    <form method="post" action="/properties/documents/upload" class="dropzone" id="dropzoneForm" style="border: 4px dotted blue;">
                                                        @csrf
                                                        <div class="form-group">
                                                            <h6 class="text-center text-primary text-lg">Drag Document Here</h6>
                                                        </div>
                                                    </form> 
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-12  m-1 p-0">
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card mb-2">
                        <div class="card-body m-1 p-1">
                            <div class="row m-0 p-0">
                                <div class="col-sm-12" style="overflow-x:auto;max-height:420px;overflow-y:auto;">
                                    <table id="example1" class="table table-bordered table-striped text-xs">
                                        <thead>
                                            <tr style="color:white;color:#77B5ED;">
                                                <th class="m-0 p-1">Sno</th>
                                                <th class="m-0 p-1">Document</th>
                                                <th class="m-0 p-1">Uploaded</th>
                                                <th class="m-0 p-1">Updated</th>
                                                <th class="m-0 p-1">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody style="overflow-x:auto;" id="uploadeddocuments">
                                        
                                        </tbody>
                                        
                                        
                                    </table>
                                </div>
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
   <!-- dropzone -->
<script src="{{ asset('assets/plugins/dropzone/dropzone.js') }}"></script>
 
<script type="text/javascript">

$(function () {
    load_reports();
});

  Dropzone.options.dropzoneForm={
  autoProcessQueue:true,
  maxFilesize: 50,
  addRemoveLinks: true,
  timeout: 500000,
//   acceptedFiles: ".xls,.xlsx",
  removedfile:function(file){
      var fileRef;
      return (fileRef=file.previewElement) !=null ? fileRef.parentNode.removeChild(file.previewElement) : void 0;
    
  },
  success: function(file, response)
  {
    if(response['error']){
        $(document).Toasts('create', {
            title: 'Failed to Upload',
            class: 'bg-warning',
            position: 'bottomLeft',
            body: response['error']
        })
    }
    else{
        $(document).Toasts('create', {
            title: 'Document Uploaded',
            class: 'bg-success',
            position: 'bottomLeft',
            body: response['success']
        })
        //first get all data for reports
        var reports=response['reports'];

        $('#uploadeddocuments').html('');
        //loop through reports
        for (var i = 0; i < reports.length; i++) {
            documentsreport='<tr class="m-0 p-0">'+
                            '<td class="m-0 p-2">'+(i+1)+'</td>'+
                            '<td class="m-0 p-2">'+reports[i]['Filename']+'</td>'+
                            '<td class="m-0 p-2">'+reports[i]['created_at']+'</td>'+
                            '<td class="m-0 p-2">'+reports[i]['updated_at']+'</td>'+
                            '<td class="m-0 p-2">'+
                                    '<a target="_blank" href="/storage/documents/'+reports[i]['Filename']+'" class="btn btn-outline-success btn-xs text-xs p-1 m-1"><i class="fa fa-download"></i> </a>'+
                                    '<a href="/properties/documents/delete/'+reports[i]['id']+'" class="btn btn-outline-danger btn-xs text-xs p-1 m-1"><i class="fa fa-trash"></i> </a>'+
                                '</td>'+
                        '</tr>';
            $('#uploadeddocuments').append(documentsreport);
        }
        
    }
    return true;
  },
  error: function(file, response)
  {
    $(document).Toasts('create', {
      title: 'Failed to Upload',
      class: 'bg-warning',
      position: 'bottomLeft',
      body: response['error']
    })
    return false;
  }
  
};


function load_reports(){
    $.ajax({
    url:"/properties/load/documents",
      method:"GET",
      success:function(data){
            //first get all data for reports
            var reports=data['reports'];

            $('#uploadeddocuments').html('');
            //loop through reports
            for (var i = 0; i < reports.length; i++) {
                documentsreport='<tr class="m-0 p-0">'+
                                '<td class="m-0 p-2">'+(i+1)+'</td>'+
                                '<td class="m-0 p-2">'+reports[i]['Filename']+'</td>'+
                                '<td class="m-0 p-2">'+reports[i]['created_at']+'</td>'+
                                '<td class="m-0 p-2">'+reports[i]['updated_at']+'</td>'+
                                '<td class="m-0 p-2">'+
                                    '<a target="_blank" href="/storage/documents/'+reports[i]['Filename']+'" class="btn btn-outline-success btn-xs text-xs p-1 m-1"><i class="fa fa-download"></i> </a>'+
                                    '<a href="/properties/documents/delete/'+reports[i]['id']+'" class="btn btn-outline-danger btn-xs text-xs p-1 m-1"><i class="fa fa-trash"></i> </a>'+
                                '</td>'+
                            '</tr>';
                $('#uploadeddocuments').append(documentsreport);
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
            title: 'Loading Documents',
            class: 'bg-warning',
            position: 'bottomLeft',
            body: errorMessage
        })
    }
  });
}
  
</script>

@endpush
