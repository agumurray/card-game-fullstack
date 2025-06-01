import { Route, Routes } from "react-router-dom";
import StatPage from "@/pages/StatPage";
import LoginPage from "@/pages/LoginPage";
import HomePage from "@/pages/HomePage";
import RegistroPage from "../pages/RegistroPage";

const AppRoutes = () => {
  return (
    <Routes>
      <Route path="/" element={<HomePage />} />
      <Route path="/stat" element={<StatPage />} />
      <Route path="/login" element={<LoginPage />} />
      <Route path="/registro" element={<RegistroPage />} />
    </Routes>
  );
};

export default AppRoutes;
