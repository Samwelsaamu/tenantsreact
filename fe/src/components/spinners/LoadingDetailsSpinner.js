import Card from 'react-bootstrap/Card';
import Placeholder from 'react-bootstrap/Placeholder';

function LoadingDetailsSpinner() {
    

  return (
    <>
      <Card.Body>
        <Placeholder as={Card.Text} animation="glow">
            <Placeholder  className='border-info bg-white' xs={12} style={{"padding": "2%","marginTop":"-20px"}}>
                <Placeholder className='border-info bg-info mb-3' style={{"height":"25px","borderRadius":"30px"}} xs={3} /> <Placeholder className='border-info bg-info mb-3' xs={5} /> <Placeholder className='border-info bg-info mb-3'  style={{"height":"25px","float":"right","borderRadius":"30px"}} xs={3} />
                <Placeholder className='border-info bg-primary' xs={5} /> <Placeholder className='border-info bg-primary' style={{"float":"right"}} xs={5} />
                <Placeholder className='border-info bg-primary' xs={5} /> <Placeholder className='border-info bg-primary' style={{"float":"right"}} xs={5} />
                {/* <Placeholder className='border-info bg-primary' xs={5} /> <Placeholder className='border-info bg-primary' style={{"float":"right"}} xs={5} /> */}

                <Placeholder className='border-info bg-primary p-5 mt-3' xs={5} /> <Placeholder className='border-info bg-primary p-5 mt-3' style={{"float":"right"}} xs={5} />
                
                <Placeholder className='border-info bg-primary mt-2 p-3' xs={5} /> <Placeholder className='border-info bg-primary mt-2 p-3' style={{"float":"right"}} xs={5} />
                {/* <Placeholder className='border-info bg-primary mt-2 p-3' xs={5} /> <Placeholder className='border-info bg-primary mt-2 p-3' style={{"float":"right"}} xs={5} />
                <Placeholder className='border-info bg-primary mt-2 p-3' xs={5} /> <Placeholder className='border-info bg-primary mt-2 p-3' style={{"float":"right"}} xs={5} /> */}
                
                <Placeholder className='border-info bg-primary p-4 mt-2' xs={12} /> <Placeholder className='border-info bg-primary p-4 mt-2' style={{"float":"right"}} xs={12} />

            </Placeholder> 
        
        </Placeholder>
      </Card.Body>
    </>
  );
}

export default LoadingDetailsSpinner;