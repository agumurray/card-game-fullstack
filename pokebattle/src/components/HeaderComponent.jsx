import { Link } from "react-router-dom";
import "@/styles/header.css";

const HeaderComponent = () => {
  return (
    <header className="header">
      <h1>
        <Link to="/" className="header-link">
          Pokebattle
        </Link>
      </h1>
    </header>
  );
};

export default HeaderComponent;
