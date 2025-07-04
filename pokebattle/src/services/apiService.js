import { api } from "../api/api";

export const createUser = (data) => api.post("/registro", data);

export const loginUser = (data) => api.post("/login", data);

export const logoutUser = () => api.post("/logout");

export const getUserData = () => api.get("/yo");

export const getMazosPorUsuario = (usuarioId) =>
  api.get(`/usuarios/${usuarioId}/mazos`);

export const eliminarMazo = (id) => api.delete(`/mazos/${id}`);

export const editarNombreMazo = (id, nombre) =>
  api.put(`/mazos/${id}`, { nombre });

export const deleteMazo = (mazoId) => api.delete(`/mazos/${mazoId}`);

export const updateMazo = (mazoId, data) => api.put(`/mazos/${mazoId}`, data);

export const createMazo = (data) => api.post("/mazos", data);

export const getAllCartas = () => api.get("/cartas");

export const getEstadisticas = () => api.get("/estadisticas");

export const getAtributos = (usuarioId, partidaId) => {
  return api.get(`/usuarios/${usuarioId}/partidas/${partidaId}/cartas`);
};
export const crearPartida = (id_mazo) => {
  return api.post("/partidas", { id_mazo });
};

export const jugarCarta = (id_partida, id_carta) =>
  api.post("/jugadas", { id_partida, id_carta });

export const editUserData = (id, data) => api.put(`/usuarios/${id}`, data);

export const FiltrarCartas = (atributo, nombre) =>
  api.get(`/cartas?atributo=${atributo}&nombre=${nombre}`);

export const FiltrarCartasN = (nombre) => api.get(`/cartas?nombre=${nombre}`);
