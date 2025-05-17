import { useEffect, useState } from "react";
import axios from "axios";

export default function TestPage() {
  const [result, setResult] = useState("Cargando...");

  useEffect(() => {
    axios
      .get(`${import.meta.env.VITE_API_URL}/ping`)
      .then((res) => {
        setResult(`API responde: ${JSON.stringify(res.data)}`);
      })
      .catch((err) => {
        setResult(`Error: no hay conexion con la API`);
      });
  }, []);

  return (
    <div>
      <h1>Conexion con la API</h1>
      <p>{result}</p>
    </div>
  );
}
