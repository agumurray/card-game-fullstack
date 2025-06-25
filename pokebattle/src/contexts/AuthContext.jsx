import { createContext, useEffect, useState } from "react";
import { getUserData, logoutUser } from "@/services/apiService";

const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const [usuario, setUsuario] = useState(null);
  const [cargando, setCargando] = useState(true);

  useEffect(() => {
    const verificarLogin = async () => {
      try {
        const res = await getUserData();
        setUsuario(res.data.usuario);
      } catch {
        localStorage.removeItem("token");
        setUsuario(null);
      } finally {
        setCargando(false);
      }
    };
    verificarLogin();
  }, []);

  const login = (token) => {
    localStorage.setItem("token", token);
    return getUserData().then((res) => setUsuario(res.data.usuario));
  };

  const logout = async () => {
    const hayPartidaActiva = localStorage.getItem("partidaActiva");

    if (hayPartidaActiva) {
      const confirmar = window.confirm(
        "Tiene una partida en curso. Si se desloguea, la perderá. ¿Desea continuar?"
      );
      if (!confirmar) return; 
    }

    try {
      await logoutUser();
    } catch (e) {
      console.warn("Error al cerrar sesión en el servidor:", e);
    } finally {
      localStorage.clear(); 
      setUsuario(null);
    }
  };

  return (
    <AuthContext.Provider value={{ usuario, cargando, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};

export default AuthContext;
