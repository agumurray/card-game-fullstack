import { useEffect, useState } from "react";
import { getMazosPorUsuario} from "../services/apiService";
import { useAuth } from "../contexts/useAuth";
import { Button, Modal, Form } from "react-bootstrap";
import { Link } from "react-router-dom";

const MisMazosPage = () => {
  const { usuario } = useAuth();
  const [mazos, setMazos] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [mazoActivo, setMazoActivo] = useState(null);
  const [editandoId, setEditandoId] = useState(null);
  const [nuevoNombre, setNuevoNombre] = useState("");

  useEffect(() => {
    if (!usuario?.id) {
      setError("Usuario no autenticado");
      setLoading(false);
      return;
    }

    getMazosPorUsuario(usuario.id)
      .then(res => {
        setMazos(res.data["Listado de mazos"] || []);
        setLoading(false);
      })
      .catch(err => {
        setError(err.response?.data?.error || "Error al cargar los mazos");
        setLoading(false);
      });
  }, [usuario]);

  const puedeCrear = mazos.length < 3;

  const handleEliminar = (id) => {
    if (confirm("¿Estás seguro que querés eliminar este mazo?")) {
      eliminarMazo(id)
        .then(() => setMazos(mazos.filter(m => m.id !== id)))
        .catch(err => alert("No se pudo eliminar: " + err.response?.data?.error));
    }
  };

  const handleGuardarNombre = (id) => {
    if (nuevoNombre.trim() === "") return;

    editarNombreMazo(id, nuevoNombre)
      .then(() => {
        setMazos(mazos.map(m => m.id === id ? { ...m, nombre: nuevoNombre } : m));
        setEditandoId(null);
      })
      .catch(err => alert("No se pudo editar: " + err.response?.data?.error));
  };

  if (loading) return <p>Cargando mazos...</p>;
  if (error) return <p className="text-danger">{error}</p>;

  return (
    <div className="container mt-5">
      <div className="d-flex justify-content-between align-items-center mb-4">
        <h2>Mis Mazos</h2>
        <Link to="/stat">
          <Button variant="primary" disabled={!puedeCrear}>
            Alta de nuevo mazo
          </Button>
        </Link>
      </div>

      <div className="row">
        {mazos.map((mazo) => (
          <div key={mazo.id} className="col-md-4 mb-4">
            <div className="border p-3 h-100 rounded text-center">
              {editandoId === mazo.id ? (
                <>
                  <Form.Control
                    type="text"
                    value={nuevoNombre}
                    onChange={(e) => setNuevoNombre(e.target.value)}
                  />
                  <Button variant="success" size="sm" onClick={() => handleGuardarNombre(mazo.id)} className="mt-2 me-2">Guardar</Button>
                  <Button variant="secondary" size="sm" onClick={() => setEditandoId(null)}>Cancelar</Button>
                </>
              ) : (
                <>
                  <h4>{mazo.nombre}</h4>
                  <p>Cantidad de cartas: {mazo.cartas.length}</p>
                  <div className="d-flex flex-column gap-2">
                    <Button variant="info" size="sm" onClick={() => setMazoActivo(mazo)}>Ver mazo</Button>
                    <Button variant="danger" size="sm" onClick={() => handleEliminar(mazo.id)}>Eliminar</Button>
                    <Button variant="warning" size="sm" onClick={() => { setEditandoId(mazo.id); setNuevoNombre(mazo.nombre); }}>Editar</Button>
                    <Link to={`/jugar/${mazo.id}`}>
                      <Button variant="success" size="sm">Jugar</Button>
                    </Link>
                  </div>
                </>
              )}
            </div>
          </div>
        ))}
      </div>

      <Modal show={mazoActivo} onHide={() => setMazoActivo(null)}>
        <Modal.Header closeButton>
          <Modal.Title>{mazoActivo?.nombre}</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <ul>
            {mazoActivo?.cartas.map(carta => (
              <li key={carta.id}>{carta.nombre}</li>
            ))}
          </ul>
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




