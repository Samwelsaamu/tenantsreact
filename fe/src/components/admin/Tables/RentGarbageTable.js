import {Link, Navigate, useNavigate} from 'react-router-dom';

function RentGarbageTable({waterbill,no,handleShow,uploadwaterbill,allchecked,handleChange}) {
    
  return (
    <tr className={`unwaterbillvaluesdiv m-0 p-0 ${(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.pid+'?'+waterbill.paymentid+'?')).length ?'bg-secondary text-info':(waterbill.saved=='No'?'bg-light text-dark':'bg-warning text-dark')}`}
      key={no} >
        <td className={`m-0 p-0 ${(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.pid+'?'+waterbill.paymentid+'?')).length ?'bg-secondary text-info':(waterbill.saved=='No'?'bg-light text-dark':'bg-warning text-dark')}`}>
            {allchecked?
                <label className="selwaterbill d-block m-0 p-1" style={{"fontSize":"12px"}}>
                    <input type="checkbox" 
                        className="selectedwaterbilltenants" 
                        name="waterbillvalues[]"
                        checked={allchecked}
                        value={waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.pid+'?'+waterbill.paymentid+'?'}
                        onChange={handleChange} />
                        <span className='m-0 p-1'>{no+1}</span>
                </label>
            :
                <label className="selwaterbill d-block m-0 p-1" style={{"fontSize":"12px"}}>
                    <input type="checkbox" 
                        className="selectedwaterbilltenants" 
                        name="waterbillvalues[]"
                        checked={(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.pid+'?'+waterbill.paymentid+'?')).length}
                        value={waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.pid+'?'+waterbill.paymentid+'?'}
                        onChange={handleChange} />
                        <span className='m-0 p-1'>{no+1}</span>
                </label>
            }
        </td>
        <th className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.pid+'?'+waterbill.paymentid+'?')).length ?'bg-secondary text-info':(waterbill.saved=='No'?'bg-light text-dark':'bg-warning text-dark')}`}>{waterbill.housename}</th>
        <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.pid+'?'+waterbill.paymentid+'?')).length ?'bg-secondary text-info':(waterbill.saved=='No'?'bg-light text-dark':'bg-warning text-dark')}`} title={waterbill.tenantname}>{waterbill.tenantfname}</td>
        <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.pid+'?'+waterbill.paymentid+'?')).length ?'bg-secondary text-info':(waterbill.saved=='No'?'bg-light text-dark':'bg-warning text-dark')}`}>{new Number((waterbill.Arrears-waterbill.Excess)).toFixed(2)}</td>
        <td className={`m-0 p-1 ${(waterbill.Rent>0)?'bg-warning text-dark':'bg-light text-dark'}`}>{new Number(waterbill.Rent).toFixed(2)}</td>
        <td className={`m-0 p-1 ${(waterbill.Rent>0)?'bg-warning text-dark':'bg-light text-dark'}`}>{new Number(waterbill.Garbage).toFixed(2)}</td>
       
        <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.pid+'?'+waterbill.paymentid+'?')).length ?'bg-secondary text-info':(waterbill.saved=='No'?'bg-light text-dark':'bg-warning text-dark')}`}>{new Number(waterbill.Waterbill).toFixed(2)}</td>
        <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.pid+'?'+waterbill.paymentid+'?')).length ?'bg-secondary text-info':(waterbill.saved=='No'?'bg-light text-dark':'bg-warning text-dark')}`} title='House Deposit + KPLC Deposit + Water Deposit'>
                                {new Number(waterbill.HseDeposit).toFixed(2)}
                                +{new Number(waterbill.KPLC).toFixed(2)}
                                +{new Number(waterbill.Water).toFixed(2)}</td>
        <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.pid+'?'+waterbill.paymentid+'?')).length ?'bg-secondary text-info':(waterbill.saved=='No'?'bg-light text-dark':'bg-warning text-dark')}`}>{new Number(waterbill.Lease).toFixed(2)}</td>
        <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.pid+'?'+waterbill.paymentid+'?')).length ?'bg-secondary text-info':(waterbill.saved=='No'?'bg-light text-dark':'bg-warning text-dark')}`}>{new Number(((waterbill.Rent)
                                +(waterbill.Arrears)
                                +(waterbill.Garbage)
                                +(waterbill.Waterbill)
                                +(waterbill.HseDeposit)
                                +(waterbill.KPLC)
                                +(waterbill.Water)
                                +(waterbill.Lease))
                                -(waterbill.Excess)).toFixed(2)}</td>


        <td className='m-0 p-1'>
        {waterbill.saved==='Yes' && <button className='btn btn-warning m-0 p-0 pl-2 pr-2' onClick={()=>{handleShow(waterbill)}}><small>Edit</small></button>}
        {waterbill.saved==='No' && <button className='btn btn-info m-0 p-0 pl-2 pr-2 text-white' onClick={()=>{handleShow(waterbill)}}><small>Add</small></button>} 
        </td>

    </tr>
  );
}

export default RentGarbageTable;