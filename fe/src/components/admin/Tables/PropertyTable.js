import {Link, Navigate, useNavigate} from 'react-router-dom';

function PropertyTable({property,no,handleShowAddHouse,deleteHouse,handleChange,allchecked,uploadwaterbill}) {
    
  return (
    <tr className={`unwaterbillvaluesdiv m-0 p-0 ${(uploadwaterbill.upwaterbill.filter( (f) => f === property.id+'?'+property.Housename+'?'+property.tenant+'?'+property.tenantname+'?')).length ?'bg-secondary text-white':'bg-light text-dark'}`}
       key={no} style={{"padding":"0px","margin":"0"}}>
        <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === property.id+'?'+property.Housename+'?'+property.tenant+'?'+property.tenantname+'?')).length ?'bg-secondary text-white':'bg-light text-dark'}`}>
        {allchecked?
              <label className="selwaterbill d-block m-0 p-1" style={{"fontSize":"12px"}}>
                  <input type="checkbox" 
                      className="selectedwaterbilltenants" 
                      name="waterbillvalues[]"
                      checked={allchecked}
                      value={property.id+'?'+property.Housename+'?'+property.tenant+'?'+property.tenantname+'?'}
                      onChange={handleChange} />
                      <span className='m-0 p-1'>{no+1}</span>
              </label>
          :
              <label className="selwaterbill d-block m-0 p-1" style={{"fontSize":"12px"}}>
                  <input type="checkbox" 
                      className="selectedwaterbilltenants" 
                      name="waterbillvalues[]"
                      checked={(uploadwaterbill.upwaterbill.filter( (f) => f === property.id+'?'+property.Housename+'?'+property.tenant+'?'+property.tenantname+'?')).length}
                      value={property.id+'?'+property.Housename+'?'+property.tenant+'?'+property.tenantname+'?'}
                      onChange={handleChange} />
                      <span className='m-0 p-1'>{no+1}</span>
              </label>
          }
        </td>
        {(uploadwaterbill.upwaterbill.filter( (f) => f === property.id+'?'+property.Housename+'?'+property.tenant+'?'+property.tenantname+'?')).length ?
        <th className={`m-0 p-1 text-white ${(uploadwaterbill.upwaterbill.filter( (f) => f === property.id+'?'+property.Housename+'?'+property.tenant+'?'+property.tenantname+'?')).length ?'bg-secondary text-white':'bg-light text-dark'}`}>{property.Housename}</th>
        :
        <th className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === property.id+'?'+property.Housename+'?'+property.tenant+'?'+property.tenantname+'?')).length ?'bg-secondary text-white':'bg-light text-dark'}`}><Link to={'/properties/house/'+property.Plot+'/'+property.id}>{property.Housename}</Link></th>
        }
        <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === property.id+'?'+property.Housename+'?'+property.tenant+'?'+property.tenantname+'?')).length ?'bg-secondary text-white':'bg-light text-dark'}`}>{property.tenantname}</td>
        <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === property.id+'?'+property.Housename+'?'+property.tenant+'?'+property.tenantname+'?')).length ?'bg-secondary text-white':'bg-light text-dark'}`}>{property.housetypename}</td>
        <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === property.id+'?'+property.Housename+'?'+property.tenant+'?'+property.tenantname+'?')).length ?'bg-secondary text-white':'bg-light text-dark'}`}>{property.Rent}</td>
        {/* <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === property.id+'?'+property.Housename+'?'+property.tenant+'?'+property.tenantname+'?')).length ?'bg-secondary text-white':'bg-light text-dark'}`}>{property.Deposit}</td> */}
        <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === property.id+'?'+property.Housename+'?'+property.tenant+'?'+property.tenantname+'?')).length ?'bg-secondary text-white':'bg-light text-dark'}`}>{property.Water}</td>
        <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === property.id+'?'+property.Housename+'?'+property.tenant+'?'+property.tenantname+'?')).length ?'bg-secondary text-white':'bg-light text-dark'}`}>{property.Kplc}</td>
        <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === property.id+'?'+property.Housename+'?'+property.tenant+'?'+property.tenantname+'?')).length ?'bg-secondary text-white':'bg-light text-dark'}`}>{property.Lease}</td>
        <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === property.id+'?'+property.Housename+'?'+property.tenant+'?'+property.tenantname+'?')).length ?'bg-secondary text-white':'bg-light text-dark'}`}>{property.Garbage}</td>
        <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === property.id+'?'+property.Housename+'?'+property.tenant+'?'+property.tenantname+'?')).length ?'bg-secondary text-white':'bg-light text-dark'}`}>{property.DueDay}</td>
        <td className={`m-0 p-1 ${(uploadwaterbill.upwaterbill.filter( (f) => f === property.id+'?'+property.Housename+'?'+property.tenant+'?'+property.tenantname+'?')).length ?'bg-secondary text-white':'bg-light text-dark'}`}>{property.Status}</td>
        <td className='d-flex justify-content-center m-0 p-1'>
            <button className='bg-warning m-0 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-dark' onClick={()=>{handleShowAddHouse(property)}}><small><i className='fa fa-edit'></i> Edit</small></button>
            <button className='bg-danger m-0 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-white' onClick={()=>{deleteHouse(property)}}><small><i className='fa fa-trash'></i> Delete</small></button>
        </td>
        
    </tr>
  );
}

export default PropertyTable;