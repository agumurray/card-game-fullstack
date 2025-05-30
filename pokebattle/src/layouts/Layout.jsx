import HeaderComponent from "@/components/HeaderComponent";
import FooterComponente from "@/components/FooterComponent";
import NavBar from "@/components/NavBarComponent";
import "@/styles/layout.css";

const Layout = ({ children }) => {
  return (
    <>
      <HeaderComponent />
      <NavBar />
      <main className="main">{children}</main>
      <FooterComponente />
    </>
  );
};

export default Layout;
