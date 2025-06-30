import { Link } from "react-router-dom";
import logo from "@/assets/logo.svg";
import "@/styles/header.css";

const HeaderComponent = () => {
  return (
    <header className="header">
      <Link to="/">
        <img src={logo} alt="Logo izquierdo" className="header-logo left" />
      </Link>

      <h1 className="header-title">
        <Link to="/" className="header-link">
          Pokebattle
        </Link>
      </h1>

      <Link to="/">
        <img src={logo} alt="Logo derecho" className="header-logo right" />
      </Link>
    </header>
  );
};

export default HeaderComponent;
