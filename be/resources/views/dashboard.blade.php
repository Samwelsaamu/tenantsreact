@extends('layouts.adminheader')
@section('title','Dashboard | Wagitonga Agencies Limited')
@section('HeaderTitle')
<!-- <div class="col-sm-6 m-0">
    <h6 class="m-2 p-0">Dashboard</h6>
</div>
<div class="col-sm-6 m-0">
  <ol class="breadcrumb float-sm-right m-0 p-1">
    <li class="breadcrumb-item"><a href="/properties/messages">Messages</a></li>
    <li class="breadcrumb-item"><a href="/properties/upload/waterbill">Upload Waterbill</a></li>
    <li class="breadcrumb-item"><a href="/properties/update/bills">Update Payments</a></li>
    <li class="breadcrumb-item active">Home</li>
  </ol>
</div> -->
@endsection
@section('css')
     <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('public/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  
@endsection
@section('sidebarlinks')
  <!-- <li class="nav-item">
    <a href="/dashboard" class="nav-link active">
          <i class="fa fa-home nav-icon"></i>
          <p>Dashboard</p>
        </a>
  </li> -->

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
        <a href="/dashboard" class="nav-link bg-secondary">
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
        <a href="/properties/View/Documents" class="nav-link">
          <i class="fa fa-file nav-icon"></i>
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
<div class="" style="">
    <div class="row justify-content-center" style="">
        <div class="col-md-12" style="">
            <div class="m-0 p-0" style="border: none;">

                <div class="card-body row p-1 m-0" style="padding-top: 10px;">
                <!-- col-lg-6 col-md-6 col-sm-6  col-12 -->
                  <!-- <div class=" p-1 m-0" style="flex:2;">
                    <div class="card " style="padding: 2px;margin: 5px;">
                      <div class="card-header bg-light p-1">  
                        <span class="mx-auto text-center text-sm">Waterbill 
                          <span class="text-sm text-danger monthy-title"></span>
                        </span>
                        
                            <div class="card-tools m-1">
                              <button type="button" class="btn btn-tool bg-info p-0 m-0 ml-1 pr-1 pl-1 text-right" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                              </button>
                            </div>
                            <span class="text-sm float-right text-info" id="monthy-titles"></span>
                      </div>
                      <div class="card-body p-1 m-0">
                        <div class=" p-2">
                          <div class="row  " id="monthlywaterbillss">
                          
                        </div>
                        </div>
                      </div>
                    </div> 
                  </div> -->

                  <div class="p-1 m-0" style="flex:1;">
                    <div class="elavation-4 bg-white p-1" style="border:none;border-radius: 10px;">
                      <div class="card-header bg-info p-1" style="border-radius: 10px;">  
                        <span class="mx-auto text-center text-sm">Waterbill 
                          <span class="download-all" id="download-all">
                            <a target="_blank" href="/properties/download/Reports/Waterbill/All/{{date('Y n')}}"
                             class="btn btn-primary p-0 pl-1 pr-1 mt-0">
                             <i class="fa fa-download"></i> {{date('M, Y')}} </a>
                          </span>
                          
                          <span class="text-sm text-danger monthy-title"></span>
                        </span>
                        
                            <div class="card-tools m-1">
                              <ul class="pagination pagination-sm">
                                <li class="page-item monthlywaterprev"><a href="#" class="page-link">&laquo;</a></li>
                                  {{ App\Http\Controllers\TenantController::getMonthsWaterLast4() }}
                                
                              </ul>
                              
                            </div>
                            <span class="text-sm float-right text-white" id="monthy-title"></span>
                      </div>
                      <div class="card-body p-1 m-0" >
                        <div class=" p-1">
                          <div class=" " id="monthlywaterbills">
                            <p class="text-danger text-center">Please Wait ... <img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading..."></p>
                          </div>
                        </div>
                      </div>
                    </div> 
                  </div>


                  <div class="p-1 m-0" style="flex:1;">
                    <div class="elavation-4 bg-white p-1" style="border:none;border-radius: 10px;">
                      <div class="card-header bg-success p-1" style="border-radius: 10px;">  
                        <span class="mx-auto text-center text-sm">Payments
                        <span class="payments-download-all" id="payments-download-all">
                            <a target="_blank" href="/properties/download/Reports/Payments/All/{{date('Y n')}}"
                             class="btn btn-success p-0 pl-1 pr-1 mt-0">
                             <i class="fa fa-download"></i> {{date('M, Y')}} </a>
                          </span>
                        <span class="text-sm text-danger payments-monthy-title"></span>
                        </span>
                        
                            <div class="card-tools m-1">
                              <ul class="pagination pagination-sm">
                                <li class="page-item monthlypaymentprev"><a href="#" class="page-link">&laquo;</a></li>
                                  {{ App\Http\Controllers\TenantController::getMonthsPaymentsLast4() }}
                                <!-- <li>
                                  <button type="button" class="btn btn-tool bg-success p-0 m-0 ml-1 pr-1 pl-1 text-right" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                  </button>
                                </li> -->
                              </ul>
                            </div>
                            <span class="text-sm float-right text-white" id="payments-monthy-title"></span>
                      </div>
                      <div class="card-body p-1 m-0">
                        <div class=" p-1">
                          <div class=" " id="monthlypaymentsbills">
                            <p class="text-danger text-center">Please Wait ... <img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading..."></p>
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
@endsection

@push('scripts')
<!-- DataTables  & Plugins -->
  <script src="{{ asset('public/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('public/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('public/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('public/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
 
 
<script type="text/javascript">
  $(function () {
    // $("#example1").DataTable({
    //   "responsive": true, "lengthChange": true, "autoWidth": false,
    //   "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    // }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    // $('#example2').DataTable({
    //   "paging": true,
    //   "lengthChange": false,
    //   "searching": false,
    //   "ordering": true,
    //   "info": true,
    //   "autoWidth": false,
    //   "responsive": true,
    // });

    getcurrentMonthDash(0,0,0,0);
    getcurrentMonthPaymentDash(0,0,0,0);

    function getcurrentMonthDash(month,monthly,yearly,currentdate){
      $('.monthy-title').html('<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
      if(monthly==0){
        $('#monthy-title').html('<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
      }
      else{
        $('#monthy-title').html('<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
      }
      $.ajax({
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"').attr('content')
          },
        type:'POST',
        url:'/properties/dash/water',
        data:{month:month},
        success: function(data)
        {
          $('.monthy-title').html('');
            $('#monthy-title').html('Waterbill ('+data['curmonth']+')');
            $('#download-all').html('<a target="_blank" href="/properties/download/Reports/Waterbill/All/'+data['month']+'" class="btn btn-primary p-0 pl-1 pr-1 mt-0"><i class="fa fa-download"></i> '+data['curmonth']+' </a>');
            
          let headerdata='';
          // <p class="text-success text-center text-md mx-auto m-0 p-0">Waterbill for '+data['curmonth']+'</p>
          headerdata=(''+
            '<table id="example1" class="table table-bordered" style="padding:2px;margin:2px;">'+
              '<thead>'+
                '<tr class="text-info text-sm" style="color: #77B5ED;">'+
                  '<th class="m-0 p-1">#</th>'+
                  '<th class="m-0 p-1">Code</th>'+
                  '<th class="m-0 p-1">Saved</th>'+
                  // '<th class="m-0 p-1">Sent</th>'+
                  '<th class="m-0 p-1">Waterbill</th>'+
                  // '<th class="m-0 p-1">Units</th>'+
                  '<th class="m-0 p-1" colspan="3">Sent</th>'+
                  '<th class="m-0 p-1 text-center">Actions</th>'+
                '</tr>'+
              '</thead>'+

              
            '<tbody class="propertiesmanageproperties">');
          let outputwaterbill='';

          var monthlybill=data['output'];
          $('#monthlywaterbills').html('');
          for (var i = 0; i < monthlybill.length; i++) {
            let outputdata='';
            let tr='';
            if((monthlybill[i]['sno'])%2==0){
              tr='<tr class="text-xs bg-light" style="padding:0px;margin:2px;background-color:#FFFFFF;">';
            }
            else{
              tr='<tr class="text-xs bg-white" style="padding:0px;margin:2px;background-color:#FFFFFF;">';
            }
            outputdata=(''+tr+
                '<td class="m-0 p-1">'+monthlybill[i]['sno']+'</td>'+
                '<td class="m-0 p-1">'+monthlybill[i]['plotcode']+'</td>'+
                '<td class="m-0 p-1">'+monthlybill[i]['totalbillshse']+'/'+monthlybill[i]['totalhouseshse']+'</td>'+
                // '<td class="m-0 p-1">'+monthlybill[i]['totalbillsmsghse']+'/'+monthlybill[i]['totalhouseshse']+'</td>'+
                '<td class="m-0 p-1">'+new Number(monthlybill[i]['totals']).toFixed(2)+'</td>'+
                // '<td class="m-0 p-1">'+monthlybill[i]['totalunits']+'</td>'+
                '<td class="m-0 p-1 bg-light" style="opacity:.8;" title="Message sent Once">'+monthlybill[i]['totalbillsmsgsentoncehse']+'</td>'+
                '<td class="m-0 p-1 bg-light" style="opacity:.5;" title="Message sent Twice">'+monthlybill[i]['totalbillsmsgsenttwicehse']+'</td>'+
                '<td class="m-0 p-1 bg-light" style="opacity:.2;" title="Message sent Three Times">'+monthlybill[i]['totalbillsmsgsentthricehse']+'</td>'+
                '<td class="m-0 p-0">'+
                  '<a title="Add Waterbill House by House" href="/properties/add/waterbill/'+monthlybill[i]['id']+'/'+monthlybill[i]['month']+'" class="btn btn-outline-primary btn-xs text-xs p-0 m-1">'+
                    '<i class="fa fa-plus-circle m-1"></i>'+
                  '</a>'+
                  '<a title="Add Waterbill By Uploading and Excel file" href="/properties/update/waterbill/'+monthlybill[i]['id']+'/'+monthlybill[i]['month']+'" class="btn btn-outline-primary btn-xs text-xs p-0 m-1">'+
                    '<i class="fa fa-upload m-1"></i>'+
                  '</a>'+
                  '<a title="Send Waterbill Message to Tenant" href="/properties/send/messages/'+monthlybill[i]['id']+'/All Water/'+monthlybill[i]['month']+'" class="btn btn-outline-primary btn-xs text-xs p-0 m-1">'+
                    '<i class="fa fa-envelope m-1"></i>'+
                  '</a>'+

                  '<a title="Download Waterbill for this Month and Property" href="/properties/download/Reports/Waterbill/'+monthlybill[i]['id']+'/'+monthlybill[i]['month']+'" class="btn btn-outline-warning text-info btn-xs text-xs p-0 m-1">'+
                    '<i class="fa fa-download m-1"></i>'+
                  '</a>'+
                  '<a title="Download Waterbill for {{date("Y")}} and this Property" href="/properties/download/Reports/Waterbill/'+monthlybill[i]['id']+'/Now" class="btn btn-outline-warning text-dark btn-xs text-xs p-0 m-1">'+
                    '<i class="fa fa-download m-1"> {{date("y")}}</i>'+
                  '</a>'+
                  '<a title="Download Waterbill for {{date("Y")-1}} and this Property" href="/properties/download/Reports/Waterbill/'+monthlybill[i]['id']+'/Previous" class="btn btn-outline-secondary btn-xs text-xs p-0 m-1">'+
                    '<i class="fa fa-download m-1"> {{date("y")-1}}</i>'+
                  '</a>'+
                '</td>'+
            '</tr>');

            outputwaterbill=outputwaterbill+outputdata;
            // $('#monthlywaterbills').append(outputdata);
          }

          let footerdata='';
          footerdata+=(''+
              '</tbody>'+
            '</table>');
          $('#monthlywaterbills').html(headerdata);
          $('.propertiesmanageproperties').html(outputwaterbill);
          $('#monthlywaterbills').append(footerdata);
          
          $("#example1").DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false,"ordering":false,"searching":false
          });
          
          // $('#monthlywaterbills').html(data);
        },
        error: function(xhr, status, error){
          var errorMessage = xhr.status + ': ' + xhr.statusText
          if (errorMessage=="0: error") {
              errorMessage="No Connection" 
          }
          $('.monthy-title').html('');
          $('#monthy-title').html(errorMessage+"<br>Could Not Load Data for "+monthly+', '+yearly);
          $('#monthlywaterbills').html(errorMessage+"<br>Could Not Load Data for "+monthly+', '+yearly);
        }
      });
    }

    function getcurrentMonthPaymentDash(month,monthly,yearly,currentdate){
      $('.payments-monthy-title').html('<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
      if(monthly==0){
        $('#payments-monthy-title').html('<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
      }
      else{
        $('#payments-monthy-title').html('<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
      }
      $.ajax({
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"').attr('content')
          },
        type:'POST',
        url:'/properties/dash/payments',
        data:{month:month},
        success: function(data)
        {
          $('.payments-monthy-title').html('');
          // if(monthly==0){
            $('#payments-monthy-title').html('Payments ('+data['curmonth']+')');
            $('#payments-download-all').html('<a target="_blank" href="/properties/download/Reports/Payments/All/'+data['month']+'" class="btn btn-primary p-0 pl-1 pr-1 mt-0"><i class="fa fa-download"></i> '+data['curmonth']+' </a>');
            
            
          // }
          // else{
          //   $('#payments-monthy-title').html("Payment Stats For "+monthly+', '+yearly);
          // }
          let headerdata='';
          // <p class="text-success text-center text-md mx-auto m-0 p-0 payments-monthy-title">Payments for '+data['curmonth']+'</p>
          headerdata=(''+
            '<table id="example2" class="table table-bordered" style="padding:2px;margin:2px;">'+
              '<thead>'+
                '<tr class="text-success text-sm" style="color: #77B5ED;">'+
                  '<th class="m-0 p-1">#</th>'+
                  '<th class="m-0 p-1">Code</th>'+
                  '<th class="m-0 p-1">Tenants</th>'+
                  '<th class="m-0 p-1">Billed</th>'+
                  '<th class="m-0 p-1">Paid</th>'+
                  // '<th class="m-0 p-1">Excess</th>'+
                  // '<th class="m-0 p-1">Arrears</th>'+
                  // '<th class="m-0 p-1">Rent</th>'+
                  '<th class="m-0 p-1" colspan="3">Sent</th>'+
                  '<th class="m-0 p-1 text-center">Actions</th>'+
                '</tr>'+
              '</thead>'+
            '<tbody class="paymentsmanageproperties">');
          let outputwaterbill='';

          var monthlypaymentsbills=data['output'];
          $('#monthlypaymentsbills').html('');
          for (var i = 0; i < monthlypaymentsbills.length; i++) {
            let outputdata='';
            let tr='';
            if((monthlypaymentsbills[i]['sno'])%2==0){
              tr='<tr class="text-xs bg-light" style="padding:0px;margin:2px;background-color:#FFFFFF;">';
            }
            else{
              tr='<tr class="text-xs bg-white" style="padding:0px;margin:2px;background-color:#FFFFFF;">';
            }
            outputdata=(''+tr+
                '<td class="m-0 p-1">'+monthlypaymentsbills[i]['sno']+'</td>'+
                '<td class="m-0 p-1">'+monthlypaymentsbills[i]['Plotcode']+'</td>'+
                '<td class="m-0 p-1">'+monthlypaymentsbills[i]['TotalPayment']+'/'+monthlypaymentsbills[i]['TotalOccupied']+'</td>'+
                '<td class="m-0 p-1">'+new Number(monthlypaymentsbills[i]['TotalUsed']).toFixed(2)+'</td>'+
                '<td class="m-0 p-1">'+new Number(monthlypaymentsbills[i]['TotalPaid']).toFixed(2)+'</td>'+
                // '<td class="m-0 p-1">'+new Number(monthlypaymentsbills[i]['Excess']).toFixed(2)+'</td>'+
                // '<td class="m-0 p-1">'+monthlypaymentsbills[i]['sno']+'</td>'+
                // '<td class="m-0 p-1">'+monthlypaymentsbills[i]['sno']+'</td>'+
                // '<td class="m-0 p-1">'+monthlypaymentsbills[i]['sno']+'</td>'+
                '<td class="m-0 p-1 bg-light" style="opacity:.8;" title="Message sent Once">0</td>'+
                '<td class="m-0 p-1 bg-light" style="opacity:.5;" title="Message sent Twice">0</td>'+
                '<td class="m-0 p-1 bg-light" style="opacity:.2;" title="Message sent Three Times">0</td>'+
                '<td class="m-0 p-0">'+
                  
                  '<a title="Update Payments records by Uploading Excell File" href="/properties/update/bills/'+monthlypaymentsbills[i]['id']+'/'+monthlypaymentsbills[i]['Month']+'" class="btn btn-outline-success btn-xs text-xs p-0 m-1">'+
                    '<i class="fa fa-briefcase m-1"></i>'+
                  '</a>'+
                  '<a title="Send Payment Message to Tenant" href="/properties/send/messages/'+monthlypaymentsbills[i]['id']+'/Summary Paid/'+monthlypaymentsbills[i]['Month']+'" class="btn btn-outline-success btn-xs text-xs p-0 m-1">'+
                    '<i class="fa fa-envelope m-1"></i>'+
                  '</a>'+
                  '<a title="Download Payments for this Month and Property" href="/properties/download/Reports/Payments/'+monthlypaymentsbills[i]['id']+'/'+monthlypaymentsbills[i]['Month']+'" class="btn btn-outline-warning text-success btn-xs text-xs p-0 m-1">'+
                    '<i class="fa fa-download m-1"></i>'+
                  '</a>'+
                  '<a title="Download Payments for {{date("Y")}} and this Property" href="/properties/download/Reports/Payments/'+monthlypaymentsbills[i]['id']+'/Now" class="btn btn-outline-warning text-dark btn-xs text-xs p-0 m-1">'+
                    '<i class="fa fa-download m-1"> {{date("y")}}</i>'+
                  '</a>'+
                  '<a title="Download Payments for {{date("Y")-1}} and this Property" href="/properties/download/Reports/Payments/'+monthlypaymentsbills[i]['id']+'/Previous" class="btn btn-outline-secondary btn-xs text-xs p-0 m-1">'+
                    '<i class="fa fa-download m-1"> {{date("y")-1}}</i>'+
                  '</a>'+
                '</td>'+
            '</tr>');

              outputwaterbill=outputwaterbill+outputdata;
              // $('#monthlypaymentsbills').append(outputdata);

          }

          let footerdata='';
          footerdata+=(''+
              '</tbody>'+
            '</table>');
          $('#monthlypaymentsbills').html(headerdata);
          $('.paymentsmanageproperties').html(outputwaterbill);
          $('#monthlypaymentsbills').append(footerdata);
          
          $("#example2").DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false,"ordering":false,"searching":false
          });

        },
        error: function(xhr, status, error){
          var errorMessage = xhr.status + ': ' + xhr.statusText
          if (errorMessage=="0: error") {
              errorMessage="No Connection" 
          }
          $('.payments-monthy-title').html('');
          $('#payments-monthy-title').html(errorMessage+"<br>Could Not Load Data for "+monthly+', '+yearly);
          $('#monthlypaymentsbills').html(errorMessage+"<br>Could Not Load Data for "+monthly+', '+yearly);
        }
      });
    }

    let links=document.querySelectorAll('.monthlywater');
    for (let i=0; i<links.length; i++){
      links[i].onclick=function(e){
        e.preventDefault();
        let j=0;
        while(j<links.length){
          links[j++].className='monthlywater';
        }
        links[i].className='monthlywater active btn btn-xs btn-info m-0 p-0';
      }
    }

    $(document).on('click', '.monthlywater', function(e){
        e.preventDefault();
        var month = $(this).data("id0");
        var monthly = $(this).data("id1");
        var yearly = $(this).data("id2");
        var currentdate = $(this).data("id3");
        getcurrentMonthDash(month,monthly,yearly,currentdate);
    }); 

    //on change month for payments
    let linkspayments=document.querySelectorAll('.monthlypayments');
    for (let i=0; i<linkspayments.length; i++){
      linkspayments[i].onclick=function(e){
        e.preventDefault();
        let j=0;
        while(j<linkspayments.length){
          linkspayments[j++].className='monthlypayments';
        }
        linkspayments[i].className='monthlypayments active btn btn-xs btn-success m-0 p-0';
      }
    }

    $(document).on('click', '.monthlypayments', function(e){
        e.preventDefault();
        var month = $(this).data("id0");
        var monthly = $(this).data("id1");
        var yearly = $(this).data("id2");
        var currentdate = $(this).data("id3");
        getcurrentMonthPaymentDash(month,monthly,yearly,currentdate);
    }); 


    $(document).on('click', '.monthlywaterprev', function(e){
        e.preventDefault();
          $('#modalpage').modal('show');
          $('#modalpage-body').html("Loading Previous Months. Please Wait... "+'<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
          $.ajax({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"').attr('content')
              },
            type:'POST',
            url:'/properties/dash/water/prev',
            data:{month:'Prev'},
            success: function(data)
            {

              $('#modalpage-title').text("Previous Months for Waterbill Stats");
              $('#modalpage-body').html(data);
            },
            error: function(xhr, status, error){
              var errorMessage = xhr.status + ': ' + xhr.statusText
              if (errorMessage=="0: error") {
                  errorMessage="No Connection" 
              }
              $('#modalpage-title').text("Previous Months for Waterbill Stats Error");
              $('#modalpage-body').html(errorMessage+"<br>Could Not Load Data for Previous Months for Waterbill Stats");
            }
          });
    }); 

    $(document).on('click', '.monthlypaymentprev', function(e){
        e.preventDefault();
          $('#modalpage').modal('show');
          $('#modalpage-body').html("Loading Previous Months. Please Wait... "+'<img src="{{ asset('public/assets/img/spinner.gif') }}" class="img-circle" alt="loading...">');
          $.ajax({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"').attr('content')
              },
            type:'POST',
            url:'/properties/dash/payment/prev',
            data:{month:'Prev'},
            success: function(data)
            {

              $('#modalpage-title').text("Previous Months for Payment Stats");
              $('#modalpage-body').html(data);
            },
            error: function(xhr, status, error){
              var errorMessage = xhr.status + ': ' + xhr.statusText
              if (errorMessage=="0: error") {
                  errorMessage="No Connection" 
              }
              $('#modalpage-title').text("Previous Months for Payment Stats Error");
              $('#modalpage-body').html(errorMessage+"<br>Could Not Load Data for Previous Months for Payment Stats");
            }
          });
    }); 
    

  });
  
</script>

@endpush
