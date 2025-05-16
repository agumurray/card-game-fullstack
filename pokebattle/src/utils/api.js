// src/utils/api.js

const API_BASE = import.meta.env.VITE_API_URL;

export async function testAPI() {
  try {
    const res = await fetch(`${API_BASE}/ping`);
    if (!res.ok) throw new Error("API no responde correctamente");
    const data = await res.json();
    return data;
  } catch (error) {
    return { error: error.message };
  }
}
