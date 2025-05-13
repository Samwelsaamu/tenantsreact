import DashSideNavBar from './DashSideNavBar';
import DashNavBar from './DashNavBar';
import Spinner from 'react-bootstrap/Spinner';
import Card from 'react-bootstrap/Card';
import Placeholder from 'react-bootstrap/Placeholder';

function DashboardSpinner() {
    

  return (
    <>
        <div className="wrapper">
            <DashNavBar />
            <DashSideNavBar />
            <main className="py-3" style={{"margin": "1%","padding":"3%","minHeight": "calc(70vh - 3rem)","borderRadius":"10px"}}>
                
                <div className='content-wrapper' style={{"paddingTop": "10px"}}>
            
                    <section className="content">
                        <div className="container text-cennter" style={{"margin": "1%","padding":"5%","borderRadius":"10px"}}>
                            <div className="row justify-content-center">
                                <Spinner animation="grow" variant="primary" size="lg" role="status"></Spinner>
                                <h5 className='text-center' style={{"marginTop": "1%"}}>Please Wait...</h5>
                                <Card.Body>
                                    
                                    <Placeholder as={Card.Text} animation="glow">
                                        <Placeholder  className='border-info' xs={12} style={{"padding": "2%","marginTop":"-20px"}}>
                                            <Placeholder className='border-info bg-white' xs={12} style={{"marginBottom":"1%","padding": "2%"}} /> 
                                            <Placeholder className='border-info bg-light' xs={6} style={{"marginBottom":"1%","padding": "3%"}} /> <Placeholder className='border-info bg-light' xs={4} style={{"marginBottom":"1%","padding": "3%"}} />
                                            <Placeholder className='border-info bg-light' xs={6} style={{"marginBottom":"1%","padding": "3%"}} /> <Placeholder className='border-info bg-light' xs={4} style={{"marginBottom":"1%","padding": "3%"}} />
                                            <Placeholder className='border-info bg-light' xs={6} style={{"marginBottom":"1%","padding": "3%"}} /> <Placeholder className='border-info bg-light' xs={4} style={{"marginBottom":"1%","padding": "3%"}} />
                                            <Placeholder className='border-info bg-light' xs={6} style={{"marginBottom":"1%","padding": "3%"}} /> <Placeholder className='border-info bg-light' xs={4} style={{"marginBottom":"1%","padding": "3%"}} />
                                        </Placeholder> 
                                    
                                    </Placeholder>
                                </Card.Body>
                            </div>
                        </div>
                    </section>
                </div>
            
            </main>
        </div>
    </>
  );
}

export default DashboardSpinner;