import { useState } from "react";
import { useAuth } from "@/contexts/useAuth";
import { editUserData } from "../services/apiService";
import { FaRegEye, FaRegEyeSlash } from "react-icons/fa";
import LoadingSpinner from "../components/LoadingSpinner";
import "@/styles/form.css";
const EditarUsuarioPage = () => {
  const [mensaje, setMensaje] = useState(null);
  const { usuario } = useAuth();
  const [showPassword, setShowPassword] = useState(false);
  const [loadingCursor, setLoadingCursor] = useState(false);
  const [put, setPut] = useState({
    nombre: "",
    clave: "",
    claverepe: "",
  });

  const handleShowPassword = (event) => {
    setShowPassword(!showPassword);
  };

  const handleInput = (event) => {
    setPut({ ...put, [event.target.name]: event.target.value });
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    setLoadingCursor(true);
    try {
      if (put.clave != put.claverepe) {
        throw new Error("la clave no coincide");
      }
      await editUserData(usuario.id, put);
      setMensaje({
        tipo: "success",
        texto: "Modificacion exitoso",
      });
    } catch (err) {
      setMensaje({
        tipo: "error",
        texto:
          err.response?.data?.error ||
          err.message ||
          "Error al editar los datos",
      });
    } finally {
      setLoadingCursor(false);
    }
  };
  return (
    <div className="container mt-5" class="form">
      <h2 className="text-center">Editar usuario</h2>
      <h2 className="text-left">Datos</h2>
      <div>
        <form onSubmit={handleSubmit}>
          <div className="mb-3">
            <label htmlFor="usuario" className="form-label">
              Usuario
            </label>
            <input
              type="text"
              className="form-control"
              disabled
              value={usuario.usuario}
            />
          </div>
          <div className="mb-3">
            <label htmlFor="nombre" className="mb-3">
              Nombre
            </label>
            <input
              type="text"
              className="form-control"
              maxlength={30}
              id="nombre"
              name="nombre"
              onChange={handleInput}
              value={put.nombre}
            />
          </div>
          <label htmlFor="clave" className="mb-3">
            Clave
          </label>
          <div className="input-group mb-3">
            <input
              type={showPassword ? "text" : "password"}
              className="form-control"
              id="clave"
              name="clave"
              onChange={handleInput}
              value={put.clave}
            />
            <button
              type="button"
              className="btn btn-dark"
              onClick={handleShowPassword}
            >
              {showPassword ? <FaRegEye /> : <FaRegEyeSlash />}
            </button>
          </div>
          <div className="mb-3">
            <label htmlFor="nombre" className="mb-3">
              Repetir Clave
            </label>
            <input
              type="password"
              className="form-control"
              id="claverepe"
              name="claverepe"
              onChange={handleInput}
              value={put.claverepe}
            />
          </div>
          <button type="submit" className="btn btn-primary w-100 mb-3">
            Modificar Datos
          </button>
        </form>
        {mensaje && (
          <div
            className={`alert mt-3 alert-${
              mensaje.tipo === "success" ? "success" : "danger"
            } alert-dismissible`}
          >
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="alert"
              aria-label="Close"
            ></button>
            {mensaje.texto}
          </div>
        )}
      </div>
      <LoadingSpinner active={loadingCursor} />
    </div>
  );
};

export default EditarUsuarioPage;
