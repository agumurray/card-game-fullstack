import { Route, Routes } from "react-router-dom";
import StatPage from "@/pages/StatPage";
import LoginPage from "@/pages/LoginPage";
import HomePage from "@/pages/HomePage";
import RegistroPage from "@/pages/RegistroPage";
import MisMazosPage from "@/pages/MisMazosPage";
import AltaMazoPage from "@/pages/AltaMazoPage";
import EditarUsuarioPage from "@/pages/EditarUsuarioPage";
import JugarPage from "@/pages/JugarPage";
import RutaPrivada from "@/components/RutaPrivada";
import CartaDemoPage from "@/pages/CartaDemoPage"

const AppRoutes = () => {
  return (
    <Routes>
      <Route path="/" element={<HomePage />} />
      <Route path="/stat" element={<StatPage />} />
      <Route path="/login" element={<LoginPage />} />
      <Route path="/registro" element={<RegistroPage />} />
      <Route path="/card-test" element={<CartaDemoPage />} />
      <Route path="/juego" element={<JugarPage />} />

      <Route
        path="/mis-mazos"
        element={
          <RutaPrivada>
            <MisMazosPage />
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

      <Route
        path="/alta-mazo"
        element={
          <RutaPrivada>
            <AltaMazoPage />
          </RutaPrivada>
        }
      />

      {/* <Route
        path="/jugar/:idMazo"
        element={
          <RutaPrivada>
            <JugarPage />
          </RutaPrivada>
        }
      /> */}
    </Routes>
  );
};

export default AppRoutes;
