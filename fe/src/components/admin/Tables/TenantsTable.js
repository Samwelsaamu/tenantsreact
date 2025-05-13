import {Link, Navigate, useNavigate} from 'react-router-dom';
import TenantsHouseLinks from './TenantsHouseLinks';

function TenantsTable({property,no,handleShowAddTenant,handleShowAddProperty,deleteTenant}) {
    // console.log(property)
  return (
        <tr key={no} style={{"padding":"0px","margin":"0","backgroundColor":"#FFFFFF"}}>
            <th className='m-0 p-1'>{no+1}</th>
            <th className='m-0 p-1'><Link to={'/properties/mgr/tenants/'+property.id}>{property.Fname}</Link></th>
            <td className='m-0 p-0'>{property.Oname}</td>
            {/* <td className='m-0 p-0'>{property.Gender}</td>
            <td className='m-0 p-0'>{property.IDno}</td>
            <td className='m-0 p-0'>{property.PhoneMasked}</td> */}
            <td className='m-0 p-1'>{property.Status}</td>
            <td className='m-0 p-1'>{property.Houses}</td>
            <td className='m-0 p-0' style={{"whiteSpace":"nowrap"}}>
                {property.housesdata  && property.housesdata.map((houses,key) => (
                    <TenantsHouseLinks houses={houses} key={key} no={key} handleShowAddProperty={handleShowAddProperty}/>
                ))
                }
            </td>
            <td className=' m-0 p-1'>
            <div className='d-flex justify-content-center my-auto'>
                {property.Houses===0 ?
                    <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-info'><Link to={'/properties/mgr/tenants/'+property.id+'/assign/'} className='text-success'><i className='fa fa-check'></i> Assign House</Link></button>
                    // <button className='bg-white m-0 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-success' onClick={()=>{handleShowAddProperty(property)}}><small><i className='fa fa-plus'></i> Assign House</small></button>
                    :
                    <>
                        <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-info'><Link to={'/properties/mgr/tenants/'+property.id+'/addhouse/'} className='text-info'><i className='fa fa-plus-circle'></i> Add House</Link></button>
                        <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-danger'><Link to={'/properties/mgr/tenants/'+property.id+'/vacate/'+property.House} className='text-danger'><i className='fa fa-minus-circle'></i> Vacate</Link></button>
                        <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-info'><Link to={'/properties/mgr/tenants/'+property.id+'/reassign/'+property.House} className='text-success'><i className='fa fa-exchange-alt'></i> Change</Link></button>
                        <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-info'><Link to={'/properties/mgr/tenants/'+property.id+'/transfer/'+property.House} className='text-primary'><i className='fa fa-play'></i> Transfer</Link></button>
                        {/* <button className='bg-white m-0 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-info' onClick={()=>{handleShowAddTenant(property)}}><small><i className='fa fa-plus'></i> Add</small></button>
                        <button className='bg-white m-0 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-success' onClick={()=>{handleShowAddTenant(property)}}><small><i className='fa fa-edit'></i> Change</small></button> */}
                    </>
                }
            
            <button className='bg-warning m-0 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-dark' onClick={()=>{handleShowAddTenant(property)}}><small><i className='fa fa-edit'></i> Edit</small></button>
            <button className='bg-danger m-0 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-white' onClick={()=>{deleteTenant(property)}}><small><i className='fa fa-trash'> Delete</i></small></button>
            </div>
            </td>

        </tr>
  );
}

export default TenantsTable;