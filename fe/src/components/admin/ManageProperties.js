import DashSideNavBar from './DashSideNavBar';
import DashNavBar from './DashNavBar';
import { useEffect, useContext, useState } from 'react';
import DashFooter from './DashFooter';
import Spinner from 'react-bootstrap/Spinner';
import axios from 'axios';

import { Link, useNavigate,useLocation } from 'react-router-dom';
import { useParams } from 'react-router';

import Swal from 'sweetalert';
import AddProperty from './AddProperty';
import AddHouse from './AddHouse';
import TableSmallSpinner from '../spinners/TableSmallSpinner';
import PropertiesTable from './Tables/PropertiesTable';
import ReLogin from '../home/ReLogin';
import { LoginContext } from '../contexts/LoginContext';



function ManageProperties(props) {
    document.title="Manage Properties";
    const {socket,loggedname, setLoggedName, loggedtoken, setLoggedToken, loggedpermissions, setLoggedPermissions, loggedroles, setLoggedRoles, loggedrole,setLoggedRole,sitedata,setSiteData} =useContext(LoginContext);
         
    const [loggedoff,setLoggedOff]=useState(false);
    const location=useLocation();
    const navigate=useNavigate();

    let par=useParams()

    const [id,setID]=useState((par.id)?par.id:'')

    // console.log(id)

    const [closed,setClosed]=useState(false)

    const [waterbillpropertyid,setWaterbillPropertyId]=useState([""]);
    
    
    const [propertydata, setPropertydata] = useState([]);
    const [housedata, setHousedata] = useState([]);
    
    const [currentwaterbill, setCurrentWaterbill] = useState([""]);
    const [currentproperty, setCurrentProperty] = useState([""]);
    const [currenthouse, setCurrentHouse] = useState('');
    

    const [search,setSearch]=useState({
        value:'',
        result:[]
    })

    
    const [show,setShow]=useState(false);
    const [showaddproperty,setShowAddProperty]=useState(false);
    
    const [showaddhouse,setShowAddHouse]=useState(false);
    

   

    const [loadingmonths,setLoadingMonths]=useState(true);
  
    

    const [loading,setLoading]=useState(false);

    
    // useEffect(()=>{
    //     let doneloading=true;
    //     const getPrevMonths = async (e) => { 
    //         const arr = [];
    //             arr.push({value: '', label: 'Select Month' });
    //         const arr1 = [];
    //             arr1.push({value: '', label: 'Select Property' });
    //         const arr2 = [];    
    //         let url=`/v2/properties/manage/load/${id}`;
    //         if(id===''){
    //             url='/v2/properties/manage/load';
    //         }
    //         else{
    //             if(id==='all'){
    //                 url=`/v2/properties/manage/load`;
    //             }
    //             else{
    //                 setLoadingMonths(false)
    //                 return false;
    //             }
                
    //         }
    //         await axios.get(url)
    //         .then(response=>{
    //             if (doneloading) {
    //                 if(response.data.status=== 200){
    //                     let respropertyinfo = response.data.propertyinfo;
                        
    //                     let resthisproperty = response.data.thisproperty;

    //                     setPropertydata(response.data.propertyinfo)
    //                     setHousedata([])
                        
    //                     let options=[];
    //                     if(id!==''){
    //                         options={value: resthisproperty.id, label: resthisproperty.Plotname+'('+resthisproperty.Plotcode+')' , data: resthisproperty}
    //                     }
                        
    //                     setWaterbillPropertyId(options)
    //                 }
    //                 else if(response.data.status=== 401){
    //                     Swal("Error",response.data.message,"error");
    //                 }
    //                 else if(response.data.status=== 500){
    //                     Swal("Error",response.data.message,"error");
    //                 }
                    
    //             }
    //         })
    //         .catch(error=>{
    //             Swal("Error",""+error,"error");
    //         })
    //     };
    //     getPrevMonths();

    //     return ()=>{
    //         doneloading=false;
            
    //         setLoadingMonths(false)
    //     }
    // },[])
        // useEffect( () =>{
        //     socket.on('load_credit_balance', (msg) =>{
        //         loadAppData();
        //     })
    
        // }, []);
    

    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingMonths(true)
        }
        const getPrevMonths = async (e) => { 
            const arr = [];
                arr.push({value: '', label: 'Select Month' });
            const arr1 = [];
                arr1.push({value: '', label: 'Select Property' });
            const arr2 = [];    
            let url=`/v2/properties/manage/load/${id}`;
            if(id===''){
                url='/v2/properties/manage/load';
            }
            else{
                // if(id==='all'){
                //     url=`/v2/properties/manage/load`;
                // }
                // else{
                    setLoadingMonths(false)
                    return false;
                // }
                
            }
            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let respropertyinfo = response.data.propertyinfo;
                        
                        let resthisproperty = response.data.thisproperty;

                        setPropertydata(response.data.propertyinfo)
                        setHousedata([])

                        let options=[];
                        if(id!==''){
                            options={value: resthisproperty.id, label: resthisproperty.Plotname+'('+resthisproperty.Plotcode+')' , data: resthisproperty}
                        }
                        
                        setWaterbillPropertyId(options)
                        setLoadingMonths(false)
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
                    }
                    else{
                        setLoadingMonths(false)
                    }
                    setLoadingMonths(false)
                }
            })
            .catch(error=>{

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
                    setLoadingMonths(false)
                }
                else{
                    let ex=error['response'].data.message;
                    if(ex==='Unauthenticated.'){
                        setLoggedOff(true); 
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_name');
                    }
                    else{
                        setLoadingMonths(false)
                        Swal("Error",""+error,"error");
                    }
                }
                
            })
        };
        getPrevMonths();

        return ()=>{
            doneloading=false;
            
            setLoadingMonths(false)
        }
    },[loggedoff])

    const loadProperties = () => {
        
            let doneloading=true;
            if (doneloading) {
                setLoadingMonths(true)
            }
            const getPrevMonths = async (e) => { 
                const arr = [];
                    arr.push({value: '', label: 'Select Month' });
                const arr1 = [];
                    arr1.push({value: '', label: 'Select Property' });
                const arr2 = [];    
                let url=`/v2/properties/manage/load/${id}`;
                if(id===''){
                    url='/v2/properties/manage/load';
                }
                else{
                    // if(id==='all'){
                    //     url=`/v2/properties/manage/load`;
                    // }
                    // else{
                        setLoadingMonths(false)
                        return false;
                    // }
                    
                }
                await axios.get(url)
                .then(response=>{
                    if (doneloading) {
                        if(response.data.status=== 200){
                            let respropertyinfo = response.data.propertyinfo;
                            
                            let resthisproperty = response.data.thisproperty;
    
                            setPropertydata(response.data.propertyinfo)
                            setHousedata([])
    
                            let options=[];
                            if(id!==''){
                                options={value: resthisproperty.id, label: resthisproperty.Plotname+'('+resthisproperty.Plotcode+')' , data: resthisproperty}
                            }
                            
                            setWaterbillPropertyId(options)
                            setLoadingMonths(false)
                        }
                        else if(response.data.status=== 401){
                            setLoggedOff(true);    
                            Swal("Error",response.data.message,"error");
                        }
                        else if(response.data.status=== 500){
                            Swal("Error",response.data.message,"error");
                        }
                        else{
                            setLoadingMonths(false)
                        }
                        setLoadingMonths(false)
                    }
                })
                .catch(error=>{
    
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
                        setLoadingMonths(false)
                    }
                    else{
                        let ex=error['response'].data.message;
                        if(ex==='Unauthenticated.'){
                            setLoggedOff(true); 
                            localStorage.removeItem('auth_token');
                            localStorage.removeItem('auth_name');
                        }
                        else{
                            setLoadingMonths(false)
                            Swal("Error",""+error,"error");
                        }
                    }
                    
                })
            };
            getPrevMonths();
    }

    useEffect(()=>{
        // if(id==='all'){
        //     let thisurl=`/properties/manage`;
        //     navigate(thisurl)
        // }
        // else{
            if(id!==''){
                let thisurl=`/properties/manage/${id}`;
                navigate(thisurl)
            }
        // }
       
    },[id,location.pathname])

    

    const handleShowAddProperty = (property) => {
        setShowAddProperty(true);
        setCurrentProperty(property)
    };

    const handleCloseAddProperty = () => {
        setShowAddProperty(false);
        document.title="Manage Properties";
    };

    const handleShowAddHouse = (property) => {
        setShowAddHouse(true);
        setCurrentProperty(property)
        setCurrentHouse('')
    };

    const handleCloseAddHouse = () => {
        setShowAddHouse(false);
        document.title="Manage Properties";
    };


    function handlePropertyChange(val) {
        setLoadingMonths(true)
        setID(val.value)
        let options={value: val.value, label: val.label , data: val}
        setWaterbillPropertyId(options) 
        setLoadingMonths(false)
    }

    const handleSearchChange =(e) =>{
        setLoadingMonths(true)
        if(propertydata.length>0){
            const results=propertydata.filter(property =>{
                if(e.target.value=== '') return propertydata
                return property.Plotname.toLowerCase().includes(e.target.value.toLowerCase()) || property.Plotcode.toLowerCase().includes(e.target.value.toLowerCase()) || property.Waterbill.toLowerCase().includes(e.target.value.toLowerCase())
            })
            setSearch({
                value:e.target.value,
                result:results
            })
        }
        setLoadingMonths(false)
    }


    const deleteProperty= (property)=>{
        const form={
            id:property.id,
        }

        let title='Delete '+property.Plotname;
        let text="This will remove this property from the system.";
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
                axios.post('/v2/delete/property/save',form)
                .then(response=>{
                    if(response.data.status=== 200){
                        Swal("Success",response.data.message);
                        loadProperties();
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
    <div className="wrapper">
        {loggedoff ? 
            <ReLogin setLoggedOff={setLoggedOff} loggedoff={loggedoff} />
        :
        <>
        <DashNavBar setClosed={setClosed} closed={closed} active='manage'/>
        <DashSideNavBar setClosed={setClosed} closed={closed} active='manage'/>
        {/* className={`nav-link ${active==='home'?'active':''}`} */}
        
        <main className="py-1">
            <div className={`content-wrapper ${closed?'closed':''}`}>

                    <section className="content">
                    <div className="p-2">
                        {/* container class */}
                        <div className="row justify-content-center m-0 p-0">
                        <div className="col-12 m-0 p-0 ">
                            <ol className="breadcrumb float-sm-right text-xs m-0 p-1">
                                <li className="breadcrumb-item"><Link to={'/dashboard'}>Dashboard</Link></li>
                                <li className="breadcrumb-item active">{ !loadingmonths && waterbillpropertyid.label } Properties </li>
                            </ol>
                        </div>
                
                        <div className="col-12">
                            <div className="row m-0 p-0">

                                <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                    <div className="card border-info m-0 p-0" >
                                        <div className="card-header text-white elevation-2 m-0 p-0">
            
                                            <div className='row justify-content-center text-center p-1 m-0'>
                                                

                                                <div className="col-12 text-xs float-right m-0 p-0">
                                                    {loadingmonths &&
                                                        <Spinner  variant="blue" size="md" role="status"></Spinner>
                                                    }
                                                   
                                                    <button className='btn btn-success border-info m-1 p-1 pl-2 pr-2' onClick={()=>{handleShowAddProperty('')}}><small><i className='fa fa-plus-circle'></i> New Property</small></button>
                                                    {propertydata  && propertydata.length>0 && <input onChange={handleSearchChange} value={search.value} className='border-info p-2 pt-0 pb-0 col-6' placeholder='Find Property Name, Code, Waterbill Status, etc ' />}
                                                    
                                                </div>
                                            </div>
                                            
                                        </div>

                                        <div className="card-body text-center m-0 p-1">
                                        
                                            <div className="row m-0 p-0">
                                                 {loadingmonths &&
                                                    <div className="col-12 text-left m-0 p-1 mt-1 mb-2">
                                                        <TableSmallSpinner />
                                                    </div>
                                                 }
                                                 {!loadingmonths &&
                                                    <div className="tableinfo col-12 m-0 p-0" style={{"overflowX":"auto"}}>
                                                        <table border="1" className="table table-hover table-bordered text-xs" id="example1">
                                                            {propertydata  && propertydata.length>0 &&
                                                                <thead  >
                                                                <tr  style={{"color":"white","backgroundColor":"#77B5ED"}}>
                                                                    <th className='elevation-2 m-0 p-1'>Sno</th>
                                                                    <th className='elevation-2 m-0 p-1'>Property Name</th>
                                                                    <th className='elevation-2 m-0 p-1'>Code</th>
                                                                    <th className='elevation-2 m-0 p-1'>Type</th>
                                                                    {/* <th className='elevation-2 m-0 p-1'>Location</th>
                                                                    <th className='elevation-2 m-0 p-1'>Address</th>
                                                                    <th className='elevation-2 m-0 p-1'>Description</th> */}
                                                                    <th className='elevation-2 m-0 p-1'>Water</th>
                                                                    {/* <th className='elevation-2 m-0 p-1'>Deposit</th>
                                                                    <th className='elevation-2 m-0 p-1'>WDeposit</th>
                                                                    <th className='elevation-2 m-0 p-1'>Others</th>
                                                                    <th className='elevation-2 m-0 p-1'>Garbage</th>
                                                                    <th className='elevation-2 m-0 p-1'>KPLCD</th> */}
                                                                    <th className='elevation-2 m-0 p-1'>Houses</th>
                                                                    <th className='elevation-2 m-0 p-1'>Occupied</th>
                                                                    {/* <th className='elevation-2 m-0 p-1'>Tenants</th> */}
                                                                    <th className='elevation-2 m-0 p-1'>Action</th>
                                                                </tr></thead>
                                                            }
                                                            
                                                            <tbody>
                                                                {propertydata  && propertydata.length>0 &&
                                                                    <>
                                                                        {(search.value==='')?
                                                                        <>
                                                                            {propertydata  && propertydata.map((property,key) => (
                                                                                <PropertiesTable property={property} key={key} no={key} handleShowAddHouse={handleShowAddHouse} handleShowAddProperty={handleShowAddProperty} deleteProperty={deleteProperty} />
                                                                            ))
                                                                            }
                                                                        </>
                                                                    :
                                                                        <>
                                                                            {search.result  && search.result.map((property,key) => (
                                                                                <PropertiesTable property={property} key={key} no={key} handleShowAddHouse={handleShowAddHouse} handleShowAddProperty={handleShowAddProperty} deleteProperty={deleteProperty} />
                                                                            ))
                                                                            }
                                                                        </>
                                                                    
                                                                    }
                                                                    </>
                                                                }
                                                                
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                 }
                                            </div>

                                        </div>

                                        

                                    </div>
                                </div>

                                


                                {showaddproperty && 
                                    <AddProperty showaddproperty={showaddproperty} handleCloseAddProperty={handleCloseAddProperty} currentproperty={currentproperty} loadProperties={loadProperties}/>
                                }

                                {showaddhouse && 
                                    <AddHouse showaddhouse={showaddhouse} handleCloseAddHouse={handleCloseAddHouse} currentproperty={currentproperty} currenthouse={currenthouse}/>
                                }

                                
                            </div>
                        </div>

                        
                            
                        </div>

                    </div>


                </section>
            </div>
        </main>


        <DashFooter />
        </>
        }
      </div>
    </>
  );
}

export default ManageProperties;