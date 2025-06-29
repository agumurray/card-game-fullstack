import { Card } from "react-bootstrap";
import "@/styles/CartaServidorComponent.css"; // nuevo import

const CartaServidorComponent = ({ atributo }) => {
  return (
    <Card className="carta-servidor text-center m-0 p-0 d-flex flex-column justify-content-center">
      <Card.Body className="d-flex flex-column justify-content-center p-3">
        <Card.Title className="mb-3">Carta Oculta</Card.Title>
        <Card.Text>
          <strong>Atributo:</strong> {atributo}
        </Card.Text>
      </Card.Body>
    </Card>
  );
};

export default CartaServidorComponent;
