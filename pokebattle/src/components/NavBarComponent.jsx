import { Link, useLocation,useNavigate} from "react-router-dom";
import { useState,useEffect } from "react";
import { useAuth } from "@/contexts/useAuth";
import CursorLoader from "./LoadingSpinner";


// Íconos
import {
  FaHome,
  FaChartBar,
  FaSignInAlt,
  FaUserPlus,
  FaSignOutAlt,
  FaUserEdit,
  FaUserCircle,
  FaLayerGroup,
} from "react-icons/fa";


const NavBar = () => {
  const [isOpen, setIsOpen] = useState(false);
  const { usuario, logout } = useAuth();
  const navigate = useNavigate();

  const location = useLocation();
  const[loading,setloading]=useState(false);
  
  useEffect(()=>{
    setloading(true);
    const timer = setTimeout(()=>{
      setloading(false);
    },300);
    return()=> clearTimeout(timer);
  },[location]);

  const toggleMenu = () => setIsOpen(!isOpen);

  return (
    <>
    <nav className="navbar navbar-expand-lg navbar-dark bg-dark px-3">
      <div className="container-fluid">
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

        <div
          className={`collapse navbar-collapse ${isOpen ? "show" : ""}`}
          id="navbarNav"
        >
          <div className="d-flex justify-content-between w-100">
            {/* IZQUIERDA */}
            <ul className="navbar-nav me-auto mb-2 mb-lg-0 d-flex gap-3">
              <li className="nav-item">
                <Link
                  className="nav-link d-flex align-items-center"
                  to="/"
                  onClick={() => setIsOpen(false)}
                >
                  <FaHome className="me-1" />
                  Inicio
                </Link>
              </li>
              <li className="nav-item">
                <Link
                  className="nav-link d-flex align-items-center"
                  to="/stat"
                  onClick={() => setIsOpen(false)}
                >
                  <FaChartBar className="me-1" />
                  Estadísticas
                </Link>
              </li>

              {usuario && (
                <>
                  <li className="nav-item">
                    <Link
                      className="nav-link d-flex align-items-center"
                      to="/mis-mazos"
                      onClick={() => setIsOpen(false)}
                    >
                      <FaLayerGroup className="me-1" />
                      Mis mazos
                    </Link>
                  </li>
                </>
              )}
            </ul>

            {/* DERECHA */}
            <ul className="navbar-nav mb-2 mb-lg-0 d-flex gap-3 align-items-center">
              {!usuario ? (
                <>
                  <li className="nav-item">
                    <Link
                      className="nav-link d-flex align-items-center"
                      to="/login"
                      onClick={() => setIsOpen(false)}
                    >
                      <FaSignInAlt className="me-1" />
                      Login
                    </Link>
                  </li>
                  <li className="nav-item">
                    <Link
                      className="nav-link d-flex align-items-center"
                      to="/registro"
                      onClick={() => setIsOpen(false)}
                    >
                      <FaUserPlus className="me-1" />
                      Registro
                    </Link>
                  </li>
                </>
              ) : (
                <>
                  <li className="nav-item d-flex align-items-center text-white fw-bold">
                    <FaUserCircle className="me-2" />
                    Hola, {usuario.nombre}
                  </li>
                  <li className="nav-item">
                    <Link
                      className="nav-link d-flex align-items-center"
                      to="/editar-usuario"
                      onClick={() => setIsOpen(false)}
                    >
                      <FaUserEdit className="me-1" />
                      Editar usuario
                    </Link>
                  </li>
                  <li className="nav-item">
                    <button
                      className="nav-link btn btn-link d-flex align-items-center"
                      onClick={() => {
                        logout();
                        navigate("/");
                        setIsOpen(false);
                      }}
                    >
                      <FaSignOutAlt className="me-1" />
                      Logout
                    </button>
                  </li>
                </>
              )}
            </ul>
          </div>
        </div>
      </div>
    </nav>
    <CursorLoader active={loading} />
    </>
   
  );
};

export default NavBar;
