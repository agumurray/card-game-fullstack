import "@/styles/footer.css";

const FooterComponent = () => {
  return (
    <footer className="footer">
      <p>
        &copy; {new Date().getFullYear()} Pokebattle – Facultad de Informática,
        UNLP.
      </p>
    </footer>
  );
};

export default FooterComponent;
