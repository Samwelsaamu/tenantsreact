import Button from 'react-bootstrap/Button';
import HomeFooter from './HomeFooter';
import HomeNavBar from './HomeNavBar';
import axios from 'axios';
import { useEffect, useState } from 'react';
import {Link, Navigate} from 'react-router-dom';
import Spinner from 'react-bootstrap/Spinner';
import Swal from 'sweetalert';

function PasswordReset() {

    document.title="Reset Password";
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
        code:'',
        password:'',
        confirmpassword:'',
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
            setRedirect(true);
            setUrl('/dashboard');
        }
        else{
            setAuthName(localStorage.getItem("auth_name"))
        }
      },[authname])
      
    if(!redirect){
        localStorage.removeItem('auth_token');
        localStorage.removeItem('auth_name');
    }

    

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
            axios.post('/v2/password/reset',form)
            .then(response=>{
                if(response.data.status=== 200){
                    setVerifyData(response.data)
                    setVerify(true)
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

    const authVerifyUser= (e)=>{
        e.preventDefault();

        if((verifydata.email).trim() ===''){
            Swal("Email Address is Required","Please Enter Your Email Address","error");
            return;
        }

        if(isNaN(formdata.code)){
            Swal("Code Must be a Numeric Value","Please Enter Numeric digits for Reset Code","error");
            return;
        }

        if(((formdata.code).trim()).length !== 6){
            Swal("Reset Code Must be 6 Digits","Please Enter six Reset Code digits","error");
            return;
        }

        if((formdata.password).trim() ===''){
            Swal("New Password is Required","Please Enter New Password","error");
            return;
        }

        if((formdata.confirmpassword).trim() ===''){
            Swal("Confirm New Password is Required","Please Enter Confirm New Password","error");
            return;
        }

        if((formdata.password).trim() !== (formdata.confirmpassword).trim()){
            Swal("Password Must Match","Please Enter Matching password","error");
            return;
        }
        

        setLoading(true);
        setLoadingRes("")
        setLoadingResOk("")

        const form={
            email:verifydata.email,
            password:formdata.password,
            code:formdata.code,
        }
        
     
        axios.get('/sanctum/csrf-cookie').then(res =>{
            axios.post('/v2/password/verify',form)
            .then(response=>{
                if(response.data.status=== 200){
                    setLoadingResOk("Logged In. Redirecting...")
                    setVerifyData([])
                    setVerify(false)
                    setLoadingRes("")
                    setFormData({...formdata,error_list:[]});
                    localStorage.setItem('auth_token',response.data.token);
                    localStorage.setItem('auth_name',response.data.username);
                    localStorage.setItem('auth_role',response.data.userrole);
                    setRedirect(true);
                    setUrl('/dashboard');

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


    

    if(redirect){
        return <Navigate to={url} />
    }
  return (
    <>
    <HomeNavBar active='login'/>
    
    <main className=' mt-3' style={{"paddingTop":"50px","minHeight": "calc(100vh - 3rem)"}}>
    <div class="container mt-3">
        <div class="row justify-content-center mt-3">
            <div class="col-md-8 mt-3">
                {verify ?
                    <div class="card border-white-b">
                    <div class="card-header bg-light text-center text-lg">Change Password</div>

                    <div class="card-body">
                       
                    <p className='border-bottom text-center p-2 pb-3 font-bold-700'>Please Enter Reset Code Sent to your Email address and New Password.</p>
                        <form className='justify-content-center' onSubmit={authVerifyUser}>
                            {!loading && 
                                <>
                                    <div class="form-group row">
                                        <label for="code" class="col-md-4 col-form-label text-md-right">Reset Code</label>
                                        <div class="col-md-6 ">
                                            <input id="code" type="text" className="form-control" name="code" placeholder="Reset Code" value={formdata.code} onChange={handleInputChange} required autoComplete="code" autoFocus/>
                                            {formdata.error_list && formdata.error_list.code && 
                                                <span className="help-block text-danger">
                                                    <strong>{formdata.error_list.code}</strong>
                                                </span>
                                            }

                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" className="form-control @error('password') is-invalid @enderror" name="password" placeholder="New Password" value={formdata.password} onChange={handleInputChange} required autoComplete="current-password"/>
                                            {formdata.error_list && formdata.error_list.password && 
                                                <span className="help-block text-danger">
                                                    <strong>{formdata.error_list.password}</strong>
                                                </span>
                                            }
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="confirmpassword" class="col-md-4 col-form-label text-md-right">Confirm New Password</label>

                                        <div class="col-md-6">
                                            <input id="confirmpassword" type="password" className="form-control @error('confirmpassword') is-invalid @enderror" name="confirmpassword" placeholder="Confirm New Password" value={formdata.confirmpassword} onChange={handleInputChange} required/>
                                            
                                        </div>
                                    </div>

                                </>
                            }

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
                                <div className="col-md-12 elevation-0 mt-2 p-2 text-center border-none">
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

                                    

                        </form>
                    </div>
                </div>
                :
                <div class="card border-white-b">
                    <div class="card-header bg-light text-center text-lg">Reset Password</div>

                    <div class="card-body">
                       
                        <form className='justify-content-center' onSubmit={authUser}>
                            {!loading && 
                                <>
                                    <div class="form-group row">
                                        <label for="username" class="col-md-4 col-form-label text-md-right">Username or Email</label>
                                        <div class="col-md-6 ">
                                            <input id="Username" type="text" className="form-control" name="email" placeholder="Username or Email Address" value={formdata.email} onChange={handleInputChange} required autoComplete="Username" autoFocus/>
                                            {formdata.error_list && formdata.error_list.email && 
                                                <span className="help-block text-danger">
                                                    <strong>{formdata.error_list.email}</strong>
                                                </span>
                                            }

                                        </div>
                                    </div>

                                </>
                            }

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
                                        <span className='text-info' style={{"padding": "10px","display":"inline-block"}}>Sending Code In...</span>
                                         
                                </div>
                            }

                            {!loading && loadingresok ==='' && 
                                <div className="row text-center m-0 mb-3 p-0">
                                        
                                    <div className="col-12 m-0 p-0">
                                        <button type="submit" className="btn btn-outline-primary border-info  pl-4 pr-4">
                                            Send Code <i className='fa fa-chevron-right'></i>
                                        </button>
                                    </div>
                                </div>
                            }

                            {loadingres!=='' && 
                                <div className="col-md-12 elevation-0 mt-2 p-2 text-center border-none">
                                    <span className="help-block text-danger">
                                        <strong>{loadingres!=='' && loadingres}</strong>
                                    </span>
                                </div>
                            }

                            {!loading && loadingresok ==='' && 
                                <div class="form-group mb-0 pt-3">
                                    <div class="d-flex justify-content-center">
                                        <Link className="btn btn-link " to="/login"> 
                                            Login?
                                        </Link>
                                    </div>

                                    
                                </div>
                                
                            }

                                    

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

export default PasswordReset