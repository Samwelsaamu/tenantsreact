import Toast from 'react-bootstrap/Toast';
import ToastContainer from 'react-bootstrap/ToastContainer';

function AddToast({handleCloseToast,showtoast,message}) {
// console.log(message)
  return (
    <>
        <Toast className='bg-success' onClose={() =>handleCloseToast()} show={showtoast} >
            <Toast.Header className='bg-info text-danger'>
                <strong className='me-auto'>Something has Changed</strong>
            </Toast.Header>
            <Toast.Body className='text-white'>
                {message}
            </Toast.Body>
        </Toast>
    </>
  );
}

export default AddToast;