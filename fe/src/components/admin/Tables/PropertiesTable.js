import {Link, Navigate, useNavigate} from 'react-router-dom';

function PropertiesTable({property,no,handleShowAddHouse,handleShowAddProperty,deleteProperty}) {
    
  return (
        <tr key={no} style={{"padding":"0px","margin":"0","backgroundColor":"#FFFFFF"}}>
            <th className='m-0 p-1'>{no+1}</th>
            <th className='m-0 p-1'  style={{"whiteSpace":"nowrap"}}><Link to={'/properties/manage/'+property.id}> {property.Plotname}</Link></th>
            <td className='m-0 p-0'>{property.Plotcode}</td>
            <td className='m-0 p-0'>{property.propertytypename}</td>
            {/* <td className='m-0 p-0'>{property.Plotarea}</td>
            <td className='m-0 p-0'>{property.Plotaddr}</td>
            <td className='m-0 p-0'>{property.Plotdesc}</td> */}
            <td className='m-0 p-1'>{property.Waterbill}</td>
            {/* <td className='m-0 p-1'>{property.Deposit}</td>
            <td className='m-0 p-1'>{property.Waterdeposit}</td>
            <td className='m-0 p-1'>{property.Outsourced}</td>
            <td className='m-0 p-1'>{property.Garbage}</td>
            <td className='m-0 p-1'>{property.Kplcdeposit}</td> */}
            <td className='m-0 p-1'><Link to={'/properties/manage/'+property.id}><i className='fa fa-info-circle'></i> View ({property.totalhouses})</Link></td>
            <td className='m-0 p-1'>{property.totaloccupied}/{property.totalhouses}</td>
            {/* <td className='m-0 p-1'>{property.totaltenants}</td> */}
            <td className=' m-0 p-1'>
            <div className='d-flex justify-content-center my-auto'>
              {property.id!=='all' &&
             <>
              <button className='bg-white m-0 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-success' onClick={()=>{handleShowAddHouse(property)}}><small><i className='fa fa-plus-circle'></i> New House</small></button>
              <button className='bg-warning m-0 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-dark' onClick={()=>{handleShowAddProperty(property)}}><small><i className='fa fa-edit'></i> Edit</small></button>
              <button className='bg-danger m-0 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-white' onClick={()=>{deleteProperty(property)}}><small><i className='fa fa-trash'></i> Delete</small></button>
              </>
             }
            </div>
            </td>

        </tr>
  );
}

export default PropertiesTable;