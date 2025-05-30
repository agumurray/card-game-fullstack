import { Link } from "react-router-dom";
import "@/styles/navbar.css";
import { useState } from "react";

const NavBar = () => {
  const [isOpen, setIsOpen] = useState(false);

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

        <div
          className={`collapse navbar-collapse offcanvas-collapse ${
            isOpen ? "show" : ""
          }`}
        >
          <ul className="navbar-nav me-auto mb-2 mb-lg-0 d-flex flex-column flex-lg-row gap-3">
            <li className="nav-item">
              <Link
                className="nav-link"
                to="/stat"
                onClick={() => setIsOpen(false)}
              >
                Estadísticas
              </Link>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  );
};

export default NavBar;
