import {Link, Navigate, useNavigate} from 'react-router-dom';

function WaterbillMessageTable({waterbill,no,preview,uploadwaterbill,allchecked,handleChange,sendMessage}) {
    let message='WATER BILL: Greetings '+waterbill.housename+' :'+waterbill.monthdate+';Previous:'+waterbill.previous+';Current:'+waterbill.current+';UnitCost Kshs.'+waterbill.cost+';Units:'+waterbill.units+';'+(waterbill.total_os>0?'CC:'+waterbill.total+';Other:'+waterbill.total_os+';':'')+'Total Kshs.'+(waterbill.total+waterbill.total_os)+'.Thank You';
    
    let msg_length=message.length;
    let msg_count=0;

    if (msg_length>160) {
        msg_count=Math.floor(msg_length/160)+1;
    }
    else{
        msg_count=1;
    }
  return (
    <div className="col-12 m-0 p-0" >
        <div className="bg-white m-0 mb-2 elevation-3 border-5 p-1" >
            <div className="row m-0 p-0">
                <div className="col-12 m-0 p-0 text-left">
                    <span className="text-dark" style={{"fontSize":"10px"}}>
                        <b>{no+1}. {waterbill.tenantfname}, {waterbill.housename}, {waterbill.phone}
                            {('+254'+waterbill.phone).length == 13 ?
                                <>
                                    {waterbill.isNumberBlacklisted =='No' || waterbill.isNumberBlacklisted ==null ?
                                        <>
                                            {waterbill.messageSent !=null?
                                                <button className="btn btn-outline-warning float-right text-xs m-1 p-0 pr-1 pl-1" onClick={()=>{sendMessage(waterbill)}}
                                                title={msg_length+' Characters, ('+msg_count+') Chargeble Messages, (Ksh '+msg_count*0.8+' Total Cost of Message'}><i className="fa fa-history"></i> Resend ({msg_length}({msg_count})(Ksh {msg_count*0.8}))</button>
                                            :
                                                <button className="btn btn-outline-info float-right text-xs m-1 p-0 pr-1 pl-1" onClick={()=>{sendMessage(waterbill)}}
                                                title={msg_length+' Characters, ('+msg_count+') Chargeble Messages, (Ksh '+msg_count*0.8+' Total Cost of Message'}><i className="fa fa-paper-plane"></i> Send ({msg_length}({msg_count})(Ksh {msg_count*0.8}))</button>
                                            }
                                        </>
                                        :
                                        <i className="float-right m-1 p-0 pr-1 pl-1 text-danger">{waterbill.isNumberBlacklisted}!!</i>}
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
                    <div className="col-12 m-0 p-0">
                        {waterbill.messageSent !=null?
                            <div className={`unwaterbillvaluesdiv p-1 text-sm border-5 ${(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.phone+'/'+waterbill.house+'/WATER BILL: Greetings '+waterbill.housename+' :'+waterbill.monthdate+';Previous:'+waterbill.previous+';Current:'+waterbill.current+';UnitCost Kshs.'+waterbill.cost+';Units:'+waterbill.units+';'+(waterbill.total_os>0?'CC:'+waterbill.total+';Other:'+waterbill.total_os+';':'')+'Total Kshs.'+(waterbill.total+waterbill.total_os)+'.Thank You')).length ?'bg-secondary text-info':'bg-warning text-dark'}`} >
                                {(('+254'+waterbill.phone).length ==13) && (waterbill.isNumberBlacklisted	 =='No' || waterbill.isNumberBlacklisted	 ==null) ?
                                    <>
                                    {allchecked? 
                                        <label className="selwaterbill-1 col-12 m-0 p-1">
                                            <input type="checkbox" 
                                                className="selectedwaterbilltenants mr-1" 
                                                name="waterbillvalues[]"
                                                checked={allchecked}
                                                value={waterbill.phone+'/'+waterbill.house+'/WATER BILL: Greetings '+waterbill.housename+' :'+waterbill.monthdate+';Previous:'+waterbill.previous+';Current:'+waterbill.current+';UnitCost Kshs.'+waterbill.cost+';Units:'+waterbill.units+';'+(waterbill.total_os>0?'CC:'+waterbill.total+';Other:'+waterbill.total_os+';':'')+'Total Kshs.'+(waterbill.total+waterbill.total_os)+'.Thank You'}
                                                onChange={handleChange} />                                                                                                                                                                                                                                      
                                                WATER BILL: Greetings {waterbill.housename} :{waterbill.monthdate}; Previous:{waterbill.previous}; Current:{waterbill.current}; Cost Kshs.{waterbill.cost}; Units:{waterbill.units}; {waterbill.total_os>0?'CC:'+waterbill.total+';Other:'+waterbill.total_os+';':''} Total Kshs.{waterbill.total+waterbill.total_os}.
                                        </label>
                                    :
                                        <label className="selwaterbill-1 col-12 m-0 p-1">
                                            <input type="checkbox" 
                                                className="selectedwaterbilltenants mr-1" 
                                                name="waterbillvalues[]"
                                                checked={(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.phone+'/'+waterbill.house+'/WATER BILL: Greetings '+waterbill.housename+' :'+waterbill.monthdate+';Previous:'+waterbill.previous+';Current:'+waterbill.current+';UnitCost Kshs.'+waterbill.cost+';Units:'+waterbill.units+';'+(waterbill.total_os>0?'CC:'+waterbill.total+';Other:'+waterbill.total_os+';':'')+'Total Kshs.'+(waterbill.total+waterbill.total_os)+'.Thank You')).length}
                                                value={waterbill.phone+'/'+waterbill.house+'/WATER BILL: Greetings '+waterbill.housename+' :'+waterbill.monthdate+';Previous:'+waterbill.previous+';Current:'+waterbill.current+';UnitCost Kshs.'+waterbill.cost+';Units:'+waterbill.units+';'+(waterbill.total_os>0?'CC:'+waterbill.total+';Other:'+waterbill.total_os+';':'')+'Total Kshs.'+(waterbill.total+waterbill.total_os)+'.Thank You'}
                                                onChange={handleChange} />
                                                WATER BILL: Greetings {waterbill.housename} :{waterbill.monthdate}; Previous:{waterbill.previous}; Current:{waterbill.current}; Cost Kshs.{waterbill.cost}; Units:{waterbill.units}; {waterbill.total_os>0?'CC:'+waterbill.total+';Other:'+waterbill.total_os+';':''} Total Kshs.{waterbill.total+waterbill.total_os}.
                                        </label>
                                    }
                                    </>
                                    :
                                    <label className=" col-12 bg-danger m-0 p-1">
                                        WATER BILL: Greetings {waterbill.housename} :{waterbill.monthdate}; Previous:{waterbill.previous}; Current:{waterbill.current}; Cost Kshs.{waterbill.cost}; Units:{waterbill.units}; {waterbill.total_os>0?'CC:'+waterbill.total+';Other:'+waterbill.total_os+';':''} Total Kshs.{waterbill.total+waterbill.total_os}.
                                    </label>
                                }
                            </div>
                            :
                            <div className={`unwaterbillvaluesdiv p-1 text-sm border-5 ${(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.phone+'/'+waterbill.house+'/WATER BILL: Greetings '+waterbill.housename+' :'+waterbill.monthdate+';Previous:'+waterbill.previous+';Current:'+waterbill.current+';UnitCost Kshs.'+waterbill.cost+';Units:'+waterbill.units+';'+(waterbill.total_os>0?'CC:'+waterbill.total+';Other:'+waterbill.total_os+';':'')+'Total Kshs.'+(waterbill.total+waterbill.total_os)+'.Thank You')).length ?'bg-secondary text-info':'bg-light text-dark'}`} >
                            {(('+254'+waterbill.phone).length ==13) && (waterbill.isNumberBlacklisted	 =='No' || waterbill.isNumberBlacklisted	 ==null) ?
                                <>
                                {allchecked?
                                    <label className="selwaterbill-1 col-12 m-0 p-1">
                                        <input type="checkbox" 
                                            className="selectedwaterbilltenants mr-1" 
                                            name="waterbillvalues[]"
                                            checked={allchecked}
                                            value={waterbill.phone+'/'+waterbill.house+'/WATER BILL: Greetings '+waterbill.housename+' :'+waterbill.monthdate+';Previous:'+waterbill.previous+';Current:'+waterbill.current+';UnitCost Kshs.'+waterbill.cost+';Units:'+waterbill.units+';'+(waterbill.total_os>0?'CC:'+waterbill.total+';Other:'+waterbill.total_os+';':'')+'Total Kshs.'+(waterbill.total+waterbill.total_os)+'.Thank You'}
                                            onChange={handleChange} />
                                            WATER BILL: Greetings {waterbill.housename} :{waterbill.monthdate}; Previous:{waterbill.previous}; Current:{waterbill.current}; Cost Kshs.{waterbill.cost}; Units:{waterbill.units}; {waterbill.total_os>0?'CC:'+waterbill.total+';Other:'+waterbill.total_os+';':''} Total Kshs.{waterbill.total+waterbill.total_os}.
                                    </label>
                                :
                                    <label className="selwaterbill-1 col-12 m-0 p-1">
                                        <input type="checkbox" 
                                            className="selectedwaterbilltenants mr-1" 
                                            name="waterbillvalues[]"
                                            checked={(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.phone+'/'+waterbill.house+'/WATER BILL: Greetings '+waterbill.housename+' :'+waterbill.monthdate+';Previous:'+waterbill.previous+';Current:'+waterbill.current+';UnitCost Kshs.'+waterbill.cost+';Units:'+waterbill.units+';'+(waterbill.total_os>0?'CC:'+waterbill.total+';Other:'+waterbill.total_os+';':'')+'Total Kshs.'+(waterbill.total+waterbill.total_os)+'.Thank You')).length}
                                            value={waterbill.phone+'/'+waterbill.house+'/WATER BILL: Greetings '+waterbill.housename+' :'+waterbill.monthdate+';Previous:'+waterbill.previous+';Current:'+waterbill.current+';UnitCost Kshs.'+waterbill.cost+';Units:'+waterbill.units+';'+(waterbill.total_os>0?'CC:'+waterbill.total+';Other:'+waterbill.total_os+';':'')+'Total Kshs.'+(waterbill.total+waterbill.total_os)+'.Thank You'}
                                            onChange={handleChange} />
                                            WATER BILL: Greetings {waterbill.housename} :{waterbill.monthdate}; Previous:{waterbill.previous}; Current:{waterbill.current}; Cost Kshs.{waterbill.cost}; Units:{waterbill.units}; {waterbill.total_os>0?'CC:'+waterbill.total+';Other:'+waterbill.total_os+';':''} Total Kshs.{waterbill.total+waterbill.total_os}.
                                    </label>
                                }
                                </>
                                :
                                <label className=" col-12 bg-danger m-0 p-1">
                                    WATER BILL: Greetings {waterbill.housename} :{waterbill.monthdate}; Previous:{waterbill.previous}; Current:{waterbill.current}; Cost Kshs.{waterbill.cost}; Units:{waterbill.units}; {waterbill.total_os>0?'CC:'+waterbill.total+';Other:'+waterbill.total_os+';':''} Total Kshs.{waterbill.total+waterbill.total_os}.
                                </label>
                            }
                            </div>
                        }
                    </div>
                </div>
            </div>

        </div>
    </div>
  );
}

export default WaterbillMessageTable;