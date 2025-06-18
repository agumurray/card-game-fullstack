import React, { useEffect, useState } from "react";
import { createMazo, getAllCartas } from "../services/apiService";

const AltaMazoPage = () => {
  const [cartas, setCartas] = useState([]);
  const [cartasSeleccionadas, setCartasSeleccionadas] = useState([]);
  const [nombreMazo, setNombreMazo] = useState("");
  const [mensaje, setMensaje] = useState("");
  const [error, setError] = useState("");

  useEffect(() => {
    getAllCartas()
      .then((res) => {
        if (res.data.status === "success") {
          setCartas(res.data.cartas);
        } else {
          setError("Error al obtener las cartas.");
        }
      })
      .catch(() => setError("Error al conectar con el servidor para obtener cartas."));
  }, []);

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
        setMensaje(`Mazo "${data["nombre mazo"]}" creado con éxito (ID ${data["id mazo"]})`);
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
        <input
          type="text"
          placeholder="Nombre del mazo"
          value={nombreMazo}
          onChange={(e) => setNombreMazo(e.target.value)}
          className="w-full border px-3 py-2 rounded"
        />

        <div className="grid grid-cols-2 gap-2">
          {cartas.map((carta) => (
            <button
              type="button"
              key={carta.id}
              onClick={() => toggleCarta(carta.id)}
              className={`p-2 border rounded ${
                cartasSeleccionadas.includes(carta.id) ? "bg-green-300" : "bg-white"
              }`}
            >
              {carta.nombre}
            </button>
          ))}
        </div>

        <p className="text-sm text-gray-600">Cartas seleccionadas: {cartasSeleccionadas.length} / 5</p>

        {error && <p className="text-red-600 font-medium">{error}</p>}
        {mensaje && <p className="text-green-600 font-medium">{mensaje}</p>}

        <button type="submit" className="bg-blue-500 text-white px-4 py-2 rounded">
          Crear mazo
        </button>
      </form>
    </div>
  );
};

export default AltaMazoPage;
