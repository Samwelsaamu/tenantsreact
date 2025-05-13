import {Link, Navigate, useNavigate} from 'react-router-dom';

function MessagesNewTable({message,no,preview,handleShow,deleteMessage,resendMessage}) {
    
  return (
    <tr key={no} style={{"padding":"0px","margin":"0","backgroundColor":"#FFFFFF"}}>
        <td className='m-0 p-1'>{no+1}</td>
        <th className='m-0 p-1'>{message.Phone}</th>
        <td className='m-0 p-0' title={message.Message}>{message.MessageMasked}</td>
        <td className='m-0 p-1'>{message.Status}</td>
        <td className='m-0 p-1'>{message.updated_at}</td>
        
        <td className='d-flex m-0 p-1'>
          {/* {message.Status !='success' && <button className='btn btn-warning m-1 p-0 pl-1 pr-1' onClick={()=>{handleShow(message)}}><small className='fa fa-paper-plane'></small></button>} */}
          <button className='btn btn-warning m-1 p-0 pl-1 pr-1' onClick={()=>{resendMessage(message)}}><small className='fa fa-paper-plane'></small></button>
          <button className='btn btn-success m-1 p-0 pl-1 pr-1' onClick={()=>{handleShow(message)}}><small className='fa fa-eye'></small></button>
          <button className='btn btn-danger m-1 p-0 pl-1 pr-1 text-white' onClick={()=>{deleteMessage(message)}}><small className='fa fa-trash'></small></button>
        </td>

    </tr>
  );
}

export default MessagesNewTable;