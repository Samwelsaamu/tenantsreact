<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Aknowledgmeent Receipt</title>

    <!-- Scripts -->
    <!-- <script src="js/app.js" defer></script> -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <!-- <link href="css/app.css" rel="stylesheet"> -->
    <style type="text/css">
        @page{size: 18cm 29cm portrait;}
        .title-head{
            text-align: center;
            color: #87C2FF;
        }

        .section-desc{
            color: #87C2FF;
        }
        .ref-nos{
            width: 30%;
            padding-right: 2%;
        }
    </style>
</head>
<body>
    <div style="font-size: 12px;">
        <div class="ribbon-wrapper ribbon-lg">
            @if($PaymentBal<=0)
            <div class="ribbon bg-primary">
              Paid
            </div>
            @else
            <div class="ribbon bg-danger">
              Not Paid
            </div>
            @endif
          </div>
        <img src="assets/img/letterhead.jpg" alt="Wagitonga Logo"  style="width:80%;height: 50px;">
        <h4 class="title-head">Rent Aknowledgment Receipt</h4>
        <div class="row">
            <div class="float-left">
                <span>{{$housename}}</span><br>
                <span>{{$TenantNames}}</span><br>
            </div>
            <div class="float-right ref-nos">
                <span><b>No:</b> </span><span class="float-right">{{$PaymentId}}</span><br>
                <span><b>Date:</b></span><span class="float-right">{{date('M d, Y')}}</span><br>
            </div>
        </div>
        <br><br>
        <table class="table table-hover text-nowrap" style="margin-top: 2%;">
                  <thead>
                    <tr>
                      <th>Item</th>
                      <th>Month</th>
                      <th>Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($PaymentRent>0)
                        <tr>
                          <td>Rent</td>
                          <td>{{$monthname}}</td>
                          <td>Kshs.{{$PaymentRent}}/==</td>
                        </tr>
                    @endif
                    @if($PaymentGarbage>0)
                        <tr>
                          <td>Garbage</td>
                          <td>{{$monthname}}</td>
                          <td>Kshs.{{$PaymentGarbage}}/==</td>
                        </tr>
                    @endif
                    @if($PaymentDeposit>0)
                        <tr>
                          <td>Deposit</td>
                          <td>{{$monthname}}</td>
                          <td>Kshs.{{$PaymentDeposit}}/==</td>
                        </tr>
                    @endif
                    @if($PaymentLease>0)
                        <tr>
                          <td>Lease</td>
                          <td>{{$monthname}}</td>
                          <td>Kshs.{{$PaymentLease}}/==</td>
                        </tr>
                    @endif
                    @if($PaymentWaterbill>0)
                        <tr>
                          <td>Water Bill</td>
                          <td>{{$monthname}}</td>
                          <td>Kshs.{{$PaymentWaterbill}}/==</td>
                        </tr>
                    @endif
                    
                  </tbody>
                </table>
        <div>
            <span class="section-desc">We Received <b>Kshs.{{$PaymentPaid}}</b>.For <b>{{$monthname}}</b>.</span>
            <hr>
            <span class="section-desc">Balance is Kshs.{{$PaymentBal}}./=====</span><br>
        </div>
        <div class="">
            <span style="text-align: center;">-------------------------Thank You.--------------------------------------------</span>
        </div>

    </div>
</body>
</html>