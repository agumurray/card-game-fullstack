import { Modal, Button } from "react-bootstrap";
import Carta from "./CartaComponent";

const ResultadoRondaComponent = ({
  show,
  onHide,
  cartaJugador,
  cartaServidor,
  fuerzaJugador,
  fuerzaServidor,
  resultado,
  mensajeFinal,
}) => {
  return (
    <Modal show={show} onHide={onHide} centered backdrop="static">
      <Modal.Header closeButton>
        <Modal.Title>Resultado de la ronda</Modal.Title>
      </Modal.Header>
      <Modal.Body className="text-center">
        <div className="d-flex justify-content-around flex-wrap">
          <div>
            <h5>Tu carta</h5>
            <Carta {...cartaJugador} />
            <p className="mt-2">
              <strong>Fuerza:</strong> {fuerzaJugador}
            </p>
          </div>
          <div>
            <h5>Carta del servidor</h5>
            <Carta {...cartaServidor} />
            <p className="mt-2">
              <strong>Fuerza:</strong> {fuerzaServidor}
            </p>
          </div>
        </div>
        <h4 className="mt-4 text-primary">
          Resultado:{" "}
          {resultado || fuerzaJugador > fuerzaServidor
            ? "Ganaste"
            : fuerzaJugador < fuerzaServidor
            ? "Perdiste"
            : "Empate"}
        </h4>
        {mensajeFinal && (
          <div className="mt-3 alert alert-info">{mensajeFinal}</div>
        )}
      </Modal.Body>
      <Modal.Footer>
        <Button onClick={onHide}>Aceptar</Button>
      </Modal.Footer>
    </Modal>
  );
};

export default ResultadoRondaComponent;
