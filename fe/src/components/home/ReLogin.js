import Button from 'react-bootstrap/Button';
import HomeFooter from './HomeFooter';
import HomeNavBar from './HomeNavBar';
import axios from 'axios';
import { useEffect, useState } from 'react';
import {Link, Navigate} from 'react-router-dom';
import Spinner from 'react-bootstrap/Spinner';

function ReLogin({setLoggedOff,loggedoff}) {

    document.title="Login";
    const [redirect,setRedirect]=useState(false);
    const [loading,setLoading]=useState(false);
    
    const [url,setUrl]=useState('');
    
    const [loadingresok,setLoadingResOk]=useState('');
    const [loadingres,setLoadingRes]=useState('');
    const [verify,setVerify]=useState(false);
    const [verify2fa,setVerify2FA]=useState(false);
    const [verifydata,setVerifyData]=useState([]);


    const [formdata,setFormData]=useState({
        email:'',
        password:'',
        error_list:[],
    });
    
    const handleInputChange=(e)=>{
        e.persist();
        setFormData({...formdata,[e.target.name]:e.target.value})
    }

    const [authname,setAuthName]=useState('');
    
    useEffect(()=>{
        if(localStorage.getItem("auth_token")){
            setAuthName('')
            // setRedirect(true);
            // setUrl('/dashboard');
        }
        else{
            setAuthName(localStorage.getItem("auth_name"))
        }
      },[authname])
      
    // if(!redirect){
    //     localStorage.removeItem('auth_token');
    //     localStorage.removeItem('auth_name');
    // }

    

    const authUser= (e)=>{
        e.preventDefault();
        setLoading(true);
        setLoadingRes("")
        setLoadingResOk("")
        const form={
            email:formdata.email,
            password:formdata.password,
        }

        axios.get('/sanctum/csrf-cookie').then(res =>{
            axios.post('/v2/login',form)
            .then(response=>{
                if(response.data.status=== 200){
                    setLoadingResOk("Logged In. Redirecting...")
                    setLoadingRes("")
                    setFormData({...formdata,error_list:[]});
                    localStorage.setItem('auth_token',response.data.token);
                    localStorage.setItem('auth_name',response.data.username);
                    localStorage.setItem('auth_role',response.data.userrole);
                    setLoggedOff(false);
                    // setUrl('/dashboard');

                }
                else if(response.data.status=== 401){
                    if(response.data.message==='Your Account has not been Verified. New Verification Code has been sent to your Email.'){
                        setLoggedOff(true);
                        setVerifyData(response.data)
                        setVerify(true)
                    }
                    else if (response.data.message==='New 2FA Code has been sent to your Email.'){
                        setLoggedOff(true);
                        setVerifyData(response.data)
                        setVerify2FA(true)
                    }
                    else{
                        setLoggedOff(true);
                        setLoadingRes(response.data.message)
                        setLoadingResOk("")
                        setFormData({...formdata,error_list:[]});
                    }
                }
                else if(response.data.status=== 500){
                    setLoadingRes(response.data.message)
                    setLoadingResOk("")
                    setFormData({...formdata,error_list:[]});
                }
                else{
                    setFormData({...formdata,error_list:response.data.errors});
                }
                setLoading(false);

            })
            .catch((error)=>{
                setLoadingRes(""+error)
                setLoadingResOk("")
                setLoading(false);
            })
        })
        .catch((error)=>{
            setLoadingRes(""+error)
            setLoadingResOk("")
            setLoading(false);
        })

    }

    const authVerifyUser= (e)=>{
        e.preventDefault();
        setLoading(true);
        setLoadingRes("")
        setLoadingResOk("")
        const form={
            email:verifydata.email,
            code:formdata.code,
        }
        
     
        axios.get('/sanctum/csrf-cookie').then(res =>{
            axios.post('/v2/account/verify',form)
            .then(response=>{
                if(response.data.status=== 200){
                    setLoadingResOk("Logged In. Redirecting...")
                    setLoadingRes("")
                    setFormData({...formdata,error_list:[]});
                    localStorage.setItem('auth_token',response.data.token);
                    localStorage.setItem('auth_name',response.data.username);
                    localStorage.setItem('auth_role',response.data.userrole);
                    setLoggedOff(false);
                }
                else if(response.data.status=== 401){
                    setLoadingRes(response.data.message)
                    setLoadingResOk("")
                    setFormData({...formdata,error_list:[]});
                }
                else if(response.data.status=== 500){
                    setLoadingRes(response.data.message)
                    setLoadingResOk("")
                    setFormData({...formdata,error_list:[]});
                }
                else{
                    setFormData({...formdata,error_list:response.data.errors});
                }
                setLoading(false);

            })
            .catch((error)=>{
                setLoadingRes(""+error)
                setLoadingResOk("")
                setLoading(false);
            })
        })
        .catch((error)=>{
            setLoadingRes(""+error)
            setLoadingResOk("")
            setLoading(false);
        })

    }

    const authVerify2FA= (e)=>{
        e.preventDefault();
        setLoading(true);
        setLoadingRes("")
        setLoadingResOk("")
        const form={
            email:verifydata.email,
            code:formdata.code,
        }
        
     
        axios.get('/sanctum/csrf-cookie').then(res =>{
            axios.post('/v2/account/2fa',form)
            .then(response=>{
                if(response.data.status=== 200){
                    setLoadingResOk("Logged In. Redirecting...")
                    setLoadingRes("")
                    setFormData({...formdata,error_list:[]});
                    localStorage.setItem('auth_token',response.data.token);
                    localStorage.setItem('auth_name',response.data.username);
                    localStorage.setItem('auth_role',response.data.userrole);
                    setLoggedOff(false);
                }
                else if(response.data.status=== 401){
                    setLoadingRes(response.data.message)
                    setLoadingResOk("")
                    setFormData({...formdata,error_list:[]});
                }
                else if(response.data.status=== 500){
                    setLoadingRes(response.data.message)
                    setLoadingResOk("")
                    setFormData({...formdata,error_list:[]});
                }
                else{
                    setFormData({...formdata,error_list:response.data.errors});
                }
                setLoading(false);

            })
            .catch((error)=>{
                setLoadingRes(""+error)
                setLoadingResOk("")
                setLoading(false);
            })
        })
        .catch((error)=>{
            setLoadingRes(""+error)
            setLoadingResOk("")
            setLoading(false);
        })

    }

    
  return (
    <>
    {/* <HomeNavBar active='login'/> */}
    <main className=' mt-3' style={{"paddingTop":"50px","minHeight": "calc(100vh - 3rem)"}}>
        <div className="">
            <div className="d-flex justify-content-center">
                <div className="mt-4 p-3">
                    {verify &&
                    <div className="card border-none m-2 p-2 ">
                        
                        <h4 className='text-center text-danger'>Verify Account.</h4>
                        {/* <h4 className='text-center text-info'>To Continue, Please Login</h4> */}
                        <div className="card-body row border-none m-2 p-2" style={{"paddingTop": "10px"}}>
                            
                            <h4 className='text-info' style={{"textAlign":"center"}}>{loading ?"":"Enter Verification Code"}</h4>
                            {/* <p className='border-bottom text-center p-2 pb-3 font-bold-700'>Please Enter Verification Code Sent to your Email address.</p> */}
                            <form className='justify-content-center' onSubmit={authVerifyUser}>
                                {!loading && 
                                    <>
                                        <div class="form-group row">
                                            <label for="code" class="col-md-4 col-form-label text-md-right">Verification Code</label>
                                            <div class="col-md-6 ">
                                                <input id="code" type="text" className="form-control" name="code" placeholder="Verification Code" value={formdata.code} onChange={handleInputChange} required autoComplete="code" autoFocus/>
                                                {formdata.error_list && formdata.error_list.code && 
                                                    <span className="help-block text-danger">
                                                        <strong>{formdata.error_list.code}</strong>
                                                    </span>
                                                }

                                            </div>
                                        </div>
                                    </>
                                }

                                <div className="form-group row mb-0 justify-content-center">
                                    
                                    {loadingresok!=='' && 
                                        <div className="col-md-10 elevation-0 mb-2 p-2 text-center border-ok">
                                            <span className="help-block text-success">
                                                <strong>{loadingresok!=='' && loadingresok}</strong>
                                            </span>
                                        </div>
                                    }

                                    {loading && 
                                        <div className="col-md-12 text-center text-white">
                                                
                                                <span className='text-black' style={{"padding": "10px","display":"inline-block"}}>Please Wait ...</span>
                                                <br/>
                                                <Spinner
                                                    as="span"
                                                    variant='info'
                                                    animation="border"
                                                    size="lg"
                                                    role="status"
                                                    aria-hidden="true"
                                                />
                                            <span className='text-info' style={{"padding": "10px","display":"inline-block"}}>Verifying In...</span>
                                            
                                        </div>
                                    }

                                    {!loading && loadingresok ==='' && 
                                        <div className="row text-center m-0 mb-3 p-0">
                                        
                                            <div className="col-12 m-0 p-0">
                                                <button type="submit" className="btn btn-success border-info  pl-4 pr-4">
                                                    Verify Now <i className='fa fa-chevron-right'></i>
                                                </button>
                                            </div>
                                        </div>
                                    }

                                    {loadingres!=='' && 
                                        <div className="col-md-10 elevation-0 mt-2 p-2 text-center border-error">
                                            <span className="help-block text-danger">
                                                <strong>{loadingres!=='' && loadingres}</strong>
                                            </span>
                                        </div>
                                    }

                                    {!loading && loadingresok ==='' && 
                                        <div class="form-group mb-0 pt-3 border-top">
                                            <div class="d-flex justify-content-center">
                                                <button type="button" onClick={()=>{setVerify(!verify)}} className="btn btn-outline-primary border-info  pl-4 pr-4">
                                                <i className='fa fa-chevron-left'></i> Login Again 
                                                </button>
                                            </div>

                                            
                                        </div>
                                        
                                    }
                                    
                                </div>
                            </form>


                        </div>
                    </div>
                    }
                    {verify2fa &&
                    <div className="card border-none m-2 p-2 ">
                        
                        <h4 className='text-center text-danger'>Verify 2FA Login.</h4>
                        {/* <h4 className='text-center text-info'>To Continue, Please Login</h4> */}
                        <div className="card-body row border-none m-2 p-2" style={{"paddingTop": "10px"}}>
                            
                            <h4 className='text-info' style={{"textAlign":"center"}}>{loading ?"":"Enter 2FA Code"}</h4>
                            {/* <p className='border-bottom text-center p-2 pb-3 font-bold-700'>Please Enter Verification Code Sent to your Email address.</p> */}
                            <form className='justify-content-center' onSubmit={authVerify2FA}>
                                {!loading && 
                                    <>
                                        <div class="form-group row">
                                            <label for="code" class="col-md-4 col-form-label text-md-right">2FA Code</label>
                                            <div class="col-md-6 ">
                                                <input id="code" type="text" className="form-control" name="code" placeholder="2FA Code" value={formdata.code} onChange={handleInputChange} required autoComplete="code" autoFocus/>
                                                {formdata.error_list && formdata.error_list.code && 
                                                    <span className="help-block text-danger">
                                                        <strong>{formdata.error_list.code}</strong>
                                                    </span>
                                                }

                                            </div>
                                        </div>
                                    </>
                                }

                                <div className="form-group row mb-0 justify-content-center">
                                    
                                    {loadingresok!=='' && 
                                        <div className="col-md-10 elevation-0 mb-2 p-2 text-center border-ok">
                                            <span className="help-block text-success">
                                                <strong>{loadingresok!=='' && loadingresok}</strong>
                                            </span>
                                        </div>
                                    }

                                    {loading && 
                                        <div className="col-md-12 text-center text-white">
                                                
                                                <span className='text-black' style={{"padding": "10px","display":"inline-block"}}>Please Wait ...</span>
                                                <br/>
                                                <Spinner
                                                    as="span"
                                                    variant='info'
                                                    animation="border"
                                                    size="lg"
                                                    role="status"
                                                    aria-hidden="true"
                                                />
                                            <span className='text-info' style={{"padding": "10px","display":"inline-block"}}>Verifying In...</span>
                                            
                                        </div>
                                    }

                                    {!loading && loadingresok ==='' && 
                                        <div className="row text-center m-0 mb-3 p-0">
                                        
                                            <div className="col-12 m-0 p-0">
                                                <button type="submit" className="btn btn-success border-info  pl-4 pr-4">
                                                    Verify Now <i className='fa fa-chevron-right'></i>
                                                </button>
                                            </div>
                                        </div>
                                    }

                                    {loadingres!=='' && 
                                        <div className="col-md-10 elevation-0 mt-2 p-2 text-center border-error">
                                            <span className="help-block text-danger">
                                                <strong>{loadingres!=='' && loadingres}</strong>
                                            </span>
                                        </div>
                                    }

                                    {!loading && loadingresok ==='' && 
                                        <div class="form-group mb-0 pt-3 border-top">
                                            <div class="d-flex justify-content-center">
                                                <button type="button" onClick={()=>{setVerify2FA(!verify2fa)}} className="btn btn-outline-primary border-info  pl-4 pr-4">
                                                <i className='fa fa-chevron-left'></i> Login Again 
                                                </button>
                                            </div>

                                            
                                        </div>
                                        
                                    }
                                    
                                </div>
                            </form>


                        </div>
                    </div>
                    }
                    {!verify && !verify2fa &&
                    <div className="card border-none m-2 p-2 ">
                        
                        <h4 className='text-center text-danger'>You Have been Signed Out.</h4>
                        {/* <h4 className='text-center text-info'>To Continue, Please Login</h4> */}
                        <div className="card-body row border-none m-2 p-2" style={{"paddingTop": "10px"}}>
                            
                        <h4 className='text-info' style={{"textAlign":"center"}}>{loading ?"":"Login Here"}</h4>
                            <form onSubmit={authUser}>
                                {!loading && 
                                    <>
                                        <div className="form-group row p-1 m-0">
                                            <label htmlFor="Username" className="col-12 col-md-3 col-lg-3 m-0 p-1 col-form-label text-left">Username</label>

                                            <div className="col-12 col-md-9 col-lg-9 m-0 p-1">
                                                <input id="Username" type="text" className="form-control" name="email" placeholder="Username or Email Address" value={formdata.email} onChange={handleInputChange} required autoComplete="Username" autoFocus/>
                                                {formdata.error_list && formdata.error_list.email && 
                                                    <span className="help-block text-danger">
                                                        <strong>{formdata.error_list.email}</strong>
                                                    </span>
                                                }
                                                
                                            </div>
                                        </div>

                                        <div className="form-group row p-1 m-0">
                                            <label htmlFor="password" className="col-12 col-md-3 col-lg-3 m-0 p-1 col-form-label text-left">Password</label>

                                            <div className="col-12 col-md-9 col-lg-9 m-0 p-1">
                                                <input id="password" type="password" className="form-control" name="password" placeholder="Password" value={formdata.password} onChange={handleInputChange} required autoComplete="current-password"/>
                                                {formdata.error_list && formdata.error_list.password && 
                                                    <span className="help-block text-danger">
                                                        <strong>{formdata.error_list.password}</strong>
                                                    </span>
                                                }
                                            </div>
                                        </div>
                                    </>
                                }

                                <div className="form-group row mb-0 justify-content-center">
                                    
                                    {loadingresok!=='' && 
                                        <div className="col-md-10 elevation-0 mb-2 p-2 text-center border-ok">
                                            <span className="help-block text-success">
                                                <strong>{loadingresok!=='' && loadingresok}</strong>
                                            </span>
                                        </div>
                                    }

                                    {loading && 
                                        <div className="col-md-12 text-center text-white">
                                                
                                                <span className='text-black' style={{"padding": "10px","display":"inline-block"}}>Please Wait ...</span>
                                                <br/>
                                                <Spinner
                                                    as="span"
                                                    variant='info'
                                                    animation="border"
                                                    size="lg"
                                                    role="status"
                                                    aria-hidden="true"
                                                />
                                                <span className='text-info' style={{"padding": "10px","display":"inline-block"}}>Signing In...</span>
                                                
                                                
                                             
                                        </div>
                                    }

                                    {!loading && loadingresok ==='' && 
                                        <div className="row text-center m-0 p-0">
                                            
                                            <div className="col-12 m-0 p-0">
                                                <button type="submit" className="btn btn-success border-info">
                                                    Continue <i className='fa fa-chevron-right'></i>
                                                </button>
                                            </div>
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
                            </form>


                        </div>
                    </div>
                    }
                </div>
                
            </div>
        </div>
        
    </main>
    
      
    </>
  );
}

export default ReLogin;