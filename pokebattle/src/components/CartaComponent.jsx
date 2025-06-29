import { Card } from "react-bootstrap";
import "@/styles/CartaComponent.css"; // nuevo import

const imagenes = import.meta.glob("@/assets/pokemons/*.svg", {
  eager: true,
  import: "default",
});

const CartaComponent = ({
  nombre,
  atributo,
  ataque,
  punto,
  customStyle = {},
}) => {
  const ruta = `/src/assets/pokemons/${nombre.toLowerCase()}.svg`;
  const imagen = imagenes[ruta];

  return (
    <Card
      style={{ ...customStyle }}
      className="carta-component text-center shadow"
    >
      <Card.Img
        variant="top"
        src={imagen}
        alt={`Imagen de ${nombre}`}
        className="carta-imagen"
      />
      <Card.Body>
        <Card.Title>
          {nombre.charAt(0).toUpperCase() + nombre.slice(1)}
        </Card.Title>
        <Card.Text>
          <strong>Atributo:</strong> {atributo} <br />
          <strong>Ataque:</strong> {ataque} <br />
          {punto && <strong>Fuerza: {punto}</strong>}
        </Card.Text>
      </Card.Body>
    </Card>
  );
};

export default CartaComponent;
