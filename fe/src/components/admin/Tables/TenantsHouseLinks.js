import {Link, Navigate, useNavigate} from 'react-router-dom';

function TenantsHouseLinks({houses,no,handleShowAddProperty}) {
    
  return (
        <button className='bg-white text-info border-info m-0 ml-1 pt-1 pl-2 pr-2 pb-1' onClick={()=>{handleShowAddProperty(houses)}}><small> <i className='fa fa-info-circle'></i> {houses.Housename}</small></button>
        // <Link key={no} className='pr-1 pl-1' to={'/properties/house/'+houses.pid+'/'+houses.hid}>{houses.Housename}</Link>
  );
}

export default TenantsHouseLinks;