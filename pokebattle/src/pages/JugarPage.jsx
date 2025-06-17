import Carta from "@/components/CartaComponent";
import CartaOculta from "@/components/CartaServidorComponent";
import fondo from "@/assets/fondo.png";
import { Container, Row, Col, Image } from "react-bootstrap";

// Valores de ejemplo
const mazoUsuario = [
  { nombre: "arcanine", atributo: "Eléctrico", ataque: 55 },
  { nombre: "blastoise", atributo: "Fuego", ataque: 60 },
  { nombre: "charizard", atributo: "Planta", ataque: 50 },
  { nombre: "arcanine", atributo: "Agua", ataque: 48 },
  { nombre: "blastoise", atributo: "Normal", ataque: 40 },
];

const mazoServidor = [
  { atributo: "Agua" },
  { atributo: "Fuego" },
  { atributo: "Planta" },
  { atributo: "Eléctrico" },
  { atributo: "Psíquico" },
];

const JuegoPage = () => {
  return (
    <Container fluid className="py-4 d-flex flex-column align-items-center">
      {/* Cartas del servidor (ocultas) */}
      <div className="d-flex justify-content-center gap-3 mb-4 flex-wrap">
        {mazoServidor.map((carta, i) => (
          <CartaOculta atributo={carta.atributo} key={i} />
        ))}
      </div>

      {/* Tablero central */}
      <Row className="justify-content-center mb-4">
        <Col xs="auto">
          <Image
            src={fondo}
            alt="Tablero"
            style={{
              width: "66rem", // mismo ancho que 5 cartas
              height: "200px",
              objectFit: "cover",
              borderRadius: "1rem",
              border: "3px solid #333",
              boxShadow: "0 0 10px rgba(0,0,0,0.5)",
            }}
          />
        </Col>
      </Row>

      {/* Cartas del usuario (visibles) */}
      <div className="d-flex justify-content-center gap-3 mt-3 flex-wrap">
        {mazoUsuario.map((carta, i) => (
          <Carta
            key={i}
            nombre={carta.nombre}
            atributo={carta.atributo}
            ataque={carta.ataque}
          />
        ))}
      </div>
    </Container>
  );
};

export default JuegoPage;
