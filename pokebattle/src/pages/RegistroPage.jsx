import { useState } from "react";
import { createUser, loginUser } from "../services/apiService";
import { useNavigate } from "react-router-dom";
import { useAuth } from "@/contexts/useAuth";

const RegistroPage = () => {
  const [mensaje, setMensaje] = useState(null);
  const navigate = useNavigate();
  const { login } = useAuth();
  const [post, setPost] = useState({
    nombre: "",
    usuario: "",
    clave: "",
  });

  const handleInput = (event) => {
    setPost({ ...post, [event.target.name]: event.target.value });
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
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
        texto: err.response?.data?.message || "Error al registrar",
      });
    }
  };

  return (
    <div className="container mt-5" style={{ maxWidth: "500px" }}>
      <h2>Registro</h2>
      <form onSubmit={handleSubmit}>
        <div className="mb-3">
          <label htmlFor="nombre" className="form-label">
            Nombre
          </label>
          <input
            type="text"
            className="form-control"
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
        <div className="mb-3">
          <label htmlFor="clave" className="form-label">
            Clave
          </label>
          <input
            type="password"
            className="form-control"
            id="clave"
            name="clave"
            onChange={handleInput}
            value={post.clave}
          />
        </div>
        <button type="submit" className="btn btn-primary w-100">
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
    </div>
  );
};

export default RegistroPage;
