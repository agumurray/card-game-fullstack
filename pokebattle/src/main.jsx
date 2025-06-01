import React from "react";
import { createRoot } from "react-dom/client";
import { BrowserRouter } from "react-router-dom";
import Layout from "@/layouts/Layout";
import AppRoutes from "@/routes";
import { AuthProvider } from "@/contexts/AuthContext";

import "bootstrap/dist/css/bootstrap.min.css";
import "@/styles/main.css";

createRoot(document.getElementById("root")).render(
  <React.StrictMode>
    <BrowserRouter>
      <AuthProvider>
        <Layout>
          <AppRoutes />
        </Layout>
      </AuthProvider>
    </BrowserRouter>
  </React.StrictMode>
);
