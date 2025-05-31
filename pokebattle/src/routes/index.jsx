import { Route, Routes } from "react-router-dom";
import StatPage from "@/pages/StatPage";
import LoginPage from "@/pages/LoginPage";
import HomePage from "@/pages/HomePage"; 

const AppRoutes = () => {
  return (
    <Routes>
      <Route path="/" element={<HomePage />} /> 
      <Route path="/stat" element={<StatPage />} />
      <Route path="/login" element={<LoginPage />} />
    </Routes>
  );
};

export default AppRoutes;
