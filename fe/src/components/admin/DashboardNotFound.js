import DashSideNavBar from './DashSideNavBar';
import DashNavBar from './DashNavBar';
import { useState } from 'react';
import DashFooter from './DashFooter';
import ReLogin from '../home/ReLogin';


function DashboardNotFound() {
    document.title="Dashboard NotFound";
    const [closed,setClosed]=useState(false)

    const [show,setShow]=useState(false);
    const [showdownloadpayments,setShowDownloadPayments]=useState(false);
    const [property,setProperty]=useState('');
    const [propertyid,setPropertyId]=useState('');

    const handleClose = () => {
        setShow(false);
        setProperty('');
        setPropertyId('');
    };
    
    const handleShow = (names,id) => {
        setShow(true);
        setProperty(names);
        setPropertyId(id);
    };

    const handlePaymentClose = () => {
        setShowDownloadPayments(false);
        setProperty('');
        setPropertyId('');
    };

    const handlePaymentShow = (names,id) => {
        setShowDownloadPayments(true);
        setProperty(names);
        setPropertyId(id);
    };

    



  return (
    <>
    <div className="wrapper">
        <DashNavBar setClosed={setClosed} closed={closed} active='home'/>
        <DashSideNavBar setClosed={setClosed} closed={closed} active='home'/>
        
        
        <main className="py-3">
            <div className={`content-wrapper ${closed?'closed':''}`} style={{"paddingTop": "10px"}}>
           
                    <section className="content">
                    <div className="container">
                        <div className="row justify-content-center">
                            <div className="col-md-8 m-2 p-2 mt-4 mb-4">
                                <div className="card border-danger" >
                                    <div className="card-header bg-danger text-white elevation-2 m-0 p-0">
                                        <h4 style={{"textAlign": "center"}}>Ooops. Resource Not Found </h4>
                                    </div>

                                    <div className="card-body text-center" style={{"paddingTop": "10px"}}>
                                        
                                        <p>
                                            Resource you are trying to access cannot be reached or found.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    
                    

                </section>
            </div>
        </main>


        <DashFooter />
      </div>
    </>
  );
}

export default DashboardNotFound;