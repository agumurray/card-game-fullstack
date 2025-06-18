import { Modal, Button } from "react-bootstrap";

const ResultadoFinalComponent = ({
  show,
  resultado,
  onJugarOtraVez,
  onIrAMazos,
}) => {
  return (
    <Modal show={show} onHide={onIrAMazos} centered backdrop="static">
      <Modal.Header>
        <Modal.Title>¡Fin de la Partida!</Modal.Title>
      </Modal.Header>
      <Modal.Body className="text-center">
        <h4 className="mb-4 text-primary">
          {resultado === "gano"
            ? "¡Ganaste la partida!"
            : resultado === "perdio"
            ? "Perdiste la partida"
            : resultado === "empato"
            ? "Empate"
            : "Resultado desconocido"}
        </h4>

        <p>¿Qué te gustaría hacer ahora?</p>
      </Modal.Body>
      <Modal.Footer className="d-flex justify-content-between">
        <Button variant="secondary" onClick={onIrAMazos}>
          Volver a mis mazos
        </Button>
        <Button variant="primary" onClick={onJugarOtraVez}>
          Jugar otra vez
        </Button>
      </Modal.Footer>
    </Modal>
  );
};

export default ResultadoFinalComponent;
