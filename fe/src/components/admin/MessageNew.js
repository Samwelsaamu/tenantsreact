import DashSideNavBar from './DashSideNavBar';
import DashNavBar from './DashNavBar';
import { useEffect, useMemo, useState, useContext } from 'react';
import DashFooter from './DashFooter';
import Spinner from 'react-bootstrap/Spinner';

import axios from 'axios';

import { useNavigate } from 'react-router-dom';
import Select from 'react-select';
import { useParams } from 'react-router';
import { useDropzone } from 'react-dropzone';

import Swal from 'sweetalert';
import AddWaterbill from './AddWaterbill';
import TableSmallSpinner from '../spinners/TableSmallSpinner';
import VacatedWaterTable from './Tables/VacatedWaterTable';
import WaterbillTable from './Tables/WaterbillTable';
import WaterbillPreviewNoMatchTable from './Tables/WaterbillPreviewNoMatchTable';
import WaterbillPreviewMatchTable from './Tables/WaterbillPreviewMatchTable';
import WaterbillPreviewSavedTable from './Tables/WaterbillPreviewSavedTable';
import ReLogin from '../home/ReLogin';
import MessagesNewTable from './Tables/MessagesNewTable';
import ViewMessage from './ViewMessage';
import { LoginContext } from '../contexts/LoginContext';

const baseStyle={
    flex:1,
    display:"flex",
    flexDirection:"column",
    alignItems:"center",
    padding:"6px",
    borderWidth:2,
    borderRadius:2,
    border:"4px dotted #007bff",
    backgroundColor:"#ffffff",
    color:"#bdbdbd",
    outline:"none",
    transition:"border .24s ease-in-out"
}

const activeStyle={
    border:"2px dotted #6f42c1"
}

const acceptStyle={
    border:"2px dotted #00e676"
}

const rejectStyle={
    border:"2px dotted #ff1744"
}

function MessageNew(props) {
    document.title="Compose Message";
    const {socket,loggedname, setLoggedName, loggedtoken, setLoggedToken, loggedpermissions, setLoggedPermissions, loggedroles, setLoggedRoles, loggedrole,setLoggedRole,sitedata,setSiteData} =useContext(LoginContext);
    
    const [loggedoff,setLoggedOff]=useState(false);
    const navigate=useNavigate();


    // State for date selected by user
    const [selectedDate, setSelectedDate] = useState(new Date());


    const [closed,setClosed]=useState(false)
    const [isOpen, setIsOpen] = useState(false)

    const [formdata,setFormData]=useState({
        Phone:'',
        Message:'',
        error_list:[],
    });

    
    



    // Array to store month string values
    const allMonthValues = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December"
    ];


    const [messagesdata, setMessagesData] = useState([]);
    
    const [currentmessage, setCurrentMessage] = useState([""]);
    


    const [search,setSearch]=useState({
        value:'',
        result:[]
    })

    const [uploadwaterbill,setUploadWaterbill]=useState({
        upwaterbill:[]
      })
    
    const [allchecked,setAllchecked]=useState(false);
    const [totalchecked,setTotalChecked]=useState(0);

    const [show,setShow]=useState(false);

    const [loadingmonths,setLoadingMonths]=useState(true);
    

    const [loading,setLoading]=useState(false);

    const [msglength, setMsglength] = useState(0)
    const [msgcount, setMsgcount] = useState(0)

    const handleInputChange=(e)=>{
        e.persist();
        setFormData({...formdata,[e.target.name]:e.target.value})

        if(e.target.name=='Message'){
            let msg_length=e.target.value.length;
            setMsglength(msg_length);

            if (msg_length>160) {
                let msg_count=Math.floor(msg_length/160)+1;
                setMsgcount(msg_count);
            }
            else{
                let msg_count=1;
                setMsgcount(msg_count);
            }

        }
    }
    
    
    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingMonths(true)
        }
        const getPrevMonths = async (e) => { 
            let url='/v2/update/messages/new/load';
            
            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        setMessagesData(response.data.messagesdata)
                        
                       
                        setLoadingMonths(false)
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error1",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error2",response.data.message,"error");
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
                        Swal("Error2",""+error,"error");
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
    },[])

    const loadMessages =() =>{
        let doneloading=true;
        if (doneloading) {
            setLoadingMonths(true)
        }
        const getPrevMonths = async (e) => { 
            let url='/v2/update/messages/new/load';
            
            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        setMessagesData(response.data.messagesdata)
                        setLoadingMonths(false)
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error1",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error2",response.data.message,"error");
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
                        Swal("Error2",""+error,"error");
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


    
    // useEffect(()=>{
    //     if(uploadwaterbill.upwaterbill.length < totalvalid){
    //         setAllchecked(false)
    //         if(uploadwaterbill.upwaterbill.length===0){
    //             setAllchecked(false)
    //         }
    //     }
        
    //     else if(uploadwaterbill.upwaterbill.length===totalvalid){
    //         setAllchecked(true)
    //         if(uploadwaterbill.upwaterbill.length===0){
    //             setAllchecked(false)
    //         }
    //     }
        
        
    // },[uploadwaterbill.upwaterbill.length])

    

    

    const handleClose = () => {
        setShow(false);
        document.title="View Message Information";
    };

    const handleShow = (message) => {
        setShow(true);
        setCurrentMessage(message)
    };

  
    

    

    const handleSearchChange =(e) =>{
    setLoadingMonths(true)
    const results=messagesdata.filter(messages =>{
        if(e.target.value=== '') return messagesdata
        return messages.Phone.toLowerCase().includes(e.target.value.toLowerCase()) || messages.Message.toLowerCase().includes(e.target.value.toLowerCase())
    })
    setSearch({
        value:e.target.value,
        result:results
    })
    setLoadingMonths(false)
    }


    const handleChange= (e) => {
        const {value,checked}=e.target;
        const {upwaterbill} =uploadwaterbill;

        if(value==='all'){
            const arr = [];
            if(checked){
                messagesdata.map((waterbilld) => {
                    if(waterbilld.waterid===null){
                        if(waterbilld.tid!=='Vacated'){
                            if(waterbilld.prevmatches==='Yes'){
                                arr.push(waterbilld.id+'?'+waterbilld.housename+'?'+waterbilld.tid+'?'+waterbilld.tenantname+'?'+waterbilld.previous+'?'+waterbilld.current+'?'+waterbilld.cost+'?'+waterbilld.units+'?'+waterbilld.total+'?'+waterbilld.waterid+'?')
                            }
                        }
                    }
                    else{
                        arr.push(waterbilld.id+'?'+waterbilld.housename+'?'+waterbilld.tid+'?'+waterbilld.tenantname+'?'+waterbilld.previous+'?'+waterbilld.current+'?'+waterbilld.cost+'?'+waterbilld.units+'?'+waterbilld.total+'?'+waterbilld.waterid+'?')
                    }
                })
                setUploadWaterbill({
                    upwaterbill:arr,
                })
            }
            else{
                setUploadWaterbill({
                    upwaterbill:[],
                })
            }
            
        }
        else{
            if(checked){
                setUploadWaterbill({
                    upwaterbill:[... upwaterbill,value],
                });
                
            }
            else{
                setUploadWaterbill({
                    upwaterbill:upwaterbill.filter((e) => e !== value),
                })
            }

        }
    }

    const sendMessage= (e)=>{
        e.preventDefault();
        if((formdata.Phone).trim() ===''){
            Swal("Enter Phone Numbers","Please Enter Contact Valid Contact Numbers","error");
            return;
        }
        if((formdata.Message).trim() ===''){
            Swal("Enter Message Details","Please Enter Message you wish to Send","error");
            return;
        }


        const form={
            Phone:formdata.Phone,
            Message:formdata.Message,
        }

        let title='Are you sure to Send';
        let text="Phone numbers ( "+formdata.Phone+" ) \n Message: "+formdata.Message;
        Swal({
            title:title+' this Message ?',
            text:text,
            buttons:true,
            infoMode:true,
        })
        .then((willcontinue) =>{
            if(willcontinue){
                setLoading(true);
                Swal("Sending....","Please Wait");
                axios.post('/v2/send/message/new',form)
                .then(response=>{
                    if(socket) {
                        socket.emit('message_sent',response.data.message);
                    }
                    if(response.data.status=== 200){
                        loadMessages();
                        Swal("Success",response.data.message);
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
                    }
                    else{
                        Swal("Error","Please Review the Errors","error");
                        setFormData({...formdata,error_list:response.data.errors});
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

    const resendMessage= (message)=>{
        if((message.Phone).trim() ===''){
            Swal("Enter Phone Numbers","Please Enter Contact Valid Contact Numbers","error");
            return;
        }
        if((message.Message).trim() ===''){
            Swal("Enter Message Details","Please Enter Message you wish to Send","error");
            return;
        }


        const form={
            Phone:message.Phone,
            Message:message.Message,
        }

        let title='Are you sure to Resend';
        let text="Phone numbers ( "+message.Phone+" ) \n Message: "+message.Message;
        Swal({
            title:title+' this Message ?',
            text:text,
            buttons:true,
            infoMode:true,
        })
        .then((willcontinue) =>{
            if(willcontinue){
                setLoading(true);
                Swal("Sending....","Please Wait");
                axios.post('/v2/send/message/new',form)
                .then(response=>{
                    if(socket) {
                        socket.emit('message_sent',response.data.message);
                    }
                    if(response.data.status=== 200){
                        loadMessages();
                        Swal("Success",response.data.message);
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
                    }
                    else{
                        Swal("Error","Please Review the Errors","error");
                        setFormData({...formdata,error_list:response.data.errors});
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

    const deleteMessage= (message)=>{
        const form={
            id:message.id,
        }

        let title='Delete \n'+message.MessageMasked+' \nSent to '+message.Phone;
        let text="This will remove this Message from the system.";
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
                axios.post('/v2/delete/message/new/save',form)
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
                    loadMessages();
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
        <DashNavBar setClosed={setClosed} closed={closed} active='newmessage'/>
        <DashSideNavBar setClosed={setClosed} closed={closed} active='newmessage'/>
        {/* className={`nav-link ${active==='home'?'active':''}`} */}
        
        <main className="py-3">
            <div className={`content-wrapper ${closed?'closed':''}`}>

                    <section className="content">
                    <div className="container">
                        <div className="row justify-content-center">

                        <div className="col-lg-4 ">
                            <div className="row m-0 p-0">

                                <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                    <div className="card border-info elevation-2" >
                                        <div className="card-body text-center p-0 m-1">
                                            <form onSubmit={sendMessage}>
                                                <div className="row m-0 p-0">
                                                    <div className="col-md-12 m-0 mt-1 p-0">
                                                        <div className="form-group row m-0 p-1 pb-2">
                                                            {/* <label htmlFor="Phone" className="col-12 col-form-label text-left p-1 mb-1">Phone Number(s)</label> */}

                                                            <div className="col-12 m-0 p-0">
                                                                <input id="Phone" type="text" className="form-control" name="Phone" value={formdata.Phone} onChange={handleInputChange} placeholder="+2547xxxxxxxx,+25410xxxxxx1" required autoComplete="Phone" autoFocus/>
                                                                {formdata.error_list && formdata.error_list.Phone && 
                                                                    <span className="help-block text-danger">
                                                                        <strong>{formdata.error_list.Phone}</strong>
                                                                    </span>
                                                                }
                                                            </div>
                                                        </div>
                                                        
                                                    </div>

                                                    <div className="col-md-12 m-0 mt-1 p-0">
                                                        <div className="form-group row m-0 p-1 pb-2">
                                                            {/* <label htmlFor="Message" className="col-12 col-form-label text-left p-1 mb-1">Message</label> */}

                                                            <div className="col-12 m-0 p-0">
                                                                <textarea id="Message" type="text" className="form-control" rows='3' name="Message" value={formdata.Message} onChange={handleInputChange} placeholder="Compose Message Here"></textarea>
                                                                <div id="charactercountsinglewater" name="charactercountsinglewater" className="text-black bold text-sm">Characters: {msglength}, {msgcount} Message(s)</div>
                                                
                                                                {formdata.error_list && formdata.error_list.Message && 
                                                                    <span className="help-block text-danger">
                                                                        <strong>{formdata.error_list.Message}</strong>
                                                                    </span>
                                                                }
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div className="col-md-12 m-0 mt-1 p-1">
                                                        
                                                        <div className='d-flex justify-content-center float-right'>
                                                            <button className='btn btn-success border-info m-1 p-1 pl-2 pr-2'> Send Message</button>
                                                        </div>    
                                                    
                                                    </div>

                                                </div>
                                            </form>
                                                
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                        </div>

                        <div className="col-lg-8">
                            <div className="row m-0 p-0">

                                <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                    <div className="card border-info m-0 p-0" >
                                        <div className="card-header bg-info text-white elevation-2 m-0 p-0">
                                            <p className='text-center p-1 m-0'>
                                                
                                                <span>
                                                    {loadingmonths &&
                                                        <Spinner  variant="light" size="md" role="status"></Spinner>
                                                    }
                                                    Messages Sent.
                                                </span>

                                                <span className="text-xs float-right m-0 p-0">
                                                    <input onChange={handleSearchChange} value={search.value} className='border-info p-1 pt-0 pb-0' placeholder='Search Message,Phone Number' />
                                                </span>
                                            </p>
                                            
                                        </div>

                                        <div className="card-body text-center m-0 p-1" >
                                        
                                            <div className="row m-0 p-0">
                                                <div className="tableinfo col-12 m-0 p-0">
                                                
                                                {loadingmonths &&
                                                    <div className="col-12 text-left m-0 p-1 mt-1 mb-2">
                                                        <TableSmallSpinner />
                                                    </div>
                                                }

                                                
                                                
                                                {!loadingmonths &&
                                                    <table border="1" className="table table-hover table-bordered text-xs" id="example1">
                                                        {messagesdata  && messagesdata &&
                                                            <thead  >
                                                            <tr  style={{"color":"white","backgroundColor":"#77B5ED"}}>
                                                                {/* <th className='elevation-2 m-0 p-1'>
                                                                   {preview?
                                                                    <label className="selwaterbill m-0 p-1 border-info" style={{"fontSize":"12px"}}>
                                                                        <input type="checkbox" 
                                                                            className="selectedwaterbilltenants" 
                                                                            name="waterbillvalues[]"
                                                                            value="all"
                                                                            checked={allchecked}
                                                                            onChange={handleChange} />
                                                                            Sno
                                                                    </label>
                                                                    :
                                                                    "Sno"
                                                                    }
                                                                </th> */}
                                                                <th className='elevation-2 m-0 p-1'>Sno</th>
                                                                <th className='elevation-2 m-0 p-1'>Phone No</th>
                                                                <th className='elevation-2 m-0 p-1'>Message</th>
                                                                <th className='elevation-2 m-0 p-1'>Status</th>
                                                                <th className='elevation-2 m-0 p-1'>Sent On</th>
                                                                <th className='elevation-2 m-0 p-1'>Action</th>
                                                            </tr></thead>
                                                        }
                                                        <tbody>
                                                            {(search.value==='')?
                                                                    <>
                                                                        {messagesdata  && messagesdata.map((message,key) => (
                                                                            <MessagesNewTable message={message} key={key} no={key} handleShow={handleShow} deleteMessage={deleteMessage} resendMessage={resendMessage} />
                                                                        ))
                                                                        }
                                                                    </>
                                                                :
                                                                    <>
                                                                        {search.result  && search.result.map((message,key) => (
                                                                            <MessagesNewTable message={message} key={key} no={key} handleShow={handleShow} deleteMessage={deleteMessage} resendMessage={resendMessage} />
                                                                        ))
                                                                        }
                                                                    </>
                                                                
                                                            }
                                                        </tbody>
                                                    </table>
                                                }
                                                </div>
                                            </div>

                                        </div>

                                        

                                    </div>
                                </div>

                                


                                {show && 
                                    <ViewMessage show={show} handleClose={handleClose} currentmessage={currentmessage}/>
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

export default MessageNew;