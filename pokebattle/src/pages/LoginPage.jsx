import { useState } from "react";
import { loginUser } from "@/services/apiService";
import { useAuth } from "@/contexts/useAuth";
import { useNavigate } from "react-router-dom";
import LoadingSpinner from "../components/LoadingSpinner";
import { FaRegEye, FaRegEyeSlash } from "react-icons/fa";
import "@/styles/form.css";
const LoginPage = () => {
  const [usuario, setUsuario] = useState("");
  const [clave, setClave] = useState("");
  const [mensaje, setMensaje] = useState(null);
  const [showPassword, setShowPassword] = useState(false);
  const [loadingCursor, setLoadingCursor] = useState(false);
  const { login } = useAuth();
  const navigate = useNavigate();

  const handleShowPassword = (event) => {
    setShowPassword(!showPassword);
  };

  const handleLogin = async (e) => {
    e.preventDefault();
    setLoadingCursor(true);
    try {
      const res = await loginUser({ usuario, clave });
      await login(res.data.token);
      setMensaje({ tipo: "success", texto: "Login exitoso" });
      navigate("/");
    } catch (err) {
      setMensaje({
        tipo: "error",
        texto: err.response?.data?.message || "Error al iniciar sesión",
      });
    } finally {
      setLoadingCursor(false);
    }
  };

  return (
    <div className="container mt-5" class="form">
      <h2>Login</h2>
      <form onSubmit={handleLogin}>
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
          <div className="input-group">
            <input
              type={showPassword ? "text" : "password"}
              className="form-control"
              id="clave"
              name="clave"
              value={clave}
              onChange={(e) => setClave(e.target.value)}
              required
            />
            <button
              type="button"
              className="btn btn-dark"
              onClick={handleShowPassword}
            >
              {showPassword ? <FaRegEye /> : <FaRegEyeSlash />}
            </button>
          </div>
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
      <LoadingSpinner active={loadingCursor} />
    </div>
  );
};

export default LoginPage;
