import { api } from "../api/api";

export const createUser = (data) => api.post("/registro", data);

export const loginUser = (data) => api.post("/login", data);

export const logoutUser = () => api.post("/logout");

export const getUserData = () => api.get("/yo");

export const getMazosPorUsuario = (usuarioId) => api.get(`/usuarios/${usuarioId}/mazos`);

export const getEstadisticas = () => api.get("/estadisticas");
