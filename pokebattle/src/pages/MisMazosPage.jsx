import { useEffect, useState } from "react";
import {
  getMazosPorUsuario,
  eliminarMazo,
  editarNombreMazo,
  crearPartida,
} from "../services/apiService";
import { useAuth } from "../contexts/useAuth";
import { Button, Modal, Form } from "react-bootstrap";
import { Link, useNavigate } from "react-router-dom";
import CartaComponent from "../components/CartaComponent";
import "@/styles/MisMazosPage.css"; // ✅ Importación del archivo de estilos

const MisMazosPage = () => {
  const { usuario } = useAuth();
  const [mazos, setMazos] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [mazoActivo, setMazoActivo] = useState(null);
  const [editandoId, setEditandoId] = useState(null);
  const [nuevoNombre, setNuevoNombre] = useState("");
  const navigate = useNavigate();

  const partidaGuardada = JSON.parse(localStorage.getItem("partidaActiva"));
  const hayPartidaEnCurso = Boolean(partidaGuardada);
  const mazoEnCursoId = partidaGuardada?.mazoId;

  useEffect(() => {
    if (!usuario?.id) {
      setError("Usuario no autenticado");
      setLoading(false);
      return;
    }

    getMazosPorUsuario(usuario.id)
      .then((res) => {
        setMazos(res.data["Listado de mazos"] || []);
        setLoading(false);
      })
      .catch((err) => {
        setError(
          err.response?.data?.error ||
            err.response.data.message ||
            "Error al cargar los mazos"
        );
        setLoading(false);
      });
  }, [usuario]);

  const handleJugar = (idMazo) => {
    crearPartida(idMazo)
      .then((res) => {
        const partida = {
          id: res.data["id de partida"],
          cartas: res.data.cartas,
          mensaje: res.data.mensaje,
        };

        if (!partida.id) {
          alert("Error interno al crear la partida. Intenta nuevamente.");
          return;
        }

        localStorage.setItem(
          "partidaActiva",
          JSON.stringify({
            id: partida.id,
            mazoId: idMazo,
            cartas: partida.cartas,
          })
        );

        navigate(`/jugar/${idMazo}`, {
          state: {
            partida: {
              ...partida,
              mazoId: idMazo,
            },
          },
        });
      })
      .catch((err) => {
        alert("Error al crear la partida: " + err.response?.data?.error);
      });
  };

  const puedeCrear = mazos.length < 3;

  const handleEliminar = (id) => {
    if (confirm("¿Estás seguro que querés eliminar este mazo?")) {
      eliminarMazo(id)
        .then(() => setMazos(mazos.filter((m) => m.id !== id)))
        .catch((err) =>
          alert("No se pudo eliminar: " + err.response?.data?.error)
        );
    }
  };

  const handleGuardarNombre = (id) => {
    if (nuevoNombre.trim() === "") return;

    editarNombreMazo(id, nuevoNombre)
      .then(() => {
        setMazos(
          mazos.map((m) => (m.id === id ? { ...m, nombre: nuevoNombre } : m))
        );
        setEditandoId(null);
      })
      .catch((err) => alert("No se pudo editar: " + err.response?.data?.error));
  };

  if (loading) return <p>Cargando mazos...</p>;
  if (error) return <p className="text-danger">{error}</p>;

  if (mazos.length === 0) {
    return (
      <div className="container mt-5 text-center">
        <h2>Mis Mazos</h2>
        <p>No tenés ningún mazo creado todavía.</p>
        <Link to="/alta-mazo">
          <Button variant="primary">Alta de nuevo mazo</Button>
        </Link>
      </div>
    );
  }

  return (
    <div className="container mis-mazos-container">
      <div className="mis-mazos-header">
        <h2>Mis Mazos</h2>
        {puedeCrear ? (
          <Link to="/alta-mazo">
            <Button variant="primary">Alta de nuevo mazo</Button>
          </Link>
        ) : (
          <Button variant="primary" disabled>
            Alta de nuevo mazo
          </Button>
        )}
      </div>

      <div className="row">
        {mazos.map((mazo) => (
          <div key={mazo.id} className="col-md-4 mb-4">
            <div className="mazo-card">
              {editandoId === mazo.id ? (
                <>
                  <Form.Control
                    type="text"
                    value={nuevoNombre}
                    onChange={(e) => setNuevoNombre(e.target.value)}
                  />
                  <Button
                    variant="success"
                    size="sm"
                    onClick={() => handleGuardarNombre(mazo.id)}
                    className="mt-2 me-2"
                  >
                    Guardar
                  </Button>
                  <Button
                    variant="secondary"
                    size="sm"
                    onClick={() => setEditandoId(null)}
                  >
                    Cancelar
                  </Button>
                </>
              ) : (
                <>
                  <h4>{mazo.nombre}</h4>
                  <p>Cantidad de cartas: {mazo.cartas.length}</p>
                  <div className="d-flex flex-column gap-2">
                    <Button
                      variant="info"
                      size="sm"
                      onClick={() => setMazoActivo(mazo)}
                    >
                      Ver mazo
                    </Button>
                    <Button
                      variant="danger"
                      size="sm"
                      onClick={() => handleEliminar(mazo.id)}
                    >
                      Eliminar
                    </Button>
                    <Button
                      variant="warning"
                      size="sm"
                      onClick={() => {
                        setEditandoId(mazo.id);
                        setNuevoNombre(mazo.nombre);
                      }}
                    >
                      Editar
                    </Button>

                    {hayPartidaEnCurso && mazo.id === mazoEnCursoId ? (
                      <Button
                        variant="success"
                        size="sm"
                        onClick={() =>
                          navigate(`/jugar/${mazo.id}`, {
                            state: { partida: partidaGuardada },
                          })
                        }
                      >
                        Reanudar
                      </Button>
                    ) : (
                      <Button
                        variant="success"
                        size="sm"
                        onClick={() => handleJugar(mazo.id)}
                        disabled={hayPartidaEnCurso}
                      >
                        Jugar
                      </Button>
                    )}
                  </div>
                </>
              )}
            </div>
          </div>
        ))}
      </div>

      <Modal show={mazoActivo} onHide={() => setMazoActivo(null)} size="lg">
        <Modal.Header closeButton>
          <Modal.Title>{mazoActivo?.nombre}</Modal.Title>
        </Modal.Header>
        <Modal.Body className="modal-cartas">
          <div className="modal-cartas-contenido">
            {mazoActivo?.cartas.map((carta) => (
              <CartaComponent
                nombre={carta.nombre}
                atributo={carta.atributo_nombre}
                ataque={carta.ataque_nombre}
                customStyle={{
                  width: "13rem",
                  height: "250px",
                }}
              />
            ))}
          </div>
        </Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={() => setMazoActivo(null)}>
            Cerrar
          </Button>
        </Modal.Footer>
      </Modal>
    </div>
  );
};

export default MisMazosPage;
