import {useEffect, useContext, useState } from 'react';

import Spinner from 'react-bootstrap/Spinner';

import Modal from 'react-bootstrap/Modal';

import axios from 'axios';

import Swal from 'sweetalert';
import ReLogin from '../home/ReLogin';
import { LoginContext } from '../contexts/LoginContext';



function AddPropertyType({show, handleClose,currentproperty,propertydata,loadPropertyHouseTypes}) {
    document.title='Add or Update Property Type';
    const {socket,loggedname, setLoggedName, loggedtoken, setLoggedToken, loggedpermissions, setLoggedPermissions, loggedroles, setLoggedRoles, loggedrole,setLoggedRole,sitedata,setSiteData} =useContext(LoginContext);
         
    const [loggedoff,setLoggedOff]=useState(false);
    const [loading,setLoading]=useState(false);

    const [loadingresok,setLoadingResOk]=useState('');
    const [loadingres,setLoadingRes]=useState('');
    const [currenthouse, setCurrentHouse] = useState('');

    const [formdata,setFormData]=useState({
        id:'',
        typename:'',
        group:'property',
        error_list:[],
    });
    
        // useEffect( () =>{
        //     socket.on('load_credit_balance', (msg) =>{
        //         loadAppData();
        //     })
    
        // }, []);
    
    
    const handleInputChange=(e)=>{
        e.persist();
        setFormData({...formdata,[e.target.name]:e.target.value})
    }

    const submitProperty= (e)=>{
        e.preventDefault();
        setLoading(true);
        setLoadingRes("")
        setLoadingResOk("")
        
        const form={
            typename:formdata.typename,
            group:formdata.group,
            id:currenthouse!==''?currenthouse.id:'',
        }

        let title='Sure to '+(currenthouse!==''?'Update':'Add New')+ ' '+(currenthouse!==''?currenthouse.typename:formdata.typename) +' ?';
        let text='';
        if(currenthouse!==''){
            text="New Changes for "+currenthouse.typename+" will be :"+
                (currenthouse.typename.trim().toLowerCase()!==formdata.typename.trim().toLowerCase()?"\n Name :: "+currenthouse.typename+" > "+formdata.typename:'');
        }
        else{
            text="New Information for "+formdata.typename+" will be :"+
                "\n Name :: "+formdata.typename;
        }
        let url='';
        if(currenthouse!==''){
            url='/v2/update/propertyhousetype/save';
        }
        else{
            url='/v2/save/propertyhousetype/save';
        }

        if(text.trim()==="New Changes for "+currenthouse.typename+" will be :"){
            Swal("Updating "+currenthouse.typename,"You have not made any changes.");
            setLoadingRes("")
            setLoadingResOk("")
            setLoading(false);
        }
        else if(text.trim()==="New Information for "+formdata.typename+" will be :"){
            Swal("New Property Type","No Property Type Infomation Specified.");
            setLoadingRes("")
            setLoadingResOk("")
            setLoading(false);
        }
        else{

            Swal({
                title:title,
                text:text,
                buttons:true,
                infoMode:true,
            })
            .then((willcontinue) =>{
                if(willcontinue){
                    axios.post(url,form)
                    .then(response=>{
                        if(response.data.status=== 200){
                            setLoadingResOk(response.data.message)
                            setFormData({...formdata,error_list:[]});
                            setLoadingRes("")
                            loadPropertyHouseTypes();
                        }
                        else if(response.data.status=== 401){
                            // setLoggedOff(true);    
                            setLoadingRes(response.data.message)
                            setFormData({...formdata,error_list:[]});
                            setLoadingResOk("")
                        }
                        else if(response.data.status=== 500){
                            setLoadingRes(response.data.message)
                            setFormData({...formdata,error_list:[]});
                            setLoadingResOk("")
                        }
                        else{
                            setFormData({...formdata,error_list:response.data.errors});
                        }
                        setLoading(false);
                        // console.log(formdata.error_list)
    
                    })
                    .catch((error)=>{
                        // if(error.message==="Request failed with status code 401"){
                        //     setLoggedOff(true);                    
                        // }
                        // else if(error.message==="Request failed with status code 500"){
                        //     setLoggedOff(true);                    
                        // }
                        setLoadingRes(""+error)
                        setLoadingResOk("")
                        setLoading(false);
                    })
                }
                else{
                    setLoadingRes("")
                    setLoadingResOk("")
                    setLoading(false);
                }
            })

        }
        

    }

    const handleShowUpdate = (typeproperty) => {
        setFormData({...formdata,typename:typeproperty.typename})
        setCurrentHouse(typeproperty)
    };

    const deletePropertyHouseType= (type)=>{
        const form={
            id:type.id,
        }

        let title='Delete '+type.typename;
        let text="This will remove this Property Type.";
        Swal({
            title:title+' ?',
            text:text,
            buttons:true,
            infoMode:true,
        })
        .then((willcontinue) =>{
            if(willcontinue){
                setLoading(true);
                Swal("Deleting....","Please Wait");
                axios.post('/v2/delete/propertyhousetype/save',form)
                .then(response=>{
                    if(response.data.status=== 200){
                        loadPropertyHouseTypes();
                        Swal("Success",response.data.message);
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
                    }
                    setLoading(false);
                })
                .catch((error)=>{
                    if(!localStorage.getItem("auth_token")){
                        let ex=error['response'].data.message;
                        if(ex==='Unauthenticated.'){
                            if(!localStorage.getItem("auth_token")){
                                setLoggedOff(true); 
                            }  
                            else{ 
                                setLoggedOff(true); 
                                localStorage.removeItem('auth_token');
                                localStorage.removeItem('auth_name');
                            }              
                        }
                        else{
                            Swal("Error",""+error,"error");
                        }
                        setLoading(false)
                    }
                    else{
                        let ex=error['response'].data.message;
                        if(ex==='Unauthenticated.'){
                            setLoggedOff(true); 
                            localStorage.removeItem('auth_token');
                            localStorage.removeItem('auth_name');
                        }
                        else{
                            setLoading(false)
                            Swal("Error",""+error,"error");
                        }
                    }
                })
            }
            else{
                setLoading(false);
            }
        })

    }
    

  return (
    <>
        <Modal size='md' show={show} onHide={handleClose} className='text-sm'>
        {(currenthouse ==='')?
            <>
                <Modal.Header className='justify-content-center bg-primary m-0 p-2' closeButton>
                    <Modal.Title className='mx-auto text-white text-sm'> 
                        <h5>Add New Property Type</h5>
                    </Modal.Title>
                </Modal.Header>
            </>
            :
            <>
                <Modal.Header className='justify-content-center bg-warning m-0 p-2' closeButton>
                    <Modal.Title className='mx-auto text-dark text-sm'> 
                        <h5>Update Property Type : {currenthouse.typename}</h5>
                    </Modal.Title>
                </Modal.Header>
            </>
            }
            
            
            <Modal.Body className='m-0 p-0'>
                    {loggedoff ? 
                        <ReLogin setLoggedOff={setLoggedOff} loggedoff={loggedoff} />
                    :
                    <div className="row m-0 p-0 justify-content-center bg-primary text-center border-none">
                    <form onSubmit={submitProperty}>
                        <div className="row m-0 p-0 mt-3 mb-3">
                            <div className="col-12 m-0 p-1">
                                <div className="border-info card p-1 elevation-2">
                                    <div className="form-group row m-0 p-1 pb-2">
                                        <label htmlFor="typename" className="col-sm-5 col-12 col-form-label text-md-right">Property Type Name</label>

                                        <div className="col-sm-7 col-12 m-0 p-0">
                                            <input id="typename" type="text" className="form-control" name="typename" value={formdata.typename} onChange={handleInputChange} placeholder="Property Type Name" required autoComplete="typename" autoFocus/>
                                            {formdata.error_list && formdata.error_list.typename && 
                                                <span className="help-block text-danger">
                                                    <strong>{formdata.error_list.typename}</strong>
                                                </span>
                                            }
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div className="col-12 m-0 p-0">
                                <div className="form-group row mb-0 justify-content-center m-1 mt-2 p-2 border-none">
                                    
                                    {loadingresok!=='' && 
                                        <div className="col-md-10 elevation-0 mb-2 p-2 text-center border-ok">
                                            <span className="help-block text-success">
                                                <strong>{loadingresok!=='' && loadingresok}</strong>
                                            </span>
                                        </div>
                                    }

                                    {loading && 
                                        <div className="col-md-12 text-center text-white">
                                                <Spinner
                                                as="span"
                                                variant='info'
                                                animation="border"
                                                size="lg"
                                                role="status"
                                                aria-hidden="true"
                                                />
                                                <span className='text-info' style={{"padding": "10px","display":"inline-block"}}>
                                                {(currenthouse !=='')?'Updating':'Adding'} ...</span>
                                                
                                        </div>
                                    }

                                    {!loading && 
                                        <div className="col-md-10">
                                            <button type="submit" className="btn btn-success">
                                                {(currenthouse !=='')?'Update Property Type ':'Save New Property Type '}
                                            </button>
                                        </div>
                                    }

                                    {loadingres!=='' && 
                                        <div className="col-md-10 elevation-0 mt-2 p-2 text-center border-error">
                                            <span className="help-block text-danger">
                                                <strong>{loadingres!=='' && loadingres}</strong>
                                            </span>
                                        </div>
                                    }
                                    
                                </div>
                            </div>

                            <div className="col-12 m-0 p-1">
                                <div className="border-info card p-1 elevation-2">
                                    
                                <div className="row m-0 p-0">
                                   {!loading &&
                                        <>
                                            {propertydata  && propertydata.map((agreement,key) => (
                                                <div key={key}  className="col-12 col-lg-6 text-left m-0 p-1 mt-1 mb-2">
                                                     <div className="card border-ok  m-0 p-0" >
                                                            <div className="text-dark text-center text-lg m-0 p-0 pt-1">
                                                                <span>{key+1}. {agreement.typename}</span>
                                                                
                                                                
                                                            </div>
                                                            <div className="card-body text-center text-sm m-0 p-1">
                                                                
                                                                    <button type='button' className='btn-warning m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-none' onClick={()=>{handleShowUpdate(agreement)}}><i className='fa fa-edit'></i> </button>
                                                                    <button type='button'  className='btn-danger m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-none' onClick={()=>{deletePropertyHouseType(agreement)}}><i className='fa fa-trash'></i> </button>
                                                                    
                                                                <div>
                                                                
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                                    
                                                ))
                                            }
                                        </>
                                    }

                                </div>
                                </div>
                            </div>
                            
                        </div>
                    </form>
                </div>
                }
            </Modal.Body>
        </Modal>

    </>
  );
}

export default AddPropertyType;