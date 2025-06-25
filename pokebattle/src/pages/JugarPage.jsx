import Carta from "@/components/CartaComponent";
import CartaOculta from "@/components/CartaServidorComponent";
import fondo from "@/assets/fondo.png";
import { Container, Row, Col, Image } from "react-bootstrap";
import { useLocation, useNavigate } from "react-router-dom";
import { useEffect, useState } from "react";
import { getAtributos, jugarCarta, crearPartida } from "../services/apiService";
import ResultadoRondaModal from "@/components/ResultadoRondaComponent";
import ResultadoFinalModal from "@/components/ResultadoFinalComponent";
import LoadingSpinner from "@/components/LoadingSpinner";

const obtenerCartasServidor = (partidaId, setCartasServidor) => {
  getAtributos(1, partidaId)
    .then((res) => {
      const atributos = res.data["atributos en mano (servidor): "] || [];
      setCartasServidor(atributos);
    })
    .catch((err) =>
      console.error(
        "Error al obtener cartas del servidor:",
        err.response?.data || err
      )
    );
};

const JugarPage = () => {
  const { state } = useLocation();
  const navigate = useNavigate();
  const { partida } = state || {};
  const [cartasJugador, setCartasJugador] = useState([]);
  const [cartasServidor, setCartasServidor] = useState([]);
  const [resultadoModal, setResultadoModal] = useState({
    show: false,
    cartaJugador: null,
    cartaServidor: null,
    fuerzaJugador: 0,
    fuerzaServidor: 0,
    resultado: "",
    mensajeFinal: "",
  });
  const [partidaFinalizada, setPartidaFinalizada] = useState(false);
  const [mostrarFinalModal, setMostrarFinalModal] = useState(false);
  const [mazoId, setMazoId] = useState(null);
  const [loading, setLoading] = useState(false);


  const handleDobleClick = async (carta) => {
    setLoading(true);
    try {
      const res = await jugarCarta(partida.id, carta.id);
      const data = res.data;

      const nuevasCartasJugador = cartasJugador.filter(
        (c) => c.id !== carta.id
      );
      setCartasJugador(nuevasCartasJugador);

      // Actualizar cartas del servidor
      obtenerCartasServidor(partida.id, setCartasServidor);

      // Guardar estado actualizado en localStorage
      localStorage.setItem(
        "partidaActiva",
        JSON.stringify({
          id: partida.id,
          mazoId: mazoId,
          cartas: nuevasCartasJugador, // guardar cartas restantes
        })
      );

      setResultadoModal({
        show: true,
        cartaJugador: {
          nombre: carta.nombre,
          atributo: carta.atributo_nombre,
          ataque: carta.ataque_nombre,
        },
        cartaServidor: {
          nombre: data["carta servidor"].nombre,
          atributo: data["carta servidor"].atributo_nombre,
          ataque: data["carta servidor"].ataque_nombre,
        },
        fuerzaJugador: data["Fuerza usuario"],
        fuerzaServidor: data["Fuerza servidor"],
        resultado: data.el_usuario,
        mensajeFinal: data.mensaje || "",
      });

      if (nuevasCartasJugador.length === 0) {
        setPartidaFinalizada(true);
        localStorage.removeItem("partidaActiva"); // limpiar si terminó
      }
    } catch (err) {
      console.error("Error al jugar carta:", err.response?.data || err);
    }finally{
      setLoading(false);
    }
  };

  const jugarOtraVez = () => {
    // Cerrar modals antes de reiniciar
    setResultadoModal((prev) => ({ ...prev, show: false }));
    setMostrarFinalModal(false);

    crearPartida(mazoId)
      .then((res) => {
        const nuevaPartida = {
          id: res.data["id de partida"],
          cartas: res.data.cartas,
          mensaje: res.data.mensaje,
          mazoId: mazoId,
        };

        localStorage.setItem(
          "partidaActiva",
          JSON.stringify({
            id: nuevaPartida.id,
            mazoId: mazoId,
            cartas: nuevaPartida.cartas,
          })
        );

        navigate(`/jugar/${mazoId}`, {
          state: { partida: nuevaPartida },
        });
      })
      .catch((err) => {
        console.error(
          "Error al crear nueva partida:",
          err.response?.data || err
        );
        alert("No se pudo iniciar una nueva partida.");
      });
  };

  useEffect(() => {
    if (!partida) {
      navigate("/mis-mazos");
      return;
    }
    setCartasJugador(partida.cartas); // si reanudaste, vienen las cartas que quedaban
    setCartasServidor([]);
    setMazoId(partida.mazoId);
    setResultadoModal({
      show: false,
      cartaJugador: null,
      cartaServidor: null,
      fuerzaJugador: 0,
      fuerzaServidor: 0,
      resultado: "",
      mensajeFinal: "",
    });
    setPartidaFinalizada(false);
    setMostrarFinalModal(false);

    obtenerCartasServidor(partida.id, setCartasServidor);
  }, [partida, navigate]);

  if (!partida) return null;

  return (
    <><LoadingSpinner active={loading} />
    <Container fluid className="py-4 d-flex flex-column align-items-center">
      <div className="d-flex justify-content-center gap-3 mb-4 flex-wrap">
        {cartasServidor.map((atributo, i) => (
          <CartaOculta atributo={atributo} key={i} />
        ))}
      </div>

      <Row className="justify-content-center mb-4">
        <Col xs="auto">
          <Image
            src={fondo}
            alt="Tablero"
            style={{
              width: "66rem",
              height: "200px",
              objectFit: "cover",
              borderRadius: "1rem",
              border: "3px solid #333",
              boxShadow: "0 0 10px rgba(0,0,0,0.5)",
            }} />
        </Col>
      </Row>

      <div className="d-flex justify-content-center gap-3 mt-3 flex-wrap">
        {cartasJugador.map((carta, i) => (
          <div
            key={i}
            onDoubleClick={() => handleDobleClick(carta)}
            style={{ cursor: "pointer" }}
          >
            <Carta
              nombre={carta.nombre}
              atributo={carta.atributo_nombre}
              ataque={carta.ataque_nombre} />
          </div>
        ))}
      </div>


      <ResultadoRondaModal
        {...resultadoModal}
        onHide={() => {
          setResultadoModal((prev) => ({ ...prev, show: false }));
          if (partidaFinalizada) {
            setMostrarFinalModal(true);
          }
        } } />

      <ResultadoFinalModal
        show={mostrarFinalModal}
        resultado={resultadoModal.resultado}
        onJugarOtraVez={jugarOtraVez}
        onIrAMazos={() => navigate("/mis-mazos")} />
    </Container></>
  );
};

export default JugarPage;
