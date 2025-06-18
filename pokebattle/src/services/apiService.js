import { api } from "../api/api";

export const createUser = (data) => api.post("/registro", data);

export const loginUser = (data) => api.post("/login", data);

export const logoutUser = () => api.post("/logout");

export const getUserData = () => api.get("/yo");

export const getMazosPorUsuario = (usuarioId) => api.get(`/usuarios/${usuarioId}/mazos`);

export const deleteMazo=(mazoId)=>api.delete(`/mazos/${mazoId}`);

export const updateMazo=(mazoId,data)=>api.put(`/mazos/${mazoId}`,data);

export const createMazo = (data) => api.post("/mazos", data);

export const getAllCartas = () => api.get("/allcards");

export const getEstadisticas = () => api.get("/estadisticas");
