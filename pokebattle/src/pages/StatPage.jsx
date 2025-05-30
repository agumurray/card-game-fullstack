import { useEffect, useState } from "react";
import { getEstadisticas } from "@/services/apiService";
import "@/styles/stat.css";

const StatPage = () => {
  const [data, setData] = useState([]);
  const [sorted, setSorted] = useState([]);
  const [sortOrder, setSortOrder] = useState("desc"); // 'desc' = mejor primero
  const [currentPage, setCurrentPage] = useState(1);
  const itemsPerPage = 5;

  useEffect(() => {
    const fetchData = async () => {
      try {
        const res = await getEstadisticas();
        const stats = res.data?.Estadisticas || [];

        const enhanced = stats.map((user) => {
          const total = user.gano + user.perdio + user.empato;
          const promedio = total ? user.gano / total : 0;
          return { ...user, total, promedio };
        });

        setData(enhanced);
      } catch (e) {
        console.error("Error al obtener estadísticas", e);
      }
    };

    fetchData();
  }, []);

  useEffect(() => {
    const sortedData = [...data].sort((a, b) => {
      return sortOrder === "desc"
        ? b.promedio - a.promedio
        : a.promedio - b.promedio;
    });
    setSorted(sortedData);
  }, [data, sortOrder]);

  const totalPages = Math.ceil(sorted.length / itemsPerPage);
  const pagedData = sorted.slice(
    (currentPage - 1) * itemsPerPage,
    currentPage * itemsPerPage
  );

  return (
    <div className="container mt-4">
      <h2 className="mb-4">📊 Listado de Estadísticas</h2>

      <div className="d-flex justify-content-between align-items-center mb-3">
        <button
          className="btn btn-outline-primary"
          onClick={() => setSortOrder(sortOrder === "desc" ? "asc" : "desc")}
        >
          Ordenar por {sortOrder === "desc" ? "peor" : "mejor"} performance
        </button>

        <div>
          Página {currentPage} de {totalPages}
        </div>
      </div>

      <div className="list-group">
        {pagedData.map((user) => (
          <div
            key={user.id_usuario}
            className={`list-group-item list-group-item-action d-flex justify-content-between align-items-center ${
              user.id_usuario === sorted[0]?.id_usuario && sortOrder === "desc"
                ? "top-performer"
                : ""
            }`}
          >
            <div>
              <h5 className="mb-1">{user.nombre}</h5>
              <small>
                Partidas: {user.total} | Ganadas: {user.gano} | Perdidas:{" "}
                {user.perdio} | Empates: {user.empato}
              </small>
            </div>
            <span className="badge bg-primary rounded-pill">
              {(user.promedio * 100).toFixed(1)}%
            </span>
          </div>
        ))}
      </div>

      <div className="d-flex justify-content-center mt-3 gap-2">
        <button
          className="btn btn-secondary"
          disabled={currentPage === 1}
          onClick={() => setCurrentPage((prev) => prev - 1)}
        >
          Anterior
        </button>
        <button
          className="btn btn-secondary"
          disabled={currentPage === totalPages}
          onClick={() => setCurrentPage((prev) => prev + 1)}
        >
          Siguiente
        </button>
      </div>
    </div>
  );
};

export default StatPage;
