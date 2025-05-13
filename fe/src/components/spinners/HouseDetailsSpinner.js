import Card from 'react-bootstrap/Card';
import Placeholder from 'react-bootstrap/Placeholder';

function HouseDetailsSpinner() {
    

  return (
    <>
      <Card.Body>
        <Placeholder as={Card.Text} animation="glow">
            <Placeholder  className='border-info bg-white' xs={12} style={{"padding": "2%","marginTop":"-20px"}}>
                <Placeholder className='border-info bg-info' style={{"height":"25px","borderRadius":"30px"}} xs={3} /> <Placeholder className='border-info bg-info' xs={5} /> <Placeholder className='border-info bg-info'  style={{"height":"25px","float":"right","borderRadius":"30px"}} xs={3} />
                <Placeholder className='border-info bg-primary' xs={5} /> <Placeholder className='border-info bg-primary' style={{"float":"right"}} xs={5} />
                <Placeholder className='border-info bg-primary' xs={5} /> <Placeholder className='border-info bg-primary' style={{"float":"right"}} xs={5} />
                <Placeholder className='border-info bg-primary' xs={5} /> <Placeholder className='border-info bg-primary' style={{"float":"right"}} xs={5} />
            </Placeholder> 
        
        </Placeholder>
      </Card.Body>
    </>
  );
}

export default HouseDetailsSpinner;