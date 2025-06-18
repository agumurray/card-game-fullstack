import { Card } from "react-bootstrap";

const CartaServidorComponent = ({ atributo }) => {
  return (
    <Card
      style={{
        width: "12rem",
        height: "270px",
        border: "2px solid #444",
        backgroundColor: "#333",
        color: "#fff",
        boxShadow: "0 4px 8px rgba(0, 0, 0, 0.2)",
      }}
      className="text-center m-0 p-0 d-flex flex-column justify-content-center"
    >
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
