import HomeFooter from './HomeFooter';
import HomeNavBar from './HomeNavBar';

function HomeContactus() {
    document.title="Contact us";
  return (
    <>
    <HomeNavBar active='contactus'/>
    <main style={{"paddingTop": "80px","minHeight": "calc(100vh - 3rem)"}}>
        <div className="container">
            <div className="row justify-content-center">
                <div className="col-md-12">
                    <div className="card" style={{"border": "none"}}>
                        
                        <div className="card-body" style={{"paddingTop": "10px"}}>
                            <div className="row">

                                <div className="col-sm-4">
                                    <div className="card card-primary texts-black" style={{"marginBottom": "5%","minHeight": "300px","textAlign": "center","border": "none"}}>
                                    <div className="card-body">
                                        <h3 className="card-title title-black">Contacts</h3>

                                        <p className="card-text"><i className="fa fa-x fa-map-marker text-primary mb-4"></i>  adress and town</p>
                                        <p className="card-text"><i className="fa fa-x fa-phone text-primary mb-4"></i> 07662 </p>
                                        <p className="card-text"> <a href="mailto:{{ App\Models\Agency::getAgencyEmail()}}" style={{"color":"black"}}><i className="fa fa-x fa-envelope-o text-primary mb-4"></i> email</a></p>
                                        <p>We Will Get Back to You As Soon as Possible</p>
                                        
                                    </div>
                                    </div>
                                </div>

                                <div className="col-sm-8">
                                    <div className="card card-primary texts-info" style={{"marginBottom": "5%","minHeight": "300px","textAlign": "center","border": "none"}}>
                                    <div className="card-body">
                                        <h3 className="card-title title-black">Send Us a Message</h3>
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
                                            <input type="submit" class="btn btn-primary" value="Send message"/>
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
        
    </main>
    <HomeFooter />
      
    </>
  );
}

export default HomeContactus;