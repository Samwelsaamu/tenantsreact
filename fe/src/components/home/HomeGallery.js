import HomeFooter from './HomeFooter';
import HomeNavBar from './HomeNavBar';

function HomeGallery() {
    document.title="Gallery";
  return (
    <>
    <HomeNavBar active='gallery'/>
        <main style={{"paddingTop": "80px","minHeight": "calc(100vh - 3rem)"}}>
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-md-10">
                        <div className="card" style={{"border": "none"}}>

                            <div className="card-body" style={{"paddingTop": "10px"}}>
                                <div className="row">

                                    <div className="col-sm-12">
                                        <div className="card card-primary texts-info" style={{"marginBottom": "5%","minHeight": "300px","textAlign": "center","border": "none"}}>
                                        <div className="card-body">
                                            <h4>Our Properties and Assets</h4>
                                            <p class="text-info">Our Gallery is Comming Soon....! </p>
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

export default HomeGallery;