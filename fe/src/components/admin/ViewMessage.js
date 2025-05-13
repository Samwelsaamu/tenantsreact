import { useState } from 'react';
import Spinner from 'react-bootstrap/Spinner';

import Modal from 'react-bootstrap/Modal';

import axios from 'axios';

import Swal from 'sweetalert';
import ReLogin from '../home/ReLogin';
// import { LoginContext } from '../contexts/LoginContext';


function ViewMessage({show,handleClose,currentmessage}) {
    document.title='View Message sent to: '+currentmessage.Phone;
    // console.log(currentmessage)
    // const {socket,loggedname, setLoggedName, loggedtoken, setLoggedToken, loggedpermissions, setLoggedPermissions, loggedroles, setLoggedRoles, loggedrole,setLoggedRole,sitedata,setSiteData} =useContext(LoginContext);
      
    const [loggedoff,setLoggedOff]=useState(false);
    const [loading,setLoading]=useState(false);

    const [loadingresok,setLoadingResOk]=useState('');
    const [loadingres,setLoadingRes]=useState('');

    
  return (
    <>
    
        <Modal size='lg' show={show} onHide={handleClose} className="mt-4">
            <Modal.Header className='justify-content-center bg-info m-0 p-2' closeButton>
                <Modal.Title className='mx-auto text-white'> 
                    <h5>Viewing Message sent to: {currentmessage.Phone}</h5>
                </Modal.Title>
            </Modal.Header>
            
            
            <Modal.Body className='m-0 p-0'>
                    {loggedoff ? 
                        <ReLogin setLoggedOff={setLoggedOff} loggedoff={loggedoff} />
                    :
                    <div className="row justify-content-center">

                        <div className="col-lg-6">
                            <div className="row m-0 p-1">
                                <div className='m-0 p-2 border-ok bg-warning elevation-2'>
                                    {currentmessage.MessageFormated}
                                </div>
                                <div className='m-1 p-1 border-ok elevation-1'>
                                    Sent to:{currentmessage.Phone}
                                </div>
                            </div>

                        </div>

                        <div className="col-lg-6">
                            <div className="row m-1 p-1 ">
                                <div className='m-1 p-1 border-ok elevation-1'>
                                    <div className='p-1'>
                                        Status: {currentmessage.Status} , Cost:   {currentmessage.Cost}
                                    </div>
                                    <div className='p-1'>
                                        Cost:   {currentmessage.Cost}
                                    </div>
                                    <div className='p-1'>
                                        Sent On: {currentmessage.created_at}
                                    </div>
                                    <div className='p-1'>
                                        Update On:   {currentmessage.updated_at}
                                    </div>
                                    {/* <div className='m-1 p-1 border-ok elevation-1'>
                                        Tenant Info:   {currentmessage.updated_at}
                                    </div> */}
                                </div>
                            </div>

                        </div>
                        {/* <hr className='text-danger' style={{"border":"1px solid blue"}} /> */}
                    </div>
                    
                }
            </Modal.Body>
            
        </Modal>

    </>
  );
}

export default ViewMessage;