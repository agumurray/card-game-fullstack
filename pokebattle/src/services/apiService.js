import { api } from "../api/api";

export const loginUser = (data) => api.post("/login", data);

export const logoutUser = () => api.post("/logout");

export const getUserData = () => api.get("/yo");

export const getEstadisticas = () => api.get("/estadisticas");
