// src/pages/StatPage.jsx
import { useEffect, useState } from "react";

export default function StatPage() {
  const [result, setResult] = useState("Cargando...");

  useEffect(() => {
    fetch(`${import.meta.env.VITE_API_URL}/ping`)
      .then((res) => {
        if (!res.ok) throw new Error("Respuesta no OK");
        return res.json();
      })
      .then((data) => setResult(`API responde: ${JSON.stringify(data)}`))
      .catch((err) => setResult(`Error: ${err.message}`));
  }, []);

  return (
    <div>
      <h1>Estadísticas</h1>
      <p>{result}</p>
    </div>
  );
}
