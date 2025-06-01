import { Route, Routes } from "react-router-dom";
import StatPage from "@/pages/StatPage";
import LoginPage from "@/pages/LoginPage";
import RegistroPage from "../pages/RegistroPage";

const AppRoutes = () => {
  return (
    <Routes>
      <Route path="/stat" element={<StatPage />} />
      <Route path="/login" element={<LoginPage />} />
      <Route path="/registro" element={<RegistroPage />} />
    </Routes>
  );
};

export default AppRoutes;
