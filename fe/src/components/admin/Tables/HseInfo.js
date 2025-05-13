import Modal from 'react-bootstrap/Modal';

import logo from '../../assets/img/wagitonga1.png'
import axios from 'axios';


import DashSideNavBar from './DashSideNavBar';
import DashNavBar from './DashNavBar';
import { useEffect, useMemo, useState } from 'react';
import DashFooter from './DashFooter';
import Spinner from 'react-bootstrap/Spinner';

import {Link, Navigate, useNavigate} from 'react-router-dom';
import { useParams } from 'react-router';

import Swal from 'sweetalert';
import AddWaterbill from './AddWaterbill';

import avatar from '../../assets/img/avatar5.png'
import avatar3 from '../../assets/img/avatar2.png'
import AddHouse from './AddHouse';
import HouseDetailsSpinner from '../spinners/HouseDetailsSpinner';
import HouseTenantDetailsSpinner from '../spinners/HouseTenantDetailsSpinner';
import ReLogin from '../../home/ReLogin';



function HseInfo({currenthouse,showaddproperty,handleCloseAddProperty}) {
    // console.log(currenthouse)

    const [loggedoff,setLoggedOff]=useState(false);
    const [plot,setPlotID]=useState((currenthouse===undefined)?'':currenthouse.pid)
    const [id,setID]=useState((currenthouse===undefined)?'':currenthouse.hid)

    // console.log(plot,id)


    const [waterbillpropertyid,setWaterbillPropertyId]=useState([""]);
    const [propertyinfo, setPropertyinfo] = useState([""]);
    
    const [houseinfo, setHouseinfo] = useState([""]);
    
    const [propertydata, setPropertydata] = useState([]);
    const [housedata, setHousedata] = useState([]);
    const [agreementdata, setAgreementdata] = useState([]);
    
    const [currentwaterbill, setCurrentWaterbill] = useState([""]);

    const [currentproperty, setCurrentProperty] = useState([""]);
    


    const [show,setShow]=useState(false);
   

    // const [loading,setLoading]=useState(true);
    const [loadingmonths,setLoadingMonths]=useState(true);
    

    const [loading,setLoading]=useState(false);

    

    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingMonths(true)
        }
        const getProperties = async (e) => { 
            const arr = [];
                arr.push({value: '', label: 'Select House' });
            const arr1 = [];
                arr1.push({value: '', label: 'Select Property' });
            let url=`/api/properties/house/${plot}/${id}`;
            

            if(id===''){
                setLoadingMonths(false)
                return false;
            }
            else{
                if(id==='all'){
                    url=`/api/properties/manage/load`;
                }
                else{
                    url=`/api/properties/house/${plot}/${id}`;
                }
            }


            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let respropertyinfo = response.data.propertyinfo;
                        
                        let reshouseinfo = response.data.propertyhouses;

                        let resthisproperty = response.data.thisproperty;
                        setCurrentProperty(response.data.thisproperty);

                        respropertyinfo.map((monthsup) => {
                            return arr1.push({value: monthsup.id, label: monthsup.Plotname , data: monthsup});
                        });
                        setPropertyinfo(arr1)

                        reshouseinfo.map((houseup) => {
                            return arr.push({value: houseup.id, label: houseup.Housename+'('+houseup.Status+')' , data: houseup});
                        });
                        setHouseinfo(arr)

                        setHousedata(response.data.thishouse)
                        setAgreementdata(response.data.agreementinfo);
                        setPropertydata([])
                        // setWaterbillData(response.data.waterbilldata);
                        
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
        getProperties();

        return ()=>{
            doneloading=false;
        }
    },[id,loggedoff])


   

    const handleShow = (waterbill) => {
        setShow(true);
        setCurrentWaterbill(waterbill)
    };


    const deleteHouse= (house)=>{
        const form={
            id:house.id,
        }

        let title='Delete '+house.Housename;
        let text="This will remove this House from the Property.";
        Swal({
            title:title+' ?',
            text:text,
            buttons:true,
            infoMode:true,
        })
        .then((willcontinue) =>{
            if(willcontinue){
                setLoading(true);
                axios.post('/api/delete/house/save',form)
                .then(response=>{
                    if(response.data.status=== 200){
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
    
        <Modal size='lg' show={showaddproperty} onHide={handleCloseAddProperty} className='text-sm'>
        
            <Modal.Header className='justify-content-center bg-warning m-0 p-2' closeButton>
                <Modal.Title className='mx-auto text-dark'> 
                    <h5>View House : {currenthouse !==undefined && currenthouse.Housename}</h5>
                </Modal.Title>
            </Modal.Header>
            
            <Modal.Body className='m-0 p-0'>
                    {loggedoff ? 
                        <ReLogin setLoggedOff={setLoggedOff} loggedoff={loggedoff} />
                    :
                    <div className="row m-0 p-0 justify-content-center text-center border-none">
                    <div className="row m-0 p-0" style={{"overflowX":"auto"}}>
                        {loadingmonths &&
                            <div className="col-12 col-md-6 col-lg-4 text-left m-0 p-1 mt-1 mb-2">
                                <HouseDetailsSpinner />
                            </div>
                        }
                        {!loadingmonths && 
                        <div className="col-12 col-lg-6 text-left m-0 p-1 mt-1 mb-2">
                            <div className="card border-info m-2 p-1" >
                                <div className="card-header text-muted text-center m-0 p-0 pt-1 pb-2">
                                    <span style={{"float":"left"}}>
                                        <span className={`m-0 p-1 text-sm bg-light ${housedata.Status==='Occupied'?'text-info':'text-danger'}`}
                                            style={{"borderRadius":"30px","border":"2px solid white"}}> {housedata.tenantname}</span> 
                                    </span> 
                                    <span className='m-0 p-1 text-sm text-dark mx-auto'>{housedata.Housename}</span>
                                    <span style={{"float":"right"}}>
                                        <span className={`m-0 p-1 text-sm bg-light ${housedata.Status==='Occupied'?'text-success':'text-danger'}`}
                                            style={{"borderRadius":"30px","border":"2px solid white"}}> {housedata.Status}</span>  
                                    </span>
                                    
                                </div>
                                
                                <div className="card-body text-center text-muted text-sm m-0 p-0 pt-1">
                                    <div className='row m-0 mb-1 p-1 elevation-2 border-none'>
                                        <div className="col-6 m-0 p-0">
                                            <div className="form-group row m-0 p-0">
                                                <label className="col-5 m-0 p-0 text-md-right">Rent:</label>

                                                <div className="col-7">
                                                    {housedata.Rent}
                                                </div>
                                            </div>
                                        </div>
                                        <div className="col-6 m-0 p-0">
                                            <div className="form-group row m-0 p-0">
                                                <label className="col-5 m-0 p-0 text-md-right">Bin:</label>

                                                <div className="col-7">
                                                    {housedata.Garbage}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div className='row m-0 mb-1 p-1 elevation-2 border-none'>
                                        <div className="col-6 m-0 p-0">
                                            <div className="form-group row m-0 p-0">
                                                <label className="col-5 m-0 p-0 text-md-right">Deposit:</label>

                                                <div className="col-7">
                                                    {housedata.Deposit}
                                                </div>
                                            </div>
                                        </div>
                                        <div className="col-6 m-0 p-0">
                                            <div className="form-group row m-0 p-0">
                                                <label className="col-5 m-0 p-0 text-md-right">Lease:</label>

                                                <div className="col-7">
                                                    {housedata.Lease}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                

                                    <div className='row m-0 mb-1 p-1 elevation-2 border-none'>
                                        <div className="col-6 m-0 p-0">
                                            <div className="form-group row m-0 p-0">
                                                <label className="col-5 m-0 p-0 text-md-right">Kplc D:</label>

                                                <div className="col-7">
                                                    {housedata.Kplc}
                                                </div>
                                            </div>
                                        </div>
                                        <div className="col-6 m-0 p-0">
                                            <div className="form-group row m-0 p-0">
                                                <label className="col-5 m-0 p-0 text-md-right">Water D:</label>

                                                <div className="col-7">
                                                    {housedata.Water}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div className='row m-0 mb-1 p-1 elevation-2 border-none'>
                                        <div className="col-6 m-0 p-0">
                                            <div className="form-group row m-0 p-0">
                                                <label className="col-5 m-0 p-0 text-md-right">Due:</label>

                                                <div className="col-7 m-0 p-0">
                                                    {housedata.DueDay}
                                                </div>
                                            </div>
                                        </div>
                                        <div className="col-6 m-0 p-0">
                                            <div className='d-flex justify-content-center m-0 p-0'>
                                                {/* <button className='bg-warning m-0 ml-1 p-0 pl-2 pr-2 border-info text-dark' onClick={()=>{handleShow(housedata)}}><small><i className='fa fa-edit'></i></small></button> */}
                                                <button className='bg-danger m-0 ml-1 p-0 pl-2 pr-2 border-info text-white' onClick={()=>{deleteHouse(housedata)}}><small><i className='fa fa-trash'></i></small></button>
                                            </div>
                                        </div>
                                    </div>


                                    
                                </div>

                            </div>
                        </div>
                        }

                        {loadingmonths &&
                            <>
                                <div className="col-12 col-md-6 col-lg-4 text-left m-0 p-1 mt-1 mb-2">
                                    <HouseTenantDetailsSpinner />
                                </div>
                                <div className="col-12 col-md-6 col-lg-4 text-left m-0 p-1 mt-1 mb-2">
                                    <HouseTenantDetailsSpinner />
                                </div>
                            </>
                        }
                        {!loadingmonths &&
                            <>
                                {agreementdata  && agreementdata.map((agreement,key) => (
                                    <div key={key}  className="col-12 col-lg-6 text-left m-0 p-1 mt-1 mb-2">
                                        {agreement.Status === 'Vacated' || agreement.Status === 'Deleted' ?
                                            <div className="card border-danger m-2 p-0" >
                                                <div className="card-header text-muted m-0 p-0 pt-1">
                                                    <span className='m-0 p-1 text-danger bg-light'
                                                                style={{"borderRadius":"50%","border":"2px solid white"}}> <i className='fa fa-ban'></i></span>  
                                                    <span >{agreement.tenantname}</span>
                                                    <span style={{"float":"right"}}>
                                                        {agreement.Gender==='Male' ?
                                                            <img width="35px" className="brand-image img-circle m-0 p-0"
                                                                style={{"borderRadius":"50%","border":"2px solid white"}} src={avatar} alt="Tenant Profile" />
                                                        :
                                                            <img width="35px" className="brand-image img-circle m-0 p-0"
                                                                style={{"borderRadius":"50%","border":"2px solid white"}} src={avatar3} alt="Tenant Profile" />
                                                        }
                                                    </span>
                                                    
                                                </div>
                                                <div className="card-body text-center text-danger text-sm m-0 p-1">
                                                    <p><span>Status : </span><strong>{agreement.Status}</strong></p>
                                                    <p><span>{agreement.Status} On : </span><strong>{agreement.DateVacated}</strong></p>
                                                </div>
                                            </div>
                                        :
                                            <>
                                                {agreement.Tenant === housedata.tenant ?
                                                    <div className="card border-ok m-2 p-0" >
                                                        <div className="card-header bg-info text-white m-0 p-0 pt-1">
                                                            <span className='m-0 p-1 text-lime bg-light'
                                                                style={{"borderRadius":"50%","border":"2px solid white"}}> <i className='fa fa-check'> </i></span> 
                                                            <span>{agreement.tenantname} ({agreement.housesassigned})</span>
                                                            <span style={{"float":"right"}}>
                                                                {agreement.Gender==='Male' ?
                                                                    <img width="35px" className="brand-image img-circle m-0 p-0"
                                                                        style={{"borderRadius":"50%","border":"2px solid white"}} src={avatar} alt="Tenant Profile" />
                                                                :
                                                                    <img width="35px" className="brand-image img-circle m-0 p-0"
                                                                        style={{"borderRadius":"50%","border":"2px solid white"}} src={avatar3} alt="Tenant Profile" />
                                                                }
                                                            </span>
                                                            
                                                        </div>
                                                        <div className="card-body text-center text-sm m-0 p-1">
                                                            <p><span>Status : </span><strong className='text-success'>{agreement.Status}</strong> (<span>{agreement.DateAssigned}</span>)</p>
                                                            <p><span>Other Houses : </span><strong>{agreement.housesoccupied}</strong></p>
                                                            <p><span>Actions: </span>
                                                            <strong className=''>
                                                                <button className='btn btn-danger m-1 p-1 text-white' onClick={()=>{handleShow(housedata)}}><small> Vacate ({housedata.Housename})</small></button>
                                                                <button className='btn btn-success m-1 p-1 text-white' onClick={()=>{handleShow(housedata)}}><small> View {agreement.tenantfname}</small></button>
                                                            </strong>
                                                            </p>
                                                            <div>
                                                            
                                                            </div>
                                                        </div>
                                                    </div>
                                                :
                                                    <div className="card border-info m-2 p-0" >
                                                        <div className="card-header text-muted m-0 p-0">
                                                            <span className='m-0 p-1 text-muted bg-light'
                                                                style={{"borderRadius":"50%","border":"2px solid white"}}> <i className='fa fa-ban'></i></span>  
                                                            <span >{agreement.tenantname}</span>
                                                            <span style={{"float":"right"}}>
                                                                {agreement.Gender==='Male' ?
                                                                    <img width="35px" className="brand-image img-circle m-0 p-0"
                                                                        style={{"borderRadius":"50%","border":"2px solid white"}} src={avatar} alt="Tenant Profile" />
                                                                :
                                                                    <img width="35px" className="brand-image img-circle m-0 p-0"
                                                                        style={{"borderRadius":"50%","border":"2px solid white"}} src={avatar3} alt="Tenant Profile" />
                                                                }
                                                            </span>
                                                            
                                                        </div>
                                                        <div className="card-body text-center text-muted text-sm m-0 p-1">
                                                            <p><span>Status : </span><strong>{agreement.Status}</strong> (<span>{agreement.DateTo}</span>)</p>
                                                            <p><span>Current House : </span><strong>{agreement.Housename}</strong></p>
                                                        </div>
                                                    </div>
                                                }
                                            </>
                                        }
                                    </div>
                                        
                                    ))
                                }
                            </>
                        }


                    </div>
                </div>
                }
            </Modal.Body>
        </Modal>

    </>
  );
}

export default HseInfo;