<div class="modal fade" id="payment{{ $waters['id'] }}">
  <div class="modal-dialog">
    <div class="modal-content bg-light">
      <div class="modal-header m-0 p-2">
        <h6 class="modal-title">Payment Summary for {{ App\Http\Controllers\TenantController::TenantNames($waters['Tenant']) }}, {{ App\Http\Controllers\TenantController::getMonthWaterDate($watermonth) }} </h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body bg-light m-0 p-0">
        
          <div class="viewhousepaymentspanelres">
            <form role="form" class="form-horizontal submitupdatebillspayments" method="POST" name="submitupdatebillspayments{{ $waters['id'] }}" id="submitupdatebillspayments{{ $waters['id'] }}">
              @csrf
              <div class="row m-0 p-0">
                <input type="hidden" name="paymentpid" id="paymentpid" value="{{$waters['pid']}}">
                <input type="hidden" name="paymenttid" id="paymenttid" value="{{$waters['Tenant']}}">
                <input type="hidden" name="paymenthid" id="paymenthid" value="{{$waters['hid']}}">
                <input type="hidden" name="paymentmonth" id="paymentmonth" value="{{$watermonth}}">

                <div class="col-12 m-0 p-0">
                  <div class="card card-primary card-outline m-0 p-0">
                    <div class="card-body m-0 mt-2 p-0">
                      <h6 class="text-center text-bold">House <span id="paymenthousename">{{$waters['Housename']}} Info </span> </h6>
                      <p class="text-center text-danger text-xs">Excess, Arrears, Billed and Paid Values should be updated</p>
                      

                      <div class="bg-light text-black text-xs m-1 p-1 viewhousepaymentspanelothers">
                        <div class="row m-0 mb-1 bg-white p-0">
                          <div class="col-4 m-0 p-1">
                            <span class="m-0 p-1">Rent: <i class="text-xs text-info">{{$waters['Rent']}}</i></span>
                          </div>
                          <div class="col-4 m-0 p-1">
                            <span class="m-0 p-1">Garbage: <i class="text-xs text-info">{{$waters['Garbage']}}</i></span>
                          </div>
                          <div class="col-4 m-0 p-1">
                            <span class="m-0 p-1">Waterbill: <i class="text-xs text-info">{{$waters['Waterbill']}}</i></span>
                          </div>
                        </div>
                        <div class="row m-0 mb-1 bg-white p-0">
                          <div class="col-4 m-0 p-1">
                          <span class="m-0 p-1">Excess: <i class="text-xs text-info">{{$waters['Excess']}}</i></span>
                          </div>
                          <div class="col-4 m-0 p-1">
                          <span class="m-0 p-1">Arrears: <i class="text-xs text-info">{{$waters['Arrears']}}</i></span>
                          </div>
                          <div class="col-4 m-0 p-1">
                          <span class="m-0 p-1">Others: <i class="text-xs text-info">{{$waters['Others']}}</i></span>
                          </div>
                        </div>
                        <div class="row m-0 mb-1 bg-white p-0">
                          <div class="col-4 m-0 p-1">
                          <span class="m-0 p-1">Billed: <i class="text-xs text-info">{{$waters['TotalUsed']}}</i></span>
                          </div>
                          <div class="col-4 m-0 p-1">
                          <span class="m-0 p-1">Paid: <i class="text-xs text-info">{{$waters['TotalPaid']}}</i></span>
                          </div>
                          <div class="col-4 m-0 p-1">
                          <span class="m-0 p-1">Bal: <i class="text-xs text-info">{{$waters['Balance']}}</i></span>
                          </div>
                        </div>
                        <div class="row m-0 mb-1 bg-white p-0">
                          <div class="col-12 m-0 p-0">
                          <span class="m-0 p-1">Payments: <i class="text-xs text-info">{{$waters['TotalPaid']}}</i></span>
                          </div>
                        </div>
                      </div>

                      <div class="form-group row m-0 p-1">
                        <label class="col-md-3 m-0 p-0 col-form-label">{{ __('Update') }} <sup class="text-danger text-xs">*</sup></label>
                        <div class="col-md-9 m-0 p-0">
                          <label class="text-xs" style="cursor:pointer;">
                            <input type="radio" name="UpdateType" value="Excess" required> Excess 
                          </label>
                          <label class="text-xs" style="cursor:pointer;">
                            <input type="radio" name="UpdateType" value="Arrears" required> Arrears
                          </label>
                          <label class="text-xs" style="cursor:pointer;">
                            <input type="radio" name="UpdateType" value="Penalty" required> Penalty
                          </label>
                          <label class="text-xs" style="cursor:pointer;">
                            <input type="radio" name="UpdateType" value="Paid" required> Paid
                          </label>
                        </div>
                      </div>

                      <div class="form-group row m-0 p-1">
                        <label for="paymentpaid" class="col-md-3 m-0 p-0 col-form-label">{{ __('Amount') }} <sup class="text-danger text-xs">*</sup></label>
                        <div class="col-md-9 m-0 p-0">
                            <input type="text" class="form-control" id="paymentpaid" name="paymentpaid"  placeholder="Update Amount" required autocomplete="paymentpaid" autofocus>
                        </div>
                      </div>

                      <div class="form-group row m-0 p-1">
                        <label for="paymentdate" class="col-md-3 m-0 p-0 col-form-label">{{ __('Date') }} </label>
                        <div class="col-md-9 m-0 p-0">
                            <input type="date" class="form-control" id="paymentdate" name="paymentdate"  placeholder="Date Done" autocomplete="paymentdate" autofocus>
                        </div>
                      </div>

                      <div class="form-group row m-0 p-1">
                        <div class="col-12 m-0 p-0" id="saveupdatepaymentload">

                        </div>
                      </div>
                    </div>
                    <div class="card-footer justify-content-center m-0 p-2">
                      <div class="">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                        <button  class="btn btn-outline-success btn-small float-right" name="submitplotbtn" id="submitplotbtn"  type="submit" >
                          Save Payment for @if($watermonth!="")
                            {{$watermonth}}
                          @endif
                          /{{ App\Http\Controllers\TenantController::TenantNames($waters['Tenant']) }}
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        
      </div>
    </div>
  </div>
</div>
<!-- end of modal -->