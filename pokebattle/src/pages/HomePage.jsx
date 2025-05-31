import "../styles/HomePage.css";

const pokemons = Array.from({ length: 40 }, (_, i) => i + 1); 

const HomePage = () => {
  return (
    <div className="text-center">
      <div className="py-5 bg-light">
        <h1 className="mb-3">¡Bienvenido a Pokebattle!</h1>
        <p className="text-muted">
          Explorá las estadísticas, iniciá sesión o registrate para comenzar.
        </p>
      </div>

      <div className="slider-container">
        <div className="slider-track">
          {[...pokemons, ...pokemons].map((id, index) => (
            <div className="slide" key={index}>
              <img
                src={`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/${id}.png`}
                alt={`Pokemon ${id}`}
              />
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

export default HomePage;
