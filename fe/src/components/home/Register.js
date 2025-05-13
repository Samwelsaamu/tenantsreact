import HomeFooter from './HomeFooter';
import HomeNavBar from './HomeNavBar';

function Register() {
  return (
    <>
    <HomeNavBar active='register'/>
    <main style={{"paddingTop": "80px","minHeight": "calc(100vh - 3rem)"}}>
        <div className="container">
            <div className="row justify-content-center">
                <div className="col-md-12">
                    <div className="card" style={{"border": "none"}}>
                        <div className="card-header text-info" style={{"backgroundColor": "transparent"}}>
                            <h1 style={{"textAlign": "center"}}>Welcome</h1>
                        </div>

                        <div className="card-body" style={{"paddingTop": "10px"}}>
                            <div className="row">

                                <div className="col-sm-6">
                                    <div className="card card-primary texts-black" style={{"marginBottom": "5%","minHeight": "300px","textAlign": "center","border": "none"}}>
                                    <div className="card-body">
                                        <h3 className="card-title title-black text-info">Contacts</h3>

                                        <p className="card-text"><i className="fa fa-x fa-map-marker text-primary mb-4"></i>  adress and town</p>
                                        <p className="card-text"><i className="fa fa-x fa-phone text-primary mb-4"></i> 07662 </p>
                                        <p className="card-text"> <a href="mailto:{{ App\Models\Agency::getAgencyEmail()}}" style={{"color":"black"}}><i className="fa fa-x fa-envelope-o text-primary mb-4"></i> email</a></p>
                                        <p>We Will Get Back to You As Soon as Possible</p>
                                        
                                    </div>
                                    </div>
                                </div>

                                <div className="col-sm-6">
                                    <div className="card card-primary texts-info" style={{"marginBottom": "5%","minHeight": "300px","textAlign": "center","border": "none"}}>
                                    <div className="card-body">
                                        <h3 className="card-title title-black text-info">Our Houses are</h3>
                                        <p className="card-text">Affordable and Cost Effective</p>
                                        <p className="card-text">Near Main Routes</p>
                                        <p className="card-text">Plenty of water</p>
                                        <p className="card-text">Provided with 24 hour security</p>
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

export default Register;