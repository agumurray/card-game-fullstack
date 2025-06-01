import { useState } from "react";
import { createUser } from "../services/apiService";
import { useNavigate } from "react-router-dom";
import { loginUser } from "@/services/apiService";
import { useAuth } from "@/contexts/AuthContext";
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
      console.log("hola");
      console.log(post.usuario);
      await createUser(post);
      console.log("hola");
      usuario = post.usuario;
      clave = post.clave;
      const res = await loginUser({ usuario, clave });
      await login(res.data.token);
      console.log("hola");
      setMensaje({
        tipo: "success",
        texto: "Login exitoso",
      });
      navigate("/"); // redirige a donde quieras
    } catch (err) {
      setMensaje({
        tipo: "error",
        texto: err.response?.data?.message || "Error al al registrar",
      });
    }
  };
  /*
  
  const handleSubmit = async (event) => {
    event.preventDefault();
    createUser(post)
      .then((response) => {
        setResult(JSON.stringify(response.data));
      })
      .catch((err) => {
        setResult(JSON.stringify(err.response.data));
      });
  };
  
  const jsonData = {
    nombre: "juansito",
    usuario: "juanpepe123",
    clave: "Pepe12345@",
  };

  useEffect(() => {
    createUser(jsonData)
      .then((response) => {
        setResult(JSON.stringify(response.data));
      })
      .catch((err) => {
        setResult(JSON.stringify(err.response.data));
      });
  }, []);*/

  return (
    <div className="container mt-5" style={{ maxWidth: "400px" }}>
      <h2>🔐Registro</h2>
      <form onSubmit={handleSubmit} className="register-form">
        <div className="mb-3">
          <label>nombre:</label>
          <br />
          <input
            type="text"
            className="form-control"
            onChange={handleInput}
            name="nombre"
          />
        </div>
        <br />
        <div className="mb-3">
          <label>usuario:</label>
          <br />
          <input
            type="text"
            className="form-control"
            onChange={handleInput}
            name="usuario"
          />
        </div>
        <br />
        <div className="mb-3">
          <label htmlFor="clave" className="form-label">
            clave:
          </label>
          <br />
          <input
            type="password"
            className="form-control"
            onChange={handleInput}
            name="clave"
          />
        </div>
        <br />
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
