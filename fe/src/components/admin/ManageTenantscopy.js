import DashSideNavBar from './DashSideNavBar';
import DashNavBar from './DashNavBar';
import { useEffect, useState } from 'react';
import DashFooter from './DashFooter';
import Spinner from 'react-bootstrap/Spinner';
import axios from 'axios';

import {useNavigate} from 'react-router-dom';
import { useParams } from 'react-router';

import Swal from 'sweetalert';

import AddHouse from './AddHouse';
import TableSmallSpinner from '../spinners/TableSmallSpinner';

import TenantsTable from './Tables/TenantsTable';
import ManagePropertyHseTenant from './ManagePropertyHseTenant';
import AddTenant from './AddTenant';
import ManagePropertyVacateHseTenant from './ManagePropertyVacateHseTenant';



function ManageTenants(props) {
    document.title="Manage Tenants";
    
    const navigate=useNavigate();

    let par=useParams()

    const [id,setID]=useState((par.id)?par.id:'')

    // console.log(id)

    const [closed,setClosed]=useState(false)

    const [waterbillpropertyid,setWaterbillPropertyId]=useState([""]);
    
    
    const [propertydata, setPropertydata] = useState([]);
    const [housedata, setHousedata] = useState([]);
    
    const [currentproperty, setCurrentProperty] = useState('');
    const [currenttenant, setCurrentTenant] = useState('');
    const [currenthouse, setCurrentHouse] = useState('');
    

    const [search,setSearch]=useState({
        value:'',
        result:[]
    })

    
    const [showvacatehouse,setShowVacateHouse]=useState(false);
    const [showaddproperty,setShowAddProperty]=useState(false);
    const [showaddtenant,setShowAddTenant]=useState(false);
    
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
    //         let url=`/api/properties/manage/load/${id}`;
    //         if(id===''){
    //             url='/api/properties/manage/load';
    //         }
    //         else{
    //             if(id==='all'){
    //                 url=`/api/properties/manage/load`;
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
            let url=`/api/properties/mgr/tenants/load/${id}`;
            if(id===''){
                url='/api/properties/mgr/tenants/load';
            }
            else{
                if(id==='all'){
                    url=`/api/properties/mgr/tenants/load`;
                }
                else{
                    setLoadingMonths(false)
                    return false;
                }
                
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
                Swal("Error",""+error,"error");
                setLoadingMonths(false)
            })
        };
        getPrevMonths();

        return ()=>{
            doneloading=false;
            
            setLoadingMonths(false)
        }
    },[])

    // properties/mgr/tenants
    useEffect(()=>{
        if(id==='all'){
            let thisurl=`/properties/mgr/tenants`;
            navigate(thisurl)
        }
        else{
            if(id!==''){
                let thisurl=`/properties/mgr/tenants/${id}`;
                navigate(thisurl)
            }
        }
       
    },[id])

    const handleShowVacateHouse = (property) => {
        setShowVacateHouse(true);
        setCurrentProperty(property)
    };

    const handleCloseVacateHouse = () => {
        setShowVacateHouse(false);
        document.title="Vacate Tenant ";
    };

    const handleShowAddProperty = (property) => {
        setShowAddProperty(true);
        setCurrentProperty(property)
    };

    const handleCloseAddProperty = () => {
        setShowAddProperty(false);
        document.title="Manage Properties";
    };

    const handleShowAddTenant = (tenant) => {
        setShowAddTenant(true);
        setCurrentTenant(tenant)
    };

    const handleCloseAddTenant = () => {
        setShowAddTenant(false);
        document.title="Manage Tenants";
    };
    

    // const handleShowAddHouse = (property) => {
    //     setShowAddHouse(true);
    //     setCurrentProperty(property)
    //     setCurrentHouse('')
    // };

    const handleShowAddHouse = (property) => {
        setShowAddHouse(true);
        setCurrentHouse(property)
    };

    const handleCloseAddHouse = () => {
        setShowAddHouse(false);
        document.title="Manage House";
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
            const results=propertydata.filter(property =>{
                if(e.target.value=== '') return propertydata
                const value_array=e.target.value.split(':');
                if(value_array.length > 1){
                    let lbl=value_array[0];
                    let vals=value_array[1];
                    if(vals=== '') return propertydata
                    if(lbl==='fname') return property.Fname.toLowerCase().includes(vals.toLowerCase())
                    else if(lbl==='oname') return property.Oname.toLowerCase().includes(vals.toLowerCase())
                    else if(lbl==='phone') return property.PhoneMasked.toLowerCase().includes(vals.toLowerCase())
                    else if(lbl==='idno') return property.IDno.toLowerCase().includes(vals.toLowerCase())
                    else if(lbl==='status') return property.Status.includes(vals)
                    else if(lbl==='house') return property.Housenames.toLowerCase().includes(vals.toLowerCase())
                    else if(lbl==='totalhouses') return property.Houses.toString().toLowerCase()===vals.toLowerCase()
                }
                else{
                    return property.Fname.toLowerCase().includes(e.target.value.toLowerCase()) ||
                        property.Oname.toLowerCase().includes(e.target.value.toLowerCase()) ||
                        property.PhoneMasked.toLowerCase().includes(e.target.value.toLowerCase()) ||
                        property.IDno.toLowerCase().includes(e.target.value.toLowerCase()) ||
                        property.Status.includes(e.target.value) ||
                        property.Housenames.toLowerCase().includes(e.target.value.toLowerCase())
                }
                
            })

            setSearch({
                value:e.target.value,
                result:results
            })
        setLoadingMonths(false)
    }


    const deleteTenant= (property)=>{
        const form={
            id:property.id,
        }

        let title='Delete '+property.Fname+' '+property.Oname;
        let text="This will remove this Tenant from the system.";
        Swal({
            title:title+' ?',
            text:text,
            buttons:true,
            infoMode:true,
        })
        .then((willcontinue) =>{
            if(willcontinue){
                setLoading(true);
                axios.post('/api/delete/tenant/save',form)
                .then(response=>{
                    if(response.data.status=== 200){
                        Swal("Success",response.data.message);
                    }
                    else if(response.data.status=== 401){
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
                    }
                    setLoading(false);
                })
                .catch((error)=>{
                    Swal("Error",""+error,"error");
                    setLoading(false);
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
        <DashNavBar setClosed={setClosed} closed={closed} active='tenant'/>
        <DashSideNavBar setClosed={setClosed} closed={closed} active='tenant'/>
        {/* className={`nav-link ${active==='home'?'active':''}`} */}
        
        <main className="py-3">
            <div className={`content-wrapper ${closed?'closed':''}`}>

                <section className="content">
                    <div className="container">
                        <div className="row justify-content-center">


                        <div className="col-12">
                            <div className="row m-0 p-0">

                                <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                    <div className="card border-info m-0 p-0" >
                                        <div className="card-header bg-info text-white elevation-2 m-0 p-0">
            
                                            <div className='row justify-content-center text-center p-1 m-0'>
                                                

                                                <div className="col-12 text-xs float-right m-0 p-0">
                                                    {loadingmonths &&
                                                        <Spinner  variant="light" size="md" role="status"></Spinner>
                                                    }
                                                     
                                                    <button className='btn btn-success border-info m-1 p-1 pl-2 pr-2' onClick={()=>{handleShowAddTenant('')}}><small><i className='fa fa-plus-circle'></i> Tenant</small></button>
                                                    {propertydata  && propertydata.length>0 && <input onChange={handleSearchChange} value={search.value} className='border-info p-1 pt-0 pb-0 col-6' placeholder='Find Fname, Oname, Property Code,Phone,Status etc' />}
                                                    <p>Find using:: fname:value, oname:value, phone:value, idno:value, status:value, house:value, totalhouses:value</p>
                                                    
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
                                                                <thead>
                                                                <tr  style={{"color":"white","backgroundColor":"#77B5ED"}}>
                                                                    <th className='elevation-2 m-0 p-1'>Sno</th>
                                                                    <th className='elevation-2 m-0 p-1'>Firstname</th>
                                                                    <th className='elevation-2 m-0 p-1'>Oname</th>
                                                                    <th className='elevation-2 m-0 p-1'>Gender</th>
                                                                    <th className='elevation-2 m-0 p-1'>IDno</th>
                                                                    <th className='elevation-2 m-0 p-1'>Phone</th>
                                                                    <th className='elevation-2 m-0 p-1'>Status</th>
                                                                    <th className='elevation-2 m-0 p-1'>Total</th>
                                                                    <th className='elevation-2 m-0 p-1'>House</th>
                                                                    <th className='elevation-2 m-0 p-1'>Action</th>
                                                                </tr></thead>
                                                            }
                                                            
                                                            <tbody>
                                                                {propertydata  && propertydata.length>0 &&
                                                                    <>
                                                                        {(search.value==='')?
                                                                        <>
                                                                            {propertydata  && propertydata.map((property,key) => (
                                                                                <TenantsTable property={property} key={key} no={key} handleShowAddTenant={handleShowAddTenant} handleShowAddProperty={handleShowAddProperty} deleteTenant={deleteTenant} />
                                                                            ))
                                                                            }
                                                                        </>
                                                                    :
                                                                        <>
                                                                            {search.result  && search.result.map((property,key) => (
                                                                                <TenantsTable property={property} key={key} no={key} handleShowAddTenant={handleShowAddTenant} handleShowAddProperty={handleShowAddProperty} deleteTenant={deleteTenant} />
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

                                
                                {showvacatehouse && 
                                    <ManagePropertyVacateHseTenant showvacatehouse={showvacatehouse} handleCloseVacateHouse={handleCloseVacateHouse} handleShowAddHouse={handleShowAddHouse} currentproperty={currentproperty} />
                                }


                                {showaddproperty && 
                                    <ManagePropertyHseTenant showaddproperty={showaddproperty} handleCloseAddProperty={handleCloseAddProperty} handleShowAddHouse={handleShowAddHouse} handleShowVacateHouse={handleShowVacateHouse} currentproperty={currentproperty} />
                                }

                                {showaddhouse && 
                                    <AddHouse showaddhouse={showaddhouse} handleCloseAddHouse={handleCloseAddHouse} currentproperty={currentproperty} currenthouse={currenthouse}/>
                                }

                                {showaddtenant && 
                                    <AddTenant showaddtenant={showaddtenant} handleCloseAddTenant={handleCloseAddTenant} currenttenant={currenttenant}/>
                                }

                                
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

export default ManageTenants;