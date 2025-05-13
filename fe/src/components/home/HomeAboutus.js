import HomeFooter from './HomeFooter';
import HomeNavBar from './HomeNavBar';

function HomeAboutus() {
    document.title="About us";
  return (
    <>
    <HomeNavBar active='aboutus'/>
    <main style={{"paddingTop": "80px","minHeight": "calc(100vh - 3rem)"}}>
        <div className="container">
            <div className="row justify-content-center">
                <div className="col-md-10">
                    <div className="card" style={{"border": "none"}}>

                        <div className="card-body" style={{"paddingTop": "10px"}}>
                            <div className="row">

                                <div className="col-sm-12">
                                    <div className="card card-primary texts-black" style={{"marginBottom": "5%","minHeight": "300px","textAlign": "center","border": "none"}}>
                                    <div className="card-body">
                                        <h3 class="card-text"><i class="fa fa-x fa-map-marker text-primary mb-4"></i> We Are Estate Agencies</h3>
                                        <p>Our Agents are Commited to Excellent Services and work</p>
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

export default HomeAboutus;