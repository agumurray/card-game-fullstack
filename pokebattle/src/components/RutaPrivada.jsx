import { Navigate } from "react-router-dom";
import { useAuth } from "@/contexts/useAuth";

const RutaPrivada = ({ children }) => {
  const { usuario, cargando } = useAuth();

  if (cargando) {
    return <p>Cargando sesión...</p>; 
  }

  if (!usuario) {
    return <Navigate to="/login" replace />;
  }

  return children;
};

export default RutaPrivada;
