import HomeFooter from './HomeFooter';
import HomeNavBar from './HomeNavBar';
function PageIsNotLive() {
    document.title="PageNotFound";
  return (
    <>
    <HomeNavBar active='home'/>
    <main style={{"paddingTop": "80px","minHeight": "calc(100vh - 3rem)"}}>
        <div class="container">
            <div class="row justify-content-center">
                <div className="col-md-10 m-2 p-2 mt-4 mb-4">
                    <div className="card border-danger" >
                        <div className="card-header bg-danger text-white elevation-2 m-0 p-4">
                            <h4 style={{"textAlign": "center"}}>Ooops. Site Under Maintainance </h4>
                        </div>

                        <div className="card-body text-center m-0 p-3" style={{"paddingTop": "10px"}}>
                            
                            <p>
                                Resource you are trying to access cannot be reached or found.
                            </p>

                            <p>
                                Please Come back after the process is finished.
                            </p>
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

export default PageIsNotLive;