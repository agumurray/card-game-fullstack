import { Route, Routes } from "react-router-dom";
import StatPage from "@/pages/StatPage";
import LoginPage from "@/pages/LoginPage";
import HomePage from "@/pages/HomePage";
import RegistroPage from "../pages/RegistroPage";
import MisMazosPage from "../pages/MisMazosPage";
import AltaMazoPage from "../pages/AltaMazoPage"; 
import EditarUsuarioPage from "../pages/EditarUsuarioPage";
import RutaPrivada from "@/components/RutaPrivada";

const AppRoutes = () => {
  return (
    <Routes>
      <Route path="/" element={<HomePage />} />
      <Route path="/stat" element={<StatPage />} />
      <Route path="/login" element={<LoginPage />} />
      <Route path="/registro" element={<RegistroPage />} />

      <Route
        path="/mis-mazos"
        element={
          <RutaPrivada>
            <MisMazosPage />
          </RutaPrivada>
        }
      />
      <Route
        path="/alta-mazo"
        element={
          <RutaPrivada>
            <AltaMazoPage />
          </RutaPrivada>
        }
      />
      <Route
        path="/editar-usuario"
        element={
          <RutaPrivada>
            <EditarUsuarioPage />
          </RutaPrivada>
        }
      />
    </Routes>
  );
};

export default AppRoutes;
