@extends('layouts.headers')
@section('title','Properties')
@section('content')
<div class="" style="">
    <div class="row justify-content-center" style="">
        <div class="col-md-10" style="">
            <div class="card" style="border: none;">

                <div class="card-body" style="padding-top: 10px;">
                    <div class="row">
                        <div class="col-sm-12" style="margin-bottom: 0px;">
                            <div class="card card-primary card-outline texts-black" style="margin-bottom: 5%;text-align: center;">
                                <h4 style="padding: 10px;" class="text-info">We are Located in Various Locations. <br>Please Contact Us for More Information</h4>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="card card-primary card-outline texts-black" style="margin-bottom: 5%;min-height: 300px;text-align: center;">
                              <div class="card-body">
                                <h3 class="card-title title-black">Contacts</h3>

                                <p class="card-text"><i class="fa fa-x fa-map-marker text-primary mb-4"></i> {{ App\Models\Agency::getAgencyAddress()}}, {{ App\Models\Agency::getAgencyTown()}}</p>
                                <p class="card-text"><i class="fa fa-x fa-phone text-primary mb-4"></i> 0{{ App\Models\Agency::getAgencyPhone()}} </p>
                                <p class="card-text"> <a href="mailto:{{ App\Models\Agency::getAgencyEmail()}}" style="color: black;"><i class="fa fa-x fa-envelope-o text-primary mb-4"></i> {{ App\Models\Agency::getAgencyEmail()}}</a></p>
                                 
                                
                              </div>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="card card-primary card-outline texts-black" style="margin-bottom: 5%;min-height: 300px;text-align: center;">
                              <div class="card-body">

                                <div class="form-group">
                                  <input type="text" id="inputName" placeholder="Your Name" class="form-control" />
                                </div>
                                <div class="form-group">
                                  <input type="email" id="inputEmail" placeholder="Your Email Address" class="form-control" />
                                </div>
                                <div class="form-group">
                                  <input type="text" id="inputSubject" placeholder="Subject or Title or Agenda" class="form-control" />
                                </div>
                                <div class="form-group">
                                  <textarea id="inputMessage" class="form-control" placeholder="Your Message, Comment or Issue" rows="4"></textarea>
                                </div>
                                <div class="form-group">
                                  <input type="submit" class="btn btn-primary" value="Send message">
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
