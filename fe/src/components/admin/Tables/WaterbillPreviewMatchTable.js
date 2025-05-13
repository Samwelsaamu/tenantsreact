import {Link, Navigate, useNavigate} from 'react-router-dom';

function WaterbillPreviewMatchTable({waterbill,no,preview,uploadwaterbill,allchecked,handleChange}) {
    
  return (
    <tr 
        className={`unwaterbillvaluesdiv m-0 p-0 ${(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.previous+'?'+waterbill.current+'?'+waterbill.cost+'?'+waterbill.units+'?'+waterbill.total+'?'+waterbill.waterid+'?')).length ?'bg-secondary text-info':'bg-light text-dark'}`}
        key={no} style={{"padding":"0px","margin":"0"}}>
        {waterbill.saved==='Yes'?
            <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.previous+'?'+waterbill.current+'?'+waterbill.cost+'?'+waterbill.units+'?'+waterbill.total+'?'+waterbill.waterid+'?')).length ?'bg-secondary text-info':'bg-light text-dark'}`}>
                {allchecked?
                    <label className="selwaterbill d-block m-0 p-1">
                        <input type="checkbox" 
                            className="selectedwaterbilltenants" 
                            name="waterbillvalues[]"
                            checked={allchecked}
                            value={waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.previous+'?'+waterbill.current+'?'+waterbill.cost+'?'+waterbill.units+'?'+waterbill.total+'?'+waterbill.waterid+'?'}
                            onChange={handleChange} />
                            <span className='m-0 p-1'>{no+1}</span>
                    </label>
                :
                    <label className="selwaterbill d-block m-0 p-1">
                        <input type="checkbox" 
                            className="selectedwaterbilltenants" 
                            name="waterbillvalues[]"
                            checked={(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.previous+'?'+waterbill.current+'?'+waterbill.cost+'?'+waterbill.units+'?'+waterbill.total+'?'+waterbill.waterid+'?')).length}
                            value={waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.previous+'?'+waterbill.current+'?'+waterbill.cost+'?'+waterbill.units+'?'+waterbill.total+'?'+waterbill.waterid+'?'}
                            onChange={handleChange} />
                            <span className='m-0 p-1'>{no+1}</span>
                    </label>
                }
            </td>
        :
            <td className='m-0 p-1'><span className='m-0 p-1'>{no+1}</span></td>
        }
        
        <th className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.previous+'?'+waterbill.current+'?'+waterbill.cost+'?'+waterbill.units+'?'+waterbill.total+'?'+waterbill.waterid+'?')).length ?'bg-secondary text-info':'bg-light text-dark'}`}>{waterbill.housename}</th>
        <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.previous+'?'+waterbill.current+'?'+waterbill.cost+'?'+waterbill.units+'?'+waterbill.total+'?'+waterbill.waterid+'?')).length ?'bg-secondary text-info':'bg-light text-dark'}`} title={waterbill.tenantname}>{waterbill.tenantfname}</td>
        <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.previous+'?'+waterbill.current+'?'+waterbill.cost+'?'+waterbill.units+'?'+waterbill.total+'?'+waterbill.waterid+'?')).length ?'bg-secondary text-info':'bg-light text-dark'}`}>{waterbill.previous}</td>
        <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.previous+'?'+waterbill.current+'?'+waterbill.cost+'?'+waterbill.units+'?'+waterbill.total+'?'+waterbill.waterid+'?')).length ?'bg-secondary text-info':'bg-light text-dark'}`}>{waterbill.current}</td>
        <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.previous+'?'+waterbill.current+'?'+waterbill.cost+'?'+waterbill.units+'?'+waterbill.total+'?'+waterbill.waterid+'?')).length ?'bg-secondary text-info':'bg-light text-dark'}`}>{new Number(waterbill.cost).toFixed(2)}</td>
        <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.previous+'?'+waterbill.current+'?'+waterbill.cost+'?'+waterbill.units+'?'+waterbill.total+'?'+waterbill.waterid+'?')).length ?'bg-secondary text-info':'bg-light text-dark'}`}>{waterbill.units}</td>
        <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.previous+'?'+waterbill.current+'?'+waterbill.cost+'?'+waterbill.units+'?'+waterbill.total+'?'+waterbill.waterid+'?')).length ?'bg-secondary text-info':'bg-light text-dark'}`}>{new Number((waterbill.total)+(waterbill.total_os)).toFixed(2)}</td>
        {preview &&
            <>
                <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.previous+'?'+waterbill.current+'?'+waterbill.cost+'?'+waterbill.units+'?'+waterbill.total+'?'+waterbill.waterid+'?')).length ?'bg-secondary text-info':'bg-light text-dark'}`}>{waterbill.saved_bill}</td>
                <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.previous+'?'+waterbill.current+'?'+waterbill.cost+'?'+waterbill.units+'?'+waterbill.total+'?'+waterbill.waterid+'?')).length ?'bg-secondary text-info':'bg-light text-dark'}`}>{waterbill.saved}</td>
                <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.previous+'?'+waterbill.current+'?'+waterbill.cost+'?'+waterbill.units+'?'+waterbill.total+'?'+waterbill.waterid+'?')).length ?'bg-secondary text-info':'bg-light text-dark'}`}>{waterbill.prevmatches}</td>
            </>
        }
        
    </tr>
  );
}

export default WaterbillPreviewMatchTable;