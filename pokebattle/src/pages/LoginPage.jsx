import { useState } from "react";
import { loginUser } from "@/services/apiService";
import { useAuth } from "@/contexts/AuthContext";
import { useNavigate } from "react-router-dom";

const LoginPage = () => {
  const [usuario, setUsuario] = useState("");
  const [clave, setClave] = useState("");
  const [mensaje, setMensaje] = useState(null);
  const { login } = useAuth();
  const navigate = useNavigate();

  const handleLogin = async (e) => {
    e.preventDefault();
    try {
      const res = await loginUser({ usuario, clave });
      await login(res.data.token);
      setMensaje({ tipo: "success", texto: "Login exitoso" });
      navigate("/"); // redirige a donde quieras
    } catch (err) {
      setMensaje({
        tipo: "error",
        texto: err.response?.data?.message || "Error al iniciar sesión",
      });
    }
  };

  return (
    <div className="container mt-5" style={{ maxWidth: "400px" }}>
      <h2>🔐 Login</h2>
      <form onSubmit={handleLogin} className="login-form">
        <div className="mb-3">
          <label htmlFor="usuario" className="form-label">
            Usuario
          </label>
          <input
            type="text"
            className="form-control"
            id="usuario"
            value={usuario}
            onChange={(e) => setUsuario(e.target.value)}
            required
          />
        </div>

        <div className="mb-3">
          <label htmlFor="clave" className="form-label">
            Clave
          </label>
          <input
            type="password"
            className="form-control"
            id="clave"
            value={clave}
            onChange={(e) => setClave(e.target.value)}
            required
          />
        </div>

        <button type="submit" className="btn btn-primary w-100">
          Ingresar
        </button>
      </form>

      {mensaje && (
        <div
          className={`alert mt-3 alert-${
            mensaje.tipo === "success" ? "success" : "danger"
          }`}
        >
          {mensaje.texto}
        </div>
      )}
    </div>
  );
};

export default LoginPage;
