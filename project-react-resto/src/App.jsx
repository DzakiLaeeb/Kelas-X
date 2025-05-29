import React, { useState, useEffect } from 'react';
import { BrowserRouter, Routes, Route, Navigate, useLocation } from 'react-router-dom';
import AdminLayout from './layouts/AdminLayout';
import PublicLayout from './layouts/PublicLayout';
import KokiLayout from './layouts/KokiLayout';
import KasirLayout from './layouts/KasirLayout';
import NotFoundPage from './pages/NotFoundPage';
import UnauthorizedPage from './pages/UnauthorizedPage';
import Home from './pages/Home';
import KategoriPage from './pages/KategoriPage';
import MenuPage from './pages/MenuPage';
import PelangganPage from './pages/PelangganPage';
import OrderPage from './pages/OrderPage';
import OrderDetailPage from './pages/OrderDetailPage';
import OrdersPage from './pages/OrdersPage';
import 'bootstrap/dist/css/bootstrap.min.css';
import './App.css';
import Login from './pages/Login';
import Register from './pages/Register';

// Admin Pages
import AdminDashboard from './pages/admin/AdminDashboard';
import KategoriAdmin from './pages/admin/KategoriAdmin';
import MenuAdmin from './pages/admin/MenuAdmin';
import PelangganAdmin from './pages/admin/PelangganAdmin';
import OrderAdmin from './pages/admin/OrderAdmin';
import OrderDetailAdmin from './pages/admin/OrderDetailAdmin';
import UserAdmin from './pages/admin/UserAdmin';

// Koki Pages
import KokiDashboard from './pages/KokiDashboard';

// Kasir Pages
import KasirDashboard from './pages/KasirDashboard';
import KasirOrderPage from './pages/KasirOrderPage';
import KasirOrderDetailPage from './pages/KasirOrderDetailPage';

import { AuthProvider, useAuth } from './contexts/AuthContext';
import ProtectedRoute from './components/ProtectedRoute';

// Component to handle role-based redirects for home page
const HomeRedirect = () => {
  const { user } = useAuth();

  if (user) {
    switch (user.role) {
      case 'admin':
        return <Navigate to="/admin" replace />;
      case 'koki':
        return <Navigate to="/koki" replace />;
      case 'kasir':
        return <Navigate to="/kasir" replace />;
      default:
        return <Home />; // Regular users stay on home page
    }
  }

  return <Home />; // Not logged in users see home page
};

/**
 * Main App Component
 *
 * Provides authentication context and sets up routing for the application
 */
function App() {
  return (
    <AuthProvider>
      <AppRoutes />
    </AuthProvider>
  );
}

/**
 * AppRoutes Component
 *
 * Sets up all application routes with proper authentication checks
 */
const AppRoutes = () => {
  const [isLoading, setIsLoading] = useState(true);
  const { user, loading: authLoading } = useAuth();

  // Simulate loading
  useEffect(() => {
    const timer = setTimeout(() => {
      setIsLoading(false);
    }, 1000);

    return () => clearTimeout(timer);
  }, []);

  if (isLoading || authLoading) {
    return <LoadingScreen />;
  }

  return (
    <BrowserRouter>
      <Routes>
        {/* Public Routes */}
        <Route path="/" element={<PublicLayout />}>
          <Route index element={<HomeRedirect />} />
          <Route path="kategori" element={<KategoriPage />} />
          <Route path="menu" element={<MenuPage />} />
          <Route path="pelanggan" element={<PelangganPage />} />
          <Route path="order" element={<OrderPage />} />
          <Route path="order-detail" element={<OrderDetailPage />} />
          <Route path="orders" element={<OrdersPage />} />
        </Route>

        {/* Admin Routes - All prefixed with /admin */}
        <Route
          path="/admin"
          element={
            <ProtectedRoute requireAdmin={true}>
              <AdminLayout />
            </ProtectedRoute>
          }
        >
          <Route index element={<AdminDashboard />} />
          <Route path="kategori" element={<KategoriAdmin />} />
          <Route path="menu" element={<MenuAdmin />} />
          <Route path="pelanggan" element={<PelangganAdmin />} />
          <Route path="order" element={<OrderAdmin />} />
          <Route path="order-detail" element={<OrderDetailAdmin />} />
          <Route path="users" element={<UserAdmin />} />
        </Route>

        {/* Koki Routes - All prefixed with /koki */}
        <Route
          path="/koki"
          element={
            <ProtectedRoute requireKoki={true}>
              <KokiLayout />
            </ProtectedRoute>
          }
        >
          <Route index element={<KokiDashboard />} />
          <Route path="order-detail" element={<OrderDetailPage />} />
        </Route>

        {/* Kasir Routes - All prefixed with /kasir */}
        <Route
          path="/kasir"
          element={
            <ProtectedRoute requireKasir={true}>
              <KasirLayout />
            </ProtectedRoute>
          }
        >
          <Route index element={<KasirDashboard />} />
          <Route path="order" element={<KasirOrderPage />} />
          <Route path="order-detail" element={<KasirOrderDetailPage />} />
        </Route>

        {/* Auth Routes */}
        <Route path="login" element={<Login />} />
        <Route path="register" element={<Register />} />

        {/* Unauthorized Page */}
        <Route path="unauthorized" element={<UnauthorizedPage />} />

        {/* 404 Page */}
        <Route path="*" element={<NotFoundPage />} />
      </Routes>
    </BrowserRouter>
  );
};

// Loading Screen Component
const LoadingScreen = () => {
  return (
    <div className="loading-screen">
      <div className="loading-content">
        <div className="spinner"></div>
        <h2 className="mt-4">Loading...</h2>
        <p>Preparing your restaurant dashboard</p>
      </div>
    </div>
  );
};

// Page Transition Component
const PageTransition = ({ children }) => {
  const location = useLocation();
  const [displayLocation, setDisplayLocation] = useState(location);
  const [transitionStage, setTransitionStage] = useState("fadeIn");

  useEffect(() => {
    if (location !== displayLocation) {
      setTransitionStage("fadeOut");
    }
  }, [location, displayLocation]);

  const handleAnimationEnd = () => {
    if (transitionStage === "fadeOut") {
      setTransitionStage("fadeIn");
      setDisplayLocation(location);
    }
  };

  return (
    <div
      className={`page-transition ${transitionStage}`}
      onAnimationEnd={handleAnimationEnd}
    >
      {children}
    </div>
  );
};

export default App;
