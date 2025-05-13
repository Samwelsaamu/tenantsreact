@extends('layouts.adminheader')
@section('title','Frequently Asked Questions | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-6">
    <h1 class="m-0">Frequently Asked Questions</h1>
</div><!-- /.col -->
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
      <li class="breadcrumb-item active">Frequently Asked Questions</li>
    </ol>
</div><!-- /.col -->
@endsection
@section('css')
    <!-- DataTables -->

@endsection
@section('sidebarlinks')
  <li class="nav-item">
    <a href="/dashboard" class="nav-link">
          <i class="fa fa-home nav-icon"></i>
          <p>Dashboard</p>
        </a>
  </li>

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
        <a href="/properties/manage" class="nav-link">
          <i class="fa fa-sitemap nav-icon"></i>
          <p>Manage Properties</p>
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
        <a href="/properties/frequentlyasked" class="nav-link active">
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
      <div class="col-12">
        <div class="card">
          <!-- ./card-header -->
          <div class="card-body" style="overflow-x: auto;">
            <table class="table table-bordered table-hover table-head-fixed text-nowrap">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Question</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                  <td>1 <i class="fas fa-angle-left fa-1x float-right"></i></td>
                  <td>Upload Waterbill Using Excel Workbook.</td>
                  <td>Functioning</td>
                </tr>
                <tr class="expandable-body">
                  <td colspan="3" class="bg-light">
                    <div class="text-center">
                        <p class="text-muted text-center" style="padding: 0px;margin:0px;display: inline;">
                            <div style="text-align: left;">
                              <ul type="1">
                                <li>Access Waterbill Link in the Sidebar Menu</li>
                                <li >Select 'Upload Waterbill' 
                                <a href="/properties/upload/waterbill" >Upload Waterbill</a> 
                                </li>
                                <li>Choose Property and Month</li>
                                <li>Browse and Select the Excel Workbook</li>
                                <li>Format-><span class="text-dark" style="font-size: 11px;">A:House, B:Tenant, C:Previous, D:Current, E:Cost, F:Units, G:Amount/Total</span></li>
                                <li>Click Preview to View Waterbills</li>
                                <li>Select and Click 'Save Selected Waterbill'</li>
                              </ul>
                            </div>
                        </p>
                    </div>
                  </td>
                </tr>

                <tr data-widget="expandable-table" aria-expanded="false">
                  <td>2 <i class="fas fa-angle-left fa-1x float-right"></i></td>
                  <td>Add Waterbill House by House</td>
                  <td>Functioning</td>
                </tr>
                <tr class="expandable-body">
                  <td colspan="3" class="bg-light">
                    <div class="text-center">
                        <p class="text-muted text-center" style="padding: 0px;margin:0px;display: inline;">
                            <div style="text-align: left;">
                            <ul type="1">
                              <li>Access Waterbill Link in the Sidebar Menu</li>
                              <li >Select 'Add Waterbill' 
                              <a href="/properties/add/waterbill" >Add Waterbill</a> 
                              </li>
                              <li>Choose Property and Month and House</li>
                              <li>Fill All Details </li>
                              <li>Check Tenant is Selected</li>
                              <li>Click Save Waterbill or Update</li>
                            </ul>
                            </div>
                        </p>
                    </div>
                  </td>
                </tr>

                <tr data-widget="expandable-table" aria-expanded="false">
                  <td>3 <i class="fas fa-angle-left fa-1x float-right"></i></td>
                  <td>Add Waterbill for Non Tenants</td>
                  <td>Functioning</td>
                </tr>
                <tr class="expandable-body">
                  <td colspan="3" class="bg-light">
                    <div class="text-center">
                        <p class="text-muted text-center" style="padding: 0px;margin:0px;display: inline;">
                            <div style="text-align: left;">
                              <ul type="1">
                                <li>Access Waterbill Link in the Sidebar Menu</li>
                                <li >Select 'Add Waterbill' 
                                <a href="/properties/add/waterbill" >Add Waterbill</a> 
                                </li>
                                <li>Choose Other Waterbill and Month and Person</li>
                                <li>Fill All Details </li>
                                <li>Click Save Waterbill or Update</li>
                              </ul>
                            </div>
                        </p>
                    </div>
                  </td>
                </tr>

                <tr data-widget="expandable-table" aria-expanded="false">
                  <td>4 <i class="fas fa-angle-left fa-1x float-right"></i></td>
                  <td>Send Waterbill for Non Tenants</td>
                  <td>Functioning</td>
                </tr>
                <tr class="expandable-body">
                  <td colspan="3" class="bg-light">
                    <div class="text-center">
                        <p class="text-muted text-center" style="padding: 0px;margin:0px;display: inline;">
                            <div style="text-align: left;">
                              <ul type="1">
                                <li>Access Messages Link in the Sidebar Menu</li>
                                <li >Select 'Send Messages' 
                                <a href="/properties/messages" >Send Messages</a> 
                                </li>
                                <li>Choose Other Message, Mode(Other Water) and Month</li>
                                <li>Click 'Send' or 'Resend'</li>
                              </ul>
                            </div>
                        </p>
                    </div>
                  </td>
                </tr>

                <tr data-widget="expandable-table" aria-expanded="false">
                  <td>5 <i class="fas fa-angle-left fa-1x float-right"></i></td>
                  <td>Send Waterbill Notification for Non Tenants</td>
                  <td>Functioning</td>
                </tr>
                <tr class="expandable-body">
                  <td colspan="3" class="bg-light">
                    <div class="text-center">
                        <p class="text-muted text-center" style="padding: 0px;margin:0px;display: inline;">
                            <div style="text-align: left;">
                              <ul type="1">
                                <li>Access Messages Link in the Sidebar Menu</li>
                                <li >Select 'Send Messages' 
                                <a href="/properties/messages" >Send Messages</a> 
                                </li>
                                <li>Choose Other Message, Mode(Other Notification) and Month</li>
                                <li>Click Green Button 'Add Payment' to Update Payments</li>
                                <li>Click 'Send' Provided the Information is Correct</li>
                              </ul>
                            </div>
                        </p>
                    </div>
                  </td>
                </tr>


                <tr data-widget="expandable-table" aria-expanded="false">
                  <td>6 <i class="fas fa-angle-left fa-1x float-right"></i></td>
                  <td>Send Waterbill for Tenants</td>
                  <td>Functioning</td>
                </tr>
                <tr class="expandable-body">
                  <td colspan="3" class="bg-light">
                    <div class="text-center">
                        <p class="text-muted text-center" style="padding: 0px;margin:0px;display: inline;">
                            <div style="text-align: left;">
                              <ul type="1">
                                <li>Access Messages Link in the Sidebar Menu</li>
                                <li >Select 'Send Messages' 
                                <a href="/properties/messages" >Send Messages</a> 
                                </li>
                                <li>Choose Property, Mode and Month</li>
                                <li>Mode: Single Water or All Water</li>
                                <li>Single Water to send per house</li>
                                <li>All Water to Select Houses and Send</li>
                                <li>Click 'Send' and 'Resend'</li>
                              </ul>
                            </div>
                        </p>
                    </div>
                  </td>
                </tr>

                <tr data-widget="expandable-table" aria-expanded="false">
                  <td>7 <i class="fas fa-angle-left fa-1x float-right"></i></td>
                  <td>Login</td>
                  <td>Functioning</td>
                </tr>
                <tr class="expandable-body">
                  <td colspan="3" class="bg-light">
                    <div class="text-center">
                        <p class="text-muted text-center" style="padding: 0px;margin:0px;display: inline;">
                            <div style="text-align: left;">
                              <ul type="1">
                                <li>Access Login Page from Home Page 'Login'</li>
                                <li>Your are Required to provide User name or Email address and password</li>
                                <li>If Unable to login, please click 'Forgot Your Password'</li>
                                <li>If Still can not access your email address, Contact Agency for more information</li>
                              </ul>
                            </div>
                        </p>
                    </div>
                  </td>
                </tr>

                <tr data-widget="expandable-table" aria-expanded="false">
                  <td>8 <i class="fas fa-angle-left fa-1x float-right"></i></td>
                  <td>Forgot or Reset Password</td>
                  <td>Functioning</td>
                </tr>
                <tr class="expandable-body">
                  <td colspan="3" class="bg-light">
                    <div class="text-center">
                        <p class="text-muted text-center" style="padding: 0px;margin:0px;display: inline;">
                            <div style="text-align: left;">
                              <ul type="1">
                                <li>Access Login Page from Home Page 'Login'</li>
                                <li>Click on 'Forgot Your Password' Link</li>
                                <li>Your are required to provide your registerd email address</li>
                                <li>A password reset link will be sent to your email account</li>
                                <li>Follow Instructions to Reset Password</li>
                              </ul>
                            </div>
                        </p>
                    </div>
                  </td>
                </tr>

                <tr data-widget="expandable-table" aria-expanded="false">
                  <td>9 <i class="fas fa-angle-left fa-1x float-right"></i></td>
                  <td>Change Password</td>
                  <td>Functioning</td>
                </tr>
                <tr class="expandable-body">
                  <td colspan="3" class="bg-light">
                    <div class="text-center">
                        <p class="text-muted text-center" style="padding: 0px;margin:0px;display: inline;">
                            <div style="text-align: left;">
                              <ul type="1">
                                <li>To Change password, your are required to be logged in</li>
                                <li>Click on Accounts and Change password dropdown link (<a href="/profile/change-password" >Change Password link</a> )</li>
                                <li>Provide your new Password and confirm it</li>
                                <li>Click Change password button</li>
                              </ul>
                            </div>
                        </p>
                    </div>
                  </td>
                </tr>

                <tr data-widget="expandable-table" aria-expanded="false">
                  <td>10 <i class="fas fa-angle-left fa-1x float-right"></i></td>
                  <td>Add New User</td>
                  <td>Functioning</td>
                </tr>
                <tr class="expandable-body">
                  <td colspan="3" class="bg-light">
                    <div class="text-center">
                        <p class="text-muted text-center" style="padding: 0px;margin:0px;display: inline;">
                            <div style="text-align: left;">
                              <ul type="1">
                                <li>To Add New User, your are required to be logged in as Admin, Owner or Agent</li>
                                <li>Click on Accounts and New Users dropdown link (<a href="/homeusers/create" >New Users Link</a> )</li>
                                <li>Provide all required information and Click 'Register'</li>
                                <li>A Notification is sent to the Users Email and Link to Login is Provided</li>
                              </ul>
                            </div>
                        </p>
                    </div>
                  </td>
                </tr>

                <tr data-widget="expandable-table" aria-expanded="false">
                  <td>11 <i class="fas fa-angle-left fa-1x float-right"></i></td>
                  <td>Logout Safely</td>
                  <td>Functioning</td>
                </tr>
                <tr class="expandable-body">
                  <td colspan="3" class="bg-light">
                    <div class="text-center">
                        <p class="text-muted text-center" style="padding: 0px;margin:0px;display: inline;">
                            <div style="text-align: left;">
                              <ul type="1">
                                <li>To Logout, your are required to be logged in </li>
                                <li>Click on 'Logout' Next to your Username on sidebar Menu </li>
                                <li>You will be logged Out and a valid password and username is required to login again</li>
                              </ul>
                            </div>
                        </p>
                    </div>
                  </td>
                </tr>

                <tr data-widget="expandable-table" aria-expanded="false">
                  <td>12 <i class="fas fa-angle-left fa-1x float-right"></i></td>
                  <td>Add New Property</td>
                  <td>Functioning</td>
                </tr>
                <tr class="expandable-body">
                  <td colspan="3" class="bg-light">
                    <div class="text-center">
                        <p class="text-muted text-center" style="padding: 0px;margin:0px;display: inline;">
                            <div style="text-align: left;">
                              <ul type="1">
                                <li>Your are required to be logged in</li>
                                <li>Click on plus sign on top menu bar and Click on new property ( <a href="/newproperties" >New Property link)</a> </li>
                                <li>Or Go to Properties link on side bar and select 'property information' then click on 'Add New Property' green Button</li>
                                <li>Fill all required details and save</li>
                              </ul>
                            </div>
                        </p>
                    </div>
                  </td>
                </tr>

                <tr data-widget="expandable-table" aria-expanded="false">
                  <td>13 <i class="fas fa-angle-left fa-1x float-right"></i></td>
                  <td>Add New House</td>
                  <td>Functioning</td>
                </tr>
                <tr class="expandable-body">
                  <td colspan="3" class="bg-light">
                    <div class="text-center">
                        <p class="text-muted text-center" style="padding: 0px;margin:0px;display: inline;">
                            <div style="text-align: left;">
                              <ul type="1">
                                <li>Your are required to be logged in</li>
                                <li>Click on plus sign on top menu bar and Click on new House(Property Code) </li>
                                <li>Or Go to Properties link on side bar and select 'property information' then click on '(Number of Houses) Houses' green Button to View all Houses</li>
                                <li>Then click on 'Add New House' green Button</li>
                                <li>Enter House Name without Prpperty Code</li>
                                <li>Fill all other details and save</li>
                              </ul>
                            </div>
                        </p>
                    </div>
                  </td>
                </tr>
                

              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
                  
    </div>
</div>
@endsection

@push('scripts')

<script type="text/javascript">
  $(function () {
    
  });
</script>
@endpush
