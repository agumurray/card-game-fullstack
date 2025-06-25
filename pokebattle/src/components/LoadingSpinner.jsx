import React, { useState, useEffect } from "react";
import loadingGif from "../assets/pokebola.gif";
import { getMousePosition } from "../utils/mouseTracker";

const LoadingSpinner = ({ active }) => {
  const [pos, setPos] = useState({ x: 0, y: 0 });

  useEffect(() => {
    if (!active) return;

    const handleMouseMove = (e) => {
      setPos({ x: e.clientX + 15, y: e.clientY + 15 });
    };

    const currentMouse = getMousePosition();
    setPos({ x: currentMouse.x + 15, y: currentMouse.y + 15 });

    window.addEventListener("mousemove", handleMouseMove);
    return () => {
      window.removeEventListener("mousemove", handleMouseMove);
    };
  }, [active]);

  if (!active) return null;

  return (
    <img
      src={loadingGif}
      alt="Cargando..."
      style={{
        position: "fixed",
        top: pos.y,
        left: pos.x,
        pointerEvents: "none",
        width: 40,
        height: 40,
        zIndex: 9999,
        userSelect: "none",
      }}
    />
  );
};

export default LoadingSpinner;

