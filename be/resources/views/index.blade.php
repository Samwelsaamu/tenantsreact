@extends('layouts.headers')
@section('title','Wagitonga Agencies Limited')
@section('content')
<div class="container" style="">
    <div class="row justify-content-center" style="">
        <div class="col-md-12" style="">
            <div class="card" style="border: none;">
                <div class="card-header text-info" style="background-color: transparent;">
                    <h1 style="text-align: center;">Welcome</h1>
                </div>

                <div class="card-body" style="padding-top: 10px;">
                    <div class="row">

                        <div class="col-sm-6">
                            <div class="card card-primary texts-black" style="margin-bottom: 5%;min-height: 300px;text-align: center;border: none;">
                              <div class="card-body">
                                <h3 class="card-title title-black text-info">Contacts</h3>

                                <p class="card-text"><i class="fa fa-x fa-map-marker text-primary mb-4"></i>  {{ App\Models\Agency::getAgencyAddress()}}, {{ App\Models\Agency::getAgencyTown()}}</p>
                                <p class="card-text"><i class="fa fa-x fa-phone text-primary mb-4"></i> 0{{ App\Models\Agency::getAgencyPhone()}} </p>
                                <p class="card-text"> <a href="mailto:{{ App\Models\Agency::getAgencyEmail()}}" style="color: black;"><i class="fa fa-x fa-envelope-o text-primary mb-4"></i> {{ App\Models\Agency::getAgencyEmail()}}</a></p>
                                 <p>We Will Get Back to You As Soon as Possible</p>
                                
                              </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="card card-primary texts-info" style="margin-bottom: 5%;min-height: 300px;text-align: center;border: none;">
                              <div class="card-body">
                                <h3 class="card-title title-black text-info">Our Houses are</h3>
                                <p class="card-text">Affordable and Cost Effective</p>
                                <p class="card-text">Near Main Routes</p>
                                <p class="card-text">Plenty of water</p>
                                <p class="card-text">Provided with 24 hour security</p>
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
