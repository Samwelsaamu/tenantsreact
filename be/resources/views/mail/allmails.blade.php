@extends('layouts.adminheader')
@section('title','All Mails | Wagitonga Agencies Limited')
@section('HeaderTitle')
<div class="col-sm-6">
    <h1 class="m-0">All Emails</h1>
</div><!-- /.col -->
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
      <li class="breadcrumb-item active">All Emails</li>
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
        <a href="/mail/getmail" class="nav-link active">
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
      <div class="col-12">
        <div class="card">
          <!-- ./card-header -->
          <div class="card-body" style="overflow-x: auto;">
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

            <table class="table table-bordered table-hover table-head-fixed" >
              <thead>
                <tr>
                  <th>Sno. </th>
                  <th>Subject</th>
                  <th>From</th>
                  <th>Attachments</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @if($aMessages!="")
                    @forelse($aMessages as $oMessage)
                        <tr data-widget="expandable-table" aria-expanded="false">
                          <td class="text-sm m-0 p-1" >
                            <i class="fas fa-angle-right fa-1x  p-1 float-right bg-light" style="border-radius:10px 10px 10px 10px;opacity:.6;margin-right:1px;"></i>
                            <span>{{$loop->index+1}}</span>.
                          </td>
                          <td>{{$oMessage->subject}}</td>
                          <td>{{$oMessage->from}}</td>
                          <td>{{$oMessage->attachments}}</td>
                          <td>
                            <form action="{{ route('mails.destroy', $oMessage->id)}}" method="post" class="form-horizontal" style="display: inline;" onsubmit="return confirm('Are you sure to delete this mail?');">
                                  @csrf
                                  @method('DELETE')
                                  <button class="btn btn-danger" type="submit" style="padding: 4px;font-size: 12px;display: inline;"><i class="fa fa-trash"></i> Delete</button>
                            </form>
                          </td>
                        </tr>
                        <tr class="expandable-body">
                            <td colspan="3" class="bg-light">
                              <div class="text-center">
                                  <p class="text-info" style="padding: 0px;margin:0px;display: inline;">
                                  <div style="text-align: left;white-space:pre-line;white-space:pre-wrap">
                                      {{$oMessage->message}}
                                  </div>
                                </p>
                              </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No messages found</td>
                        </tr>
                    @endforelse
                  @else
                    <tr>
                        <td colspan="4">{{$msgerror}}</td>
                    </tr>
                  @endif
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
