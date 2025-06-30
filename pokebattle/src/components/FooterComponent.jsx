import "@/styles/footer.css";

const FooterComponent = () => {
  return (
    <footer className="footer">
      <p>
        &copy; {new Date().getFullYear()} Pokebattle – Murray Agustin, Ibañez
        Kevin, Poggio Santiago 
      </p>
    </footer>
  );
};

export default FooterComponent;
