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
                  <th>UID</th>
                  <th>Subject</th>
                  <th>From</th>
                  <th>Attachments</th>
                </tr>
              </thead>
              <tbody>
                @if($aMessages!="")
                    @forelse($aMessages as $oMessage)
                        <tr data-widget="expandable-table" aria-expanded="false">
                          <td>{{$oMessage->getUid()}} <i class="fas fa-angle-left fa-1x float-right"></i></td>
                          <td>{{$oMessage->getSubject()}}</td>
                          <td>{{$oMessage->getFrom()[0]->mail}}</td>
                          <td>{{$oMessage->getAttachments()->count() > 0 ? 'yes' : 'no'}}</td>
                        </tr>
                        <tr class="expandable-body">
                            <td colspan="4" class="bg-info">
                              <div class="text-center">
                                <p class="text-muted text-center" style="padding: 0px;margin:0px;display: inline;">
                                <div style="text-align: left;">
                                  @if($oMessage->hasHTMLBody())
                                    {{$oMessage->getHTMLBody()}}
                                  @elseif($oMessage->getTextBody())
                                    {{$oMessage->getTextBody()}}
                                  @else

                                  @endif
                                  
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
