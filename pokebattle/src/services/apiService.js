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

export const getEstadisticas = () => api.get("/estadisticas");

export const editUserData = (id, data) => api.put(`/usuarios/${id}`, data);
