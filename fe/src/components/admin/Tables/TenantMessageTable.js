import {Link, Navigate, useNavigate} from 'react-router-dom';

function TenantMessageTable({waterbill,no,preview,uploadwaterbill,allchecked,handleChange,sendMessage,formdata}) {
    String.prototype.namef =function() { return this.replace(/{fname}/g, waterbill.tenantfname) }
    String.prototype.name =function() { return this.replace(/{name}/g, waterbill.tenantname) }
    String.prototype.house =function() { return this.replace(/{house}/g, waterbill.housename) }
    let message=(formdata.Message).namef();
    message=(message).name();
    message=(message).house();

    let msg_length=message.length;
    let msg_count=0;

    if (msg_length>160) {
        msg_count=Math.floor(msg_length/160)+1;
    }
    else{
        msg_count=1;
    }
    // document.write("The quick brown fox".name())
  return (
    <div className="col-12 m-0 p-0" >
        <div className="bg-white m-0 mb-2 elevation-3 border-5 p-1" >
            <div className="row m-0 p-0">
                <div className="col-12 m-0 p-0 text-left">
                    <span className="text-dark" style={{"fontSize":"12px"}}>
                        <b>{no+1}.  House: {waterbill.housename}, Name: {waterbill.tenantname},Phone: {waterbill.phone}
                            {waterbill.phone.length ==13 ?
                                <>
                                {waterbill.isblacklisted	 =='No' || waterbill.isblacklisted	 ==null ?
                                    <>
                                    {waterbill.messageSent !=null?
                                        <button className="btn btn-outline-warning float-right text-xs m-1 p-0 pr-1 pl-1" onClick={()=>{sendMessage(waterbill,message)}}
                                        title={msg_length+' Characters, ('+msg_count+') Chargeble Messages, (Ksh '+msg_count*0.8+' Total Cost of Message'}><i className="fa fa-history"></i> Resend ({msg_length}({msg_count})(Ksh {msg_count*0.8}))</button>
                                    :
                                        <button className="btn btn-outline-info float-right text-xs m-1 p-0 pr-1 pl-1" onClick={()=>{sendMessage(waterbill,message)}}
                                        title={msg_length+' Characters, ('+msg_count+') Chargeble Messages, (Ksh '+msg_count*0.8+' Total Cost of Message'}><i className="fa fa-paper-plane"></i> Send ({msg_length}({msg_count})(Ksh {msg_count*0.8}))</button>
                                    }
                                    </>
                                :
                                <i className="float-right m-1 p-0 pr-1 pl-1 text-danger">{waterbill.isblacklisted	}!!</i>}
                                </>
                            :
                            <i className="float-right m-1 p-0 pr-1 pl-1 text-danger">Check Phone No!!</i>}

                        </b>
                        
                        {waterbill.messageSent !=null?
                            <i className="float-right m-1 p-0 pr-1 pl-1 text-muted">{waterbill.messageSent} {waterbill.status == null?'':<span className="text-sm">({waterbill.status})</span>}</i>
                            :
                            <i className="float-right m-1 p-0 pr-1 pl-1 text-muted">Not sent</i>
                        }

                    </span>
                </div>
            </div>
        
            <div className="">
                <div className="row m-0 p-0">
                    <div className="col-12 m-0 p-0 text-left">
                        
                        <div className={`unwaterbillvaluesdiv p-1 text-md-left text-sm border-5 ${(uploadwaterbill.upwaterbill.filter( (f) => f === (formdata.Message == message?waterbill.phone:waterbill.phone+'/'+waterbill.house+'/'+message))).length ?'bg-secondary text-info':'bg-light text-dark'}`} >
                            {(waterbill.phone.length ==13) && (waterbill.isblacklisted	 =='No' || waterbill.isblacklisted	 ==null) ?
                                <>
                                {allchecked?
                                    <label className="selwaterbill-1 col-12 m-0 p-1">
                                        <input type="checkbox" 
                                            className="selectedwaterbilltenants mr-1" 
                                            name="waterbillvalues[]"
                                            checked={allchecked}
                                            value={(formdata.Message == message?waterbill.phone:waterbill.phone+'/'+waterbill.house+'/'+message)}
                                            onChange={handleChange} />
                                            {message}
                                    </label>
                                :
                                    <label className="selwaterbill-1 col-12 m-0 p-1">
                                        <input type="checkbox" 
                                            className="selectedwaterbilltenants mr-1" 
                                            name="waterbillvalues[]"
                                            checked={(uploadwaterbill.upwaterbill.filter( (f) => f === (formdata.Message == message?waterbill.phone:waterbill.phone+'/'+waterbill.house+'/'+message))).length}
                                            value={(formdata.Message == message?waterbill.phone:waterbill.phone+'/'+waterbill.house+'/'+message)}
                                            onChange={handleChange} />
                                            {message}
                                    </label>
                                }
                                </>
                                :
                                <label className=" col-12 bg-danger m-0 p-1">
                                    {message}
                                </label>
                            }
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
  );
}

export default TenantMessageTable;