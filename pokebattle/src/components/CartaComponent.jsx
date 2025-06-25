import { Card } from "react-bootstrap";

// Importa todos los SVGs de la carpeta pokemons
const imagenes = import.meta.glob("@/assets/pokemons/*.svg", {
  eager: true,
  import: "default",
});

const CartaComponent = ({ nombre, atributo, ataque, punto }) => {
  const ruta = `/src/assets/pokemons/${nombre.toLowerCase()}.svg`;
  const imagen = imagenes[ruta];

  return (
    <Card
      style={{ width: "13rem", height: "270px", border: "2px solid #444" }}
      className="text-center shadow"
    >
      <Card.Img
        variant="top"
        src={imagen}
        alt={`Imagen de ${nombre}`}
        style={{ height: "150px", objectFit: "contain", padding: "10px",display:"block",marginInline:"auto",width:"100%"}}
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
