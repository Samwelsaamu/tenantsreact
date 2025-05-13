import { useEffect, useContext, useState } from 'react';

import Spinner from 'react-bootstrap/Spinner';

import Modal from 'react-bootstrap/Modal';

import axios from 'axios';

import Swal from 'sweetalert';
import ReLogin from '../home/ReLogin';
import { LoginContext } from '../contexts/LoginContext';



function AddTenant({showaddtenant,handleCloseAddTenant,currenttenant,loadTenants}) {
    document.title=(currenttenant!=='')?'Update Tenant : '+currenttenant.Plotname:'Add New Tenant';
    const {socket,loggedname, setLoggedName, loggedtoken, setLoggedToken, loggedpermissions, setLoggedPermissions, loggedroles, setLoggedRoles, loggedrole,setLoggedRole,sitedata,setSiteData} =useContext(LoginContext);
         
    const [loggedoff,setLoggedOff]=useState(false);
    const [loading,setLoading]=useState(false);

    const [loadingresok,setLoadingResOk]=useState('');
    const [loadingres,setLoadingRes]=useState('');

   
    const [formdata,setFormData]=useState({
        Fname:currenttenant.Fname,
        Oname:currenttenant.Oname,
        IDno:currenttenant.IDno,
        Email:(currenttenant.Email===null?"":currenttenant.Email),
        id:currenttenant.id,
        Gender:currenttenant.Gender,
        HudumaNo:(currenttenant.HudumaNo===null?"":currenttenant.HudumaNo),
        Phone:currenttenant.Phone,
        Status:currenttenant.Status,
        error_list:[],
    });
        // useEffect( () =>{
        //     socket.on('load_credit_balance', (msg) =>{
        //         loadAppData();
        //     })
    
        // }, []);
    
    
    // console.log(formdata)
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
            Fname:formdata.Fname,
            Oname:formdata.Oname,
            IDno:formdata.IDno,
            Email:formdata.Email,
            Phone:formdata.Phone,
            id:formdata.id,
            Gender:formdata.Gender,
            HudumaNo:formdata.HudumaNo,
            Status:formdata.Status,
        }

        let title='Sure to '+(currenttenant!==''?'Update':'Add')+ ' '+(currenttenant!==''?currenttenant.Fname:formdata.Fname) +' ?';
        let text='';
        if(currenttenant!==''){
            text="New Changes for "+currenttenant.Fname+" will be :"+
                (currenttenant.Fname.trim().toLowerCase()!==formdata.Fname.trim().toLowerCase()?"\n FName :: "+currenttenant.Fname+" > "+formdata.Fname:'')+
                (currenttenant.Oname.trim().toLowerCase()!==formdata.Oname.trim().toLowerCase()?"\n Oname :: "+currenttenant.Oname+" > "+formdata.Oname:'')+
                // (currenttenant.Email.trim().toLowerCase()!==formdata.Email.trim().toLowerCase()?"\n Email :: "+currenttenant.Email+" > "+formdata.Email:'')+
                ((currenttenant.Email===null?"":currenttenant.Email).trim().toLowerCase()!==formdata.Email.trim().toLowerCase()?"\n Email :: "+(currenttenant.Email===null?"":currenttenant.Email)+" > "+formdata.Email:'')+
                // ((currenttenant.HudumaNo===null?"":currenttenant.HudumaNo).trim().toLowerCase()!==formdata.HudumaNo.trim().toLowerCase()?"\n Huduma No :: "+(currenttenant.HudumaNo===null?"":currenttenant.HudumaNo)+" > "+formdata.HudumaNo:'')+
                (currenttenant.Gender.trim().toLowerCase()!==formdata.Gender.trim().toLowerCase()?"\n Gender :: "+currenttenant.Gender+" > "+formdata.Gender:'')+
                (currenttenant.IDno.trim().toLowerCase()!==formdata.IDno.trim().toLowerCase()?"\n IDNo :: "+currenttenant.IDno+" > "+formdata.IDno:'')+
                (currenttenant.Phone!==formdata.Phone?"\n Phone :: "+currenttenant.Phone+" > "+formdata.Phone:'')+
                (currenttenant.Status.trim().toLowerCase()!==formdata.Status.trim().toLowerCase()?"\n Status :: "+currenttenant.Status+" > "+formdata.Status:'')
                ;
        }
        else{
            text="New Information for "+formdata.Fname+" will be :"+
                "\n FName :: "+formdata.Fname+
                "\n Oname :: "+formdata.Oname+
                "\n Email :: "+formdata.Email+
                "\n Phone :: "+formdata.Phone+
                "\n ID no ::"+formdata.IDno+
                "\n Gender :: "+formdata.Gender+
                "\n Status :: "+formdata.Status;
        }
        let url='';
        if(currenttenant!==''){
            url='/v2/update/tenant/save';
        }
        else{
            url='/v2/save/tenant/save';
        }

        if(text.trim()==="New Changes for "+currenttenant.Fname+" will be :"){
            Swal("Updating "+currenttenant.Fname,"You have not made any changes.");
            setLoadingRes("")
            setLoadingResOk("")
            setLoading(false);
        }
        else if(text.trim()==="New Information for "+formdata.Fname+" will be :"){
            Swal("New Property","No Property Infomation Specified.");
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
                        loadTenants();
                        setLoading(false);
    
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
    

  return (
    <>
    
        <Modal size='lg' show={showaddtenant} onHide={handleCloseAddTenant} className='text-sm'>
        {(currenttenant !=='')?
            <>
                <Modal.Header className='justify-content-center bg-warning m-0 p-2' closeButton>
                    <Modal.Title className='mx-auto text-dark'> 
                        <h5>Update Tenant for : {currenttenant.Fname+' '+currenttenant.Oname} ({currenttenant.Status})</h5>
                    </Modal.Title>
                </Modal.Header>
            </>
            :
            <>
                <Modal.Header className='justify-content-center bg-info m-0 p-2' closeButton>
                    <Modal.Title className='mx-auto text-white'> 
                        <h5>Add New Tenant </h5>
                    </Modal.Title>
                </Modal.Header>
            </>
        }
            
            
            <Modal.Body className='m-0 p-0'>
                    {loggedoff ? 
                        <ReLogin setLoggedOff={setLoggedOff} loggedoff={loggedoff} />
                    :
                    <div className="row m-0 p-0 justify-content-center text-center border-none">
                    <form onSubmit={submitProperty}>
                        <div className="row m-0 p-0 mt-3 mb-3">
                            <div className="col-6 m-0 p-1">
                                <div className="border-info card p-1 elevation-2">
                                    <div className="form-group row m-0 p-1 pb-2">
                                        <label htmlFor="Fname" className="col-sm-4 col-12 col-form-label text-md-right">Fname</label>

                                        <div className="col-sm-8 col-12 m-0 p-0">
                                            <input id="Fname" type="text" className="form-control" name="Fname" value={formdata.Fname} onChange={handleInputChange} placeholder="First Name" required autoComplete="Fname" autoFocus/>
                                            {formdata.error_list && formdata.error_list.Fname && 
                                                <span className="help-block text-danger">
                                                    <strong>{formdata.error_list.Fname}</strong>
                                                </span>
                                            }
                                        </div>
                                    </div>

                                    <div className="form-group row m-0 p-1 pb-2">
                                        <label htmlFor="Oname" className="col-sm-4 col-12 col-form-label text-md-right">Oname</label>

                                        <div className="col-sm-8 col-12 m-0 p-0">
                                            <input id="Oname" type="text" className="form-control" name="Oname" value={formdata.Oname} onChange={handleInputChange} placeholder="Other Names" required autoComplete="Oname" autoFocus/>
                                            {formdata.error_list && formdata.error_list.Oname && 
                                                <span className="help-block text-danger">
                                                    <strong>{formdata.error_list.Oname}</strong>
                                                </span>
                                            }
                                        </div>
                                    </div>

                                    <div className="form-group row m-0 p-1 border-light mb-1 ">
                                        <label htmlFor="Gender" className="col-sm-4 col-12 col-form-label text-md-right">Gender</label>

                                        <div className="col-sm-8 col-12 m-0 p-0" style={{"float":"right"}}>
                                            <label className='m-0 p-1 text-left' style={{"cursor": "pointer","float":"left"}}>
                                                    <input type="radio" required checked={formdata.Gender==="Male"?"true":""} onChange={handleInputChange} className="" name="Gender" value="Male" autoComplete="Gender"/> Male
                                            </label>
                                            <label className='m-0 p-1 text-left' style={{"cursor": "pointer","float":"left"}}>
                                                <input type="radio" required checked={formdata.Gender==="Female"?"true":""} onChange={handleInputChange} className="" name="Gender" value="Female" autoComplete="Gender"/> Female
                                            </label>
                                            <label className='m-0 p-1 text-left' style={{"cursor": "pointer","float":"left"}}>
                                                <input type="radio" required checked={formdata.Gender==="Other"?"true":""} onChange={handleInputChange} className="" name="Gender" value="Other" autoComplete="Gender"/> Other
                                            </label>
                                        </div>
                                    </div>
                                    {(currenttenant ==='') &&
                                        <div className="form-group row m-0 p-1 pb-2">
                                            <label htmlFor="IDno" className="col-sm-4 col-12 col-form-label text-md-right">IDNo</label>

                                            <div className="col-sm-8 col-12 m-0 p-0">
                                                <input id="IDno" type="text" className="form-control" name="IDno" value={formdata.IDno} onChange={handleInputChange} placeholder="ID No" required autoComplete="IDno" autoFocus/>
                                                {formdata.error_list && formdata.error_list.IDno && 
                                                    <span className="help-block text-danger">
                                                        <strong>{formdata.error_list.IDno}</strong>
                                                    </span>
                                                }
                                            </div>
                                        </div>
                                    }

                                    
                                </div>
                            </div>

                            <div className="col-6 m-0 p-1">
                                <div className="border-info card p-1 elevation-2">
                                    {(currenttenant ==='') &&
                                        <div className="form-group row m-0 p-1 pb-2">
                                            <label htmlFor="HudumaNo" className="col-sm-4 col-12 col-form-label text-md-right">HudumaNo</label>

                                            <div className="col-sm-8 col-12 m-0 p-0">
                                                <input id="HudumaNo" type="number" className="form-control" disabled name="HudumaNo" value={formdata.HudumaNo} onChange={handleInputChange} placeholder="Huduma Number" autoComplete="HudumaNo" autoFocus/>
                                                {formdata.error_list && formdata.error_list.HudumaNo && 
                                                    <span className="help-block text-danger">
                                                        <strong>{formdata.error_list.HudumaNo}</strong>
                                                    </span>
                                                }
                                            </div>
                                        </div>
                                    }

                                    {(currenttenant !=='') &&
                                        <div className="form-group row m-0 p-1 pb-2">
                                            <label htmlFor="IDno" className="col-sm-4 col-12 col-form-label text-md-right">IDNo</label>

                                            <div className="col-sm-8 col-12 m-0 p-0">
                                                <input id="IDno" type="text" className="form-control" name="IDno" value={formdata.IDno} onChange={handleInputChange} placeholder="ID No" required autoComplete="IDno" autoFocus/>
                                                {formdata.error_list && formdata.error_list.IDno && 
                                                    <span className="help-block text-danger">
                                                        <strong>{formdata.error_list.IDno}</strong>
                                                    </span>
                                                }
                                            </div>
                                        </div>
                                    }

                                    <div className="form-group row m-0 p-1 pb-2">
                                        <label htmlFor="Phone" className="col-sm-4 col-12 col-form-label text-md-right">Phone</label>

                                        <div className="col-sm-8 col-12 m-0 p-0">
                                            <input id="Phone" type="tel" className="form-control" name="Phone" value={formdata.Phone} onChange={handleInputChange} placeholder="Phone Number" required autoComplete="Phone" autoFocus/>
                                            {formdata.error_list && formdata.error_list.Phone && 
                                                <span className="help-block text-danger">
                                                    <strong>{formdata.error_list.Phone}</strong>
                                                </span>
                                            }
                                        </div>
                                    </div>

                                    <div className="form-group row m-0 p-1 pb-2">
                                        <label htmlFor="Email" className="col-sm-4 col-12 col-form-label text-md-right">Email</label>

                                        <div className="col-sm-8 col-12 m-0 p-0">
                                            <input id="Email" type="email" className="form-control" name="Email" value={formdata.Email} onChange={handleInputChange} placeholder="Email Address" autoComplete="Email" autoFocus/>
                                            {formdata.error_list && formdata.error_list.Email && 
                                                <span className="help-block text-danger">
                                                    <strong>{formdata.error_list.Email}</strong>
                                                </span>
                                            }
                                        </div>
                                    </div>

                                    {(currenttenant ==='') &&
                                        <>
                                            <div className="form-group row m-0 p-1 border-light mb-1 ">
                                                <label htmlFor="Status" className="col-sm-4 col-12 col-form-label text-md-right">Status</label>

                                                <div className="col-sm-8 col-12 m-0 p-0" style={{"float":"right"}}>
                                                    <label className='m-0 p-1 text-left' style={{"cursor": "pointer","float":"left"}}>
                                                            <input type="radio" required checked={formdata.Status==="New"?"true":""} onChange={handleInputChange} className="" name="Status" value="New" autoComplete="Status"/> Tenant
                                                    </label>
                                                    <label className='m-0 p-1 text-left' style={{"cursor": "pointer","float":"left"}}>
                                                        <input type="radio" required checked={formdata.Status==="Other"?"true":""} onChange={handleInputChange} className="" name="Status" value="Other" autoComplete="Status"/> Other
                                                    </label>
                                                </div>
                                            </div>
                                        </>
                                    }

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
                                                {(currenttenant !=='')?'Updating':'Adding'} ...</span>
                                                
                                        </div>
                                    }

                                    {!loading && loadingresok ==='' && 
                                        <div className="col-md-10">
                                            <button type="submit" className="btn btn-success">
                                                {(currenttenant !=='')?'Update ':'Save Tenant'} {currenttenant.Fname} Information
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
                        </div>
                    </form>
                </div>
                }
            </Modal.Body>
        </Modal>

    </>
  );
}

export default AddTenant;