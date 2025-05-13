import {Link, Navigate, useNavigate} from 'react-router-dom';

function RoleUsersTable({user,no,assignUser,removeUser}) {
    
  return (
    <tr className={`unwaterbillvaluesdiv m-0 p-0`}
      key={no} >
        
        <td className='m-0 p-1'>{no+1}</td>
        
        <td className='m-0 p-1 text-left'>{user.Fullname} </td>
        <td className='m-0 p-1 text-left'>{user.Userrole} </td>
        <td className=' m-0 p-1'>
          <div className='d-flex justify-content-center my-auto'>
            {user.hasRoles?
              <button className='bg-danger m-0 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-white' onClick={()=>{removeUser(user)}}><small><i className='fa fa-times'> Remove</i></small></button>
            :
              <button className='bg-success m-0 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-white' onClick={()=>{assignUser(user)}}><small><i className='fa fa-check'></i> Assign</small></button>
            }
          </div>
        </td>
    </tr>
  );
}

export default RoleUsersTable;