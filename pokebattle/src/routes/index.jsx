import { Route, Routes } from 'react-router-dom';
import StatPage from '@/pages/StatPage';
import LoginPage from '@/pages/LoginPage';

const AppRoutes = () => {
  return (
    <Routes>
      <Route path="/stat" element={<StatPage />} />
      <Route path="/login" element={<LoginPage />} />
    </Routes>
  );
};

export default AppRoutes;
