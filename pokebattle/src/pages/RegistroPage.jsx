import { useState } from "react";
import { createUser, loginUser } from "../services/apiService";
import { useNavigate } from "react-router-dom";
import { useAuth } from "@/contexts/useAuth";
import LoadingSpinner from "../components/LoadingSpinner";
import { FaRegEye, FaRegEyeSlash } from "react-icons/fa";
import "@/styles/form.css";
const RegistroPage = () => {
  const [mensaje, setMensaje] = useState(null);
  const [showPassword, setShowPassword] = useState(false);
  const navigate = useNavigate();
  const { login } = useAuth();
  const [loadingCursor, setLoadingCursor] = useState(false);
  const [post, setPost] = useState({
    nombre: "",
    usuario: "",
    clave: "",
  });

  const handleShowPassword = (event) => {
    setShowPassword(!showPassword);
  };

  const handleInput = (event) => {
    setPost({ ...post, [event.target.name]: event.target.value });
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    setLoadingCursor(true);
    try {
      await createUser(post);
      const { usuario, clave } = post;
      const res = await loginUser({ usuario, clave });
      await login(res.data.token);
      setMensaje({
        tipo: "success",
        texto: "Login exitoso",
      });
      navigate("/");
    } catch (err) {
      setMensaje({
        tipo: "error",
        texto: err.response?.data?.error || "Error al registrar",
      });
    } finally {
      setLoadingCursor(false);
    }
  };

  return (
    <div className="container mt-5" class="form">
      <h2>Registro</h2>
      <form onSubmit={handleSubmit}>
        <div className="mb-3">
          <label htmlFor="nombre" className="form-label">
            Nombre
          </label>
          <input
            type="text"
            className="form-control"
            maxlength={30}
            id="nombre"
            name="nombre"
            onChange={handleInput}
            value={post.nombre}
          />
        </div>
        <div className="mb-3">
          <label htmlFor="usuario" className="form-label">
            Usuario
          </label>
          <input
            type="text"
            className="form-control"
            id="usuario"
            name="usuario"
            onChange={handleInput}
            value={post.usuario}
          />
        </div>
        <label htmlFor="clave">Clave</label>
        <div className="input-group mb-3">
          <input
            type={showPassword ? "text" : "password"}
            className="form-control"
            id="clave"
            name="clave"
            onChange={handleInput}
            value={post.clave}
          />
          <button
            type="button"
            className="btn btn-dark"
            onClick={handleShowPassword}
          >
            {showPassword ? <FaRegEye /> : <FaRegEyeSlash />}
          </button>
        </div>
        <button type="submit" className="btn btn-primary w-100 mb-3">
          Registrar
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

export default RegistroPage;
