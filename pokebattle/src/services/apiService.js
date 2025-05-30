import { api } from "../api/api";

export const getEstadisticas = () => api.get("/estadisticas");