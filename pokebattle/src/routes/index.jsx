import { Routes, Route } from "react-router-dom";
import StatPage from "@/pages/StatPage"

const AppRoutes = () => {
  return (
    <Routes>
      <Route path="/stat" element={<StatPage />} />
    </Routes>
  );
};

export default AppRoutes;
