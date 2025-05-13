import {Link, Navigate, useNavigate} from 'react-router-dom';

function ReminderMessageTable({waterbill,no,preview,uploadwaterbill,allchecked,handleChange,sendMessage,formdata}) {
    let balance='';
    if(waterbill.Balance>0){
        if(waterbill.CarriedForward>0){
            balance='Previous Arrears '+Math.abs(waterbill.CarriedForward) +' Kindly Pay a Balance of Kshs.'+waterbill.Balance+'.';
        }
        else{
            balance='Kindly Pay a Balance of Kshs.'+waterbill.Balance+'.';
        }
    }
    else if(waterbill.Balance<0){
        balance='Overpayment Kshs.'+Math.abs(waterbill.Balance)+'.';
    }
    else if(waterbill.Balance==0){
        balance='You have No Arrears.';
    }

    let paid='';
    if(waterbill.TotalPaid>0){
        if(waterbill.CarriedForward<0){
            paid='Received Ksh.'+waterbill.TotalPaid+'. Last Month Overpayment of '+Math.abs(waterbill.CarriedForward);
        }
        else{
            paid='Received Ksh.'+waterbill.TotalPaid+'.';
        }
    }
    else{
        if(waterbill.CarriedForward<0){
            if(waterbill.TotalPaid>0){
                paid='Received Ksh.'+waterbill.TotalPaid+'. Last Month Overpayment of '+Math.abs(waterbill.CarriedForward);
            }
            else{
                paid='Last Month Overpayment of '+Math.abs(waterbill.CarriedForward);
            }

        }
    }

    let rent='';
    if(waterbill.Rent>0){
        rent='Rent '+waterbill.Rent+' ';
    }

    let garbage='';
    if(waterbill.Garbage>0){
        garbage='Garbage '+waterbill.Garbage+' ';
    }
    
    let waterbillds='';
    if(waterbill.Waterbill>0){
        waterbillds='Waterbill '+waterbill.Waterbill+' ';
    }
    
    let housed='';
    if(waterbill.HseDeposit>0){
        housed='Deposit '+waterbill.HseDeposit+' ';
    }
    
    let waterd='';
    if(waterbill.Water>0){
        waterd='Water Deposit '+waterbill.Water+' ';
    }
    
    let kplcd='';
    if(waterbill.KPLC>0){
        kplcd='KPLC Deposit '+waterbill.KPLC+' ';
    }
    
    let lease='';
    if(waterbill.Lease>0){
        lease='Lease '+waterbill.Lease+' ';
    }

    // String.prototype.namef =function() { return this.replace(/{fname}/g, waterbill.tenantfname) }
    String.prototype.name =function() { return this.replace(/{name}/g, waterbill.tenantfname) }
    String.prototype.house =function() { return this.replace(/{house}/g, waterbill.housename) }
    String.prototype.balance =function() { return this.replace(/{balance}/g, balance) }
    String.prototype.paid =function() { return this.replace(/{paid}/g, paid) }
    String.prototype.total =function() { return this.replace(/{total}/g, waterbill.TotalUsed) }
    String.prototype.month =function() { return this.replace(/{month}/g, waterbill.monthdate) }
    String.prototype.rent =function() { return this.replace(/{rent}/g, rent) }
    String.prototype.garbage =function() { return this.replace(/{garbage}/g, garbage) }
    String.prototype.waterbillds =function() { return this.replace(/{waterbillds}/g, waterbillds) }
    String.prototype.housed =function() { return this.replace(/{housed}/g, housed) }
    String.prototype.waterd =function() { return this.replace(/{waterd}/g, waterd) }
    String.prototype.kplcd =function() { return this.replace(/{kplcd}/g, kplcd) }
    String.prototype.lease =function() { return this.replace(/{lease}/g, lease) }

    let message=(formdata.Message).name();
    message=(message).house();
    message=(message).balance();
    message=(message).paid();
    message=(message).month();
    message=(message).total();
    message=(message).rent();
    message=(message).garbage();
    message=(message).waterbillds();
    message=(message).housed();
    message=(message).waterd();
    message=(message).kplcd();
    message=(message).lease();

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
                                {waterbill.isNumberBlacklisted =='No' || waterbill.isNumberBlacklisted ==null ?
                                <>
                                    {waterbill.messageSent !=false?
                                        <button className="btn btn-outline-warning float-right text-xs m-1 p-0 pr-1 pl-1" onClick={()=>{sendMessage(waterbill,message)}}
                                        title={msg_length+' Characters, ('+msg_count+') Chargeble Messages, (Ksh '+msg_count*0.8+' Total Cost of Message'}><i className="fa fa-history"></i> Resend ({msg_length}({msg_count})(Ksh {msg_count*0.8}))</button>
                                    :
                                        <button className="btn btn-outline-info float-right text-xs m-1 p-0 pr-1 pl-1" onClick={()=>{sendMessage(waterbill,message)}}
                                        title={msg_length+' Characters, ('+msg_count+') Chargeble Messages, (Ksh '+msg_count*0.8+' Total Cost of Message'}><i className="fa fa-paper-plane"></i> Send ({msg_length}({msg_count})(Ksh {msg_count*0.8}))</button>
                                    }
                                </>
                                :
                                <i className="float-right m-1 p-0 pr-1 pl-1 text-danger">{waterbill.isNumberBlacklisted}!!</i>}
                                </>
                            :
                            <i className="float-right m-1 p-0 pr-1 pl-1 text-danger">Check Phone No!!</i>}
                        </b>
                        
                        {waterbill.messageSent !=false?
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
                            {waterbill.phone.length ==13 ?
                                <>
                                {waterbill.isNumberBlacklisted =='No' || waterbill.isNumberBlacklisted ==null ?
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

export default ReminderMessageTable;