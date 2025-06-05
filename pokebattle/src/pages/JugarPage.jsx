import { useEffect, useState, useRef } from "react";
import { useParams } from "react-router-dom";
import { Container, Row, Col, Card, Alert, Button } from "react-bootstrap";
import { useAuth } from "../contexts/useAuth";
import { crearPartida, jugarCarta, getAtributos } from "../services/apiService";

const JugarPage = () => {
  const { usuario } = useAuth();
  const { idMazo: mazoId } = useParams();
  const [partidaId, setPartidaId] = useState(null);
  const [cartasUsuario, setCartasUsuario] = useState([]);
  const [atributosServidor, setAtributosServidor] = useState([]);
  const [jugadas, setJugadas] = useState([]);
  const [resultadoRonda, setResultadoRonda] = useState(null);
  const [resultadoFinal, setResultadoFinal] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  const yaInicio = useRef(false); // 🔒 evita múltiples llamadas

  useEffect(() => {
    const iniciar = async () => {
      try {
        const res = await crearPartida(Number(mazoId));
        setPartidaId(res.data["id de partida"]);
        setCartasUsuario(res.data.cartas);
        const attrRes = await getAtributos(1, res.data["id de partida"]);
        setAtributosServidor(attrRes.data["atributos en mano"] || []);
      } catch (e) {
        setError(e.response?.data?.error || "Error al iniciar partida");
      } finally {
        setLoading(false);
      }
    };

    if (usuario?.id && !yaInicio.current) {
      yaInicio.current = true;
      iniciar();
    }
  }, [usuario, mazoId]);

  const handleJugarCarta = async (carta) => {
    if (!partidaId || resultadoFinal) return;
    try {
      const res = await jugarCarta(partidaId, carta.id);

      const nuevaJugada = {
        cartaUsuario: carta,
        cartaServidorId: res.data["carta servidor"],
        fuerzaUsuario: res.data["Fuerza usuario"],
        fuerzaServidor: res.data["Fuerza servidor"],
      };

      setJugadas((prev) => [...prev, nuevaJugada]);
      setResultadoRonda(res.data.mensaje || "Ronda jugada");

      if (res.data["mensaje"] === "La partida ha finalizado") {
        setResultadoFinal(res.data["el_usuario"]);
      }

      setCartasUsuario((prev) => prev.filter((c) => c.id !== carta.id));

      if (!res.data["mensaje"]) {
        const attrRes = await getAtributos(1, partidaId);
        setAtributosServidor(attrRes.data["atributos en mano"] || []);
      }
    } catch (e) {
      setError(e.response?.data?.error || "Error al jugar carta");
    }
  };

  if (loading) return <p>Cargando partida...</p>;
  if (error) return <Alert variant="danger">{error}</Alert>;

  return (
    <Container className="mt-4">
      <h2 className="text-center mb-4">Partida en juego</h2>

      <Row className="mb-4 justify-content-center">
        {atributosServidor.map((attr, idx) => (
          <Col key={idx} xs={2}>
            <Card className="text-center">
              <Card.Body>
                <Card.Title>🂠</Card.Title>
                <Card.Text>{attr.nombre}</Card.Text>
              </Card.Body>
            </Card>
          </Col>
        ))}
      </Row>

      <Row className="mt-4 justify-content-center">
        {cartasUsuario.map((carta) => (
          <Col key={carta.id} xs={2}>
            <Card
              onDoubleClick={() => handleJugarCarta(carta)}
              style={{ cursor: "pointer" }}
            >
              {carta.imagen && (
                <Card.Img variant="top" src={carta.imagen} alt={carta.nombre} />
              )}
              <Card.Body>
                <Card.Title>{carta.nombre}</Card.Title>
                <Card.Text>Atributo: {carta.atributo}</Card.Text>
                <Card.Text>Ataque: {carta.fuerza}</Card.Text>
              </Card.Body>
            </Card>
          </Col>
        ))}
      </Row>

      {resultadoRonda && (
        <Alert variant="info" className="mt-4 text-center">
          {resultadoRonda}
        </Alert>
      )}

      {resultadoFinal && (
        <Alert variant="success" className="mt-4 text-center">
          Resultado final: El usuario {resultadoFinal}
          <div className="mt-2">
            <Button onClick={() => window.location.reload()}>
              ¿Jugar otra vez?
            </Button>
          </div>
        </Alert>
      )}
    </Container>
  );
};

export default JugarPage;
