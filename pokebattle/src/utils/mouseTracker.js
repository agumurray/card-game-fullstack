let mousePosition = { x: 0, y: 0 };

const updateMousePosition = (e) => {
  mousePosition = { x: e.clientX, y: e.clientY };
};

window.addEventListener("mousemove", updateMousePosition);

export const getMousePosition = () => mousePosition;
