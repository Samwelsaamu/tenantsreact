import HomeFooter from './HomeFooter';
import HomeNavBar from './HomeNavBar';
import { useEffect, useState, useContext, } from 'react';
import { LoginContext } from '../contexts/LoginContext';


function Home() {
    document.title="Home";

    let thismessages='';
    const {socket,loggedname, setLoggedName, loggedtoken, setLoggedToken, loggedpermissions, setLoggedPermissions, loggedroles, setLoggedRoles, loggedrole,setLoggedRole,sitedata,setSiteData} =useContext(LoginContext);
    // console.log(sitedata,loggedname,loggedrole)

    const [messages, setMessages]= useState([]);
    const [inputMessage,setInputMessage]=useState('');
    
    
    useEffect( () =>{
        socket.on('chat_message', (msg) =>{
            setMessages((prevMessages) => [...prevMessages,msg]);
            // console.log(msg)
        })

    }, []);

    
    const sendMessage  = () =>{
        
        if(socket) {
            // console.log(inputMessage)
            socket.emit('chat_message',inputMessage);
            setInputMessage('');
        }
    }


    
  return (
    <>
    <HomeNavBar active='home'/>
    <main style={{"paddingTop": "80px","minHeight": "calc(100vh - 3rem)"}}>
        <div className="container">
            <div className="row justify-content-center">
                <div className="col-md-12">
                    <div className="card" style={{"border": "none"}}>
                        <div className="card-header text-dark" style={{"backgroundColor": "transparent"}}>
                            <h1 style={{"textAlign": "center"}}>{sitedata && sitedata.Names}</h1>
                        </div>

                        {/* <div className="card-body" style={{"paddingTop": "10px"}}>
                            <div className="row">

                                <div className="col-sm-6">
                                    <div>
                                        {thismessages}
                                         {messages && messages.map((message,index) =>{
                                            <p key={index}>{message}</p>
                                        })} 
                                    </div>
                                </div>

                                <div className="col-sm-6">
                                        <div>
                                            <input type='text' value={inputMessage}
                                                onChange={(e) => setInputMessage(e.target.value)}
                                            />
                                        </div>
                                        <button className='btn btn-success' onClick={sendMessage}>Send</button>
                                </div>

                            </div>
                        </div> */}

                        <div className="card-body" style={{"paddingTop": "10px"}}>
                            <div className="row">

                                <div className="col-sm-6">
                                    <div className="card card-primary texts-black" style={{"marginBottom": "5%","minHeight": "300px","textAlign": "center","border": "none"}}>
                                    <div className="card-body">
                                        <h3 className="card-title title-black text-info">Contacts</h3>

                                        <p className="card-text"><i className="fa fa-x fa-map-marker text-primary mb-2"></i> <br/> {sitedata && sitedata.Address},{sitedata && sitedata.Town}</p>
                                        <p className="card-text"><i className="fa fa-x fa-phone text-primary mb-2"></i><br/> 0{sitedata && sitedata.Phone} </p>
                                        <p className="card-text"> <a href="mailto:{{ App\Models\Agency::getAgencyEmail()}}" style={{"color":"black"}}><i className="fa fa-x fa-envelope-o text-primary mb-2"></i> <br/>{sitedata && sitedata.Email}</a></p>
                                        <p>We Will Get Back to You As Soon as Possible</p>
                                        
                                    </div>
                                    </div>
                                </div>

                                <div className="col-sm-6">
                                    <div className="card card-primary texts-info" style={{"marginBottom": "5%","minHeight": "300px","textAlign": "center","border": "none"}}>
                                    <div className="card-body">
                                        <h3 className="card-title title-black text-info">Our Houses are</h3>
                                        <p className="card-text">Affordable and Cost Effective</p>
                                        <p className="card-text">Near Main Routes</p>
                                        <p className="card-text">Plenty of water</p>
                                        <p className="card-text">Provided with 24 hour security</p>
                                    </div>
                                    </div>
                                </div>


                            </div>


                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        {/* <Spinner animation="grow" variant="primary" role="status">
            <span className="visually-hidden" >Loading...</span>
        </Spinner> */}
    </main>
    <HomeFooter />
      
    </>
  );
}

export default Home;