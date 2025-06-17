import { Card } from "react-bootstrap";

// Importa todos los SVGs de la carpeta pokemons
const imagenes = import.meta.glob("@/assets/pokemons/*.svg", {
  eager: true,
  import: "default",
});

const CartaComponent = ({ nombre, atributo, ataque }) => {
  const ruta = `/src/assets/pokemons/${nombre}.svg`;
  const imagen = imagenes[ruta];

  return (
    <Card
      style={{ width: "12rem", height: "270px", border: "2px solid #444" }}
      className="text-center shadow"
    >
      <Card.Img
        variant="top"
        src={imagen}
        alt={`Imagen de ${nombre}`}
        style={{ height: "150px", objectFit: "contain", padding: "10px" }}
      />
      <Card.Body>
        <Card.Title>
          {nombre.charAt(0).toUpperCase() + nombre.slice(1)}
        </Card.Title>
        <Card.Text>
          <strong>Atributo:</strong> {atributo} <br />
          <strong>Ataque:</strong> {ataque}
        </Card.Text>
      </Card.Body>
    </Card>
  );
};

export default CartaComponent;
