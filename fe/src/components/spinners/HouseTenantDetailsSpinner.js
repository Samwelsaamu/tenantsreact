import Card from 'react-bootstrap/Card';
import Placeholder from 'react-bootstrap/Placeholder';

function HouseTenantDetailsSpinner() {
    

  return (
    <>
      <Card.Body>
        <Placeholder as={Card.Text} animation="glow">
            <Placeholder  className='border-info bg-white' xs={12} style={{"padding": "2%","marginTop":"-20px"}}>
                <Placeholder className='border-info bg-info' style={{"height":"30px","borderRadius":"50%"}} xs={1} /> <Placeholder className='border-info bg-info' xs={7} /> <Placeholder className='border-info bg-info' style={{"height":"40px","float":"right","borderRadius":"50%"}} xs={2} /> 
                <Placeholder className='border-info bg-primary' xs={12} />
                <Placeholder className='border-info bg-primary' xs={10} /> 
                <Placeholder className='border-info bg-primary' xs={12} /> 
            </Placeholder> 
        
        </Placeholder>
      </Card.Body>
    </>
  );
}

export default HouseTenantDetailsSpinner;