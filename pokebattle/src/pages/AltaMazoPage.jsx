import Carta from "@/components/CartaComponent";
import { useEffect, useState } from "react";
import {
  createMazo,
  getAllCartas,
  FiltrarCartas,
  FiltrarCartasN,
} from "../services/apiService";

const AltaMazoPage = () => {
  const [cartas, setCartas] = useState([]);
  const [cartasSeleccionadas, setCartasSeleccionadas] = useState([]);
  const [nombreMazo, setNombreMazo] = useState("");
  const [mensaje, setMensaje] = useState("");
  const [error, setError] = useState("");
  const [filtro, setFiltro] = useState({
    atributo: "",
    nombre: "",
  });

  useEffect(() => {
    if (!filtro.nombre && !filtro.atributo) {
      getAllCartas()
        .then((res) => {
          if (res.data.status === "success") {
            setCartas(res.data.cartas);
          } else {
            setError("Error al obtener las cartas.");
          }
        })
        .catch(() =>
          setError("Error al conectar con el servidor para obtener cartas.")
        );
    } else {
      if (!filtro.atributo) {
        FiltrarCartasN(filtro.nombre)
          .then((res) => {
            if (res.data.status === "success") {
              setCartas(res.data.cartas);
              setError("");
            }
          })
          .catch(() => {
            setError("no se encontro ninguna carta con esos parametros");
            setCartas([]);
          });
      } else {
        FiltrarCartas(filtro.atributo, filtro.nombre)
          .then((res) => {
            if (res.data.status === "success") {
              setCartas(res.data.cartas);
              setError("");
            }
          })
          .catch(() => {
            setError("no se encontro ninguna carta con esos parametros");
            setCartas([]);
          });
      }
    }
  }, [filtro]);

  const borrarFiltros = () => {
    setFiltro({ ...filtro, nombre: "", atributo: "" });
  };

  const toggleCarta = (id) => {
    if (cartasSeleccionadas.includes(id)) {
      setCartasSeleccionadas(cartasSeleccionadas.filter((c) => c !== id));
    } else if (cartasSeleccionadas.length < 5) {
      setCartasSeleccionadas([...cartasSeleccionadas, id]);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setMensaje("");
    setError("");

    if (!nombreMazo.trim()) {
      setError("El nombre del mazo es obligatorio.");
      return;
    }

    if (cartasSeleccionadas.length !== 5) {
      setError("Debe seleccionar exactamente 5 cartas.");
      return;
    }

    try {
      const res = await createMazo({
        nombre: nombreMazo,
        cartas: cartasSeleccionadas,
      });

      const data = res.data;

      if (data.status === "success") {
        setMensaje(
          `Mazo "${data["nombre mazo"]}" creado con éxito (ID ${data["id mazo"]})`
        );
        setCartasSeleccionadas([]);
        setNombreMazo("");
      } else {
        setError(data.message || "Error al crear el mazo.");
      }
    } catch (err) {
      setError("Ocurrió un error al conectar con el servidor.");
    }
  };

  return (
    <div className="p-4 max-w-xl mx-auto">
      <h2 className="text-xl font-bold mb-4">Crear nuevo mazo</h2>

      <form onSubmit={handleSubmit} className="space-y-4">
        <div className="container d-flex justify-content-between align-items-center">
          <div className="me-3 flex-grow-1">
            <input
              type="text"
              placeholder="Nombre del mazo"
              value={nombreMazo}
              onChange={(e) => setNombreMazo(e.target.value)}
              className=" w-full border px-3 py-2 rounded"
            />
          </div>
          <div className="d-flex align-items-center">
            <select
              className="form-select"
              value={filtro.atributo}
              onChange={(e) =>
                setFiltro({ ...filtro, atributo: e.target.value })
              }
            >
              <option value="">Ninguno</option>
              <option value="1">Fuego</option>
              <option value="2">Agua</option>
              <option value="3">Tierra</option>
              <option value="4">Normal</option>
              <option value="5">Volador</option>
              <option value="6">Piedra</option>
            </select>
            <input
              type="text"
              placeholder="Buscar"
              value={filtro.nombre}
              onChange={(e) => setFiltro({ ...filtro, nombre: e.target.value })}
              className="w-full border px-3 py-2 rounded"
            />
          </div>
          <button
            type="button"
            onClick={borrarFiltros}
            className="btn btn-danger px-4 py-2"
          >
            Borrar Filtro
          </button>
        </div>
        <div className="grid grid-cols-2 gap-2">
          {cartas.map((carta) => (
            <button
              type="button"
              key={carta.id}
              onClick={() => toggleCarta(carta.id)}
              className={`p-2 border rounded ${
                cartasSeleccionadas.includes(carta.id)
                  ? "bg-success"
                  : "bg-white"
              }`}
            >
              <Carta
                nombre={carta.nombre}
                atributo={carta.atributo_nombre}
                ataque={carta.ataque_nombre}
                punto={carta.ataque}
              />
            </button>
          ))}
        </div>

        <p className="fs-5">
          Cartas seleccionadas: {cartasSeleccionadas.length} / 5
        </p>
        {error && <p className="alert alert-danger">{error}</p>}
        {mensaje && <p className="alert alert-success">{mensaje}</p>}

        <button type="submit" className="btn btn-dark">
          Crear mazo
        </button>
      </form>
    </div>
  );
};

export default AltaMazoPage;
