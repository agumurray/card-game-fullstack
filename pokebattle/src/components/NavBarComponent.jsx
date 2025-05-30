import { Link } from "react-router-dom";
import { useState } from "react";
import { useAuth } from "@/contexts/AuthContext";

const NavBar = () => {
  const [isOpen, setIsOpen] = useState(false);
  const { usuario, logout } = useAuth();

  const toggleMenu = () => setIsOpen(!isOpen);

  return (
    <nav className="navbar navbar-expand-lg navbar-dark bg-dark px-3">
      <div className="container-fluid">
        <Link className="navbar-brand" to="/">
          Inicio
        </Link>

        <button
          className="navbar-toggler"
          type="button"
          onClick={toggleMenu}
          aria-controls="navbarNav"
          aria-expanded={isOpen}
          aria-label="Toggle navigation"
        >
          <span className="navbar-toggler-icon" />
        </button>

        <div className={`collapse navbar-collapse ${isOpen ? "show" : ""}`}>
          <ul className="navbar-nav me-auto mb-2 mb-lg-0 d-flex gap-3">
            <li className="nav-item">
              <Link
                className="nav-link"
                to="/stat"
                onClick={() => setIsOpen(false)}
              >
                Estadísticas
              </Link>
            </li>

            {!usuario ? (
              <>
                <li className="nav-item">
                  <Link className="nav-link" to="/login">
                    Login
                  </Link>
                </li>
                <li className="nav-item">
                  <Link className="nav-link" to="/registro">
                    Registro
                  </Link>
                </li>
              </>
            ) : (
              <>
                <li className="nav-item">
                  <span className="nav-link disabled">
                    Hola, {usuario.nombre}
                  </span>
                </li>
                <li className="nav-item">
                  <Link className="nav-link" to="/mis-mazos">
                    Mis mazos
                  </Link>
                </li>
                <li className="nav-item">
                  <Link className="nav-link" to="/editar-usuario">
                    Editar usuario
                  </Link>
                </li>
                <li className="nav-item">
                  <button className="nav-link btn btn-link" onClick={logout}>
                    Logout
                  </button>
                </li>
              </>
            )}
          </ul>
        </div>
      </div>
    </nav>
  );
};

export default NavBar;
